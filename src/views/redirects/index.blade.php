 
@extends('layout.app')
@section('content')
    @include('common.content_header', ['name' => 'Danh sách link 301'])
    <div class="content">
        <div class="card">
            <div class="card-header header-elements-inline">

                <h5 class="card-title">Danh sách link redirect 301</h5>

                <div class="header-elements ">
                    <a class="call_ajax_modal btn btn-teal" href="{{ route('redirect.redirects.create') }}">Tạo mới</a>
                </div>
            </div>
            <table class="table datatable-fixed-both" width="100%">
                <thead id="checkbox_all">
                    <tr>
                        <th>#</th>
                        <th>Trạng thái</th>
                        <th>Link Cũ</th>
                        <th>Link 301</th>
                        <th class="all text-center sorting_disabled"><i class="icon-checkmark3"></i></th>
                    </tr>
                </thead>
                <tbody id="checkbox_list">
                    @foreach ($rows as $key => $row)
                        <tr>
                            <td>{{ ($page - 1) * config('data.default_limit_pagination', 30) + ($key + 1) }}</td>
                            <td>
                                {{!empty($row['status']) && $row['status'] == 'active'? '✅' : '⛔'}} 
                            </td>
                            <td><a href="{{ $row['url_old'] ?? '' }}" target="_blank">{{ $row['url_old'] ?? '' }}</a></td>
                            <td><a href="{{ $row['url_new'] ?? '' }}" target="_blank">{{ $row['url_new'] ?? '' }}</a></td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route('redirect.redirects.edit', ['redirect' => $row['_id']]) }}"
                                            class="dropdown-item call_ajax_modal">Sửa</a>
                                        <a class="quick-action-confirm dropdown-item" content="Bạn có chắc muốn xóa không"
                                            action="{{ route('redirect.redirects.destroy', ['redirect' => $row['_id']]) }}'"
                                            method="delete" href="#">Xóa</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                {!! $pagination ?? '' !!}
            </div>
        </div>
    </div>
@stop
@section('left-slidebar')
    @include('redirect::redirects.filter')
@endsection
