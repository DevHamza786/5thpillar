<?php

namespace App\Support;

/**
 * Converts imported WordPress / WPBakery post HTML into safe display HTML (plain paragraphs).
 */
final class NewsArticleContent
{
    /**
     * Returns escaped HTML: one or more &lt;p&gt; blocks, no shortcodes.
     */
    public static function toPlainParagraphs(string $html): string
    {
        $plain = self::extractPlainText($html);
        if ($plain === '') {
            return '';
        }

        $blocks = preg_split("/\r\n|\n|\r/", $plain) ?: [];
        $paragraphs = [];
        $buf = '';

        foreach ($blocks as $line) {
            $line = trim($line);
            if ($line === '') {
                if ($buf !== '') {
                    $paragraphs[] = $buf;
                    $buf = '';
                }
                continue;
            }
            $buf .= ($buf === '' ? '' : ' ').$line;
        }
        if ($buf !== '') {
            $paragraphs[] = $buf;
        }

        $out = '';
        foreach ($paragraphs as $p) {
            $out .= '<p>'.e(trim($p)).'</p>';
        }

        return $out;
    }

    private static function extractPlainText(string $html): string
    {
        $decoded = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $chunks = [];
        if (preg_match_all('/\[vc_column_text[^\]]*\](.*?)\[\/vc_column_text\]/is', $decoded, $m)) {
            foreach ($m[1] as $inner) {
                $inner = trim($inner);
                if ($inner !== '') {
                    $chunks[] = $inner;
                }
            }
        }

        $text = $chunks !== [] ? implode("\n\n", $chunks) : $decoded;

        $text = preg_replace('/\[[^\]]+\]/', '', $text) ?? '';
        $text = preg_replace('#</p>\s*<p[^>]*>#i', "\n\n", $text);
        $text = preg_replace('#</p>#i', "\n\n", $text);
        $text = preg_replace('#<p[^>]*>#i', '', $text);
        $text = preg_replace('#<br\s*/?>#i', "\n", $text);
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/[ \t\x{00A0}]+/u', ' ', $text) ?? '';
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? '';

        return trim($text);
    }
}
