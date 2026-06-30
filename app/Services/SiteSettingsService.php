<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class SiteSettingsService
{
    private const CACHE_KEY = 'site_settings.all';

    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, function () {
            $stored = SiteSetting::query()->pluck('value', 'key')->all();

            return [
                'form_notifications' => $this->mergeFormNotifications($stored['form_notifications'] ?? null),
                'mail' => $this->mergeMail($stored['mail'] ?? null),
                'locale' => $this->mergeLocale($stored['locale'] ?? null),
            ];
        });
    }

    public function formNotifications(): array
    {
        return $this->all()['form_notifications'];
    }

    public function formNotification(string $key): array
    {
        $forms = $this->formNotifications();

        return $forms[$key] ?? (config('site.form_notifications.'.$key) ?? []);
    }

    public function mail(): array
    {
        return $this->all()['mail'];
    }

    public function locale(): array
    {
        return $this->all()['locale'];
    }

    public function saveLocale(array $input): void
    {
        $defaults = config('site.locale', []);
        $current = $this->locale();
        $prefix = Str::lower(trim((string) ($input['prefix'] ?? $current['prefix'] ?? 'urdu'), '/'));
        $prefix = preg_replace('/[^a-z0-9\-]+/u', '-', $prefix) ?? 'urdu';
        $prefix = trim($prefix, '-');

        if ($prefix === '') {
            $prefix = 'urdu';
        }

        $routeSlugs = [];
        foreach (array_keys($defaults['urdu_route_slugs'] ?? []) as $key) {
            $routeSlugs[$key] = trim((string) ($input['route_slugs'][$key] ?? $current['route_slugs'][$key] ?? $key));
            if ($routeSlugs[$key] === '') {
                $routeSlugs[$key] = (string) ($defaults['urdu_route_slugs'][$key] ?? $key);
            }
        }

        $this->persist('locale', [
            'enabled' => filter_var($input['enabled'] ?? $current['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'prefix' => $prefix,
            'route_slugs' => $routeSlugs,
        ]);

        config(['site.locale' => array_merge($defaults, [
            'enabled' => filter_var($input['enabled'] ?? $current['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'prefix' => $prefix,
            'route_slugs' => $routeSlugs,
        ])]);
    }

    public function saveFormNotifications(array $input): void
    {
        $defaults = config('site.form_notifications', []);
        $normalized = [];

        foreach ($defaults as $key => $default) {
            $row = (array) ($input[$key] ?? []);
            $normalized[$key] = [
                'label' => $default['label'] ?? $key,
                'enabled' => filter_var($row['enabled'] ?? $default['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'to' => trim((string) ($row['to'] ?? $default['to'] ?? '')),
                'cc' => trim((string) ($row['cc'] ?? $default['cc'] ?? '')),
                'user_confirmation' => filter_var($row['user_confirmation'] ?? $default['user_confirmation'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'user_subject' => trim((string) ($row['user_subject'] ?? $default['user_subject'] ?? '')),
                'user_message' => trim((string) ($row['user_message'] ?? $default['user_message'] ?? '')),
            ];
        }

        $this->persist('form_notifications', $normalized);
    }

    public function saveMail(array $input): void
    {
        $current = $this->mail();
        $password = trim((string) ($input['password'] ?? ''));

        $mail = [
            'mailer' => trim((string) ($input['mailer'] ?? $current['mailer'] ?? 'log')) ?: 'log',
            'host' => trim((string) ($input['host'] ?? $current['host'] ?? '')),
            'port' => (int) ($input['port'] ?? $current['port'] ?? 587),
            'username' => trim((string) ($input['username'] ?? $current['username'] ?? '')),
            'encryption' => trim((string) ($input['encryption'] ?? $current['encryption'] ?? 'tls')),
            'from_address' => trim((string) ($input['from_address'] ?? $current['from_address'] ?? '')),
            'from_name' => trim((string) ($input['from_name'] ?? $current['from_name'] ?? '')),
            'password' => $password !== '' ? Crypt::encryptString($password) : ($current['password'] ?? ''),
        ];

        $this->persist('mail', $mail);
    }

    public function applyMailConfig(): void
    {
        $mail = $this->mail();
        $password = $this->decryptPassword((string) ($mail['password'] ?? ''));

        config([
            'mail.default' => $mail['mailer'] ?: 'log',
            'mail.from.address' => $mail['from_address'] ?: config('mail.from.address'),
            'mail.from.name' => $mail['from_name'] ?: config('mail.from.name'),
            'mail.mailers.smtp.host' => $mail['host'] ?: config('mail.mailers.smtp.host'),
            'mail.mailers.smtp.port' => (int) ($mail['port'] ?: config('mail.mailers.smtp.port')),
            'mail.mailers.smtp.username' => $mail['username'] ?: config('mail.mailers.smtp.username'),
            'mail.mailers.smtp.password' => $password ?: config('mail.mailers.smtp.password'),
            'mail.mailers.smtp.encryption' => $mail['encryption'] ?: config('mail.mailers.smtp.encryption'),
        ]);
    }

    public function mailForAdminForm(): array
    {
        $mail = $this->mail();
        $mail['password'] = $this->decryptPassword((string) ($mail['password'] ?? ''));

        return $mail;
    }

    private function mergeFormNotifications(?array $stored): array
    {
        $defaults = config('site.form_notifications', []);
        $merged = [];

        foreach ($defaults as $key => $default) {
            $merged[$key] = array_merge($default, (array) ($stored[$key] ?? []));
        }

        return $merged;
    }

    private function mergeMail(?array $stored): array
    {
        return array_merge(config('site.mail', []), $stored ?? []);
    }

    private function mergeLocale(?array $stored): array
    {
        $defaults = config('site.locale', []);
        $merged = array_merge($defaults, $stored ?? []);

        if (isset($merged['route_slugs']) && is_array($merged['route_slugs'])) {
            $merged['route_slugs'] = array_merge(
                $defaults['urdu_route_slugs'] ?? [],
                $merged['route_slugs']
            );
        }

        return $merged;
    }

    private function persist(string $key, array $value): void
    {
        SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    private function decryptPassword(string $value): string
    {
        if ($value === '') {
            return '';
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return $value;
        }
    }
}
