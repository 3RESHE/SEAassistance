<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($password, $email)
    {
        $this->password = $password;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.send_password')
                    ->with([
                        'password' => $this->password,
                        'email' => $this->email,
                    ])
                    ->subject('SEAassist Account Details');
    }
    
}
