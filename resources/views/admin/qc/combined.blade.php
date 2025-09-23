@extends('layouts.app')

@section('title')
{{ __('QC - Assign Control Materials & Reference Values') }}
@endsection

@section('css')
<style>
  .content-wrapper { background:#f5f7fa; }
  .qc-card { border-radius:12px; border:1px solid #e5e7eb; background:#fff; box-shadow:0 6px 18px rgba(0,0,0,.06); }
  .qc-title { font-weight:800; font-size:1.05rem; color:#1f2937; margin:0; }
  .qc-title i { color:#3b82f6; margin-right:8px; }
  .list-item { cursor:pointer; padding:12px 14px; border-bottom:1px solid #eef2f7; transition: background .12s ease; }
  .list-item:hover { background:#f9fafb; }
  .list-item.active { background:#e0f2fe; }
  .list-item.just-added { background:#ecfeff; }
  .badge-level { display:inline-block; padding:2px 8px; border-radius:9999px; font-size:12px; font-weight:700; }
  .lvl-low{ background:#fee2e2; color:#991b1b; }
  .lvl-normal{ background:#d1fae5; color:#065f46; }
  .lvl-high{ background:#e0e7ff; color:#3730a3; }
  .btn-primary{ background:#3b82f6; border-color:#3b82f6; }
  .btn-outline-primary{ border-color:#3b82f6; color:#3b82f6; }
  .btn-outline-primary:hover{ background:#3b82f6; color:#fff; }
  .btn-pill { border-radius:9999px; padding:6px 14px; font-weight:700; }
  .btn-back { border-color:#6b7280; color:#374151; }
  .btn-back:hover { background:#374151; color:#fff; }
  .material-actions .btn { padding:0 6px; }
  .lot-number { font-size: 0.95rem; font-weight: 700; color:#111827; }
  .assignments-box { min-height:48px; padding:6px 0; }
  .placeholder { color:#6b7280; font-size: 0.9rem; }
  .sel-badge { display:inline-block; padding:2px 8px; border-radius:9999px; font-size:12px; font-weight:700; margin-left:8px; }
  .sel-yes { background:#d1fae5; color:#065f46; }
  .sel-no { background:#e5e7eb; color:#374151; }
  .list-item.disabled { cursor: not-allowed; opacity: 0.6; }
  .list-item.disabled:hover { background: #f3f4f6; }
  .assignment-count { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
  .assignment-count .count-text { font-weight: 600; color: #1f2937; }
  .assignment-count .status-text { font-size: 14px; }
  .assignment-count .status-success { color: #059669; }
  .assignment-count .status-muted { color: #6b7280; }
  .remove-assignment { cursor: pointer; color: #ef4444; margin-left: 8px; }
  .remove-assignment:hover { color: #dc2626; }
  .hint{ color:#6b7280; font-size:12px; }
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0 text-dark"><i class="fas fa-box"></i> {{ __('Assign Control Materials & Reference Values') }}</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('Admin Main')}}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.qc') }}">QC</a></li>
          <li class="breadcrumb-item active">{{ __('Materials & Reference Values') }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="mb-2 d-flex align-items-center justify-content-between">
  <button type="button" class="btn btn-back btn-sm btn-pill" onclick="if(history.length>1){history.back();}else{window.location.href='{{ route('admin.home') }}';}">
    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
  </button>
</div>

<!-- Analyte Context Header -->
<div class="card qc-card mb-4">
  <div class="card-body">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h4 class="mb-1">{{ $analyte->name }} <span class="text-muted">[{{ $analyte->unit }}]</span></h4>
        <p class="mb-0 text-muted">{{ __('Decimals') }}: {{ $analyte->decimals ?? 2 }}</p>
      </div>
      <div class="text-right">
        <span class="badge badge-primary">{{ __('Analyte Selected') }}</span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- LEFT SIDE: Registered Control Materials -->
  <div class="col-lg-6">
    <div class="card qc-card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="qc-title mb-0">{{ __('Registered Control Materials') }}</h3>
        <div class="input-group input-group-sm" style="width:200px">
          <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
          <input id="q" type="text" class="form-control" placeholder="{{ __('Search name or lot') }}">
        </div>
      </div>
      <div class="card-body p-0" style="max-height:420px; overflow:auto" id="materials-list"></div>
      <div class="card-footer d-flex justify-content-between align-items-center">
        <button class="btn btn-primary btn-sm btn-pill" id="btn-new"><i class="fas fa-plus"></i> {{ __('Add new material') }}</button>
        <span class="text-muted small">{{ __('Total') }}: <span id="mat-count">0</span></span>
      </div>
    </div>
  </div>

  <!-- RIGHT SIDE: QC Materials for Selected Analyte -->
  <div class="col-lg-6">
    <div class="card qc-card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="qc-title mb-0">{{ __('QC Materials for') }} <span class="text-danger">{{ $analyte->name }}</span></h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label>{{ __('Assign to this analyte') }}</label>
          <div id="assignments" class="d-flex flex-wrap assignments-box"></div>
        </div>
        <button class="btn btn-primary" id="btn-save-assign">{{ __('Save Assignment') }}</button>
      </div>
    </div>

    <div class="card qc-card mt-3" id="new-form" style="display:none">
      <div class="card-header"><h3 class="qc-title mb-0">{{ __('Register Control Material') }}</h3></div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4"><label>{{ __('Name') }}</label><input type="text" id="m_name" class="form-control"></div>
          <div class="form-group col-md-4"><label>{{ __('Lot Number') }}</label><input type="text" id="m_lot" class="form-control"></div>
          <div class="form-group col-md-2"><label>{{ __('Level') }}</label>
            <select id="m_level" class="form-control">
              <option>Low</option><option>Normal</option><option>High</option>
            </select>
          </div>
          <div class="form-group col-md-2"><label>{{ __('Expiry Date') }}</label><input type="date" id="m_exp" class="form-control"></div>
        </div>
        <button class="btn btn-primary" id="btn-save-mat">{{ __('Save Material') }}</button>
      </div>
    </div>
  </div>
</div>

<!-- BOTTOM SECTION: Input Reference Values -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card qc-card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="qc-title mb-0">{{ __('Input Reference Values') }}</h3>
        <div class="text-muted small" id="decimals-hint">{{ __('Decimals') }}: {{ $analyte->decimals ?? 2 }}</div>
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
          <div id="controls-list" style="max-height:200px; overflow:auto"></div>
        </div>
        <button class="btn btn-primary btn-pill" id="btn-load">{{ __('Load Reference Values') }}</button>
      </div>
    </div>

    <div class="card qc-card mt-3">
      <div class="card-header">
        <h3 class="qc-title mb-0">{{ __('Reference Values Forms') }}</h3>
      </div>
      <div class="card-body" id="forms"></div>
      <div class="card-footer">
        <button class="btn btn-primary btn-pill" id="btn-save">{{ __('Save Reference Values') }}</button>
        <button class="btn btn-secondary btn-pill ml-2" id="btn-reset">{{ __('Reset') }}</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
  var analyteId = {{ $analyte->id }};
  var analyteName = '{{ $analyte->name }}';
  var analyteDecimals = {{ $analyte->decimals ?? 2 }};
  var analytes = [];
  var materials = [];
  var assigned = [];
  var assignedIds = [];
  var controls = [];
  var lastAddedId = 0;

  function esc(s){ return String(s||'').replace(/[&<>"']/g, function(c){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]); }); }

  function badge(level){
    var cls = level==='Low'?'lvl-low':(level==='High'?'lvl-high':'lvl-normal');
    return '<span class="badge-level '+cls+'">'+level+'</span>';
  }

  function setStepInputs(root){
    var step = analyteDecimals>0 ? (1/Math.pow(10,analyteDecimals)).toFixed(analyteDecimals) : '1';
    (root||document).querySelectorAll('input[type="number"]').forEach(function(inp){ inp.step = step; });
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

  function renderList(){
    var wrap = document.getElementById('materials-list'); wrap.innerHTML='';
    materials.forEach(function(m){
      var div=document.createElement('div');
      var isSelected = assigned.includes(m.id);
      var isDisabled = !isSelected && assigned.length >= 3;
      
      div.className='list-item'+(assigned.includes(m.id)?' active':'')+(lastAddedId===m.id?' just-added':'')+(isDisabled?' disabled':'');
      if(isDisabled) div.style.opacity = '0.6';
      
      div.innerHTML='<div class="d-flex justify-content-between align-items-center">'
        +'<div>'
          +'<div class="font-weight-bold">'+m.name+' <span class="lot-number">#'+m.lot_number+'</span></div>'
          +'<div class="text-muted small">'+(m.expiry_date?('Exp: '+m.expiry_date):'')
            +'<span class="sel-badge '+(isSelected?'sel-yes':'sel-no')+'">'+(isSelected?@json(__('selected')):@json(__('not selected')) )+'</span>'
            +(isDisabled?' <span class="text-warning small">(Max 3 reached)</span>':'')
          +'</div>'
        +'</div>'
        +'<div class="d-flex align-items-center material-actions">'
          +badge(m.level)
          +'<button type="button" class="btn btn-link text-danger btn-sm ml-2 material-delete" title="Delete"><i class="fas fa-trash"></i></button>'
        +'</div>'
        +'</div>';
      
      div.onclick=function(){
        if(isDisabled) {
          alert('Maximum of 3 control materials allowed per analyte. Please remove one before adding another.');
          return;
        }
        var idx=assigned.indexOf(m.id);
        if(idx===-1) {
          if(assigned.length >= 3) {
            alert('Maximum of 3 control materials allowed per analyte. Please remove one before adding another.');
            return;
          }
          assigned.push(m.id);
        } else {
          assigned.splice(idx,1);
        }
        renderList(); 
        renderAssign();
        loadControls();
      };
      
      var del = div.querySelector('.material-delete');
      del.onclick=function(e){ e.stopPropagation(); if(confirm('Delete this material?')) destroyMaterial(m.id); };
      wrap.appendChild(div);
    });
  }

  function renderAssign(){
    var tgt = document.getElementById('assignments'); 
    if (!tgt) {
      console.error('Assignments container not found!');
      return;
    }
    
    tgt.innerHTML='';
    
    var selected = materials.filter(function(m){return assigned.includes(m.id)});
    selected.forEach(function(m){
      var chip=document.createElement('div');
      chip.className='mr-2 mb-2 badge badge-pill badge-light';
      chip.innerHTML = m.name+' ('+m.level+') <i class="fas fa-times remove-assignment" onclick="removeAssignment('+m.id+')"></i>';
      tgt.appendChild(chip);
    });
    
    if(!selected.length){
      if(assigned.length===0){
        var ph=document.createElement('div'); ph.className='placeholder'; ph.textContent=@json(__('No materials assigned yet. Click items on the left to assign.'));
        tgt.appendChild(ph);
      }
    }
  }

  function loadControls(){
    var list = document.getElementById('controls-list'); list.innerHTML='';
    controls = materials.filter(function(m){ return assigned.includes(m.id); });
    assignedIds = assigned.slice();
    
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
    });
  }

  function loadMaterials(){
    var q = document.getElementById('q').value.trim();
    fetch(@json(route('admin.qc.materials.list'))+'?q='+encodeURIComponent(q)).then(r=>r.json()).then(function(res){
      materials = res.data||[]; 
      renderList(); 
      renderAssign(); 
      loadControls();
      var mc=document.getElementById('mat-count'); if(mc){ mc.textContent=materials.length; }
    });
    fetch(@json(route('admin.qc.materials.assigned_for_analyte'))+'?analyte_id='+analyteId).then(r=>r.json()).then(function(res){
      if(res.ok && res.assigned) {
        assigned = res.assigned.map(Number); 
        renderList(); 
        renderAssign();
        loadControls();
      } else {
        console.error('Failed to load assignments:', res);
        assigned = [];
        renderAssign();
        loadControls();
      }
    }).catch(function(e){
      console.error('Error loading assignments:', e);
      assigned = [];
      renderAssign();
      loadControls();
    });
  }

  // Event listeners
  document.getElementById('q').addEventListener('input', function(){ loadMaterials(); });
  document.getElementById('btn-new').onclick=function(){ document.getElementById('new-form').style.display='block'; };
  document.getElementById('btn-save-mat').onclick=function(){
    var payload={ name:document.getElementById('m_name').value.trim(), lot_number:document.getElementById('m_lot').value.trim(), level:document.getElementById('m_level').value, expiry_date:document.getElementById('m_exp').value, analyte_id: analyteId };
    fetch(@json(route('admin.qc.materials.store')),{ method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':@json(csrf_token())}, body:JSON.stringify(payload)}).then(function(r){ if(!r.ok) return r.text().then(t=>Promise.reject(t)); return r.json(); }).then(function(res){ lastAddedId = res.id||0; if(lastAddedId){ if(!assigned.includes(lastAddedId)) assigned.push(lastAddedId); } renderAssign(); loadMaterials(); document.getElementById('new-form').style.display='none'; }).catch(function(e){ alert('Save failed: '+e); });
  };
  document.getElementById('btn-save-assign').onclick=function(){
    fetch(@json(route('admin.qc.materials.assign_for_analyte')),{ method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':@json(csrf_token())}, body:JSON.stringify({ analyte_id:analyteId, control_ids:assigned })}).then(function(r){ if(!r.ok) return r.text().then(t=>Promise.reject(t)); return r.json(); }).then(function(){ alert('Saved'); loadControls(); }).catch(function(e){ alert('Save failed: '+e); });
  };

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

  document.getElementById('select-all-controls').addEventListener('change', function(){
    var all = Array.from(document.querySelectorAll('#controls-list input[type="checkbox"]'));
    if(this.checked){ assignedIds = controls.map(function(c){ return Number(c.id); }); all.forEach(function(cb){ cb.checked = true; }); }
    else { assignedIds = []; all.forEach(function(cb){ cb.checked = false; }); }
  });

  // compute automatically when mean/sd change
  document.addEventListener('input', function(e){
    if(e.target && (e.target.matches('input[data-k="mean"]') || e.target.matches('input[data-k="sd"]'))){
      var card = e.target.closest('div[data-cid]');
      if(card){ updateCalc(card); }
    }
  });

  function destroyMaterial(id){
    fetch(@json(url('admin/qc/materials'))+'/'+id, { method:'DELETE', headers:{ 'Accept':'application/json', 'X-CSRF-TOKEN':@json(csrf_token()) } }).then(function(r){ if(!r.ok) return r.text().then(t=>Promise.reject(t)); return r.json(); }).then(function(){
      var idx = assigned.indexOf(id); if(idx!==-1) assigned.splice(idx,1);
      loadMaterials();
    }).catch(function(e){ alert('Delete failed: '+e); });
  }

  function removeAssignment(materialId) {
    if (confirm('Are you sure you want to remove this material from the assignment?')) {
      var index = assigned.indexOf(materialId);
      if (index !== -1) {
        assigned.splice(index, 1);
        renderAssign();
        loadControls();
      }
    }
  }

  // Initialize
  loadMaterials();
})();
</script>
@endsection
