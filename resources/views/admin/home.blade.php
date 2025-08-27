@extends('layouts.app')

@section('title')
{{__('Admin Portal')}}
@endsection

@section('css')
<style>
/* Enhanced Info Boxes */
.info-box {
    transition: all 0.3s ease;
    cursor: pointer;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border: none;
    overflow: hidden;
    position: relative;
}

.info-box:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.info-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));
}

.info-box-icon {
    border-radius: 12px 0 0 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    min-height: 80px;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
}

.info-box-content {
    padding: 20px;
    background: linear-gradient(135deg, rgba(0,0,0,0.06), rgba(0,0,0,0.03));
}

.info-box-text {
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
    display: block;
    margin-bottom: 8px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.info-box-number {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.9);
    display: block;
    font-weight: 500;
}

/* Disabled state for non-clickable info boxes */
.info-box.disabled { cursor: default; }
.info-box.disabled:hover { transform: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }

/* Enhanced Table */
.table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    font-weight: 600;
    padding: 15px 12px;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: scale(1.01);
}

/* Recent Patients table themed like Patients box (soft blue) */
.recent-patients-table thead th {
    background: #bfdbfe; /* soft blue */
    color: #1f3b64;      /* dark slate text */
    border: none;
}

.recent-patients-table tbody tr:hover {
    background-color: rgba(191, 219, 254, 0.25);
}

/* Soft badge for patient code */
.badge-soft-blue {
    background-color: #bfdbfe;
    color: #1f3b64;
}

/* Card Enhancements */
.card {
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: none;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    padding: 20px;
}

.card-title {
    font-weight: 700;
    color: #495057;
    margin: 0;
}

.card-tools {
    float: right;
}

