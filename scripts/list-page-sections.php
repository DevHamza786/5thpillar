<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$slug = $argv[1] ?? 'about-us';
$page = App\Models\Page::where('slug', $slug)->first();

if (! $page) {
    fwrite(STDERR, "Page not found: {$slug}\n");
    exit(1);
}

foreach ($page->sections as $section) {
    $role = $section->settings['role'] ?? 'append';
    echo "{$section->sort_order}. {$section->section_type} (role={$role}, enabled=".($section->is_enabled ? 'yes' : 'no').")\n";
}
