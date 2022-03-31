<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Bid extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $dealer;
    public $itemName;
    public $price;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $dealer, $itemName, $price)
    {
        $this->name = $name;
        $this->dealer = $dealer;
        $this->itemName = $itemName;
        $this->price = $price;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("You have received a bid on {$this->itemName}")
            ->view("emails.bid-received");
    }
}
