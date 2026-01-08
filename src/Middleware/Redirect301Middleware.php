<?php

namespace Redirect\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Redirect\models\RedirectRule;

class Redirect301Middleware
{
    public function handle(Request $request, Closure $next)
    {
        $service = config('app.service_code', 'default');
        if (!in_array($request->method(), ['GET', 'HEAD'])) {
            return $next($request);
        }
        // cache map: from_path => rule (nhanh)
        $redirects = Cache::remember("redirect301.$service", 3600, function () {
            $model = new RedirectRule();
            return $model->all(['status' => 'active'], ['limit' => 1000])->keyBy('url_old')->toArray();
        });
        // ép https
        $url = preg_replace('#^http://#i', 'https://', request()->url());
        // bỏ slash cuối (nếu muốn)
        $url = rtrim($url, '/');
        // get full url
        $rule = $redirects[$url] ?? null;
        if (!$rule) return $next($request);
        return redirect()->to($rule['url_new'], 301);
    }
}
