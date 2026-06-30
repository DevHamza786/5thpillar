<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Services\FormNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AccountDeletionController extends Controller
{
    public function __construct(
        private readonly FormNotificationService $notifier
    ) {}

    public function send(Request $request): RedirectResponse
    {
        $rules = [
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:80',
            'reason' => 'required|string|max:5000',
        ];

        if (config('services.recaptcha.secret_key')) {
            $rules['g-recaptcha-response'] = 'required|string';
        }

        $validated = $request->validate($rules);

        if ($secret = config('services.recaptcha.secret_key')) {
            $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            if (! ($verify->json('success') ?? false)) {
                return back()
                    ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])
                    ->withInput();
            }
        }

        FormSubmission::create([
            'form_type' => FormSubmission::TYPE_ACCOUNT_DELETION,
            'name' => $validated['email'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'city' => null,
            'message' => $validated['reason'],
        ]);

        $plain = "Account deletion request\r\n\r\n"
            .'Email: '.$validated['email']."\r\n"
            .'Phone: '.$validated['phone']."\r\n\r\n"
            ."Reason for deletion:\r\n"
            .$validated['reason'];

        $this->notifier->notifyAdminRaw(
            'account_deletion',
            '[Account Deletion Request] '.$validated['email'],
            $plain,
            $validated['email']
        );

        $this->notifier->sendUserConfirmation('account_deletion', $validated['email']);

        return back()->with(
            'account_deletion_status',
            'Thank you. Your account deletion request has been submitted. Our team will contact you shortly.'
        );
    }
}
