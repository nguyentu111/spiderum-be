<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailForgotPassService extends Mailable
{
    use Queueable, SerializesModels;

    private string $token;
    private string $username;
    /**
     * Create a new message instance.
     */
    public function __construct(string $token,string $username)
    {
        $this->token = $token;
        $this->username = $username;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lấy lại mật khẩu cho tài khoản của bạn',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'auth.mail-forgot-password',
            with: [
                'token' => $this->token,
                'username' => $this->username
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
