@extends('layouts.app')

@section('title')
{{ __('VitaGuard AI') }}
@endsection

@section('css')
<style>
  /* Page theming */
  .vg-page {
    /* subtle background for separation */
  }

  /* Card polish */
  .vg-card {
    border-radius: 14px;
    border: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    overflow: hidden;
  }
  .vg-card .card-header {
    background: linear-gradient(135deg, #f8f9fb 0%, #eef2f7 100%);
    border-bottom: 2px solid #e3e7ee;
    padding: 16px 18px;
  }

  /* Section titles */
  .vg-section-title {
    font-weight: 800;
    font-size: 1.15rem;
    color: #1f2d3d;
    margin: 0;
  }
  .vg-model-title {
    font-weight: 800;
    font-size: 1.05rem;
    color: #243447;
    margin-bottom: 6px;
  }

  /* Pills/badges */
  .vg-badge {
    background-color: #e6f0ff;
    color: #173a63;
    font-weight: 700;
    padding: 6px 10px;
    border-radius: 10px;
  }

  /* Tables */
  .vg-table thead th {
    background: #eef4ff;
    color: #22324a;
    border: none !important;
    font-weight: 700;
  }
  .vg-table tbody tr:hover {
    background-color: #f6f9ff;
  }

  /* Forms */
  .vg-form .form-group {
    margin-bottom: 10px;
  }
  .vg-form label {
    font-weight: 700;
    color: #2b3a4a;
  }
  .vg-form .form-control,
  .vg-form select.form-control {
    border-radius: 8px;
    border-color: #d7dde5;
  }

  /* Buttons */
  .vg-btn-primary {
    background: #4263eb;
    border-color: #4263eb;
    font-weight: 700;
  }
  .vg-btn-primary:hover {
    filter: brightness(0.95);
  }

  /* Selected patient card */
  .vg-patient-card {
    border-radius: 12px;
    border: 1px solid #e6ebf2;
    box-shadow: 0 8px 18px rgba(0,0,0,0.05);
    overflow: hidden;
  }
  .vg-patient-card .vg-patient-header {
    background: linear-gradient(135deg, #eef4ff 0%, #eaf4ff 100%);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #e3e7ee;
  }
  .vg-avatar {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: #4263eb;
    color: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    font-weight: 800;
  }
  .vg-patient-name {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 800;
    color: #1f2d3d;
  }
  .vg-patient-body { padding: 12px 16px; }
  .vg-line { display: flex; align-items: center; gap: 8px; margin: 6px 0; color: #334155; }
  .vg-line i { color: #6b7a90; width: 16px; text-align: center; }
  .vg-patient-actions { padding: 14px 16px; border-top: 1px solid #e6ebf2; background: #fafcff; text-align: center; }
  .vg-analyze-btn {
    background: #2f6df6;
    border-color: #2f6df6;
    color: #fff !important;
    font-weight: 800;
    padding: 10px 22px;
    border-radius: 10px;
    min-width: 200px;
  }
  .vg-analyze-btn:hover { filter: brightness(0.95); }

  /* Prediction result styles */
  .vg-result-badge {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    padding: 10px 14px;
    border-radius: 10px;
    font-weight: 800;
    border: 1px solid transparent;
    margin-top: 12px;
    margin-bottom: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
  }
  .vg-result-1 { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
  .vg-result-0 { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
  .vg-result-badge .vg-label { font-size: 1rem; line-height: 1; }
  .vg-result-badge .vg-pct { font-family: monospace; font-size: 1rem; opacity: 0.9; margin-left: 6px; }
</style>
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <i class="fas fa-robot"></i>
          {{ __('VitaGuard AI') }}
        </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('Admin Main')}}</a></li>
          <li class="breadcrumb-item active">{{ __('VitaGuard AI') }}</li>
        </ol>
      </div>
    </div>
  </div>
  </div>
@endsection

@section('content')
<div class="row vg-page">
  <!-- Left: Patients -->
  <div class="col-lg-4">
    <div class="card card-primary card-outline vg-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="vg-section-title mb-0"><i class="fas fa-user-injured"></i> {{ __('Patients') }}</h3>
      </div>
      <div class="card-body p-0">
        @if(!$selectedPatient)
        <div class="p-2 border-bottom">
          <input type="text" id="vg-patient-search" class="form-control form-control-sm" placeholder="{{ __('Search patients by name or code...') }}">
        </div>
        <div class="table-responsive" style="max-height: 420px;">
          <table class="table table-striped mb-0 vg-table" id="vg-patient-table">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ __('Code') }}</th>
                <th>{{ __('Name') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($patients as $patient)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td><span class="vg-badge">{{ $patient->code }}</span></td>
                <td>
                  <a href="{{ route('admin.vitaguard.index', array_filter(['patient_id' => $patient->id, 'model' => $selectedModelKey])) }}">
                    <strong>{{ $patient->name }}</strong>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="p-0">
          <div class="vg-patient-card">
            <div class="vg-patient-header">
              <div class="vg-avatar">{{ strtoupper(substr($selectedPatient->name,0,1)) }}</div>
              <div>
                <p class="vg-patient-name">{{ $selectedPatient->name }}</p>
                <div><span class="vg-badge">{{ $selectedPatient->code }}</span></div>
              </div>
            </div>
            <div class="vg-patient-body">
              @if(!is_null($selectedPatient->age))
                <div class="vg-line"><i class="fas fa-birthday-cake"></i> {{ __('Age') }}: {{ $selectedPatient->age }}</div>
              @endif
              @if($selectedPatient->phone)
                <div class="vg-line"><i class="fas fa-phone"></i> {{ $selectedPatient->phone }}</div>
              @endif
              @if($selectedPatient->email)
                <div class="vg-line"><i class="fas fa-envelope"></i> {{ $selectedPatient->email }}</div>
              @endif
              @if($selectedPatient->address)
                <div class="vg-line"><i class="fas fa-map-marker-alt"></i> {{ $selectedPatient->address }}</div>
              @endif
            </div>
            <div class="vg-patient-actions">
              <a href="{{ route('admin.vitaguard.index', array_filter(['model' => $selectedModelKey])) }}" class="btn btn-sm" style="background:#2f6df6; color:#fff; font-weight:800; border-radius:10px; padding:10px 22px; min-width:200px;">
                {{ __('Change patient') }}
              </a>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Right: Models from config -->
  <div class="col-lg-8">
    <div class="card card-primary card-outline vg-card">
      <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <h3 class="vg-section-title mb-2"><i class="fas fa-microchip"></i> {{ __('Models') }}</h3>
        @if(!empty($modelKeys))
        <form method="get" class="form-inline">
          <label class="mr-2 mb-2">{{ __('Select Model') }}</label>
          <select name="model" class="form-control form-control-sm mb-2" onchange="this.form.submit()">
            @foreach($modelKeys as $key)
              <option value="{{ $key }}" @if($key===$selectedModelKey) selected @endif>{{ ucwords(str_replace('_',' ', $key)) }}</option>
            @endforeach
          </select>
        </form>
        @endif
      </div>
      <div class="card-body">
        @if(empty($config))
          <div class="alert alert-warning mb-0">
            {{ __('No config found. Please create dr/config.json to describe VitaGuard models.') }}
          </div>
        @elseif(!empty($selectedModelCfg))
          <div class="border rounded p-3">
            <h5 class="mb-2 vg-model-title">{{ ucwords(str_replace('_',' ', $selectedModelKey)) }}</h5>
            @if(!empty($selectedModelCfg['path']))
              <p class="text-muted mb-2"><small><i class="fas fa-file-alt"></i> {{ $selectedModelCfg['path'] }}</small></p>
            @endif
            @if($selectedPatient)
              <div class="alert py-2" style="background:#2f6df6; color:#ffffff;">
                <strong style="color:#ffffff;">{{ __('Selected Patient') }}:</strong>
                {{ $selectedPatient->name }} — <span class="vg-badge">{{ $selectedPatient->code }}</span>
              </div>
            @endif
            @php $inputs = $selectedModelCfg['inputs'] ?? []; @endphp
            @if(!empty($inputs))
              <form class="vg-form" id="vg-kidney-form">
                @foreach($inputs as $name => $meta)
                  <div class="form-group">
                    <label class="mb-0">{{ $meta['label'] ?? $name }} @if(!empty($meta['required']))<span class="text-danger">*</span>@endif</label>
                    @if(($meta['type'] ?? 'text') === 'select')
                      <select class="form-control form-control-sm" name="inputs[{{ $name }}]">
                        @php $opts = $meta['options'] ?? []; @endphp
                        @foreach($opts as $opt)
                          @php $selected = (old('inputs.'.$name) === $opt) || ($opt==='No' && in_array('Yes',$opts) && in_array('No',$opts)); @endphp
                          <option value="{{ $opt }}" @if($selected) selected @endif>{{ $opt }}</option>
                        @endforeach
                      </select>
                    @else
                      <input type="{{ $meta['type'] ?? 'text' }}" class="form-control form-control-sm" name="inputs[{{ $name }}]" placeholder="{{ $meta['placeholder'] ?? '' }}">
                    @endif
                  </div>
                @endforeach
                <div class="text-center mt-3">
                  @if($selectedModelKey === 'kidney')
                    <button type="button" id="vg-predict-btn" class="btn vg-analyze-btn">{{ __('Analyze') }}</button>
                  @else
                    <button type="button" class="btn vg-analyze-btn" disabled title="{{ __('This model is not yet active') }}">{{ __('Analyze') }}</button>
                  @endif
                </div>
              </form>
              @if($selectedModelKey === 'kidney')
                <div id="vg-predict-result" class="mt-3" style="display:none"></div>
              @endif
            @else
              <p class="text-muted mb-0"><small>{{ __('No inputs described in config.') }}</small></p>
            @endif
          </div>
        @else
          <div class="alert alert-info mb-0">{{ __('Select a model to view its inputs.') }}</div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  (function(){
    var input = document.getElementById('vg-patient-search');
    if(!input) return;
    var table = document.getElementById('vg-patient-table');
    var rows = table ? table.getElementsByTagName('tr') : [];
    input.addEventListener('keyup', function(){
      var q = this.value.toLowerCase();
      for (var i = 1; i < rows.length; i++) { // skip header
        var cells = rows[i].getElementsByTagName('td');
        if (!cells.length) continue;
        var code = (cells[1].innerText || '').toLowerCase();
        var name = (cells[2].innerText || '').toLowerCase();
        rows[i].style.display = (code.indexOf(q) > -1 || name.indexOf(q) > -1) ? '' : 'none';
      }
    });
  })();
</script>
<script>
  (function(){
    var btn = document.getElementById('vg-predict-btn');
    var form = document.getElementById('vg-kidney-form');
    var box = document.getElementById('vg-predict-result');
    if(!btn || !form || !box) return;

    function collectInputs(){
      var data = {};
      var els = form.querySelectorAll('input[name^="inputs"], select[name^="inputs"][name]');
      for (var i=0;i<els.length;i++){
        var el = els[i];
        var m = el.name.match(/^inputs\[(.+)\]$/);
        if(!m) continue;
        data[m[1]] = el.value;
      }
      return data;
    }

    btn.onclick = function(){
      var payload = { model: 'kidney', inputs: collectInputs() };
      // Reset result box completely to avoid any Bootstrap alert remnants
      box.style.display = 'none';
      box.removeAttribute('class');
      box.innerHTML = '';
      btn.disabled = true;
      var txt = btn.innerText;
      btn.innerText = 'Predicting...';
      fetch(@json(route('admin.vitaguard.predict')),{ method:'POST', headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) }, body: JSON.stringify(payload) })
      .then(function(r){ return r.json(); })
      .then(function(res){
        // Show box and render
        box.style.display = '';
        // Never use Bootstrap alert classes here to avoid green label
        box.classList.remove('alert','alert-success','alert-danger');
        if(!res.ok){
          box.innerHTML = '<div class="text-danger">' + (res.error || 'Prediction failed') + '</div>';
          return;
        }
        var prob = res.probability; var cls = Number(res.class);
        var pillClass = cls === 1 ? 'vg-result-badge vg-result-1' : 'vg-result-badge vg-result-0';
        var label = cls === 1 ? 'Disease' : 'Healthy';
        var raw = (cls === 1 ? prob : (1 - prob));
        var rawStr = (Math.round(raw * 1e8) / 1e8).toFixed(8);
        box.className='';
        box.innerHTML = '<div class="d-flex flex-column align-items-center">'
          + '<div class="' + pillClass + '">'
          +   '<span class="vg-label">' + label + '</span>'
          +   '<span class="vg-pct">' + rawStr + '</span>'
          + '</div>'
          + '</div>';
      })
      .catch(function(e){ box.style.display=''; box.classList.remove('alert','alert-success','alert-danger'); box.innerHTML = '<div class="text-danger">Error: ' + (e && e.message ? e.message : 'Unknown') + '</div>'; })
      .finally(function(){ btn.disabled=false; btn.innerText = txt; });
    };
  })();
</script>
 
@endsection

