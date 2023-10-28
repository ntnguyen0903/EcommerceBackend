<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData; // Dữ liệu email

    /**
     * Create a new message instance.
     *
     * @param array $emailData Dữ liệu email
     * @return void
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.account_activation')
            ->subject('Kích hoạt tài khoản')
            ->with(['emailData' => $this->emailData]);
    }

}
