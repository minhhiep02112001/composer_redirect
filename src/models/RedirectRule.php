<?php

namespace Redirect\Models;

use Illuminate\Support\Facades\DB;

class RedirectRule
{
    protected $table = 'redirect_rules';

    public static function simplePaginate(array $filter = [], int $perPage = 20)
    {
        $q = DB::connection(env('DB_CONNECTION'))->table('redirect_rules');

        if (!empty($filter['url_old'])) {
            $like = str_replace(['%', '_'], ['\%', '\_'], $filter['url_old']);
            $q->where('url_old', 'like', "%{$like}%");
        }

        if (!empty($filter['url_new'])) {
            $like = str_replace(['%', '_'], ['\%', '\_'], $filter['url_new']);
            $q->where('url_new', 'like', "%{$like}%");
        }

        if (!empty($filter['status'])) {
            $q->where('status', (int)$filter['status']);
        }

        return $q->orderBy('_id', 'desc')->simplePaginate($perPage);
    }

    public static function find($id)
    {
        $item = DB::connection(env('DB_CONNECTION'))->table('redirect_rules')->where('_id', $id)->first();
        return collect($item)->toArray();
    }
    public static function findByOld(string $urlOld)
    {
        return DB::connection(env('DB_CONNECTION'))->table('redirect_rules')
            ->where('url_old', $urlOld)
            ->where('status', 1)
            ->first();
    }

    public static function create(array $data)
    {
        return DB::connection(env('DB_CONNECTION'))->table('redirect_rules')->insertGetId($data);
    }

    public static function update(int|string $id, array $data)
    {
        return DB::connection(env('DB_CONNECTION'))->table('redirect_rules')->where('_id', $id)->update($data);
    }

    public static function delete(int|string $id)
    {
        return DB::connection(env('DB_CONNECTION'))->table('redirect_rules')->where('_id', $id)->delete();
    }
}
