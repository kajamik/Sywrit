<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $obj;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->obj->from)
                    ->to($this->obj->to)
                    ->subject($this->obj->subject)
                    ->view('vendor.notifications.support_email');
    }
}
