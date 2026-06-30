<?php

namespace App\Services;

use Illuminate\Support\Str;

class CmsSectionRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function types(): array
    {
        return config('cms.section_types', []);
    }

    public function hasType(string $type): bool
    {
        return array_key_exists($type, $this->types());
    }

    public function label(string $type): string
    {
        return (string) ($this->types()[$type]['label'] ?? $type);
    }

    public function viewName(string $type): string
    {
        return (string) ($this->types()[$type]['view'] ?? $type);
    }

    /**
     * @return array<string, mixed>
     */
    public function defaultContent(string $type): array
    {
        return match ($type) {
            'text' => [
                'heading' => '',
                'heading_ur' => '',
                'subheading' => '',
                'subheading_ur' => '',
                'content' => '',
                'content_ur' => '',
                'button_text' => '',
                'button_text_ur' => '',
                'button_url' => '',
            ],
            'image' => [
                'image' => '',
                'image_ur' => '',
                'alt' => '',
                'alt_ur' => '',
                'caption' => '',
                'caption_ur' => '',
            ],
            'gallery' => ['images' => []],
            'video' => [
                'video_file' => '',
                'embed_url' => '',
                'thumbnail' => '',
                'caption' => '',
                'caption_ur' => '',
            ],
            'pdf' => [
                'pdf_path' => '',
                'pdf_path_ur' => '',
                'download_label' => 'Download',
                'download_label_ur' => '',
            ],
            'table' => [
                'heading' => '',
                'heading_ur' => '',
                'columns' => ['Column 1', 'Column 2'],
                'columns_ur' => [],
                'rows' => [],
            ],
            'rich_content' => [
                'html' => '',
                'html_ur' => '',
            ],
            'pdf_table' => [
                'title' => '',
                'title_ur' => '',
                'column_label' => 'Document',
                'column_label_ur' => '',
                'download_label' => 'Click Here',
                'download_label_ur' => '',
                'rows' => [],
            ],
            'html' => [
                'html' => '',
                'html_ur' => '',
            ],
            'home_popup' => [
                'image' => 'assets/images/home/cdc-web-banner.webp',
                'alt' => '',
                'alt_ur' => '',
                'aria_label' => 'Home announcement',
                'aria_label_ur' => '',
                'enabled' => true,
            ],
            'hero_slider' => [
                'slides' => [],
            ],
            'home_about_banner' => [
                'kicker' => 'ABOUT',
                'kicker_ur' => '',
                'title' => '5th Pillar',
                'title_ur' => '',
                'title_line2' => 'Family Takaful',
                'title_line2_ur' => '',
                'text' => '',
                'text_ur' => '',
                'bg_image' => 'assets/images/home/Sec-bg.webp',
                'cta_text' => 'More About Us',
                'cta_text_ur' => '',
                'cta_link' => '/about-us',
            ],
            'icon_cards' => [
                'heading' => 'Mission & Vision',
                'heading_ur' => '',
                'cards' => [],
            ],
            'value_chain' => [
                'title' => '',
                'title_ur' => '',
                'image' => '',
                'image_ur' => '',
                'pdf_path' => '',
                'pdf_path_ur' => '',
                'button_label' => 'Download the Value Chain',
                'button_label_ur' => '',
            ],
            'intro_milestones' => [
                'lead' => '',
                'lead_ur' => '',
                'items' => [],
            ],
            'sponsor_band' => [
                'heading' => '',
                'heading_ur' => '',
                'intro' => '',
                'intro_ur' => '',
                'blocks' => [],
                'closing' => '',
                'closing_ur' => '',
                'bg_image' => '',
                'bg_image_ur' => '',
            ],
            'image_band' => [
                'heading' => '',
                'heading_ur' => '',
                'heading_html' => false,
                'image' => '',
                'image_ur' => '',
                'bg_image' => '',
                'bg_image_ur' => '',
                'alt' => '',
            ],
            'text_band' => [
                'heading' => '',
                'heading_ur' => '',
                'text' => '',
                'text_ur' => '',
                'bg_image' => '',
                'bg_image_ur' => '',
                'layout' => 'default',
            ],
            'team_grid' => [
                'members' => [],
            ],
            default => [],
        };
    }

    /**
     * @return array<string, mixed>
     */
    public function defaultSettings(string $type): array
    {
        $homeSlot = match ($type) {
            'home_popup' => 'popup',
            'hero_slider' => 'hero',
            'home_about_banner' => 'about',
            'icon_cards' => 'mission',
            'value_chain' => 'value_chain',
            default => '',
        };

        return match ($type) {
            'text', 'image', 'gallery', 'video', 'pdf', 'table', 'rich_content', 'html' => [
                'role' => 'primary',
            ],
            'pdf_table' => [
                'role' => 'primary',
                'wrapper_class' => 'laravel-financial-statements-page',
            ],
            'home_popup', 'hero_slider', 'home_about_banner', 'icon_cards', 'value_chain' => [
                'role' => 'home',
                'slot' => $homeSlot,
            ],
            'intro_milestones', 'team_grid' => [
                'role' => 'primary',
            ],
            'sponsor_band', 'image_band', 'text_band' => [
                'role' => 'tertiary',
            ],
            default => [
                'role' => 'append',
            ],
        };
    }

    /**
     * @param  array<string, mixed>|null  $content
     * @return array<string, mixed>|null
     */
    public function normalizeContent(string $type, ?array $content): ?array
    {
        if ($content === null) {
            return $type === 'content' ? null : $this->defaultContent($type);
        }

        return match ($type) {
            'text' => $this->normalizeText($content),
            'image' => $this->normalizeImage($content),
            'gallery' => $this->normalizeGallery($content),
            'video' => $this->normalizeVideo($content),
            'pdf' => $this->normalizePdf($content),
            'table' => $this->normalizeTable($content),
            'rich_content' => [
                'html' => (string) ($content['html'] ?? ''),
                'html_ur' => (string) ($content['html_ur'] ?? ''),
            ],
            'pdf_table' => $this->normalizePdfTable($content),
            'html' => [
                'html' => (string) ($content['html'] ?? ''),
                'html_ur' => (string) ($content['html_ur'] ?? ''),
            ],
            'home_popup' => [
                'image' => $this->normalizeAssetPath((string) ($content['image'] ?? '')),
                'alt' => trim((string) ($content['alt'] ?? '')),
                'alt_ur' => trim((string) ($content['alt_ur'] ?? '')),
                'aria_label' => trim((string) ($content['aria_label'] ?? 'Home announcement')),
                'aria_label_ur' => trim((string) ($content['aria_label_ur'] ?? '')),
                'enabled' => filter_var($content['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN),
            ],
            'hero_slider' => $this->normalizeHeroSlider($content),
            'home_about_banner' => $this->normalizeHomeAboutBanner($content),
            'icon_cards' => $this->normalizeIconCards($content),
            'value_chain' => $this->normalizeValueChain($content),
            'intro_milestones' => $this->normalizeIntroMilestones($content),
            'sponsor_band' => $this->normalizeSponsorBand($content),
            'image_band' => $this->normalizeImageBand($content),
            'text_band' => $this->normalizeTextBand($content),
            'team_grid' => $this->normalizeTeamGrid($content),
            'content' => null,
            default => $content,
        };
    }

    /**
     * @param  array<string, mixed>|null  $settings
     * @return array<string, mixed>
     */
    public function normalizeSettings(string $type, ?array $settings): array
    {
        $defaults = $this->defaultSettings($type);
        $settings = $settings ?? [];

        $role = (string) ($settings['role'] ?? $defaults['role'] ?? 'append');
        $allowedRoles = config('cms.section_types.'.$type.'.roles', ['append']);

        if (! in_array($role, $allowedRoles, true)) {
            $role = $defaults['role'] ?? 'append';
        }

        $normalized = array_merge($defaults, $settings, ['role' => $role]);

        if ($type === 'pdf_table') {
            $normalized['wrapper_class'] = trim((string) ($normalized['wrapper_class'] ?? 'laravel-financial-statements-page'));
        }

        if ($role === 'home') {
            $slots = array_keys(config('cms.home_slots', []));
            $slot = trim((string) ($normalized['slot'] ?? $defaults['slot'] ?? ''));

            if ($slot === '' || ! in_array($slot, $slots, true)) {
                $slot = (string) ($defaults['slot'] ?? 'popup');
            }

            $normalized['slot'] = $slot;
        }

        return $normalized;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeText(array $input): array
    {
        return [
            'heading' => trim((string) ($input['heading'] ?? '')),
            'heading_ur' => trim((string) ($input['heading_ur'] ?? '')),
            'subheading' => trim((string) ($input['subheading'] ?? '')),
            'subheading_ur' => trim((string) ($input['subheading_ur'] ?? '')),
            'content' => trim((string) ($input['content'] ?? '')),
            'content_ur' => trim((string) ($input['content_ur'] ?? '')),
            'button_text' => trim((string) ($input['button_text'] ?? '')),
            'button_text_ur' => trim((string) ($input['button_text_ur'] ?? '')),
            'button_url' => trim((string) ($input['button_url'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeImage(array $input): array
    {
        return [
            'image' => $this->normalizeAssetPath((string) ($input['image'] ?? '')),
            'image_ur' => $this->normalizeAssetPath((string) ($input['image_ur'] ?? '')),
            'alt' => trim((string) ($input['alt'] ?? '')),
            'alt_ur' => trim((string) ($input['alt_ur'] ?? '')),
            'caption' => trim((string) ($input['caption'] ?? '')),
            'caption_ur' => trim((string) ($input['caption_ur'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeGallery(array $input): array
    {
        $images = [];

        foreach ((array) ($input['images'] ?? []) as $index => $image) {
            if (! is_array($image)) {
                continue;
            }

            $path = $this->normalizeAssetPath((string) ($image['path'] ?? ''));
            if ($path === '') {
                continue;
            }

            $images[] = [
                'path' => $path,
                'path_ur' => $this->normalizeAssetPath((string) ($image['path_ur'] ?? '')),
                'alt' => trim((string) ($image['alt'] ?? '')),
                'alt_ur' => trim((string) ($image['alt_ur'] ?? '')),
                'sort_order' => (int) ($image['sort_order'] ?? $index),
            ];
        }

        usort($images, fn (array $a, array $b): int => $a['sort_order'] <=> $b['sort_order']);

        return ['images' => $images];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeVideo(array $input): array
    {
        return [
            'video_file' => $this->normalizeAssetPath((string) ($input['video_file'] ?? '')),
            'embed_url' => trim((string) ($input['embed_url'] ?? '')),
            'thumbnail' => $this->normalizeAssetPath((string) ($input['thumbnail'] ?? '')),
            'caption' => trim((string) ($input['caption'] ?? '')),
            'caption_ur' => trim((string) ($input['caption_ur'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizePdf(array $input): array
    {
        return [
            'pdf_path' => $this->normalizeAssetPath((string) ($input['pdf_path'] ?? '')),
            'pdf_path_ur' => $this->normalizeAssetPath((string) ($input['pdf_path_ur'] ?? '')),
            'download_label' => trim((string) ($input['download_label'] ?? 'Download')) ?: 'Download',
            'download_label_ur' => trim((string) ($input['download_label_ur'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeTable(array $input): array
    {
        $columns = array_values(array_filter(array_map(
            static fn ($col) => trim((string) $col),
            (array) ($input['columns'] ?? [])
        )));

        if ($columns === []) {
            $columns = ['Column 1', 'Column 2'];
        }

        $rows = [];
        foreach ((array) ($input['rows'] ?? []) as $row) {
            if (! is_array($row)) {
                continue;
            }

            $cells = array_values(array_map(
                static fn ($cell) => trim((string) $cell),
                (array) ($row['cells'] ?? $row)
            ));

            if ($cells === [] || $cells === ['']) {
                continue;
            }

            $rows[] = ['cells' => $cells];
        }

        return [
            'heading' => trim((string) ($input['heading'] ?? '')),
            'heading_ur' => trim((string) ($input['heading_ur'] ?? '')),
            'columns' => $columns,
            'columns_ur' => array_values(array_map(
                static fn ($col) => trim((string) $col),
                (array) ($input['columns_ur'] ?? [])
            )),
            'rows' => $rows,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizePdfTable(array $input): array
    {
        $rows = [];

        foreach ((array) ($input['rows'] ?? []) as $row) {
            if (! is_array($row)) {
                continue;
            }

            $label = trim((string) ($row['label'] ?? ''));
            $path = $this->normalizeAssetPath((string) ($row['path'] ?? ''));

            if ($label === '' && $path === '') {
                continue;
            }

            $rows[] = [
                'label' => $label,
                'label_ur' => trim((string) ($row['label_ur'] ?? '')),
                'path' => $path,
            ];
        }

        return [
            'title' => trim((string) ($input['title'] ?? '')),
            'title_ur' => trim((string) ($input['title_ur'] ?? '')),
            'column_label' => trim((string) ($input['column_label'] ?? 'Document')) ?: 'Document',
            'column_label_ur' => trim((string) ($input['column_label_ur'] ?? '')),
            'download_label' => trim((string) ($input['download_label'] ?? 'Click Here')) ?: 'Click Here',
            'download_label_ur' => trim((string) ($input['download_label_ur'] ?? '')),
            'rows' => $rows,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeHeroSlider(array $input): array
    {
        $slides = [];

        foreach ((array) ($input['slides'] ?? []) as $slide) {
            if (! is_array($slide)) {
                continue;
            }

            $title = trim((string) ($slide['title'] ?? ''));
            $bg = $this->normalizeAssetPath((string) ($slide['bg'] ?? ''));

            if ($title === '' && $bg === '') {
                continue;
            }

            $slides[] = [
                'subtitle' => trim((string) ($slide['subtitle'] ?? '')),
                'subtitle_ur' => trim((string) ($slide['subtitle_ur'] ?? '')),
                'title' => $title,
                'title_ur' => trim((string) ($slide['title_ur'] ?? '')),
                'title_line2' => trim((string) ($slide['title_line2'] ?? '')),
                'title_line2_ur' => trim((string) ($slide['title_line2_ur'] ?? '')),
                'bg' => $bg,
                'cta_text' => trim((string) ($slide['cta_text'] ?? '')),
                'cta_text_ur' => trim((string) ($slide['cta_text_ur'] ?? '')),
                'cta_link' => trim((string) ($slide['cta_link'] ?? '')),
            ];
        }

        return ['slides' => $slides];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeHomeAboutBanner(array $input): array
    {
        return [
            'kicker' => trim((string) ($input['kicker'] ?? 'ABOUT')),
            'kicker_ur' => trim((string) ($input['kicker_ur'] ?? '')),
            'title' => trim((string) ($input['title'] ?? '')),
            'title_ur' => trim((string) ($input['title_ur'] ?? '')),
            'title_line2' => trim((string) ($input['title_line2'] ?? '')),
            'title_line2_ur' => trim((string) ($input['title_line2_ur'] ?? '')),
            'text' => trim((string) ($input['text'] ?? '')),
            'text_ur' => trim((string) ($input['text_ur'] ?? '')),
            'bg_image' => $this->normalizeAssetPath((string) ($input['bg_image'] ?? '')),
            'cta_text' => trim((string) ($input['cta_text'] ?? 'More About Us')),
            'cta_text_ur' => trim((string) ($input['cta_text_ur'] ?? '')),
            'cta_link' => trim((string) ($input['cta_link'] ?? '/about-us')),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeIconCards(array $input): array
    {
        $cards = [];

        foreach ((array) ($input['cards'] ?? []) as $card) {
            if (! is_array($card)) {
                continue;
            }

            $title = trim((string) ($card['title'] ?? ''));
            $icon = $this->normalizeAssetPath((string) ($card['icon'] ?? ''));

            if ($title === '' && $icon === '') {
                continue;
            }

            $cards[] = [
                'icon' => $icon,
                'title' => $title,
                'title_ur' => trim((string) ($card['title_ur'] ?? '')),
                'text' => trim((string) ($card['text'] ?? '')),
                'text_ur' => trim((string) ($card['text_ur'] ?? '')),
                'icon_class' => trim((string) ($card['icon_class'] ?? '')),
            ];
        }

        return [
            'heading' => trim((string) ($input['heading'] ?? '')),
            'heading_ur' => trim((string) ($input['heading_ur'] ?? '')),
            'cards' => $cards,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeValueChain(array $input): array
    {
        return [
            'title' => trim((string) ($input['title'] ?? '')),
            'title_ur' => trim((string) ($input['title_ur'] ?? '')),
            'image' => $this->normalizeAssetPath((string) ($input['image'] ?? '')),
            'image_ur' => $this->normalizeAssetPath((string) ($input['image_ur'] ?? '')),
            'pdf_path' => $this->normalizeAssetPath((string) ($input['pdf_path'] ?? '')),
            'pdf_path_ur' => $this->normalizeAssetPath((string) ($input['pdf_path_ur'] ?? '')),
            'button_label' => trim((string) ($input['button_label'] ?? 'Download the Value Chain')),
            'button_label_ur' => trim((string) ($input['button_label_ur'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeIntroMilestones(array $input): array
    {
        $items = [];

        foreach ((array) ($input['items'] ?? []) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $text = trim((string) ($item['text'] ?? ''));

            if ($text === '') {
                continue;
            }

            $items[] = [
                'text' => $text,
                'text_ur' => trim((string) ($item['text_ur'] ?? '')),
            ];
        }

        return [
            'lead' => trim((string) ($input['lead'] ?? '')),
            'lead_ur' => trim((string) ($input['lead_ur'] ?? '')),
            'items' => $items,
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeSponsorBand(array $input): array
    {
        $blocks = [];

        foreach ((array) ($input['blocks'] ?? []) as $block) {
            if (! is_array($block)) {
                continue;
            }

            $strong = trim((string) ($block['strong'] ?? ''));
            $text = trim((string) ($block['text'] ?? ''));

            if ($strong === '' && $text === '') {
                continue;
            }

            $blocks[] = [
                'strong' => $strong,
                'strong_ur' => trim((string) ($block['strong_ur'] ?? '')),
                'text' => $text,
                'text_ur' => trim((string) ($block['text_ur'] ?? '')),
            ];
        }

        return [
            'heading' => trim((string) ($input['heading'] ?? '')),
            'heading_ur' => trim((string) ($input['heading_ur'] ?? '')),
            'intro' => trim((string) ($input['intro'] ?? '')),
            'intro_ur' => trim((string) ($input['intro_ur'] ?? '')),
            'blocks' => $blocks,
            'closing' => trim((string) ($input['closing'] ?? '')),
            'closing_ur' => trim((string) ($input['closing_ur'] ?? '')),
            'bg_image' => $this->normalizeAssetPath((string) ($input['bg_image'] ?? '')),
            'bg_image_ur' => $this->normalizeAssetPath((string) ($input['bg_image_ur'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeImageBand(array $input): array
    {
        return [
            'heading' => trim((string) ($input['heading'] ?? '')),
            'heading_ur' => trim((string) ($input['heading_ur'] ?? '')),
            'heading_html' => filter_var($input['heading_html'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'image' => $this->normalizeAssetPath((string) ($input['image'] ?? '')),
            'image_ur' => $this->normalizeAssetPath((string) ($input['image_ur'] ?? '')),
            'bg_image' => $this->normalizeAssetPath((string) ($input['bg_image'] ?? '')),
            'bg_image_ur' => $this->normalizeAssetPath((string) ($input['bg_image_ur'] ?? '')),
            'alt' => trim((string) ($input['alt'] ?? '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeTextBand(array $input): array
    {
        return [
            'heading' => trim((string) ($input['heading'] ?? '')),
            'heading_ur' => trim((string) ($input['heading_ur'] ?? '')),
            'text' => trim((string) ($input['text'] ?? '')),
            'text_ur' => trim((string) ($input['text_ur'] ?? '')),
            'bg_image' => $this->normalizeAssetPath((string) ($input['bg_image'] ?? '')),
            'bg_image_ur' => $this->normalizeAssetPath((string) ($input['bg_image_ur'] ?? '')),
            'layout' => trim((string) ($input['layout'] ?? 'default')) ?: 'default',
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalizeTeamGrid(array $input): array
    {
        $members = [];

        foreach ((array) ($input['members'] ?? []) as $member) {
            if (! is_array($member)) {
                continue;
            }

            $name = trim((string) ($member['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $members[] = [
                'name' => $name,
                'subtitle' => trim((string) ($member['subtitle'] ?? '')),
                'subtitle_ur' => trim((string) ($member['subtitle_ur'] ?? '')),
                'image' => $this->normalizeAssetPath((string) ($member['image'] ?? '')),
                'linkedin' => trim((string) ($member['linkedin'] ?? '')),
            ];
        }

        return ['members' => $members];
    }

    public function normalizeAssetPath(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $path)) {
            $parsed = parse_url($path, PHP_URL_PATH);
            if (is_string($parsed) && str_contains($parsed, '/assets/')) {
                return 'assets/'.ltrim(Str::after($parsed, '/assets/'), '/');
            }

            return $path;
        }

        $path = ltrim($path, '/');

        if (! str_starts_with($path, 'assets/')) {
            $path = 'assets/'.$path;
        }

        return $path;
    }

    public function assetUrl(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }

    /**
     * @param  array<string, mixed>  $content
     */
    public function transContent(array $content, string $key, mixed $default = ''): mixed
    {
        $locale = app()->getLocale();

        if (in_array($locale, ['ur', 'urdu'], true)) {
            $urKey = $key.'_ur';
            if (! empty($content[$urKey])) {
                return $content[$urKey];
            }
        }

        return $content[$key] ?? $default;
    }
}
