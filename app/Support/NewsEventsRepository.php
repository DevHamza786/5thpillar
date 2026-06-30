<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

final class NewsEventsRepository
{
    private static ?Collection $posts = null;

    /**
     * All news posts, newest first (same order as live WordPress archive).
     *
     * @return Collection<int, array{slug: string, title: string, date: string, image: ?string, content: string}>
     */
    public static function posts(): Collection
    {
        if (self::$posts !== null) {
            return self::$posts;
        }

        $path = resource_path('data/news-events-data.json');
        if (! File::exists($path)) {
            self::$posts = collect();

            return self::$posts;
        }

        $raw = json_decode(File::get($path), true);
        if (! is_array($raw)) {
            self::$posts = collect();

            return self::$posts;
        }

        self::$posts = collect($raw)->map(function (array $post): array {
            if (! empty($post['image']) && is_string($post['image'])) {
                $post['image'] = self::normalizeImageUrl($post['image']);
            }

            return $post;
        })->values();

        return self::$posts;
    }

    /**
     * @return ?array{slug: string, title: string, date: string, image: ?string, content: string}
     */
    public static function find(string $slug): ?array
    {
        $post = self::posts()->firstWhere('slug', $slug);

        return $post !== null ? $post : null;
    }

    private static function normalizeImageUrl(string $image): string
    {
        if (preg_match('#(?:assets/images/news/|wp-content/uploads/|uploads/)(.+)$#i', $image, $m)) {
            return asset('assets/images/news/'.$m[1]);
        }

        if (preg_match('#^https?://#i', $image)) {
            return $image;
        }

        return asset(ltrim($image, '/'));
    }
}
