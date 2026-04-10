<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class TranslateUiHtml
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (App::getLocale() !== 'en') {
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');
        if ($contentType !== '' && stripos($contentType, 'text/html') === false) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            return $response;
        }

        $map = config('ui_translation.fr_to_en', []);
        if (empty($map)) {
            return $response;
        }

        // Replace longest strings first to avoid partial collisions.
        uksort($map, static fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));

        $response->setContent(strtr($content, $map));

        return $response;
    }
}
