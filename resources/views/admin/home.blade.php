@extends('layouts.app')

@section('title')
{{__('Admin Home')}}
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <i class="nav-icon fas fa-home"></i>
          {{__('Admin Home')}}
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">{{__('Admin Home')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{__('Welcome')}}</h3>
      </div>
      <div class="card-body">
        <h1>Hello World</h1>
        <p>{{__('Welcome to the admin panel.')}}</p>
        <a href="{{route('admin.index')}}" class="btn btn-primary">{{__('Go to Dashboard')}}</a>
      </div>
    </div>
  </div>
</div>
@endsection 