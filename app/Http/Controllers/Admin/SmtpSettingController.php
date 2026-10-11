<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendTestEmailRequest;
use App\Http\Requests\Admin\UpdateSmtpSettingsRequest;
use App\Models\Setting;
use App\Services\MailConfigService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SmtpSettingController extends Controller
{
    /**
     * Display the SMTP and email configuration page.
     */
    public function edit(): View
    {
        $settings = Setting::allCached();

        $pendingJobsCount = 0;
        $failedJobsCount = 0;

        try {
            $pendingJobsCount = DB::table('jobs')->count();
            $failedJobsCount = DB::table('failed_jobs')->count();
        } catch (\Throwable $e) {
            // tables might not exist in some environments
        }

        return view('admin.settings.smtp', [
            'settings' => $settings,
            'pendingJobsCount' => $pendingJobsCount,
            'failedJobsCount' => $failedJobsCount,
        ]);
    }

    /**
     * Update SMTP and email settings.
     */
    public function update(UpdateSmtpSettingsRequest $request, MailConfigService $mailConfigService): RedirectResponse
    {
        $validated = $request->validated();

        $fields = [
            'mail_mailer' => 'text',
            'mail_host' => 'text',
            'mail_port' => 'text',
            'mail_username' => 'text',
            'mail_encryption' => 'text',
            'mail_from_address' => 'text',
            'mail_from_name' => 'text',
        ];

        foreach ($fields as $field => $type) {
            Setting::set($field, $validated[$field] ?? '', 'smtp', $type);
        }

        // Only update password if a new one is explicitly entered
        if ($request->filled('mail_password')) {
            Setting::set('mail_password', $validated['mail_password'], 'smtp', 'password');
        }

        // Re-apply fresh mail configuration
        $mailConfigService->apply();

        return redirect()->route('admin.settings.smtp')
            ->with('success', 'SMTP configuration has been updated and applied successfully.');
    }

    /**
     * Send a diagnostic test email to verify credentials.
     */
    public function test(SendTestEmailRequest $request, MailConfigService $mailConfigService): RedirectResponse
    {
        $recipient = $request->input('test_email');
        $sendViaQueue = $request->boolean('send_via_queue');

        $result = $mailConfigService->sendTestEmail($recipient, $sendViaQueue);

        if ($result['success']) {
            return redirect()->route('admin.settings.smtp')
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.settings.smtp')
            ->with('error', $result['message'])
            ->withInput();
    }
}
