<?php

namespace App\Services;

use App\Mail\HajjPlanLeadNotification;
use App\Models\HajjPlanLead;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class FormNotificationService
{
    public function __construct(
        private readonly SiteSettingsService $settings
    ) {}

    public function notifyAdminRaw(
        string $formKey,
        string $subject,
        string $body,
        ?string $replyToEmail = null,
        ?string $replyToName = null,
        ?UploadedFile $attachment = null
    ): void {
        $config = $this->settings->formNotification($formKey);

        if (! ($config['enabled'] ?? true)) {
            return;
        }

        $to = trim((string) ($config['to'] ?? ''));
        if ($to === '') {
            return;
        }

        $this->settings->applyMailConfig();

        Mail::raw($body, function ($message) use ($subject, $to, $config, $replyToEmail, $replyToName, $attachment) {
            $message->to($this->parseEmails($to))
                ->subject($subject);

            $cc = $this->parseEmails((string) ($config['cc'] ?? ''));
            if ($cc !== []) {
                $message->cc($cc);
            }

            if ($replyToEmail) {
                $message->replyTo($replyToEmail, $replyToName ?: null);
            }

            if ($attachment && $attachment->isValid()) {
                $message->attach($attachment->getRealPath(), [
                    'as' => $attachment->getClientOriginalName(),
                    'mime' => $attachment->getClientMimeType() ?: 'application/octet-stream',
                ]);
            }
        });
    }

    public function notifyAdminMailable(string $formKey, Mailable $mailable, string|array $fallbackTo = ''): void
    {
        $config = $this->settings->formNotification($formKey);

        if (! ($config['enabled'] ?? true)) {
            return;
        }

        $to = trim((string) ($config['to'] ?? ''));
        if ($to === '') {
            $to = is_array($fallbackTo) ? implode(',', $fallbackTo) : (string) $fallbackTo;
        }

        if ($to === '') {
            return;
        }

        $this->settings->applyMailConfig();

        $mailer = Mail::to($this->parseEmails($to));

        $cc = $this->parseEmails((string) ($config['cc'] ?? ''));
        if ($cc !== []) {
            $mailer->cc($cc);
        }

        $mailer->send($mailable);
    }

    public function notifyPlannerLead(HajjPlanLead $lead, array $response): void
    {
        $formKey = ($lead->plan_type ?? 'hajj') === 'umrah' ? 'umrah_planner' : 'hajj_planner';

        try {
            $this->notifyAdminMailable($formKey, new HajjPlanLeadNotification($lead, $response));
        } catch (\Throwable) {
            // Planner still returns calculation results if mail fails.
        }

        $this->sendUserConfirmation($formKey, $lead->email, $lead->name);
    }

    public function sendUserConfirmation(string $formKey, string $email, ?string $name = null): void
    {
        $config = $this->settings->formNotification($formKey);

        if (! ($config['user_confirmation'] ?? false)) {
            return;
        }

        $subject = trim((string) ($config['user_subject'] ?? ''));
        $message = trim((string) ($config['user_message'] ?? ''));

        if ($subject === '' || $message === '' || $email === '') {
            return;
        }

        $this->settings->applyMailConfig();

        $greeting = $name ? "Dear {$name},\r\n\r\n" : '';

        try {
            Mail::raw($greeting.$message, function ($mail) use ($email, $name, $subject) {
                $mail->to($email, $name ?: null)->subject($subject);
            });
        } catch (\Throwable) {
            // User submission should still succeed.
        }
    }

    /**
     * @return list<string>
     */
    private function parseEmails(string $value): array
    {
        return array_values(array_filter(array_map(
            fn (string $email) => trim($email),
            preg_split('/[;,]+/', $value) ?: []
        )));
    }
}
