<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The number of times the queued job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 60;

    public ?Subscriber $subscriber;

    /**
     * @var Collection<int, Product>
     */
    public Collection $digitalProducts;

    /**
     * @var Collection<int, Product>
     */
    public Collection $physicalProducts;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        ?Subscriber $subscriber = null
    ) {
        $this->subscriber = $subscriber ?? Subscriber::where('email', $user->email)->first();

        // 2 Digital products (top selling / featured / latest)
        $this->digitalProducts = Product::where('status', 'active')
            ->where('product_type', 'digital')
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->latest()
            ->take(2)
            ->get();

        // 2 Physical products (top selling / featured / latest)
        $this->physicalProducts = Product::where('status', 'active')
            ->where('product_type', 'physical')
            ->orderByDesc('is_featured')
            ->orderByDesc('rating')
            ->latest()
            ->take(2)
            ->get();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to DigiGo! Thank You for Joining Our Store',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
