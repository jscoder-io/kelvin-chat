<?php

namespace App\Livewire;

use App\Jobs\UploadImage;
use App\Models\Message;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class ChatBoxUpload extends ModalComponent
{
    use WithFileUploads;

    #[Validate('image|max:1024')] // 1MB Max
    public $file;

    public $message;

    public function mount(Message $message)
    {
        $this->message = $message;
    }

    public function send()
    {
        $this->validate();

        $filepath = $this->file->store('chat');
        $fullpath = storage_path(sprintf('app/%s', $filepath));

        UploadImage::dispatch($this->message, $fullpath);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.chat-box-upload');
    }
}
