<?php

return [
    'fund_managers_report_slug' => env('FUND_MANAGERS_REPORT_SLUG', 'fund-managers-report'),
    'daily_fund_prices_slug' => env('DAILY_FUND_PRICES_SLUG', 'daily-fund-prices'),

    'page_statuses' => [
        'draft' => 'Draft',
        'published' => 'Published',
    ],

    /*
    |--------------------------------------------------------------------------
    | Section types (Phase 2)
    |--------------------------------------------------------------------------
    | Each type defines admin fields and a frontend partial under
    | resources/views/pages/partials/sections/{view}.blade.php
    */
    'section_types' => [
        'text' => [
            'label' => 'Text section',
            'description' => 'Heading, subheading, body text, and optional button.',
            'view' => 'text',
            'roles' => ['primary', 'append', 'tertiary'],
        ],
        'image' => [
            'label' => 'Image section',
            'description' => 'Single image with alt text and optional caption.',
            'view' => 'image',
            'roles' => ['primary', 'append', 'tertiary'],
        ],
        'gallery' => [
            'label' => 'Gallery section',
            'description' => 'Multiple images with sort order.',
            'view' => 'gallery',
            'roles' => ['primary', 'append', 'tertiary'],
        ],
        'video' => [
            'label' => 'Video section',
            'description' => 'Uploaded video file or embed URL with thumbnail.',
            'view' => 'video',
            'roles' => ['primary', 'append', 'tertiary'],
        ],
        'pdf' => [
            'label' => 'PDF download',
            'description' => 'Single PDF file with download label.',
            'view' => 'pdf',
            'roles' => ['primary', 'append', 'tertiary'],
        ],
        'table' => [
            'label' => 'Data table',
            'description' => 'Dynamic columns and rows.',
            'view' => 'table',
            'roles' => ['primary', 'append', 'tertiary'],
        ],
        'rich_content' => [
            'label' => 'Rich content',
            'description' => 'WYSIWYG editor for formatted content, images, and links.',
            'view' => 'rich-content',
            'roles' => ['primary', 'append', 'tertiary'],
        ],
        'content' => [
            'label' => 'Text block',
            'description' => 'Heading and rich HTML body.',
            'view' => 'content',
            'roles' => ['append'],
        ],
        'html' => [
            'label' => 'HTML block',
            'description' => 'Free-form HTML stored in structured content.',
            'view' => 'html',
            'roles' => ['append', 'primary'],
        ],
        'pdf_table' => [
            'label' => 'PDF download table',
            'description' => 'Two-column table with document labels and download links.',
            'view' => 'pdf-table',
            'roles' => ['primary', 'append'],
        ],
        'home_popup' => [
            'label' => 'Home popup banner',
            'description' => 'Modal popup image on the homepage.',
            'view' => 'home-popup',
            'roles' => ['home'],
        ],
        'hero_slider' => [
            'label' => 'Hero slider',
            'description' => 'Homepage masthead slides with CTA buttons.',
            'view' => 'hero-slider',
            'roles' => ['home'],
        ],
        'home_about_banner' => [
            'label' => 'Home about banner',
            'description' => 'About strip below the homepage slider.',
            'view' => 'home-about-banner',
            'roles' => ['home'],
        ],
        'icon_cards' => [
            'label' => 'Icon cards',
            'description' => 'Mission / vision style icon cards.',
            'view' => 'icon-cards',
            'roles' => ['home', 'append'],
        ],
        'value_chain' => [
            'label' => 'Value chain panel',
            'description' => 'Animated value chain image with PDF download.',
            'view' => 'value-chain',
            'roles' => ['home', 'append'],
        ],
        'intro_milestones' => [
            'label' => 'Intro + milestones',
            'description' => 'Lead paragraph and bullet milestones.',
            'view' => 'intro-milestones',
            'roles' => ['primary'],
        ],
        'sponsor_band' => [
            'label' => 'Sponsors band',
            'description' => 'Full-width sponsors content band.',
            'view' => 'sponsor-band',
            'roles' => ['tertiary'],
        ],
        'image_band' => [
            'label' => 'Image band',
            'description' => 'Full-width heading and image band.',
            'view' => 'image-band',
            'roles' => ['tertiary'],
        ],
        'text_band' => [
            'label' => 'Text band',
            'description' => 'Full-width text content band.',
            'view' => 'text-band',
            'roles' => ['tertiary'],
        ],
        'team_grid' => [
            'label' => 'Team grid',
            'description' => 'Management team member cards.',
            'view' => 'team-grid',
            'roles' => ['primary'],
        ],
    ],

    'section_roles' => [
        'primary' => 'Main page content',
        'append' => 'Below main content',
        'tertiary' => 'Full-width bands (below page)',
        'home' => 'Homepage section',
    ],

    'home_slots' => [
        'popup' => 'Popup banner',
        'hero' => 'Hero slider',
        'about' => 'About banner',
        'mission' => 'Mission & vision cards',
        'value_chain' => 'Value chain panel',
    ],

    /*
    |--------------------------------------------------------------------------
    | Data tables (Phase 3)
    |--------------------------------------------------------------------------
    */
    'tables' => [
        'fund_prices_archive' => [
            'label' => 'Fund price archives',
            'description' => 'Historical daily fund prices grouped by year and month for archive pages.',
            'settings' => [
                'group_by' => ['year', 'month'],
                'filters' => ['year', 'month'],
            ],
            'columns' => [
                ['key' => 'year', 'label' => 'Year', 'type' => 'integer', 'required' => true],
                ['key' => 'month', 'label' => 'Month', 'type' => 'string', 'required' => true],
                ['key' => 'date', 'label' => 'Date label', 'type' => 'string', 'required' => true],
                ['key' => 'agg_bid', 'label' => 'Aggressive bid', 'type' => 'string'],
                ['key' => 'agg_offer', 'label' => 'Aggressive offer', 'type' => 'string'],
                ['key' => 'bal_bid', 'label' => 'Balanced bid', 'type' => 'string'],
                ['key' => 'bal_offer', 'label' => 'Balanced offer', 'type' => 'string'],
                ['key' => 'con_bid', 'label' => 'Conservative bid', 'type' => 'string'],
                ['key' => 'con_offer', 'label' => 'Conservative offer', 'type' => 'string'],
                ['key' => 'brk_bid', 'label' => 'Barkat bid', 'type' => 'string'],
                ['key' => 'brk_offer', 'label' => 'Barkat offer', 'type' => 'string'],
            ],
        ],
    ],

    'media' => [
        'max_upload_kb' => 51200,
        'image_folders' => [
            'images' => 'Images (root)',
            'images/home' => 'Home',
            'images/products' => 'Products',
            'images/news' => 'News',
            'images/team' => 'Team',
            'images/banners' => 'Banners',
        ],
        'pdf_folders' => [
            'pdf' => 'PDFs (root)',
            'pdf/reports' => 'Reports',
            'pdf/forms' => 'Forms',
            'pdf/investors' => 'Investors',
        ],
        'allowed_image_mimes' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
            'image/svg+xml',
        ],
        'allowed_pdf_mimes' => [
            'application/pdf',
            'application/x-pdf',
        ],
    ],

    /*
    | Legacy WordPress demo / placeholder pages (slug = view_key).
    | Run: php artisan cms:purge-legacy-pages
    */
    'legacy_demo_slugs' => [
        'appointment', 'bancatakaful', 'bancatakaful-tester', 'bank-islami', 'blogs',
        'book-a-call', 'brochure', 'chess-2', 'chess-6', 'classic-1', 'classic-2', 'classic-3',
        'cobbles', 'delete-my-account', 'demo', 'digital-products', 'donate',
        'donation-confirmation', 'donation-confirmation-2', 'donation-failed',
        'donation-failed-2', 'donation-history', 'donor-dashboard', 'donor-dashboard-2',
        'download-our-brochure', 'dubai-islamic-bank', 'eventts', 'federal-insurance-ombudsman',
        'grid', 'group-family-takaful', 'group-takaful-plans', 'haazir-hajj-savings-plan',
        'hajj-planner', 'home-2', 'home-3-boxed', 'home-4', 'home-5',
        'home-elementor', 'home-tourism', 'individual-banca-takaful', 'list-of-authorized-agents',
        'masonry', 'our-events', 'our-events-2', 'our-services', 'partners', 'portfolio-2',
        'portfolio-3', 'portfolio-4', 'programs', 'protection-plans', 'search-facility',
        'service-plus', 'shortcodes', 'sticky-buttons', 'traditional-saving-plan',
        'typography', '__trashed',
    ],
];