.card-tools .btn {
    margin-left: 8px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.card-tools .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Responsive Design */
@media (max-width: 768px) {
    .info-box-icon {
        font-size: 2rem;
        min-height: 60px;
    }
    
    .info-box-content {
        padding: 15px;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out;
}

/* Inline actions in tables */
.action-buttons {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Soft theme variants for info boxes (lighter, site-aligned colors) */
.info-box-soft-blue { background-color: #bfdbfe; }
.info-box-soft-slate { background-color: #cbd5e1; }
.info-box-soft-cyan { background-color: #bae6fd; }
.info-box-soft-steel { background-color: #cfd8e3; }
.info-box-soft-indigo { background-color: #c7d2fe; }
.info-box-soft-navy { background-color: #9ec5fe; }
.info-box-soft-gray { background-color: #d1d5db; }
.info-box-soft-sky { background-color: #bde0fe; }

.info-box.info-box-soft-blue .info-box-text,
.info-box.info-box-soft-blue .info-box-number { color: #1f3b64; }

.info-box.info-box-soft-slate .info-box-text,
.info-box.info-box-soft-slate .info-box-number { color: #2d3748; }

.info-box.info-box-soft-cyan .info-box-text,
.info-box.info-box-soft-cyan .info-box-number { color: #0f4c5c; }

.info-box.info-box-soft-steel .info-box-text,
.info-box.info-box-soft-steel .info-box-number { color: #243b53; }

.info-box.info-box-soft-indigo .info-box-text,
.info-box.info-box-soft-indigo .info-box-number { color: #3730a3; }

.info-box.info-box-soft-navy .info-box-text,
.info-box.info-box-soft-navy .info-box-number { color: #1e3a8a; }

.info-box.info-box-soft-gray .info-box-text,
.info-box.info-box-soft-gray .info-box-number { color: #2f3e46; }

.info-box.info-box-soft-sky .info-box-text,
.info-box.info-box-soft-sky .info-box-number { color: #1e40af; }
.action-buttons form {
    display: inline;
    margin: 0;
}
</style>
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
<div class="row mb-4 animate-fade-in">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-bolt"></i>
          {{__('Quick Access')}}
        </h3>
        <div class="card-tools">
          <a href="{{route('admin.index')}}" class="btn btn-primary btn-sm">
            <i class="fas fa-chart-line"></i> {{__('Full Dashboard')}}
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Patients Box -->
          @can('view_patient')
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <a href="{{route('admin.patients.index')}}" class="text-decoration-none">
              <div class="info-box info-box-soft-blue">
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

          
          <!-- QC Box -->
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <a href="{{ url('/admin/qc') }}" class="text-decoration-none">
              <div class="info-box info-box-soft-gray">
                <span class="info-box-icon">
                  <i class="fas fa-shield-alt"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">{{ __('Quality Control (QC)') }}</span>
                  <span class="info-box-number">{{ __('Open') }}</span>
                </div>
              </div>
            </a>
          </div>

          <!-- Invoices Box (moved to Reports place) -->
          @can('view_group')
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <a href="{{ route('admin.groups.index') }}" class="text-decoration-none">
              <div class="info-box info-box-soft-blue">
                <span class="info-box-icon">
                  <i class="fas fa-layer-group"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">{{ __('Invoices') }}</span>
                  <span class="info-box-number">{{ __('Manage') }}</span>
                </div>
              </div>
            </a>
          </div>
          @endcan

          <!-- Reports Box (moved to Tests place) -->
          @can('view_report')
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <a href="{{route('admin.reports.index')}}" class="text-decoration-none">
              <div class="info-box info-box-soft-gray">
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

          

          <!-- Tests Box (moved to Doctors place) -->
          @can('view_test')
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
          
          <!-- Force new row after first four boxes on lg+ screens -->
          <div class="w-100 d-none d-lg-block"></div>
            <a href="{{route('admin.tests.index')}}" class="text-decoration-none">
              <div class="info-box info-box-soft-blue">
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

          <!-- Price List Box -->
          @canany(['view_test_prices','view_culture_prices'])
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <a href="{{route('admin.prices.tests')}}" class="text-decoration-none">
              <div class="info-box info-box-soft-gray">
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

          <!-- Doctors Box (placed next to Price List) -->
          @can('view_doctor')
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <a href="{{route('admin.doctors.index')}}" class="text-decoration-none">
              <div class="info-box info-box-soft-blue">
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

          <!-- VitaGuard Ai Box -->
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <a href="{{ route('admin.vitaguard.index') }}" class="text-decoration-none">
              <div class="info-box info-box-soft-gray">
                <span class="info-box-icon">
                  <i class="fa fa-robot"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">VitaGuard AI</span>
                  <span class="info-box-number">Open</span>
                </div>
              </div>
            </a>
          </div>

          

          <!-- Vita Ai Chatbot Box (click opens admin-embedded page) -->
          @can('view_chat')
          <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
            <a href="{{ url('/admin/vitachatbot') }}" class="text-decoration-none">
              <div class="info-box info-box-soft-blue">
                <span class="info-box-icon">
                  <i class="far fa-comment-dots"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">{{ __('Vita Ai Chatbot') }}</span>
                  <span class="info-box-number">{{ __('Open') }}</span>
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
<div class="row animate-fade-in">
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
          <a href="{{ route('admin.patients.create', ['return_url' => url('/admin')]) }}" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> {{__('Add New')}}
          </a>
          @endcan
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover recent-patients-table">
            <thead>
              <tr>
                <th width="10px">#</th>
                <th>{{__('Code')}}</th>
                <th>{{__('Name')}}</th>
                <th>{{__('Phone')}}</th>
                <th>{{__('Email')}}</th>
                <th>{{__('Created')}}</th>
                <th width="120px">{{__('Action')}}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($patients as $patient)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td><span class="badge badge-soft-blue">{{$patient->code}}</span></td>
                <td><strong>{{$patient->name}}</strong></td>
                <td>{{$patient->phone}}</td>
                <td>{{$patient->email}}</td>
                <td><small class="text-muted">{{$patient->created_at->format('d/m/Y')}}</small></td>
                <td>
                  <div class="action-buttons">
                    @can('edit_patient')
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.patients.edit', ['patient' => $patient->id, 'return_url' => url('/admin')]) }}" title="{{ __('Edit') }}">
                      <i class="fa fa-edit" aria-hidden="true"></i>
                    </a>
                    @endcan

                    @can('create_group')
                    <a class="btn btn-info btn-sm" href="{{ route('admin.groups.create', ['patient_id' => $patient->id]) }}" title="{{ __('Create Report') }}">
                      <i class="fa fa-file-medical"></i>
                    </a>
                    @endcan

                    @can('delete_patient')
                    <form method="POST" action="{{route('admin.patients.destroy',$patient->id)}}">
                      @csrf
                      <input type="hidden" name="_method" value="delete">
                      <input type="hidden" name="return_url" value="{{ url('/admin') }}">
                      <button type="submit" class="btn btn-danger btn-sm delete_patient" title="{{ __('Delete') }}">
                        <i class="fa fa-trash"></i>
                      </button>
                    </form>
                    @endcan
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="fas fa-user-slash fa-3x mb-3"></i>
                  <br>{{__('No patients found')}}
                </td>
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