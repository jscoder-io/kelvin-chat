<?php

namespace App\Marketplace;

use App\Models\Message;
use App\Models\Shop;
use App\Models\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Utils;
use GuzzleHttp\Psr7\Utils as Psr7Utils;

class Carousell
{
    protected $shop;
    protected $jwt_token;
    protected $session_key;
    protected $csrf_token;
    protected $_csrf;

    protected $inbox_url = 'https://www.carousell.sg/ds/offer/1.0/me/?_path=/1.0/me/&count=100&type=all';
    protected $read_url = 'https://www.carousell.sg/ds/api-offer/2.7/offer/%s/?_path=/2.7/offer/%s/&fetch_dispute=1&mark_as_read=true';
    protected $chat_url = 'https://api-f3cb6187-cb42-4cd1-95fc-1c46f8856006.sendbird.com/v3/group_channels/%s/messages?is_sdk=true&prev_limit=150&next_limit=0&include=false&reverse=false&message_ts=9007199254740991&message_type=&include_reply_type=none&with_sorted_meta_array=false&include_reactions=false&include_thread_info=false&include_parent_message_info=false&show_subchannel_message_only=false&include_poll_details=true';
    protected $send_url = 'https://api-f3cb6187-cb42-4cd1-95fc-1c46f8856006.sendbird.com/v3/group_channels/%s/messages';
    protected $group_url = 'https://api-f3cb6187-cb42-4cd1-95fc-1c46f8856006.sendbird.com/v3/group_channels/%s?show_member=true&show_read_receipt=true&show_delivery_receipt=true';
    protected $accept_offer_url = 'https://www.carousell.sg/ds/api-offer/2.5/offer/%s/accept/?_path=/2.5/offer/%s/accept/';
    protected $decline_offer_url = 'https://www.carousell.sg/ds/api-offer/2.1/offer/%s/decline/?_path=/2.1/offer/%s/decline/';

    protected $app_id = 'F3CB6187-CB42-4CD1-95FC-1C46F8856006';

    public function __construct(Shop $shop)
    {
        $this->shop = $shop;

        $shop->tokens->each(function ($token) {
            $var = str($token->key)->explode('-')->implode('_');
            $this->{$var} = $token->value;
        });
    }

    public function inbox()
    {
        $client = new Client();

        $jar = CookieJar::fromArray(['jwt' => $this->jwt_token], 'www.carousell.sg');

        $results = ['success' => false, 'messages' => []];

        try {
            $res = $client->request('GET', $this->inbox_url, [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Content-Type'  => 'application/json',
                    'User-Agent'    => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
                ],
                'cookies' => $jar
            ]);
        } catch (\Exception $e) {
            $this->validateJwtToken(false);
            return $results;
        }

        if ($res->getStatusCode() == '200') {
            $json = (string) $res->getBody();
            $data = Utils::jsonDecode($json, true);

            $results['success'] = true;
            foreach ($data['data']['offers'] as $offer) {
                $results['messages'][] = [
                    'chat_id' => $offer['id'],
                    'buyer_id' => $offer['user']['id'],
                    'username' => $offer['user']['username'],
                    'profile_image' => $offer['user']['profile']['image_url'],
                    'product_title' => $offer['product']['title'],
                    'product_image' => $offer['product']['primary_photo_url'],
                    'price_formatted' => sprintf('%s%s', $offer['currency_symbol'], $offer['product']['price_formatted']),
                    'product_url' => $this->getProductUrl($offer),
                    'channel_url' => $offer['channel_url'],
                    'latest_message' => $offer['latest_price_message'],
                    'unread_count' => $offer['unread_count'],
                    'latest_created' => $offer['latest_price_created'],
                    'data' => $offer,
                ];
            }

            $this->validateJwtToken();
        }

