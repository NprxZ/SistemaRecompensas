<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class RedemptionVoucher extends Mailable
{
    use Queueable, SerializesModels;

    public $redemption;
    public $user;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct($redemption, $user, $pdfContent)
    {
        $this->redemption = $redemption;
        $this->user = $user;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comprobante de Canje #' . $this->redemption->redemption_id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.redemption-voucher',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'canje-' . $this->redemption->redemption_code . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}