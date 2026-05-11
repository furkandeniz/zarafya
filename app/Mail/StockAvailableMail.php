<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Product $product,
        public ?string $variantLabel = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = '"' . $this->product->name . '"';
        if ($this->variantLabel) {
            $subject .= ' (' . $this->variantLabel . ')';
        }
        $subject .= ' stoka girdi!';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.stock-available');
    }
}
