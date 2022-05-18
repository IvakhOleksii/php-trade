<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registration extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $type;
    public $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $type, $verifyKey)
    {
        $this->name = $name;
        $this->type = $type;
        $this->link = $verifyKey ? config('app.url') . "/verify/$verifyKey" : "";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->type === "owner" ? "Done! Your owner account has been created" : "Your dealer account is under review")
            ->view($this->type == "owner" ? "emails.owner-registration" : "emails.dealer-registration");
    }
}
