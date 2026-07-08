<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $subscriberName) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Deine Newsletter-Anmeldung bei ' . config('app.name'));
    }

    public function content(): Content
    {
        return new Content(view: 'mail.newsletter-confirmation');
    }
}
