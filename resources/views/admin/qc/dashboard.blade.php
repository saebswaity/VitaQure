@extends('layouts.app')

@section('title')
{{ __('Quality Control (QC) System') }}
@endsection

@section('css')
<style>
  .content-wrapper { background:#f5f7fa; }
  .qc-card { border-radius: 12px; border:1px solid #e5e7eb; background:#fff; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
  .qc-card .card-body { padding: 18px; }
</style>
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><i class="fas fa-shield-alt"></i> {{ __('Quality Control (QC) System') }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('Admin Main')}}</a></li>
          <li class="breadcrumb-item active">QC</li>
        </ol>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-lg-8 col-md-10">
    <div class="card qc-card">
      <div class="card-body">
        <h3 class="mb-2">{{ __('Welcome to the QC System') }}</h3>
        <p class="text-muted mb-3">{{ __('Please define analytes first to continue.') }}</p>
        <a href="{{ url('/admin/qc/analytes') }}" class="btn btn-primary"><i class="fas fa-flask"></i> {{ __('Go to Analyte Definition') }}</a>
      </div>
    </div>
  </div>
</div>
@endsection

