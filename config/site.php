<?php

return [
    'backup' => [
        'disk' => 'local',
        'directory' => 'backups',
        'include_paths' => [
            'public/assets',
            'storage/app/public',
        ],
        'retain_count' => 10,
    ],

    'form_notifications' => [
        'inquiry' => [
            'label' => 'Contact — Inquiry',
            'enabled' => true,
            'to' => env('CONTACT_MAIL_TO', 'info@5thpillartakaful.com'),
            'cc' => '',
            'user_confirmation' => true,
            'user_subject' => 'Thank you for contacting 5th Pillar Family Takaful',
            'user_message' => 'We have received your inquiry and will get back to you soon.',
        ],
        'complaint' => [
            'label' => 'Contact — Complaint',
            'enabled' => true,
            'to' => env('CONTACT_MAIL_TO', 'info@5thpillartakaful.com'),
            'cc' => '',
            'user_confirmation' => true,
            'user_subject' => 'Your complaint has been received',
            'user_message' => 'Thank you. Your complaint has been submitted. We will get back to you soon.',
        ],
        'online_complaint' => [
            'label' => 'Online complaint form',
            'enabled' => true,
            'to' => env('INVESTOR_COMPLAINT_MAIL_TO', env('CONTACT_MAIL_TO', 'grievance@5thpillartakaful.com')),
            'cc' => '',
            'user_confirmation' => true,
            'user_subject' => 'Online complaint received',
            'user_message' => 'Thank you. Your online complaint has been submitted. We will get back to you soon.',
        ],
        'brochure_lead' => [
            'label' => 'Brochure download modal',
            'enabled' => true,
            'to' => env('CONTACT_MAIL_TO', 'info@5thpillartakaful.com'),
            'cc' => '',
            'user_confirmation' => false,
            'user_subject' => 'Your brochure download',
            'user_message' => 'Thank you for your interest in 5th Pillar Family Takaful.',
        ],
        'hajj_planner' => [
            'label' => 'Hajj planner form',
            'enabled' => true,
            'to' => env('CONTACT_MAIL_TO', 'info@5thpillartakaful.com'),
            'cc' => '',
            'user_confirmation' => true,
            'user_subject' => 'Your Hajj planner request',
            'user_message' => 'Thank you. We have received your Hajj planner details and will contact you if needed.',
        ],
        'umrah_planner' => [
            'label' => 'Umrah planner form',
            'enabled' => true,
            'to' => env('CONTACT_MAIL_TO', 'info@5thpillartakaful.com'),
            'cc' => '',
            'user_confirmation' => true,
            'user_subject' => 'Your Umrah planner request',
            'user_message' => 'Thank you. We have received your Umrah planner details and will contact you if needed.',
        ],
        'account_deletion' => [
            'label' => 'Account deletion request',
            'enabled' => true,
            'to' => env('CONTACT_MAIL_TO', 'info@5thpillartakaful.com'),
            'cc' => '',
            'user_confirmation' => true,
            'user_subject' => 'Your account deletion request',
            'user_message' => 'We have received your account deletion request. Our team will process it and contact you if any further information is required.',
        ],
    ],

    'mail' => [
        'mailer' => env('MAIL_MAILER', 'log'),
        'host' => env('MAIL_HOST', '127.0.0.1'),
        'port' => (int) env('MAIL_PORT', 2525),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'from_address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'from_name' => env('MAIL_FROM_NAME', env('APP_NAME', '5th Pillar')),
    ],

    'locale' => [
        'enabled' => env('URDU_LOCALE_ENABLED', true),
        'prefix' => env('URDU_URL_PREFIX', 'urdu'),
        // Temporary: send all /urdu/* public pages to the live WordPress site.
        'redirect_to_live' => env('URDU_REDIRECT_TO_LIVE', false),
        'live_base_url' => rtrim((string) env('URDU_LIVE_BASE_URL', 'https://5thpillartakaful.com'), '/'),
        // Slugs that must render from Laravel (Urdu) even while redirect_to_live
        // is on — i.e. new pages that do not exist on the WordPress site.
        'redirect_to_live_except' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('URDU_REDIRECT_TO_LIVE_EXCEPT', 'digital-savings'))
        ))),
        'urdu_route_slugs' => [
            'hajj-planner' => 'hajj-planner',
            'umrah-planner' => 'umrah-planner',
            'news-and-events' => 'news-and-events',
            'online-complaint-form' => 'online-complaint-form',
        ],
    ],
];
