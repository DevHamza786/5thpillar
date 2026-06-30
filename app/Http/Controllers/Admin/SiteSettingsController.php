<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingsService;
use App\Services\UrduLocaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SiteSettingsController extends Controller
{
    public function email(SiteSettingsService $settings): View
    {
        return view('admin.settings.email', [
            'forms' => $settings->formNotifications(),
            'mail' => $settings->mailForAdminForm(),
            'mailers' => [
                'log' => 'Log only (development)',
                'smtp' => 'SMTP',
                'sendmail' => 'Sendmail',
            ],
        ]);
    }

    public function updateEmail(Request $request, SiteSettingsService $settings): RedirectResponse
    {
        $validated = $request->validate([
            'mail' => ['required', 'array'],
            'mail.mailer' => ['required', 'string', 'in:log,smtp,sendmail'],
            'mail.host' => ['nullable', 'string', 'max:255'],
            'mail.port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'mail.username' => ['nullable', 'string', 'max:255'],
            'mail.password' => ['nullable', 'string', 'max:255'],
            'mail.encryption' => ['nullable', 'string', 'max:20'],
            'mail.from_address' => ['required', 'email', 'max:255'],
            'mail.from_name' => ['required', 'string', 'max:255'],
            'forms' => ['required', 'array'],
            'forms.*.enabled' => ['nullable'],
            'forms.*.to' => ['nullable', 'string', 'max:500'],
            'forms.*.cc' => ['nullable', 'string', 'max:500'],
            'forms.*.user_confirmation' => ['nullable'],
            'forms.*.user_subject' => ['nullable', 'string', 'max:255'],
            'forms.*.user_message' => ['nullable', 'string', 'max:2000'],
        ]);

        $settings->saveMail($validated['mail']);
        $settings->saveFormNotifications($validated['forms']);

        return back()->with('status', __('Email and form notification settings saved.'));
    }

    public function testEmail(Request $request, SiteSettingsService $settings): RedirectResponse
    {
        $validated = $request->validate([
            'test_email' => ['required', 'email', 'max:255'],
        ]);

        $settings->applyMailConfig();

        try {
            Mail::raw(
                __('This is a test email from :app admin panel. Your mail settings are working.', ['app' => config('app.name')]),
                function ($message) use ($validated) {
                    $message->to($validated['test_email'])
                        ->subject(__('Test email — :app', ['app' => config('app.name')]));
                }
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['test_email' => $e->getMessage()]);
        }

        return back()->with('status', __('Test email sent to :email', ['email' => $validated['test_email']]));
    }

    public function urdu(UrduLocaleService $urdu): View
    {
        return view('admin.settings.urdu', [
            'locale' => $urdu->forAdminForm(),
            'routeDefinitions' => $urdu->routeSlugDefinitions(),
            'pages' => \App\Models\Page::query()
                ->orderBy('title')
                ->get(['id', 'title', 'slug', 'slug_ur', 'status', 'status_ur']),
        ]);
    }

    public function updateUrdu(Request $request, SiteSettingsService $settings): RedirectResponse
    {
        $validated = $request->validate([
            'enabled' => ['nullable'],
            'prefix' => ['required', 'string', 'max:64', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'route_slugs' => ['nullable', 'array'],
            'route_slugs.*' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\-_]+(?:[\p{L}\p{N}\-_]+)*$/u'],
        ]);

        $settings->saveLocale($validated);

        return back()->with('status', __('Urdu URL settings saved. Run php artisan route:clear if routes were cached.'));
    }
}