        return $results;
    }

    public function chat(Message $message)
    {
        $this->setAsRead($message->chat_id);

        $client = new Client();

        try {
            $res = $client->request('GET', sprintf($this->chat_url, $message->channel_url), [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'App-Id' => $this->app_id,
                    'Session-Key' => $this->session_key,
                ],
            ]);

            $json = (string) $res->getBody();
            $data = Utils::jsonDecode($json, true);

            $this->validateSessionKey();

            $results = ['success' => true, 'messages' => []];
            foreach ($data['messages'] as $chat) {
                //if ($this->isChatValid($chat['type'], $chat['custom_type'])) {
                    $results['messages'][] = [
                        'chat_id' => $chat['message_id'],
                        'message' => $chat['message'] ?? null,
                        'type' => $chat['type'],
                        'custom_type' => $chat['custom_type'],
                        'user' => $this->getUserFromPayload($message->buyer_id, $chat),
                        'data' => isset($chat['data']) ? Utils::jsonDecode($chat['data'], true) : [],
                        'file' => $chat['file'] ?? [],
                        'created_at' => date('Y-m-d H:i:s', substr($chat['created_at'], 0, -3)),
                    ];
                //}
            }
            return $results;
        } catch (\Exception $e) {
            $this->validateSessionKey(false);
            return ['success' => false, 'messages' => []];
        }
    }

    public function send(Message $message, string $text)
    {
        $seller_id = $this->getSellerId($message);
        if ($seller_id === false) {
            return;
        }

        $client = new Client();

        try {
            $res = $client->request('POST', sprintf($this->send_url, $message->channel_url), [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'App-Id' => $this->app_id,
                    'Session-Key' => $this->session_key,
                ],
                'json' => [
                    'message_type' => 'MESG',
                    'user_id'      => $seller_id,
                    'message'      => $text,
                    'data'         => '{"offer_id":"'.$message->chat_id.'","source":"web"}',
                    'custom_type'  => 'MESSAGE',
                ]
            ]);

            $json = (string) $res->getBody();

            $this->validateSessionKey();
        } catch (\Exception $e) {
            $this->validateSessionKey(false);
        }
    }

    public function upload(Message $message, string $fullpath)
    {
        $seller_id = $this->getSellerId($message);
        if ($seller_id === false) {
            return;
        }

        $client = new Client();

        try {
            $res = $client->request('POST', sprintf($this->send_url, $message->channel_url), [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'App-Id' => $this->app_id,
                    'Session-Key' => $this->session_key,
                ],
                'multipart' => [
                    [
                        'name'     => 'message_type',
                        'contents' => 'FILE'
                    ],
                    [
                        'name'     => 'user_id',
                        'contents' => $seller_id
                    ],
                    [
                        'name'     => 'data',
                        'contents' => '{"offer_id":"'.$message->chat_id.'","source":"web"}'
                    ],
                    [
                        'name'     => 'custom_type',
                        'contents' => 'IMAGE'
                    ],
                    [
                        'name'     => 'file',
                        'contents' => Psr7Utils::tryFopen($fullpath, 'r')
                    ]
                ]
            ]);

            $json = (string) $res->getBody();

            $this->validateSessionKey();
        } catch (\Exception $e) {
            $this->validateSessionKey(false);
        }
    }

    public function acceptOffer(Message $message)
    {
        $client = new Client();

        $jar = CookieJar::fromArray(['jwt' => $this->jwt_token, '_csrf' => $this->_csrf], 'www.carousell.sg');

        $results = ['success' => false, 'data' => []];

        try {
            $res = $client->request('POST', sprintf($this->accept_offer_url, $message->chat_id, $message->chat_id), [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Csrf-Token'    => $this->csrf_token,
                    'Content-Type'  => 'application/json',
                    'User-Agent'    => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
                ],
                'cookies' => $jar
            ]);
        } catch (\Exception $e) {
            $this->validateCsrfToken(false);
            return $results;
        }

        if ($res->getStatusCode() == '200') {
            $json = (string) $res->getBody();
            $data = Utils::jsonDecode($json, true);

            $results = ['success' => true, 'data' => $data];
            $this->validateCsrfToken();
        }

        return $results;
    }

    public function declineOffer(Message $message)
    {
        $client = new Client();

        $jar = CookieJar::fromArray(['jwt' => $this->jwt_token, '_csrf' => $this->_csrf], 'www.carousell.sg');

        $results = ['success' => false, 'data' => []];

        try {
            $res = $client->request('POST', sprintf($this->decline_offer_url, $message->chat_id, $message->chat_id), [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Csrf-Token'    => $this->csrf_token,
                    'Content-Type'  => 'application/json',
                    'User-Agent'    => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
                ],
                'cookies' => $jar
            ]);
        } catch (\Exception $e) {
            $this->validateCsrfToken(false);
            return $results;
        }

        if ($res->getStatusCode() == '200') {
            $json = (string) $res->getBody();
            $data = Utils::jsonDecode($json, true);

            $results = ['success' => true, 'data' => $data];
            $this->validateCsrfToken();
        }

        return $results;
    }

    public function csrfToken()
    {
        $client = new Client();

        $jar = CookieJar::fromArray(['jwt' => $this->jwt_token, '_csrf' => $this->_csrf], 'www.carousell.sg');

        $results = ['success' => false, 'data' => []];

        try {
            $res = $client->request('POST', 'https://www.carousell.sg/ds/chat-management/chat/1.0/get-chat-benefits/?_path=/chat/1.0/get-chat-benefits/', [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Csrf-Token'    => $this->csrf_token,
                    'Content-Type'  => 'application/json',
                    'User-Agent'    => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
                ],
                'json' => [
                    'chatBenefitTypes' => [[
                        'chatBenefitType' => 1,
                    ]]
                ],
                'cookies' => $jar
            ]);
        } catch (\Exception $e) {
            $this->validateCsrfToken(false);
            return $results;
        }

        if ($res->getStatusCode() == '200') {
            $json = (string) $res->getBody();
            $data = Utils::jsonDecode($json, true);

            $results = ['success' => true, 'data' => $data];
            $this->validateCsrfToken();
        }

        return $results;
    }

    public function sessionKey($channel_url)
    {
        $client = new Client();

        try {
            $res = $client->request('GET', sprintf($this->chat_url, $channel_url), [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'App-Id' => $this->app_id,
                    'Session-Key' => $this->session_key,
                ],
            ]);

            $json = (string) $res->getBody();

            $this->validateSessionKey();
        } catch (\Exception $e) {
            $this->validateSessionKey(false);
        }
    }

    public function scan()
    {
        $inbox = $this->inbox();
        if ($inbox['success']
            && isset($inbox['messages'][0])
            && $url = $inbox['messages'][0]['channel_url']
        ) {
            $this->sessionKey($url);
        }
        $this->csrfToken();
    }

    protected function getSellerId(Message $message)
    {
        $client = new Client();

        try {
            $res = $client->request('GET', sprintf($this->group_url, $message->channel_url), [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'App-Id' => $this->app_id,
                    'Session-Key' => $this->session_key,
                ],
            ]);

            $json = (string) $res->getBody();
            $data = Utils::jsonDecode($json, true);
            $data = Utils::jsonDecode($data['data'], true);

            $this->validateSessionKey();

            return $data['seller_id'];
        } catch (\Exception $e) {
            $this->validateSessionKey(false);
            return false;
        }
    }

    protected function setAsRead($chat_id)
    {
        $client = new Client();

        $jar = CookieJar::fromArray(['jwt' => $this->jwt_token], 'www.carousell.sg');

        try {
            $res = $client->request('GET', sprintf($this->read_url, $chat_id, $chat_id), [
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'Content-Type'  => 'application/json',
                    'User-Agent'    => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
                ],
                'cookies' => $jar
            ]);
            $this->validateJwtToken();
            return (string) $res->getBody();
        } catch (\Exception $e) {
            $this->validateJwtToken(false);
            return '';
        }
    }

    protected function validateJwtToken($valid = true)
    {
        $token = Token::where('key', 'jwt-token')
            ->where('shop_id', $this->shop->id)
            ->first();

        if ($token) {
            $token->status = $valid ? 'valid' : 'invalid';
            $token->save();
        }
    }

    protected function validateSessionKey($valid = true)
    {
        $token = Token::where('key', 'session-key')
            ->where('shop_id', $this->shop->id)
            ->first();

        if ($token) {
            $token->status = $valid ? 'valid' : 'invalid';
            $token->save();
        }
    }

    protected function validateCsrfToken($valid = true)
    {
        $token = Token::where('key', 'csrf-token')
            ->where('shop_id', $this->shop->id)
            ->first();

        if ($token) {
            $token->status = $valid ? 'valid' : 'invalid';
            $token->save();
        }

        $token = Token::where('key', '_csrf')
            ->where('shop_id', $this->shop->id)
            ->first();

        if ($token) {
            $token->status = $valid ? 'valid' : 'invalid';
            $token->save();
        }
    }

    protected function isChatValid($type, $custom_type)
    {
        if ($type == 'MESG' && $custom_type == 'MESSAGE') {
            return true;
        } elseif ($type == 'MESG' && $custom_type == 'DELETED') {
            return true;
        } elseif ($type == 'MESG' && $custom_type == 'MAKE_OFFER') {
            return true;
        } elseif ($type == 'MESG' && $custom_type == 'DECLINE_OFFER') {
            return true;
        } elseif ($type == 'MESG' && $custom_type == 'ACCEPT_OFFER') {
            return true;
        } elseif ($type == 'MESG' && $custom_type == 'MESSAGE_CTA') {
            return true;
        } elseif ($type == 'FILE' && $custom_type == 'IMAGE') {
            return true;
        } elseif ($type == 'ADMM') {
            return true;
        } else {
            return false;
        }
    }

    protected function getProductUrl($data)
    {
        if ($data['user']['username'] == 'carousell_assistant') {
            return null;
        }
        return sprintf('https://www.carousell.sg/p/%s-%s/', $data['product']['title'], $data['product']['id']);
    }

    protected function getUserFromPayload($buyer_id, array $chat = [])
    {
        if (empty($chat['user'])) {
            return 'system';
        }
        return ($chat['user']['user_id'] == $buyer_id) ? 'buyer' : 'admin';
    }
}
