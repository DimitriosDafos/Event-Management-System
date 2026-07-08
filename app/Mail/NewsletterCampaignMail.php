<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $campaignSubject,
        public string $body,
        public string $subscriberName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->campaignSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.newsletter-campaign');
    }
}
