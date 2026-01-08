<?php

namespace Redirect\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Redirect\models\RedirectRule;

class RedirectController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $model;
    private $fillable = ['url_old', 'url_new', 'status'];

    public function index(Request $request)
    {
        $filter = $request->get('filter', []);
        $options = []; 
     
        $rows = RedirectRule::simplePaginate($filter, config('data.default_limit_pagination'));
        $data = [
            'rows' => $rows ?? [],
            'filter' => $request->get('filter', []),
            'pagination' =>  $rows->isNotEmpty() && ($rows instanceof \Illuminate\Pagination\Paginator) ? $rows->setPath(\URL::current())->appends($request->get('filter', []))->links() : '',
            'page' => $request->get('page', 1),
        ];
        return view('redirect::redirects.index', $data);
    }
    public function create(Request $request)
    {
        return view('redirect::redirects.form', ['method' => 'POST', 'action' => route('redirect.redirects.store')]);
    }
    public function show($id)
    {
        return $this->model->detail($id);
    }
    public function store(Request $request)
    {
        $data = $request->only($this->fillable);
        $validator = \Validator::make($data, [
            'url_old' => 'required|string|max:255',
            'url_new' => 'required|string|max:255',
        ]);
        // ❌ bỏ query string (?...)
        $data['url_old'] = strtok($data['url_old'], '?');
        $data['url_new'] = strtok($data['url_new'], '?');

        // ✅ ép https nếu cần
        $data['url_old'] = preg_replace('#^http://#i', 'https://', $data['url_old']);
        $data['url_new'] = preg_replace('#^http://#i', 'https://', $data['url_new']);

        // ✅ bỏ slash cuối
        $data['url_old'] = rtrim($data['url_old'], '/');
        $data['url_new'] = rtrim($data['url_new'], '/');

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->first()], 400);
        }
        if (RedirectRule::create($data)) {
            return response()->json(['status' => 'success', 'message' => 'Created successfully'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Created Failed'], 400);
    }
    public function edit($id)
    {
        return view('redirect::redirects.form', ['row' => RedirectRule::find($id), 'method' => 'PUT', 'action' => route('redirect.redirects.update', ['redirect' => $id])]);
    }
    public function update($id, Request $request)
    {
        $data = $request->only($this->fillable);
        $validator = \Validator::make($data, [
            'url_old' => 'required|string|max:255',
            'url_new' => 'required|string|max:255',
        ]);
        // ❌ bỏ query string (?...)
        $data['url_old'] = strtok($data['url_old'], '?');
        $data['url_new'] = strtok($data['url_new'], '?');

        // ✅ ép https nếu cần
        $data['url_old'] = preg_replace('#^http://#i', 'https://', $data['url_old']);
        $data['url_new'] = preg_replace('#^http://#i', 'https://', $data['url_new']);

        // ✅ bỏ slash cuối
        $data['url_old'] = rtrim($data['url_old'], '/');
        $data['url_new'] = rtrim($data['url_new'], '/');

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->first()], 400);
        }
        if (RedirectRule::update($id, $data)) {
            return response()->json(['status' => 'success', 'message' => 'Updated successfully'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Updated Failed'], 400);
    }
    public function destroy($id)
    {
        if (RedirectRule::delete($id)) {
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Deleted Failed'], 200);
    }
}
