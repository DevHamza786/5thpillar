<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class BrochureLeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $allowedKeys = array_keys(config('brochures.pdfs', []));
        if ($allowedKeys === []) {
            abort(404);
        }

        $cities = config('brochures.cities', []);

        $cityRules = ['required', 'string', 'max:120'];
        if ($cities !== []) {
            $cityRules[] = Rule::in($cities);
        }

        $rules = [
            'brochure_key' => 'required|in:'.implode(',', $allowedKeys),
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:80',
            'address' => 'nullable|string|max:500',
            'gender' => 'required|in:male,female',
            'city' => $cityRules,
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
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

        $pdfRelative = config('brochures.pdfs.'.$validated['brochure_key']);
        $fullPath = public_path($pdfRelative);
        if (! is_file($fullPath)) {
            return back()
                ->withErrors(['brochure' => 'This brochure file is not available yet. Please try again later or contact us.'])
                ->withInput();
        }

        $recipient = env('CONTACT_MAIL_TO', 'info@5thpillartakaful.com');
        $lines = [
            'Brochure download (lead form)',
            'Brochure: '.$validated['brochure_key'],
            'Name: '.$validated['name'],
            'Email: '.$validated['email'],
            'Phone: '.$validated['phone'],
            'Address: '.($validated['address'] ?? '—'),
            'Gender: '.$validated['gender'],
            'City: '.$validated['city'],
            'Company: '.($validated['company'] ?? '—'),
            'Job title: '.($validated['job_title'] ?? '—'),
        ];
        $plain = implode("\r\n", $lines);

        try {
            Mail::raw($plain, function ($message) use ($validated, $recipient) {
                $message->to($recipient)
                    ->replyTo($validated['email'], $validated['name'])
                    ->subject('[Brochure download] '.$validated['name'].' — '.$validated['brochure_key']);
            });
        } catch (\Throwable) {
            // Still allow PDF download if mail is misconfigured locally
        }

        return redirect()->to(asset($pdfRelative));
    }
}
