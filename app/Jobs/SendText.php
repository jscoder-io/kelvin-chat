<?php

namespace App\Jobs;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shop;
    protected $text;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct(Shop $shop, Message $message, string $text)
    {
        $this->shop = $shop;
        $this->text = $text;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $marketplace = MarketplaceFactory::create($this->shop);
        $marketplace->send($this->message, $this->prependPreset($this->text));
    }

    protected function prependPreset($text)
    {
        if (auth()->user()->role > 0) {
            if (auth()->user()->preset) {
                return sprintf('%s: %s', auth()->user()->preset, $text);
            }
            return $text;
        }
        return sprintf('ADM: %s', $text);
    }
}
