<?php

namespace App\Jobs;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shop;
    protected $fullpath;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct(Message $message, string $fullpath)
    {
        $this->shop = $message->shop;
        $this->fullpath = $fullpath;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $marketplace = MarketplaceFactory::create($this->shop);
        $marketplace->upload($this->message, $this->fullpath);
    }
}
