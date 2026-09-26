<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menyimpan halaman publik anonim di cache edge Vercel supaya pengunjung dan
 * crawler tidak menunggu render PHP yang lambat.
 *
 * Keamanan: hanya GET tanpa cookie sesi yang di-cache dan seluruh header
 * Set-Cookie dibuang, sehingga tidak ada sesi yang dibagikan ke banyak
 * pengguna lewat cache bersama. Halaman dengan form (kontak, login) tidak
 * ada di daftar karena token CSRF-nya terikat pada sesi.
 */
class CachePublicPage
{
    /**
     * @var list<string>
     */
    private const CACHEABLE_ROUTES = [
        'home',
        'about.show',
        'academic.show',
        'news.index',
        'news.show',
        'achievements.index',
        'extracurriculars.index',
        'extracurriculars.show',
        'gallery.index',
        'admission.show',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Dicek sebelum $next: EncryptCookies di dalam pipeline dapat
        // mengosongkan cookie sesi yang gagal didekripsi.
        $hasSession = $request->hasCookie((string) config('session.cookie'));

        $response = $next($request);

        if (! $this->shouldCache($request, $response, $hasSession)) {
            return $response;
        }

        $response->headers->remove('Set-Cookie');

        // SWR sengaja hanya 10 menit, bukan 1 jam: HTML yang basi masih
        // menyebut nama build lama, dan file tersebut hilang saat deploy
        // berikutnya sehingga CSS/JS bisa 404 dan halaman tampil tanpa gaya.
        $edge = 'public, s-maxage=300, stale-while-revalidate=600';
        $response->headers->set('Cache-Control', $edge);
        $response->headers->set('Vercel-CDN-Cache-Control', $edge);
        $response->headers->set('CDN-Cache-Control', $edge);

        return $response;
    }

    private function shouldCache(Request $request, Response $response, bool $hasSession): bool
    {
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return false;
        }

        if ($hasSession) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        if (! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return false;
        }

        return in_array($request->route()?->getName(), self::CACHEABLE_ROUTES, true);
    }
}
