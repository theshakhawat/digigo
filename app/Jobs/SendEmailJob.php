<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the queued job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string|array $recipient,
        public Mailable $mailable
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->recipient)->send($this->mailable);
        } catch (\Throwable $e) {
            Log::error('SendEmailJob Failed: '.$e->getMessage(), [
                'recipient' => $this->recipient,
                'mailable' => get_class($this->mailable),
                'exception' => $e,
            ]);

            throw $e;
        }
    }
}
