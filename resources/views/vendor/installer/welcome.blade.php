@extends('vendor.installer.layouts.master')

@section('template_title')
{{ trans('installer_messages.welcome.templateTitle') }}
@endsection

@section('title')
{{ trans('installer_messages.welcome.title') }}
@endsection

@section('container')
<p class="text-center">
  {{ trans('installer_messages.welcome.message') }}
</p>
@if(session()->has('error'))
<p class="alert alert-danger">
  {{session('error')}}
</p>
@endif
<p class="text-center">
<form action="{{ route('LaravelInstaller::check') }}" method="POST">
  <div class="form-group">
    <label for="purchase_code">{{__('Purchase code')}}</label>
    <input type="text" name="purchase_code" id="purchase_code" required>
  </div>
  <button class="button">
    {{__('Send')}}
  </button>
</form>
</p>
@endsection