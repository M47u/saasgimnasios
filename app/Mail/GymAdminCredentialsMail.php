<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GymAdminCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $gimnasioNombre,
        public string $email,
        public string $password
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Credenciales de acceso - {$this->gimnasioNombre}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.gym-admin-credentials',
            with: [
                'gimnasioNombre' => $this->gimnasioNombre,
                'email' => $this->email,
                'password' => $this->password,
            ]
        );
    }
}
