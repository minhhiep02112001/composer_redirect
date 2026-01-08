@extends('layout.app')
@section('content')
    <div class="content">
        <div class="card">
            <form class="ajax-submit-form" action="{{ $action }}" method="{{ $method }}">
                <input type="hidden" name="old-date" value="{{ $row['date'] ?? ''}}">
                <div class="card-header header-elements-inline bg-">
                    <h5 class="card-title">Thông tin linh 301</h5>
                    <div class="header-elements"> 
                        <button id="submit_class" type="submit" class="btn btn-success ajax-submit-button">Lưu thông tin<i class="icon-paperplane ml-2"></i></button>
                        @csrf
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="control-label col-sm-2 col-xs-12">Link Cũ <span style="color:red">*</span></label>
                        <div class="col-sm-10 col-xs-12 validation_form">
                            <input type="text" name="url_old" value="{{ $row['url_old'] ?? ''}}" autocomplete="off" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2 col-xs-12">Link 301 <span style="color:red">*</span></label>
                        <div class="col-sm-10 col-xs-12 validation_form">
                            <input type="text" name="url_new" value="{{ $row['url_new'] ?? ''}}" autocomplete="off" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2 col-xs-12">Trạng thái</label>
                        <div class="col-sm-10 col-xs-12 validation_form">
                         <select name="status" id="status" class="form-control select2_single">
                            <option value="active" {{ !empty($row['status']) && $row['status'] == 'active' ? 'selected' : '' }}>Kích hoạt</option>
                            <option value="inactive" {{ !empty($row['status']) && $row['status'] == 'inactive' ? 'selected' : '' }}>Vô hiệu hóa</option>
                         </select>   
                        </div>
                    </div> 
                </div>
            </form>
        </div>
    </div>
@stop
