<?php

namespace Redirect\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class RedirectController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $model;
    private $fillable = ['url_old', 'url_new', 'status'];
    public function __construct()
    {
        $this->model = \App::make(\Redirect\models\RedirectRule::class);
    }
    public function index(Request $request)
    {
        $filter = $request->get('filter', []);
        $options = [];
        $options['pagination'] = $request->get('page', 1);
        if (!empty($filter['url_old'])) {
            $filter['url_old']  = ['like' => $filter['url_old']];
        }
        if (!empty($filter['url_new'])) {
            $filter['url_new']  = ['like' => $filter['url_new']];
        }
        $rows = $this->model->all($filter, $options);
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
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->first()], 400);
        }
        if ($this->model->create($data)) {
            return response()->json(['status' => 'success', 'message' => 'Created successfully'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Created Failed'], 400);
    }
    public function edit($id)
    {
        return view('redirect::redirects.edit', ['row' => $this->model->detail($id), 'method' => 'PUT', 'action' => route('redirect.redirects.update', ['id' => $id])]);
    }
    public function update($id, Request $request)
    {
        $data = $request->only($this->fillable);
        $validator = \Validator::make($data, [
            'url_old' => 'required|string|max:255',
            'url_new' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->first()], 400);
        }
        if ($this->model->update($id, $data)) {
            return response()->json(['status' => 'success', 'message' => 'Updated successfully'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Updated Failed'], 400);
    }
    public function destroy($id)
    {
        if ($this->model->delete($id)) {
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Deleted Failed'], 200);
    }
}
