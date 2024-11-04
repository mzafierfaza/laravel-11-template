<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * user var
     *
     * @var User
     */
    public string $url;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param bool $isVerificationCode
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Konfirmasi akun LMS KelasKita'))->view('stisla.emails.create-password-users', [
            'url' => $this->url
        ]);
    }
}
