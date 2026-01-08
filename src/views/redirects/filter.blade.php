@extends('common.filter_layout')
@section('section_filter')
  
    <!-- Sidebar search -->
    <div class="sidebar-section">
        <div class="sidebar-section-header">
            <span class="font-weight-semibold">Tìm kiếm</span>
            <div class="list-icons ml-auto">
                <a href="#sidebar-search" class="list-icons-item" data-toggle="collapse">
                    <i class="icon-arrow-down12"></i>
                </a>
            </div>
        </div>
        <div class="collapse show" id="sidebar-search">
            <div class="sidebar-section-body">
                <div class="form-group">
                    <label class="control-label" for="name">Link Old</label>
                    <input type="text" name="filter[url_old]" class="form-control" value="{{ $filter['url_old'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="control-label" for="name">Link 301</label>
                    <input type="text" name="filter[url_new]" class="form-control" value="{{ $filter['url_new'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="control-label">Trạng thái</label>
                    <select name="filter[status]"  class="select2_single form-control">
                        <option value="">---</option>
                        <option value="active"
                            {{ isset($filter['status']) && $filter['status'] == 'active' ? 'selected' : '' }}>
                            Kích hoạt
                        </option>
                        <option value="inactive"
                            {{ isset($filter['status']) && $filter['status'] == 'inactive' ? 'selected' : '' }}>
                            Vô hiệu hóa
                        </option> 
                    </select>
                </div>



            </div>
        </div>
    </div>
    <!-- /sidebar search -->
@endsection
