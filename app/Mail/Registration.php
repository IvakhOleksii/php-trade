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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("Done! Your {$this->type} account has been created")
            ->view($this->type == "owner" ? "emails.owner-registration.blade.php" : "emails.dealer-registration.blade.php");
    }
}
