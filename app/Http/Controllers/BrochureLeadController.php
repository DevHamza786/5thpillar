<?php

namespace App\Http\Controllers;

use App\Models\BrochureLead;
use App\Services\FormNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use App\Support\PublicPath;

class BrochureLeadController extends Controller
{
    public function __construct(
        private readonly FormNotificationService $notifier
    ) {}
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $allowedKeys = array_keys(config('brochures.pdfs', []));
        if ($allowedKeys === []) {
            abort(404);
        }

        $wantsJson = $request->ajax() || $request->wantsJson();

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
                if ($wantsJson) {
                    return response()->json([
                        'message' => 'reCAPTCHA verification failed. Please try again.',
                        'errors' => ['g-recaptcha-response' => ['reCAPTCHA verification failed. Please try again.']],
                    ], 422);
                }

                return back()
                    ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])
                    ->withInput();
            }
        }

        $pdfRelative = config('brochures.pdfs.'.$validated['brochure_key']);
        $fullPath = public_path($pdfRelative);
        if (! is_file($fullPath)) {
            if ($wantsJson) {
                return response()->json([
                    'message' => 'This brochure file is not available yet.',
                    'errors' => ['brochure' => ['This brochure file is not available yet. Please try again later or contact us.']],
                ], 422);
            }

            return back()
                ->withErrors(['brochure' => 'This brochure file is not available yet. Please try again later or contact us.'])
                ->withInput();
        }

        BrochureLead::create($validated);

        $lines = [
            'Brochure download (lead form)',
            'Brochure: '.$validated['brochure_key'],
            'Name: '.$validated['name'],
            'Email: '.$validated['email'],
            'Phone: '.$validated['phone'],
            'Address: '.($validated['address'] ?? '—'),
            'Gender: '.$validated['gender'],
            'City: '.$validated['city'],
        ];
        $plain = implode("\r\n", $lines);

        try {
            $this->notifier->notifyAdminRaw(
                'brochure_lead',
                '[Brochure download] '.$validated['name'].' — '.$validated['brochure_key'],
                $plain,
                $validated['email'],
                $validated['name']
            );
            $this->notifier->sendUserConfirmation('brochure_lead', $validated['email'], $validated['name']);
        } catch (\Throwable) {
            // Still allow PDF download if mail is misconfigured locally
        }

        $pdfUrl = PublicPath::uploadHref($pdfRelative);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['pdf_url' => $pdfUrl]);
        }

        return redirect()->to($pdfUrl);
    }
}
