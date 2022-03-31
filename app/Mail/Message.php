<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Message extends Mailable
{
    use Queueable, SerializesModels;

    public $senderName;
    public $recipientName;
    public $itemName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($senderName, $recipientName, $itemName)
    {
        $this->senderName = $senderName;
        $this->recipientName = $recipientName;
        $this->itemName = $itemName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("You have received a message about {$this->itemName}")
            ->view("emails.message-received");
    }
}
