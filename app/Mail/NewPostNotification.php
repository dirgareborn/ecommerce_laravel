<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPostNotification extends Mailable
{
    use SerializesModels;

    public $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function build()
    {
        return $this->subject("Postingan Baru di Website")
                    ->view('emails.new_post');
    }
}
