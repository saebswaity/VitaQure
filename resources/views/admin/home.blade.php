@extends('layouts.app')

@section('title')
{{__('Admin Portal')}}
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          {{__('Admin Portal')}}
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">{{__('Admin Portal')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<!-- Quick Access Boxes -->
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-bolt"></i>
          {{__('Quick Access')}}
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Patients Box -->
          @can('view_patient')
          <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <a href="{{route('admin.patients.index')}}" class="text-decoration-none">
              <div class="info-box bg-info">
                <span class="info-box-icon">
                  <i class="fas fa-user-injured"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">{{__('Patients')}}</span>
                  <span class="info-box-number">{{__('Manage')}}</span>
                </div>
              </div>
            </a>
          </div>
          @endcan

          <!-- Reports Box -->
          @can('view_report')
          <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <a href="{{route('admin.reports.index')}}" class="text-decoration-none">
              <div class="info-box bg-success">
                <span class="info-box-icon">
                  <i class="fas fa-flag"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">{{__('Reports')}}</span>
                  <span class="info-box-number">{{__('View')}}</span>
                </div>
              </div>
            </a>
          </div>
          @endcan

          <!-- Tests Box -->
          @can('view_test')
          <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <a href="{{route('admin.tests.index')}}" class="text-decoration-none">
              <div class="info-box bg-warning">
                <span class="info-box-icon">
                  <i class="fas fa-flask"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">{{__('Tests')}}</span>
                  <span class="info-box-number">{{__('Manage')}}</span>
                </div>
              </div>
            </a>
          </div>
          @endcan

          <!-- Doctors Box -->
          @can('view_doctor')
          <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <a href="{{route('admin.doctors.index')}}" class="text-decoration-none">
              <div class="info-box bg-danger">
                <span class="info-box-icon">
                  <i class="fa fa-user-md"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">{{__('Doctors')}}</span>
                  <span class="info-box-number">{{__('Manage')}}</span>
                </div>
              </div>
            </a>
          </div>
          @endcan

          <!-- Price List Box -->
          @canany(['view_test_prices','view_culture_prices'])
          <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <a href="{{route('admin.prices.tests')}}" class="text-decoration-none">
              <div class="info-box bg-primary">
                <span class="info-box-icon">
                  <i class="fas fa-list"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">{{__('Price List')}}</span>
                  <span class="info-box-number">{{__('Manage')}}</span>
                </div>
              </div>
            </a>
          </div>
          @endcan
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Patients List -->
@can('view_patient')
<div class="row">
  <div class="col-lg-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-user-injured"></i>
          {{__('Recent Patients')}}
        </h3>
        <div class="card-tools">
          <a href="{{route('admin.patients.index')}}" class="btn btn-primary btn-sm">
            <i class="fas fa-list"></i> {{__('View All')}}
          </a>
          @can('create_patient')
          <a href="{{route('admin.patients.create')}}" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> {{__('Add New')}}
          </a>
          @endcan
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <th width="10px">#</th>
                <th>{{__('Code')}}</th>
                <th>{{__('Name')}}</th>
                <th>{{__('Phone')}}</th>
                <th>{{__('Email')}}</th>
                <th>{{__('Created')}}</th>
                <th width="100px">{{__('Action')}}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($patients as $patient)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$patient->code}}</td>
                <td>{{$patient->name}}</td>
                <td>{{$patient->phone}}</td>
                <td>{{$patient->email}}</td>
                <td>{{$patient->created_at->format('d/m/Y')}}</td>
                <td>
                  <a href="{{route('admin.patients.show', $patient->id)}}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                  </a>
                  @can('edit_patient')
                  <a href="{{route('admin.patients.edit', $patient->id)}}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i>
                  </a>
                  @endcan
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center">{{__('No patients found')}}</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endcan
@endsection 