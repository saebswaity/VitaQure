@extends('layouts.app')

@section('title')
{{__('Tests')}}
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <i class="fa fa-flask"></i>
          {{__('Tests')}}
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__('Dashboard')}}</a></li>
          <li class="breadcrumb-item active">{{__('Tests')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<div class="card card-primary card-outline">
  <div class="card-header d-flex align-items-center">
    <h3 class="card-title mb-0">{{__('Tests Table')}}</h3>
    <div class="ml-auto d-flex align-items-center">
      <div class="mr-2" style="min-width: 240px;">
        <select id="category_filter" class="form-control form-control-sm">
          <option value="">{{ __('All Tests') }}</option>
          <option value="culture">{{ __('Culture') }}</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->catogery }}</option>
          @endforeach
        </select>
      </div>
      @can('create_test')
      <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-plus"></i> {{__('Create')}}
        </button>
        <div class="dropdown-menu dropdown-menu-right">
          <a class="dropdown-item" href="{{ route('admin.tests.create') }}">{{ __('Create Test') }}</a>
          <a class="dropdown-item" href="{{ route('admin.cultures.create') }}">{{ __('Create Culture') }}</a>
          <a class="dropdown-item" href="{{ url('admin/catogery/create') }}">{{ __('Create Test Category') }}</a>
        </div>
      </div>
      @endcan
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    
    <div class="row">
      <div class="col-lg-12 table-responsive">
        <table id="tests_table" class="table table-striped table-hover table-bordered" width="100%">
          <thead>
            <tr>
              <th width="10px">#</th>
              <th>{{__('Type')}}</th>
              <th>{{__('Name')}}</th>
              <th>{{__('Shortcut')}}</th>
              <th>{{__('Sample Type')}}</th>
              <th>{{__('Price')}}</th>
              <th width="100px">{{__('Action')}}</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>

  </div>
  <!-- /.card-body -->
</div>


@endsection
@section('scripts')
<script src="{{ url('js/admin/tests.js') }}?v={{ @filemtime(public_path('js/admin/tests.js')) }}"></script>
@endsection