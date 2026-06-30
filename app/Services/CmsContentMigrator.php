<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageSection;
use DOMDocument;
use DOMXPath;

class CmsContentMigrator
{
    public function __construct(
        private readonly CmsSectionRegistry $registry
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function sectionsForPage(Page $page): array
    {
        $slug = $page->templateSlug();

        return match ($slug) {
            'about-us' => $this->aboutUsSections(),
            default => $this->parseHtmlSections((string) $page->content),
        };
    }

    public function migrate(Page $page, bool $force = false, bool $dryRun = false): int
    {
        if ($page->sections()->exists() && ! $force) {
            return 0;
        }

        if ($force && ! $dryRun) {
            $page->sections()->delete();
        }

        $definitions = $this->sectionsForPage($page);

        if ($definitions === []) {
            return 0;
        }

        if ($dryRun) {
            return count($definitions);
        }

        foreach ($definitions as $index => $definition) {
            $type = (string) ($definition['section_type'] ?? 'rich_content');
            $page->sections()->create([
                'section_type' => $type,
                'heading' => $definition['heading'] ?? null,
                'heading_ur' => $definition['heading_ur'] ?? null,
                'body_html' => $definition['body_html'] ?? null,
                'body_html_ur' => $definition['body_html_ur'] ?? null,
                'content' => $this->registry->normalizeContent($type, $definition['content'] ?? []),
                'settings' => $this->registry->normalizeSettings($type, $definition['settings'] ?? null),
                'is_enabled' => true,
                'sort_order' => $index,
            ]);
        }

        return count($definitions);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function aboutUsSections(): array
    {
        return [
            [
                'section_type' => 'intro_milestones',
                'settings' => ['role' => 'primary'],
                'content' => [
                    'lead' => '5th Pillar Family Takaful Limited is a new entrant into the Family Takaful sector of Pakistan which is supported by eminent business houses from Kuwait and Pakistan. The company has set industry records with remarkable milestones such as:',
                    'items' => [
                        ['text' => 'Largest FDI in Takaful sector of Pakistan'],
                        ['text' => 'Foreign shareholders own 68% of 5th Pillar Takaful and 32% is held by Pakistani interests'],
                        ['text' => 'Largest initial paid up capital of Rs 2.00 billion in Pakistan’s Takaful sector history'],
                        ['text' => 'Highest initial credit rating “A+ Stable outlook” from Pakistan Credit Rating Agency (PACRA)'],
                        ['text' => 'Licensed by the SECP to underwrite Shariah compliant Family Takaful business in Pakistan'],
                        ['text' => 'State of the art IT platform to support business operations throughout the membership lifecycle'],
                        ['text' => 'Upcoming customer engagement mobile app/web portal to provide 24/7 information and assistance to members from the comfort of their homes'],
                    ],
                ],
            ],
            [
                'section_type' => 'sponsor_band',
                'settings' => ['role' => 'tertiary'],
                'content' => [
                    'heading' => 'Our Sponsors',
                    'intro' => '5th Pillar Family Takaful Limited is backed by distinguished sponsors:',
                    'blocks' => [
                        ['strong' => 'Kuwait International Investment Holding Company (KIIC)', 'text' => ' is a leading investment company headquartered in Kuwait City, Kuwait.'],
                        ['strong' => 'Al Bahar Group', 'text' => ' formerly known as IFA Group, is a Kuwait-based company incorporated in 1974.'],
                        ['strong' => '5th Pillar Holding DIFC Dubai, UAE', 'text' => ' is a special purpose company which has been set up by renowned business houses from Kuwait.'],
                        ['strong' => 'Muhammadi Family & Associates', 'text' => ' include the Muhammadi Family who have been doing business in the Takaful/Insurance sector for over three generations in Pakistan.'],
                    ],
                    'closing' => 'This significant financial backing has allowed the company to invest in cutting-edge technology and develop innovative products in order to provide unparalleled customer service to its clients.',
                ],
            ],
            [
                'section_type' => 'image_band',
                'settings' => ['role' => 'tertiary'],
                'content' => [
                    'heading' => 'The Road Map To Our<br>End-to-End Value Chain',
                    'heading_html' => true,
                    'image' => 'assets/images/about/5th-Pillar-End-to-End-Value-Chain-1.webp',
                    'alt' => '5th Pillar End-to-End Value Chain',
                ],
            ],
            [
                'section_type' => 'text_band',
                'settings' => ['role' => 'tertiary', 'layout' => 'retakaful'],
                'content' => [
                    'heading' => 'ReTakaful Arrangements',
                    'text' => 'We have made ReTakaful arrangements with Hannover Re (world’s renowned ReTakaful Company) which allows us to enjoy the expertise of one of the most progressive institutions across the globe.',
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function parseHtmlSections(string $html): array
    {
        $html = trim($html);
        if ($html === '') {
            return [];
        }

        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $sections = [];

        $headingNodes = $xpath->query('//h1|//h2|//h3|//h4');

        if ($headingNodes === false || $headingNodes->length === 0) {
            return [[
                'section_type' => 'rich_content',
                'settings' => ['role' => 'primary'],
                'content' => ['html' => $html],
            ]];
        }

        for ($i = 0; $i < $headingNodes->length; $i++) {
            $headingNode = $headingNodes->item($i);
            if ($headingNode === null) {
                continue;
            }

            $heading = trim(preg_replace('/\s+/u', ' ', $headingNode->textContent ?? ''));
            if ($heading === '') {
                continue;
            }

            $chunkHtml = $this->collectHtmlUntilNextHeading($headingNode, $headingNodes, $i);
            $sections[] = $this->classifySection($heading, $chunkHtml);
        }

        return $sections;
    }

    private function collectHtmlUntilNextHeading(\DOMNode $headingNode, \DOMNodeList $allHeadings, int $index): string
    {
        $parts = [];
        $stop = $allHeadings->item($index + 1);
        $cursor = $headingNode->nextSibling;

        while ($cursor !== null) {
            if ($stop !== null && $cursor->isSameNode($stop)) {
                break;
            }

            if ($cursor->nodeType === XML_ELEMENT_NODE) {
                $parts[] = $cursor->ownerDocument->saveHTML($cursor);
            } elseif ($cursor->nodeType === XML_TEXT_NODE) {
                $text = trim($cursor->textContent ?? '');
                if ($text !== '') {
                    $parts[] = '<p>'.htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'</p>';
                }
            }

            $cursor = $cursor->nextSibling;
        }

        return implode("\n", $parts);
    }

    /**
     * @return array<string, mixed>
     */
    private function classifySection(string $heading, string $html): array
    {
        $lower = strtolower($heading);

        if (str_contains($lower, 'sponsor')) {
            return [
                'section_type' => 'sponsor_band',
                'settings' => ['role' => 'tertiary'],
                'content' => [
                    'heading' => $heading,
                    'intro' => strip_tags($html),
                ],
            ];
        }

        if (str_contains($lower, 'road map') || str_contains($lower, 'value chain')) {
            preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $img);

            return [
                'section_type' => 'image_band',
                'settings' => ['role' => 'tertiary'],
                'content' => [
                    'heading' => $heading,
                    'heading_html' => str_contains($heading, '<'),
                    'image' => $img[1] ?? '',
                ],
            ];
        }

        if (preg_match('/<table\b/i', $html)) {
            if (preg_match_all('/<a[^>]+href=["\']([^"\']+\.pdf[^"\']*)["\']/i', $html, $links)) {
                $rows = [];
                foreach ($links[1] as $link) {
                    $rows[] = ['label' => $heading, 'path' => $link];
                }

                return [
                    'section_type' => 'pdf_table',
                    'settings' => ['role' => 'primary', 'wrapper_class' => 'laravel-financial-statements-page'],
                    'content' => [
                        'title' => $heading,
                        'column_label' => 'Document',
                        'download_label' => 'Click Here',
                        'rows' => $rows,
                    ],
                ];
            }

            return [
                'section_type' => 'rich_content',
                'settings' => ['role' => 'primary'],
                'content' => ['html' => '<h2>'.e($heading).'</h2>'.$html],
            ];
        }

        if (preg_match('/<img\b/i', $html) && strip_tags($html) === '') {
            preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $img);
            preg_match('/alt=["\']([^"\']*)["\']/i', $html, $alt);

            return [
                'section_type' => 'image',
                'settings' => ['role' => 'primary'],
                'content' => [
                    'image' => $img[1] ?? '',
                    'alt' => $alt[1] ?? $heading,
                ],
            ];
        }

        if (preg_match_all('/<img\b/i', $html, $imgs) && count($imgs[0]) > 1) {
            preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $srcs);
            $images = [];
            foreach ($srcs[1] ?? [] as $index => $src) {
                $images[] = ['path' => $src, 'sort_order' => $index];
            }

            return [
                'section_type' => 'gallery',
                'settings' => ['role' => 'primary'],
                'content' => ['images' => $images],
            ];
        }

        return [
            'section_type' => 'text',
            'settings' => ['role' => 'primary'],
            'content' => [
                'heading' => $heading,
                'content' => trim(strip_tags($html)),
            ],
        ];
    }
}
