@extends('layouts.app')

@section('title')
{{ __('Quality Control - Define Analytes') }}
@endsection

@section('css')
<style>
  .content-wrapper { background:#f5f7fa; }
  .qc-card { border-radius:12px; border:1px solid #e5e7eb; background:#fff; box-shadow:0 6px 18px rgba(0,0,0,.06); }
  .qc-title { font-weight:800; font-size:1.05rem; color:#1f2937; margin:0; }
  .qc-title i { color:#3b82f6; margin-right:8px; }
  .btn-primary{ background:#3b82f6; border-color:#3b82f6; }
  .hint{ color:#6b7280; font-size:12px; }
  .btn-pill { border-radius:9999px; padding:6px 14px; font-weight:700; }
  .btn-back { border-color:#6b7280; color:#374151; }
  .btn-back:hover { background:#374151; color:#fff; }
  .qc-form .form-group { margin-bottom: 12px; }
  .qc-form label { font-weight: 700; color: #1f2937; font-size: 14px; }
  .qc-form .form-control, .qc-form select.form-control { border-radius: 6px; border:1px solid #d1d5da; font-size:14px; height:38px; }
  .qc-form .form-control:focus, .qc-form select.form-control:focus { border-color:#3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); }
  .qc-error { display:none; color:#b91c1c; font-size:12px; margin-top:4px; }
  .required::after { content:' *'; color:#b91c1c; }
  .qc-button-bar .btn { min-width: 140px; font-weight: 700; border:none; transition: transform .12s ease, box-shadow .12s ease; }
  .qc-button-bar .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(0,0,0,.08); }
  .btn-add { background:#3b82f6; color:#fff; border-radius: 9999px; letter-spacing:.2px; }
  .btn-add:hover { filter:brightness(0.95); }
  .btn-reset { background:#1f2937; color:#fff; border-radius: 9999px; }
  .btn-reset:hover { filter:brightness(0.95); }
  .btn-outline-primary.btn-sm { border-color:#3b82f6; color:#3b82f6; }
  .btn-outline-primary.btn-sm:hover { background:#3b82f6; color:#fff; }
  .qc-table thead th { background: #eef4ff; color: #22324a; border: none !important; font-weight: 700; position:sticky; top:0; z-index:1; }
  .qc-table tbody tr { cursor: pointer; transition: background .12s ease; }
  .qc-table tbody tr:hover { background-color: #eff6ff; }
  .qc-table tbody tr.active { background-color: #e0e7ff; }
  .qc-action { color:#6b7280; cursor:pointer; }
  .qc-action:hover { color:#ef4444; }
  .lvl-badge { display:inline-block; background:#e5e7eb; color:#374151; font-weight:700; font-size:12px; border-radius:9999px; padding:2px 8px; margin:0 4px 4px 0; }
  .input-group-text { background:#eef2ff; border-color:#e5e7eb; color:#374151; }
  #search { border-color:#d1d5da; }
  #search:focus { border-color:#3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"><i class="fas fa-flask"></i> {{ __('Define Analytes') }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('Admin Main')}}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.qc') }}">QC</a></li>
          <li class="breadcrumb-item active">{{ __('Define Analytes') }}</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="d-flex align-items-center justify-content-between mt-4">
    <button type="button" class="btn btn-back btn-sm btn-pill" onclick="window.location.href='{{ route('admin.home') }}'">
      <i class="fas fa-arrow-left"></i> {{ __('Back') }}
    </button>
  </div>
  <div class="mt-3 text-center hint">{{ __('Define laboratory tests (analytes) with name, unit, and decimal precision.') }}</div>
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-lg-4">
    <div class="card qc-card">
      <div class="card-header"><h3 class="qc-title">{{ __('Analyte Details') }}</h3></div>
      <div class="card-body">
        <form class="qc-form" onsubmit="return false;">
          <div class="form-group">
            <label class="required">{{ __('Name') }}</label>
            <input type="text" class="form-control" id="name" maxlength="15" placeholder="e.g., Glucose">
            <small class="text-muted">{{ __('Max 15 characters.') }}</small>
            <div class="qc-error" id="err-name"></div>
          </div>
          <div class="form-group">
            <label class="required">{{ __('Unit') }}</label>
            <select class="form-control" id="unit">
              <option value="" selected disabled>{{ __('Select unit') }}</option>
              <option>mmol/L</option>
              <option>g/L</option>
              <option>U/L</option>
              <option>mg/dL</option>
              <option>ng/mL</option>
              <option>IU/L</option>
              <option>µg/L</option>
            </select>
            <small class="text-muted">{{ __('Max 6 characters.') }}</small>
            <div class="qc-error" id="err-unit"></div>
          </div>
          <div class="form-group">
            <label class="required">{{ __('Decimals') }}</label>
            <input type="number" class="form-control" id="decimals" min="0" max="3" step="1" value="0">
            <div class="qc-error" id="err-decimals"></div>
          </div>
          <div class="qc-button-bar mt-3 d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-add mr-2" id="btn-add">{{ __('Add to list') }}</button>
            <button type="button" class="btn btn-reset" id="btn-reset">{{ __('Reset fields') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card qc-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="qc-title mb-0">{{ __('Analytes') }}</h3>
        <div class="d-flex align-items-center ml-auto" style="margin-left:auto">
          <div class="input-group input-group-sm mr-2" style="width:260px">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input id="search" type="text" class="form-control" placeholder="{{ __('Search by name or unit') }}">
          </div>
          <select id="sort-mode" class="form-control form-control-sm">
            <option value="name_asc">{{ __('Name (A–Z)') }}</option>
            <option value="name_desc">{{ __('Name (Z–A)') }}</option>
            <option value="unit_asc">{{ __('Unit (A–Z)') }}</option>
            <option value="unit_desc">{{ __('Unit (Z–A)') }}</option>
            <option value="decimals_asc">{{ __('Decimals (0–3)') }}</option>
            <option value="decimals_desc">{{ __('Decimals (3–0)') }}</option>
          </select>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive" style="max-height: 420px;">
          <table class="table qc-table" id="tbl">
            <thead>
              <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Levels') }}</th>
                <th>{{ __('Unit') }}</th>
                <th>{{ __('Decimals') }}</th>
                <th style="width:120px;">{{ __('Action') }}</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
  var rows = [];
  var selectedId = null;
  var filtered = [];
  // Persisted UI state
  var storedSort = null;
  var storedSearch = null;

  function esc(s){ return String(s||'').replace(/[&<>"']/g, function(c){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]); }); }
  function showErr(id,msg){ var el=document.getElementById('err-'+id); if(el){ el.textContent=msg; el.style.display='block'; } }
  function clearErr(){ ['name','unit','decimals'].forEach(function(k){ var el=document.getElementById('err-'+k); if(el){ el.textContent=''; el.style.display='none'; } }); }

  function validate(){
    clearErr();
    var name=document.getElementById('name').value.trim();
    var unit=document.getElementById('unit').value;
    var decimals=Number(document.getElementById('decimals').value);
    var ok=true;
    if(!name){ showErr('name','Name is required'); ok=false; } else if(name.length>15){ showErr('name','Max 15 characters'); ok=false; }
    if(!unit){ showErr('unit','Unit is required'); ok=false; } else if(unit.length>6){ showErr('unit','Max 6 characters'); ok=false; }
    if(!(decimals>=0 && decimals<=3)){ showErr('decimals','Decimals must be 0-3'); ok=false; }
    return ok? {name:name, unit:unit, decimals:decimals} : null;
  }

  function render(){
    var tbody=document.querySelector('#tbl tbody'); tbody.innerHTML='';
    var data = filtered.length ? filtered : rows;
    data.forEach(function(r){
      var tr=document.createElement('tr'); tr.dataset.id=r.id;
      tr.innerHTML='<td>'+esc(r.name)+'</td>'+
                   '<td>'+(typeof r.levels_count!=='undefined' ? (r.levels_count+'') : (r.levels||''))+'</td>'+
                   '<td>'+esc(r.unit)+'</td>'+
                   '<td>'+Number(r.decimals)+'</td>'+
                   '<td><i class="fas fa-edit qc-action mr-2" title="Edit" style="color:#3b82f6;"></i><i class="fas fa-trash qc-action" title="Delete"></i></td>';
      tr.onclick=function(e){
        if(e.target && (e.target.classList.contains('fa-trash') || e.target.classList.contains('fa-edit'))) return;
        // Navigate to Daily QC Entry page for this analyte
        window.location.href = @json(url('admin/qc/entries')) + '?analyte_id=' + encodeURIComponent(r.id);
      };
      tr.querySelector('.fa-edit').onclick=function(e){ e.stopPropagation(); window.location.href = @json(url('admin/qc/materials-combined')) + '?analyte_id=' + encodeURIComponent(r.id); };
      tr.querySelector('.fa-trash').onclick=function(e){ e.stopPropagation(); if(confirm('Delete this analyte?')) destroy(r.id); };
      if(selectedId===r.id) tr.classList.add('active');
      tbody.appendChild(tr);
    });
    // Note: btn-replace button was removed, so this line is no longer needed
  }

  function select(id){ selectedId=id; var r=rows.find(function(x){return x.id===id}); if(!r) return; document.getElementById('name').value=r.name; document.getElementById('unit').value=r.unit; document.getElementById('decimals').value=r.decimals; render(); }

  function sortRows(mode){
    switch(mode){
      case 'name_asc': rows.sort(function(a,b){ return a.name.localeCompare(b.name); }); break;
      case 'name_desc': rows.sort(function(a,b){ return b.name.localeCompare(a.name); }); break;
      case 'unit_asc': rows.sort(function(a,b){ return (a.unit||'').localeCompare(b.unit||''); }); break;
      case 'unit_desc': rows.sort(function(a,b){ return (b.unit||'').localeCompare(a.unit||''); }); break;
      case 'decimals_asc': rows.sort(function(a,b){ return Number(a.decimals) - Number(b.decimals); }); break;
      case 'decimals_desc': rows.sort(function(a,b){ return Number(b.decimals) - Number(a.decimals); }); break;
    }
  }

  function load(){ fetch(@json(route('admin.qc.analytes.list'))).then(r=>r.json()).then(function(res){
    rows=(res.data||[]).map(function(x){ return { id:x.id, name:x.name, unit:x.unit, decimals:Number(x.decimals), levels: x.levels||'', levels_count: (typeof x.levels_count!=='undefined'? Number(x.levels_count): undefined) }; });
    // Apply persisted search and sort
    filtered=[];
    if(storedSearch){
      var q = storedSearch.toLowerCase();
      filtered = rows.filter(function(r){ return (r.name||'').toLowerCase().includes(q) || (r.unit||'').toLowerCase().includes(q); });
    }
    if(storedSort){ sortRows(storedSort); }
    render();
  }); }
  function store(data){ return fetch(@json(route('admin.qc.analytes.store')),{ method:'POST', headers:{ 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) }, body: JSON.stringify(data) }).then(handle); }
  function update(id,data){ return fetch(@json(url('admin/qc/analytes')).replace(/\/$/,'')+'/'+id,{ method:'PUT', headers:{ 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) }, body: JSON.stringify(data) }).then(handle); }
  function destroy(id){ fetch(@json(url('admin/qc/analytes')).replace(/\/$/,'')+'/'+id,{ method:'DELETE', headers:{ 'Accept':'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) } }).then(handle).then(function(res){ if(!res.ok){ alert(res.error||'Delete failed'); return; } selectedId=null; load(); }); }
  function handle(r){ if(!r.ok){ return r.text().then(function(t){ throw new Error('HTTP '+r.status+' '+t); }); } return r.json(); }

  document.getElementById('btn-add').onclick=function(){ var data=validate(); if(!data) return; store(data).then(function(res){ if(!res.ok){ alert(res.error||'Save failed'); return; } load(); document.getElementById('btn-reset').click(); }).catch(function(e){ alert('Save failed: '+e.message); }); };
  // Replace button removed per request
  document.getElementById('btn-reset').onclick=function(){ selectedId=null; clearErr(); document.getElementById('name').value=''; document.getElementById('unit').value=''; document.getElementById('decimals').value='0'; render(); };
  document.getElementById('sort-mode').addEventListener('change', function(){
    var mode = this.value;
    localStorage.setItem('qc_sort_mode', mode);
    sortRows(mode);
    render();
  });

  // Search filter by name or unit
  document.getElementById('search').addEventListener('input', function(){
    var q = this.value.trim().toLowerCase();
    localStorage.setItem('qc_search', this.value.trim());
    if(!q){ filtered=[]; render(); return; }
    filtered = rows.filter(function(r){ return (r.name||'').toLowerCase().includes(q) || (r.unit||'').toLowerCase().includes(q); });
    render();
  });

  // Live update levels count when materials page adds/removes assignments
  window.addEventListener('storage', function(e){
    if(e.key === 'qc_assign_update'){
      try {
        var p = JSON.parse(e.newValue||'{}');
        var rid = Number(p.analyte_id||0);
        var delta = Number(p.change||0);
        if(rid && delta){
          var r = rows.find(function(x){ return Number(x.id)===rid; });
          if(r){ r.levels_count = Number(r.levels_count||0) + delta; render(); }
        }
      } catch(_){}
    }
  });

  // Refresh when navigating back to this page from materials
  window.addEventListener('pageshow', function(ev){ if(ev.persisted){ load(); } });

  // Restore persisted UI state
  storedSort = localStorage.getItem('qc_sort_mode') || document.getElementById('sort-mode').value;
  storedSearch = localStorage.getItem('qc_search') || '';
  document.getElementById('sort-mode').value = storedSort;
  document.getElementById('search').value = storedSearch;

  load();
})();
</script>
@endsection
