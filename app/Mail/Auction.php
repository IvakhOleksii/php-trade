<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Auction extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $type;
    public $info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $type, $info)
    {
        $this->name = $name;
        $this->type = $type;
        $this->info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Your auction has been " . ($this->type == 'publish' ? 'published' : 'rejected'))
            ->view("emails.auction-submitted");
    }
}
