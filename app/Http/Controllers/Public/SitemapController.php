<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\News;
use Illuminate\Http\Response;

/**
 * Sitemap XML untuk crawler mesin pencari.
 *
 * Hanya memuat URL yang benar-benar publik: halaman statis, berita yang sudah
 * terbit, dan ekstrakurikuler yang aktif. Halaman portal tidak dimasukkan
 * karena butuh autentikasi, dan URL yang menghasilkan 404 di sitemap akan
 * merusak kredibilitas domain di mata crawler.
 */
class SitemapController extends Controller
{
    /**
     * Halaman statis, urut dari yang paling penting.
     *
     * @var list<array{0: string, 1: string, 2: float}>
     */
    private const STATIC_PAGES = [
        ['home', 'daily', '1.0'],
        ['academic.show', 'weekly', '0.9'],
        ['admission.show', 'weekly', '0.9'],
        ['news.index', 'daily', '0.8'],
        ['achievements.index', 'weekly', '0.7'],
        ['extracurriculars.index', 'weekly', '0.7'],
        ['gallery.index', 'weekly', '0.6'],
        ['about.show', 'monthly', '0.6'],
        ['contact.show', 'monthly', '0.5'],
    ];

    public function __invoke(): Response
    {
        $xml = $this->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    private function render(): string
    {
        $urls = [];

        foreach (self::STATIC_PAGES as [$route, $freq, $priority]) {
            $urls[] = [
                'loc' => route($route),
                'changefreq' => $freq,
                'priority' => $priority,
            ];
        }

        foreach (News::published()->latest('published_at')->get() as $news) {
            $urls[] = [
                'loc' => route('news.show', $news),
                'lastmod' => ($news->updated_at ?? $news->published_at)?->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        foreach (Extracurricular::active()->orderBy('name')->get() as $activity) {
            $urls[] = [
                'loc' => route('extracurriculars.show', $activity),
                'lastmod' => $activity->updated_at?->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ];
        }

        $body = '';

        foreach ($urls as $url) {
            $body .= "    <url>\n";
            $body .= '        <loc>'.$this->escape($url['loc'])."</loc>\n";

            if (! empty($url['lastmod'])) {
                $body .= '        <lastmod>'.$this->escape($url['lastmod'])."</lastmod>\n";
            }

            if (! empty($url['changefreq'])) {
                $body .= '        <changefreq>'.$this->escape($url['changefreq'])."</changefreq>\n";
            }

            if (! empty($url['priority'])) {
                $body .= '        <priority>'.$this->escape($url['priority'])."</priority>\n";
            }

            $body .= "    </url>\n";
        }

        return '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            ."<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n"
            .$body
            ."</urlset>\n";
    }

    /**
     * Melindungi nilai yang bisa mengandung & dan <, misalnya judul berita
     * yang disisipkan ke URL. Tanpa ini, XML-nya jadi tidak valid.
     */
    private function escape(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
