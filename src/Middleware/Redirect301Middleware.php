<?php
namespace Redirect\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Microservices\models\RedirectRule;

class Redirect301Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->method(), ['GET', 'HEAD'])) {
            return $next($request);
        }

        // chuẩn hoá path dạng "/abc/xyz" (không domain, không query)
        $path = '/' . ltrim($request->path(), '/');
        if ($path !== '/') $path = rtrim($path, '/');

        // cache map: from_path => rule (nhanh)
        $map = Cache::remember('micro_redirect_map_v1', 3600, function () {
            return RedirectRule::query()
                ->where('is_active', 1)
                ->get(['from_path','to_url','to_route_name','to_route_params','status_code'])
                ->keyBy('from_path')
                ->toArray();
        });

        $rule = $map[$path] ?? null;
        if (!$rule) return $next($request);

        // resolve target
        $target = null;

        if (!empty($rule['to_route_name'])) {
            $params = $rule['to_route_params'] ?? [];
            $target = route($rule['to_route_name'], is_array($params) ? $params : []);
        } elseif (!empty($rule['to_url'])) {
            $target = $rule['to_url'];
        }

        if (!$target) return $next($request);

        // tránh loop
        $targetPath = $target;
        $targetPath = preg_replace('#^https?://[^/]+#i', '', $targetPath);
        $targetPath = '/' . ltrim($targetPath, '/');
        if ($targetPath !== '/') $targetPath = rtrim($targetPath, '/');

        if ($targetPath === $path) return $next($request);

        // giữ query string (tuỳ bạn muốn hay không)
        if ($request->getQueryString()) {
            $target .= (Str::contains($target, '?') ? '&' : '?') . $request->getQueryString();
        }

        // tăng hits (đừng update DB mỗi request nếu traffic lớn; tạm để đơn giản)
        RedirectRule::where('from_path', $path)->increment('hits');

        return redirect()->to($target, (int)($rule['status_code'] ?? 301));
    }
}
