@extends('layouts.app')

@section('title')
    Catogerys
@endsection

@section('breadcrumb')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fa fa-flask"></i>
                        Catogerys
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">Catogerys</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Catogerys Table</h3>
            @can('create_test')
                <a href="/admin/catogery/create" class="btn btn-primary btn-sm float-right">
                    <i class="fa fa-plus"></i> {{ __('Create') }}
                </a>
            @endcan
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table id="tests_table" class="table table-striped table-hover table-bordered" width="100%">
                        <thead>
                            <tr class=" bg-blue">
                                <th width="10px">#</th>
                                <th>{{ __('Name') }}</th>
                                <th>Descreption</th>
                                <th width="100px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($catogeryTests as $catogery)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $catogery->catogery }}</td>
                                    <td>{{ $catogery->description }}</td>
                                    <td>
                                        {{-- <form action="{{ route('admin.catogery.destroy', $catogery->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="fa fa-trash"></i> {{ __('') }}
                                    </button>
                                </form> --}}
                                        {{-- <button type="button" class="btn btn-danger btn-sm delete-button"
                                            data-toggle="modal" data-target="#deleteModal" data-id="{{ $catogery->id }}"> --}}
                                            <button type="button" class="btn btn-danger btn-sm delete-button"
                                        data-toggle="modal" data-target="#deleteModal" data-id="{{ $catogery->id }}" data-name="{{ $catogery->catogery }}">
                                            <i class="fa fa-trash"></i>
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal" data-backdrop="static"
                                            data-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">
                                                            !!! Delete  {{ $catogery->catogery }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ __('When you delete this category all realted Test will be deleted  Are you sure you want to delete this category ?') }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form id="delete-form" method="POST"
                                                            action="{{ route('admin.catogery.destroy', $catogery->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{ __('Close') }}</button>
                                                            <button type="submit"
                                                                class="btn btn-danger">{{ __('Delete') }}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.delete-button').on('click', function() {
                var categoryId = $(this).data('id');
                var categoryName = $(this).data('name');
                var actionUrl = '/admin/catogery/' + categoryId;

                $('#delete-form').attr('action', actionUrl);
                $('#deleteModalLabel').text('Delete ' + categoryName);
                $('#delete-message').text('When you delete this category, all related tests will be deleted. Are you sure you want to delete this category?');
            });
        });
    </script>
@endsection
