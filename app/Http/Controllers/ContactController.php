<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\File;

class ContactController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $rules = [
            'contact_type' => 'required|in:complaint,inquiry',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:80',
            'city' => 'required|string|max:120',
            'message' => 'required|string|max:5000',
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

        $recipient = env('CONTACT_MAIL_TO', 'info@5thpillartakaful.com');

        $typeLabel = $validated['contact_type'] === 'complaint' ? 'Complaint' : 'Inquiry';
        $plain = "Type: {$typeLabel}\r\n"
            ."Name: {$validated['name']}\r\n"
            ."Email: {$validated['email']}\r\n"
            ."Phone: {$validated['phone']}\r\n"
            ."City: {$validated['city']}\r\n\r\n"
            .$validated['message'];

        Mail::raw($plain, function ($message) use ($validated, $recipient, $typeLabel) {
            $message->to($recipient)
                ->replyTo($validated['email'], $validated['name'])
                ->subject('[Website '.$typeLabel.'] '.$validated['name']);
        });

        return back()->with('contact_status', 'Thank you. Your message has been sent. We will get back to you soon.');
    }

    public function sendOnlineComplaint(Request $request): RedirectResponse
    {
        $rules = [
            'pmd' => 'nullable|string|max:120',
            'cnic' => 'required|string|max:32',
            'salutation' => 'required|string|in:Mr.,Mrs.,Miss.',
            'participant_name' => 'required|string|max:255',
            'cell' => 'required|string|max:80',
            'email' => 'required|email|max:255',
            'complaint_details' => 'nullable|string|max:15000',
            'attachment' => ['nullable', File::types(['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'])->max(10240)],
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

        $recipient = env('INVESTOR_COMPLAINT_MAIL_TO', env('CONTACT_MAIL_TO', 'grievance@5thpillartakaful.com'));

        $displayName = trim($validated['salutation'].' '.$validated['participant_name']);

        $pmd = trim((string) ($validated['pmd'] ?? ''));
        $details = trim((string) ($validated['complaint_details'] ?? ''));

        $plain = "Online complaint form submission\r\n\r\n"
            .'PMD: '.($pmd !== '' ? $pmd : '—')."\r\n"
            .'CNIC: '.$validated['cnic']."\r\n"
            .'Participant: '.$displayName."\r\n"
            .'Cell: '.$validated['cell']."\r\n"
            .'Email: '.$validated['email']."\r\n\r\n"
            ."Complaint details:\r\n"
            .($details !== '' ? $details : '—');

        Mail::raw($plain, function ($message) use ($validated, $recipient, $displayName, $request) {
            $message->to($recipient)
                ->replyTo($validated['email'], $displayName)
                ->subject('[Online Complaint Form] '.$displayName);

            $upload = $request->file('attachment');
            if ($upload && $upload->isValid()) {
                $message->attach($upload->getRealPath(), [
                    'as' => $upload->getClientOriginalName(),
                    'mime' => $upload->getClientMimeType() ?: 'application/octet-stream',
                ]);
            }
        });

        return back()->with(
            'online_complaint_status',
            'Thank you. Your complaint has been submitted. We will get back to you soon.'
        );
    }
}
