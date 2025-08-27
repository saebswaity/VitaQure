@extends('layouts.app')

@section('title')
{{ __('QC - Reference Values') }}
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
  .analyte-select { min-width:240px; border-radius:9999px; }
  .control-item { padding:8px 10px; border-bottom:1px solid #eef2f7; display:flex; align-items:center; justify-content:space-between; }
  .control-item:hover { background:#f9fafb; }
  .status-badge { font-size:12px; border-radius:9999px; padding:2px 8px; }
  .status-saved { background:#d1fae5; color:#065f46; }
  .status-missing { background:#fee2e2; color:#991b1b; }
  .sigma-wrap { border:1px solid #e5e7eb; border-radius:12px; background:#fff; box-shadow:0 6px 18px rgba(0,0,0,.06); }
  .sigma-title { padding:10px 14px; border-bottom:1px solid #e5e7eb; font-weight:700; color:#374151; }
  .sigma-body { padding:12px; }
  .sigma-legend { gap:14px; }
  .sigma-legend .legend-item { display:inline-flex; align-items:center; font-size:12px; color:#374151; margin-right:10px; margin-bottom:6px; }
  .sigma-legend .legend-color { width:14px; height:10px; border-radius:2px; margin-right:6px; display:inline-block; }
  .sigma-legend .legend-line { width:22px; height:0; border-bottom-width:3px; border-bottom-style:solid; display:inline-block; margin-right:6px; border-radius:2px; }
  .legend-mean { border-bottom-color:#22c55e; }
  .legend-1sd { border-bottom-color:#f59e0b; border-bottom-style:dotted; }
  .legend-2sd { border-bottom-color:#f59e0b; border-bottom-style:dotted; }
  .legend-3sd { border-bottom-color:#ef4444; border-bottom-style:dashed; }
</style>
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0 text-dark"><i class="fas fa-chart-line"></i> {{ __('Reference Values') }}</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('Admin Main')}}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.qc') }}">QC</a></li>
          <li class="breadcrumb-item active">{{ __('Reference Values') }}</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="d-flex align-items-center justify-content-between mt-4">
    <button type="button" class="btn btn-back btn-sm btn-pill" onclick="if(history.length>1){history.back();}else{window.location.href='{{ route('admin.home') }}';}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</button>
    <a class="btn btn-primary btn-sm btn-pill" id="btn-next" href="#">{{ __('Next: Daily QC Entry') }}</a>
  </div>
  <div class="mt-3 text-center hint">{{ __('These values are set once and used as baseline for future QC analysis.') }}</div>




        
        


</div>
@endsection

@section('content')
<div class="row">
  <div class="col-lg-6">
    <div class="card qc-card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="qc-title mb-0">{{ __('Select Analyte & Controls') }}</h3>
        <div class="d-flex align-items-center">
          <label class="mb-0 mr-2 text-muted">{{ __('Analyte') }}</label>
          <select id="analyte" class="form-control form-control-sm analyte-select"></select>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <label class="mb-0">{{ __('Control Materials (assigned)') }}</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="select-all-controls">
              <label class="form-check-label" for="select-all-controls">{{ __('Select all') }}</label>
            </div>
          </div>
          <div id="controls-list" style="max-height:320px; overflow:auto"></div>
        </div>
        <button class="btn btn-primary btn-pill" id="btn-load">{{ __('Load Reference Values') }}</button>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card qc-card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="qc-title mb-0">{{ __('Input Reference Values') }}</h3>
        <div class="text-muted small" id="decimals-hint"></div>
      </div>
      <div class="card-body" id="forms"></div>
      <div class="card-footer">
        <button class="btn btn-primary btn-pill" id="btn-save">{{ __('Save Reference Values') }}</button>
        <button class="btn btn-secondary btn-pill ml-2" id="btn-reset">{{ __('Reset') }}</button>
      </div>
    </div>
    <div class="mt-3 text-center hint">{{ __('These values are set once and used as baseline for future QC analysis.') }}</div>
  </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
  var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
  var analytes = [];
  var assignedIds = [];
  var controls = [];
  var analyteDecimals = 2;
  var preControlId = parseInt(new URLSearchParams(location.search).get('control_id')||'0');

  function opt(v,t){ var o=document.createElement('option'); o.value=v; o.textContent=t; return o; }

  function setStepInputs(root){
    var step = analyteDecimals>0 ? (1/Math.pow(10,analyteDecimals)).toFixed(analyteDecimals) : '1';
    (root||document).querySelectorAll('input[type="number"]').forEach(function(inp){ inp.step = step; });
    var hint = document.getElementById('decimals-hint'); if(hint){ hint.textContent = '{{ __('Decimals') }}: '+analyteDecimals; }
  }

  function formBlock(c){
    var wrap=document.createElement('div');
    wrap.className='border rounded p-3 mb-3';
    wrap.dataset.cid=c.id;
    wrap.innerHTML='<div class="d-flex justify-content-between mb-2"><h6 class="mb-0">Control: '+c.name+' (Lot: '+c.lot_number+') - '+c.level+'</h6><span class="status-badge status-missing" data-status>'+@json(__('not saved'))+'</span></div>'+
      '<div class="form-row">'
        +'<div class="form-group col-md-3"><label>MEAN</label><input type="number" step="0.0001" class="form-control" data-k="mean"></div>'
        +'<div class="form-group col-md-3"><label>SD</label><input type="number" step="0.0001" class="form-control" data-k="sd"></div>'
      +'</div>'+
      '<div class="form-row">'
        +'<div class="form-group col-md-2"><label>+1SD</label><input type="number" class="form-control" data-calc="plus_1sd" data-k="plus_1sd"></div>'
        +'<div class="form-group col-md-2"><label>+2SD</label><input type="number" class="form-control" data-calc="plus_2sd" data-k="plus_2sd"></div>'
        +'<div class="form-group col-md-2"><label>+3SD</label><input type="number" class="form-control" data-calc="plus_3sd" data-k="plus_3sd"></div>'
        +'<div class="form-group col-md-2"><label>-1SD</label><input type="number" class="form-control" data-calc="minus_1sd" data-k="minus_1sd"></div>'
        +'<div class="form-group col-md-2"><label>-2SD</label><input type="number" class="form-control" data-calc="minus_2sd" data-k="minus_2sd"></div>'
        +'<div class="form-group col-md-2"><label>-3SD</label><input type="number" class="form-control" data-calc="minus_3sd" data-k="minus_3sd"></div>'
      +'</div>';
    return wrap;
  }

  function loadValues(){
    var aid = analyteId;
    var cids = assignedIds.slice();
    if(!aid || !cids.length){ document.getElementById('forms').innerHTML=''; return; }
    fetch(@json(route('admin.qc.reference.load')),{ method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':@json(csrf_token())}, body:JSON.stringify({ analyte_id:aid, control_ids:cids })}).then(r=>r.json()).then(function(res){
      var forms=document.getElementById('forms'); forms.innerHTML='';
      var map = {}; controls.forEach(function(c){ map[c.id]=c; });
      cids.forEach(function(cid){
        var c = map[cid]; if(!c) return;
        var block=formBlock(c); forms.appendChild(block);
        var data = (res.data||{})[String(cid)] || null;
        if(data){
          var meanInp = block.querySelector('input[data-k="mean"]');
          var sdInp = block.querySelector('input[data-k="sd"]');
          if(meanInp && data.mean!=null) meanInp.value = data.mean;
          if(sdInp && data.sd!=null) sdInp.value = data.sd;
          updateCalc(block);
          var badge = block.querySelector('[data-status]'); if(badge){ badge.textContent=@json(__('saved')); badge.classList.remove('status-missing'); badge.classList.add('status-saved'); }
        }
      });
      setStepInputs(forms);
      document.getElementById('btn-next').href = @json(url('admin/qc/entries'))+'?analyte_id='+aid;
    });
  }

  function updateCalc(scope){
    var root = scope || document;
    var mean = parseFloat((root.querySelector('input[data-k="mean"]')||{}).value||'');
    var sd = parseFloat((root.querySelector('input[data-k="sd"]')||{}).value||'');
    var setVal = function(sel, val){ var el=root.querySelector(sel); if(el){ el.value = isFinite(val)? val.toFixed(analyteDecimals) : ''; } };
    if(isFinite(mean) && isFinite(sd)){
      setVal('input[data-calc="plus_1sd"]', mean+sd);
      setVal('input[data-calc="plus_2sd"]', mean+2*sd);
      setVal('input[data-calc="plus_3sd"]', mean+3*sd);
      setVal('input[data-calc="minus_1sd"]', mean-sd);
      setVal('input[data-calc="minus_2sd"]', mean-2*sd);
      setVal('input[data-calc="minus_3sd"]', mean-3*sd);
    } else {
      ['plus_1sd','plus_2sd','plus_3sd','minus_1sd','minus_2sd','minus_3sd'].forEach(function(k){ setVal('input[data-calc="'+k+'"]',''); });
    }
  }

  document.getElementById('btn-load').onclick=loadValues;
  document.getElementById('btn-save').onclick=function(){
    var aid = analyteId;
    var forms = Array.from(document.querySelectorAll('#forms > div[data-cid]'));
    var items = forms.map(function(div){
      var cid = parseInt(div.dataset.cid);
      var obj = { control_id: cid };
      var meanInp = div.querySelector('input[data-k="mean"]');
      var sdInp = div.querySelector('input[data-k="sd"]');
      obj.mean = meanInp && meanInp.value ? parseFloat(meanInp.value) : null;
      obj.sd = sdInp && sdInp.value ? parseFloat(sdInp.value) : null;
      // Optional manual overrides
      ['plus_1sd','plus_2sd','plus_3sd','minus_1sd','minus_2sd','minus_3sd'].forEach(function(k){
        var el = div.querySelector('input[data-k="'+k+'"]');
        if(el && el.value){ obj[k] = parseFloat(el.value); }
      });
      return obj;
    });
    for(var i=0;i<items.length;i++){
      var it=items[i];
      if(typeof it.mean !== 'number' || isNaN(it.mean) || typeof it.sd !== 'number' || isNaN(it.sd)) { alert('Mean and SD are required and must be numeric.'); return; }
      if(!(it.sd>0)){ alert('SD must be positive.'); return; }
    }
    if(!confirm('Save reference values?')) return;
    fetch(@json(route('admin.qc.reference.save')),{ method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':@json(csrf_token())}, body:JSON.stringify({ analyte_id: aid, items: items })}).then(function(r){ if(!r.ok) return r.text().then(t=>Promise.reject(t)); return r.json(); }).then(function(){ alert('Saved'); }).catch(function(e){ alert('Save failed: '+e); });
  };
  document.getElementById('btn-reset').onclick=function(){ document.querySelectorAll('#forms input[data-k]').forEach(function(inp){ inp.value=''; }); };

  function loadAnalytes(){
    fetch(@json(route('admin.qc.reference.options'))).then(r=>r.json()).then(function(res){
      analytes = res.analytes||[];
      var aSel=document.getElementById('analyte'); aSel.innerHTML='';
      analytes.forEach(function(a){ aSel.appendChild(opt(a.id, a.name+' ['+(a.unit||'')+']')); });
      if(!analyteId && analytes.length){ analyteId = Number(analytes[0].id); }
      aSel.value = String(analyteId);
      var a = analytes.find(function(x){ return Number(x.id)===analyteId; });
      analyteDecimals = a ? Number(a.decimals||0) : 2;
      loadAssigned();
    });
  }

  function loadAssigned(){
    fetch(@json(route('admin.qc.materials.assigned_for_analyte'))+'?analyte_id='+analyteId).then(r=>r.json()).then(function(res){
      var list = document.getElementById('controls-list'); list.innerHTML='';
      controls = res.materials||[];
      assignedIds = (res.assigned||[]).map(Number);
      if(preControlId && !assignedIds.includes(preControlId)){
        if(controls.some(function(c){ return Number(c.id)===preControlId; })){
          assignedIds.push(preControlId);
        }
      }
      controls.forEach(function(c){
        var row=document.createElement('div'); row.className='control-item';
        var left=document.createElement('div'); left.className='d-flex align-items-center';
        var cb=document.createElement('input'); cb.type='checkbox'; cb.className='mr-2'; cb.value=c.id; cb.checked=assignedIds.includes(c.id);
        cb.addEventListener('change', function(){
          var id = Number(this.value);
          if(this.checked){ if(!assignedIds.includes(id)) assignedIds.push(id); }
          else { var i=assignedIds.indexOf(id); if(i!==-1) assignedIds.splice(i,1); }
        });
        left.appendChild(cb);
        var lbl=document.createElement('div'); lbl.innerHTML='<strong>'+c.name+'</strong> <small class="text-muted">#'+c.lot_number+'</small> ('+c.level+')'; left.appendChild(lbl);
        var status=document.createElement('span'); status.className='status-badge status-missing'; status.textContent='—';
        row.appendChild(left); row.appendChild(status);
        list.appendChild(row);
      });
      setStepInputs();
    }).then(loadValues);
  }

  // compute automatically when mean/sd change
  document.addEventListener('input', function(e){
    if(e.target && (e.target.matches('input[data-k="mean"]') || e.target.matches('input[data-k="sd"]'))){
      var card = e.target.closest('div[data-cid]');
      if(card){ updateCalc(card); }
    }
  });

  document.getElementById('select-all-controls').addEventListener('change', function(){
    var all = Array.from(document.querySelectorAll('#controls-list input[type="checkbox"]'));
    if(this.checked){ assignedIds = controls.map(function(c){ return Number(c.id); }); all.forEach(function(cb){ cb.checked = true; }); }
    else { assignedIds = []; all.forEach(function(cb){ cb.checked = false; }); }
  });

  document.getElementById('analyte').addEventListener('change', function(){
    analyteId = Number(this.value);
    var a = analytes.find(function(x){ return Number(x.id)===analyteId; });
    analyteDecimals = a ? Number(a.decimals||0) : 2;
    try{ var u=new URL(location.href); u.searchParams.set('analyte_id', analyteId); history.replaceState(null,'',u.toString()); }catch(_){ }
    loadAssigned();
  });

  loadAnalytes();
})();
</script>
@endsection

