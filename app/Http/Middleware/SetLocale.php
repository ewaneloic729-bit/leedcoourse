<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * @var string[]
     */
    private array $supportedLocales = ['fr', 'en'];

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale');

        if (! in_array($locale, $this->supportedLocales, true)) {
            $locale = $request->cookie('app_locale');
        }

        if (! in_array($locale, $this->supportedLocales, true)) {
            $locale = $request->getPreferredLanguage($this->supportedLocales) ?? config('app.locale', 'fr');
        }

        if (! in_array($locale, $this->supportedLocales, true)) {
            $locale = 'fr';
        }

        if ($request->session()->get('locale') !== $locale) {
            $request->session()->put('locale', $locale);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
