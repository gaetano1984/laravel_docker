<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNewsAttach extends Mailable
{
    use Queueable, SerializesModels;

    public $attach = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attach)
    {
        //
        $this->attach = $attach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mess = $this->view('mail.sendnews');
        foreach($this->attach as $att){
            $mess->attach($att);
        }
        return $mess;
    }
}
