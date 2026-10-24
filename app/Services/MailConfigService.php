<?php

namespace App\Services;

use App\Mail\SmtpTestMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailConfigService
{
    /**
     * Apply SMTP settings from database to Laravel runtime mail configuration.
     */
    public function apply(): void
    {
        try {
            $mailer = Setting::get('mail_mailer', config('mail.default', 'smtp'));
            $host = Setting::get('mail_host');

            // If a host or mailer is configured
            if (! empty($host) || ! empty($mailer)) {
                $port = (int) (Setting::get('mail_port') ?: config('mail.mailers.smtp.port', 587));
                $encryption = strtolower((string) Setting::get('mail_encryption', 'tls'));
                $username = Setting::get('mail_username');
                $password = Setting::get('mail_password');
                $fromAddress = Setting::get('mail_from_address', config('mail.from.address', 'hello@example.com'));
                $fromName = Setting::get('mail_from_name', config('mail.from.name', 'DigiGo'));

                $scheme = null;
                if ($encryption === 'ssl' || $port === 465) {
                    $scheme = 'smtps';
                } elseif ($encryption === 'tls') {
                    $scheme = 'smtp';
                }

                Config::set('mail.default', $mailer);
                Config::set('mail.mailers.smtp.transport', 'smtp');
                Config::set('mail.mailers.smtp.host', $host ?: config('mail.mailers.smtp.host', '127.0.0.1'));
                Config::set('mail.mailers.smtp.port', $port);
                Config::set('mail.mailers.smtp.scheme', $scheme);
                Config::set('mail.mailers.smtp.encryption', in_array($encryption, ['tls', 'ssl']) ? $encryption : null);
                Config::set('mail.mailers.smtp.username', $username);
                Config::set('mail.mailers.smtp.password', $password);
                Config::set('mail.from.address', $fromAddress);
                Config::set('mail.from.name', $fromName);

                Mail::forgetMailers();
            }
        } catch (\Throwable $e) {
            // Silently fail during bootstrapping if database is not yet migrated or unreachable
            Log::debug('MailConfigService: unable to apply settings', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Send a test email using currently configured mailer.
     *
     * @return array{success: bool, message: string}
     */
    public function sendTestEmail(string $recipient, bool $queue = false): array
    {
        try {
            // Make sure current settings are applied and fresh
            $this->apply();

            $details = [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => Setting::get('mail_encryption', 'tls'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
                'sent_at' => now()->toFormattedDateString().' '.now()->toTimeString(),
            ];

            if ($queue) {
                Mail::to($recipient)->queue(new SmtpTestMail($details));

                return [
                    'success' => true,
                    'message' => "Test email successfully queued into background jobs pipeline for {$recipient}!",
                ];
            }

            Mail::to($recipient)->send(new SmtpTestMail($details));

            return [
                'success' => true,
                'message' => "Test email successfully sent to {$recipient}!",
            ];
        } catch (\Throwable $e) {
            Log::error('SMTP Test Email Error: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send email: '.$e->getMessage(),
            ];
        }
    }
}
