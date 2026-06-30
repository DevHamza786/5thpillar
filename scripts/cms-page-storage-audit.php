<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pages = App\Models\Page::query()->orderBy('slug')->get();
$withSections = 0;
$withContent = 0;
$sectionPages = [];

foreach ($pages as $page) {
    $sectionCount = $page->sections()->count();
    $hasContent = filled($page->content);
    if ($sectionCount > 0) {
        $withSections++;
        $sectionPages[$page->slug] = [
            'sections' => $sectionCount,
            'types' => $page->sections()->pluck('section_type')->unique()->values()->all(),
            'has_content' => $hasContent,
            'view_key' => $page->view_key,
        ];
    }
    if ($hasContent) {
        $withContent++;
    }
}

echo "Total pages: {$pages->count()}\n";
echo "Pages with sections: {$withSections}\n";
echo "Pages with legacy content: {$withContent}\n\n";
echo "Pages with sections:\n";
foreach ($sectionPages as $slug => $info) {
    echo "  {$slug}: {$info['sections']} sections [" . implode(', ', $info['types']) . "] content=" . ($info['has_content'] ? 'yes' : 'no') . "\n";
}
