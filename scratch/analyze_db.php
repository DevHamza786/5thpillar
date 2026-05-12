<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$slugs = ['vision-mission', 'management-team', 'corporate-information'];
foreach ($slugs as $slug) {
    $page = App\Models\Page::where('slug', $slug)->first();
    echo "SLUG: $slug\n";
    if ($page) {
        $content = $page->content;
        echo "Length: " . strlen($content) . "\n";
        echo "Sample: " . substr($content, 0, 2000) . "\n";
    }
    echo "-------------------\n";
}
