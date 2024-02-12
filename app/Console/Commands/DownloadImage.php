<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\Message;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File as Filesystem;

class DownloadImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:download:image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Image';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $message = Message::whereNull('profile_image_copy')
            ->orWhereNull('product_image_copy')
            ->orderBy('latest_created', 'desc')
            ->limit(1)->get()->first();

        if (! $message) {
            return;
        }

        $key = '';
        $filename = '';

        if (! $message->profile_image_copy) {
            $key = 'profile_image_copy';
            $filename = $message->profile_image;
        }
        if (! $message->product_image_copy) {
            $key = 'product_image_copy';
            $filename = $message->product_image;
        }

        try {
            $file = File::firstOrNew(['filename' => $filename]);
            if (is_null($file->id) || ($file && $file->attempts < 3)) {
                $file->attempts = $file->id ? ++$file->attempts : 0;
                $file->save();

                $filepath = storage_path(sprintf('app/public/%s', Filesystem::basename($filename)));
                $fileurl = url(sprintf('storage/%s', Filesystem::basename($filename)));

                if (Filesystem::missing($filepath)) {
                    $client = new Client();
                    $client->request('GET', $filename, ['sink' => $filepath]); // Download
                }

                $message->{$key} = $fileurl ?: null;
                $message->save();
            }
            if ($file->attempts == 3) {
                $message->{$key} = $filename;
                $message->save();
            }
        } catch (\Exception $e) {}
    }
}
