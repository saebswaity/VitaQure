@extends('layouts.app')

@section('title')
Create Catogery
@endsection

@section('css')
<link rel="stylesheet" href="{{url('plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('breadcrumb')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fa fa-flask"></i>
                    Create Catogery

                </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__('Dashboard')}}</a></li>
                    <li class="breadcrumb-item "><a href="">Catogery</a></li>
                    <li class="breadcrumb-item active"> Create Catogery
                    </li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">{{__('Create')}}</h3>
    </div>
    <form action="/posts/store" method="post" id="test_form">
        <!-- /.card-header -->
        <div class="card-body">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="catogery">Catogery</label>
                        <input type="text" class="form-control" name="catogery" id="catogery" required>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="precautions">Description (optinal)</label>
                        <textarea name="description" id="description" rows="3" class="form-control" placeholder="description"></textarea>
                    </div>
                </div>
            </div>
            <!-- @include('admin.tests.Catogery_form') -->
        </div>
        <!-- /.card-body -->

        <div class=" card-footer">
            <button type="submit" class="btn btn-primary"> <i class="fa fa-check"></i> {{__('Save')}}</button>
        </div>
    </form>

</div>
@endsection