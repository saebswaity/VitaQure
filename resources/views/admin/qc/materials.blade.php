@extends('layouts.app')

@section('title')
{{ __('QC - Assign Control Materials') }}
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
  .analyte-select { min-width:240px; border-radius:9999px; }
  .list-item.disabled { cursor: not-allowed; opacity: 0.6; }
  .list-item.disabled:hover { background: #f3f4f6; }
  .assignment-count { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
  .assignment-count .count-text { font-weight: 600; color: #1f2937; }
  .assignment-count .status-text { font-size: 14px; }
  .assignment-count .status-success { color: #059669; }
  .assignment-count .status-muted { color: #6b7280; }
  .remove-assignment { cursor: pointer; color: #ef4444; margin-left: 8px; }
  .remove-assignment:hover { color: #dc2626; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0 text-dark"><i class="fas fa-box"></i> {{ __('Assign Control Materials') }}</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('Admin Main')}}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.qc') }}">QC</a></li>
          <li class="breadcrumb-item active">{{ __('Assign Materials') }}</li>
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
  <a class="btn btn-primary btn-sm btn-pill" id="btn-next" href="#">{{ __('Next: Reference Values') }}</a>
  
</div>
<div class="row">
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
  <div class="col-lg-6">
    <div class="card qc-card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h3 class="qc-title mb-0" id="assign-title">{{ __('QC materials for') }} <span class="text-danger">{{ __('analyte') }}</span></h3>
        <div class="d-flex align-items-center">
          <label class="mb-0 mr-2 text-muted">{{ __('Analyte') }}</label>
          <select id="analyte-switch" class="form-control form-control-sm analyte-select"></select>
        </div>
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
@endsection

@section('scripts')
<script>
(function(){
  var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
  var analyteName = '';
  var analytes = [];
  var materials = [];
  var assigned = [];
  var lastAddedId = 0;

  function esc(s){ return String(s||'').replace(/[&<>"']/g, function(c){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]); }); }

  function badge(level){
    var cls = level==='Low'?'lvl-low':(level==='High'?'lvl-high':'lvl-normal');
    return '<span class="badge-level '+cls+'">'+level+'</span>';
  }

  function populateAnalyteSelect(){
    var sel = document.getElementById('analyte-switch');
    sel.innerHTML = '';
    analytes.forEach(function(a){
      var o=document.createElement('option'); o.value=a.id; o.textContent=a.name + (a.unit?(' ['+a.unit+']'):''); sel.appendChild(o);
    });
    if(analyteId){ sel.value=String(analyteId); }
  }

  function setAnalyteHeader(){
    document.getElementById('assign-title').innerHTML = 'QC materials for <span class="text-danger">'+esc(analyteName)+'</span>';
    document.getElementById('btn-next').href = @json(url('admin/qc/reference'))+'?analyte_id='+analyteId;
  }

  function loadAnalyte(){
    fetch(@json(route('admin.qc.analytes.list'))).then(r=>r.json()).then(function(res){
      analytes = res.data||[];
      if(!analyteId && analytes.length){ analyteId = Number(analytes[0].id); }
      var a=analytes.find(function(x){return Number(x.id)===analyteId});
      analyteName = a ? a.name : ('#'+analyteId);
      populateAnalyteSelect();
      setAnalyteHeader();
      // update URL to reflect selection
      try{ var u=new URL(location.href); u.searchParams.set('analyte_id', analyteId); history.replaceState(null,'',u.toString()); }catch(_){ }
      // Render assignments immediately to show the section
      renderAssign();
      // after selection ready, load materials/assignments
      loadMaterials();
    });
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
    
    console.log('Rendering assignments, current assigned:', assigned);
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
      } else if (materials.length===0) {
        // assignments exist but materials not loaded yet → avoid placeholder
      }
    }
    
    var cnt=document.getElementById('assign-count'); if(cnt){ cnt.textContent=assigned.length+' '+@json(__('assigned')); }
    try { localStorage.setItem('qc_assign_update', JSON.stringify({ analyte_id: analyteId, change: 0, assigned: assigned.length, ts: Date.now() })); } catch(_){ }
    
    console.log('Assignments rendered successfully');
  }

  function loadMaterials(){
    var q = document.getElementById('q').value.trim();
    fetch(@json(route('admin.qc.materials.list'))+'?q='+encodeURIComponent(q)).then(r=>r.json()).then(function(res){
      materials = res.data||[]; renderList(); renderAssign(); var mc=document.getElementById('mat-count'); if(mc){ mc.textContent=materials.length; }
    });
    fetch(@json(route('admin.qc.materials.assigned_for_analyte'))+'?analyte_id='+analyteId).then(r=>r.json()).then(function(res){
      if(res.ok && res.assigned) {
        assigned = res.assigned.map(Number); 
        renderList(); 
        renderAssign();
      } else {
        console.error('Failed to load assignments:', res);
        assigned = [];
        renderAssign();
      }
    }).catch(function(e){
      console.error('Error loading assignments:', e);
      assigned = [];
      renderAssign();
    });
  }

  document.getElementById('q').addEventListener('input', function(){ loadMaterials(); });
  document.getElementById('analyte-switch').addEventListener('change', function(){
    analyteId = Number(this.value);
    var a=analytes.find(function(x){return Number(x.id)===analyteId});
    analyteName = a ? a.name : ('#'+analyteId);
    setAnalyteHeader();
    try{ var u=new URL(location.href); u.searchParams.set('analyte_id', analyteId); history.replaceState(null,'',u.toString()); }catch(_){ }
    loadMaterials();
  });
  document.getElementById('btn-new').onclick=function(){ document.getElementById('new-form').style.display='block'; };
  document.getElementById('btn-save-mat').onclick=function(){
    var payload={ name:document.getElementById('m_name').value.trim(), lot_number:document.getElementById('m_lot').value.trim(), level:document.getElementById('m_level').value, expiry_date:document.getElementById('m_exp').value, analyte_id: analyteId };
    fetch(@json(route('admin.qc.materials.store')),{ method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':@json(csrf_token())}, body:JSON.stringify(payload)}).then(function(r){ if(!r.ok) return r.text().then(t=>Promise.reject(t)); return r.json(); }).then(function(res){ lastAddedId = res.id||0; if(lastAddedId){ if(!assigned.includes(lastAddedId)) assigned.push(lastAddedId); try { localStorage.setItem('qc_assign_update', JSON.stringify({ analyte_id: analyteId, change: 1, ts: Date.now() })); } catch(_){ } } renderAssign(); loadMaterials(); document.getElementById('new-form').style.display='none'; }).catch(function(e){ alert('Save failed: '+e); });
  };
  document.getElementById('btn-save-assign').onclick=function(){
    fetch(@json(route('admin.qc.materials.assign_for_analyte')),{ method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':@json(csrf_token())}, body:JSON.stringify({ analyte_id:analyteId, control_ids:assigned })}).then(function(r){ if(!r.ok) return r.text().then(t=>Promise.reject(t)); return r.json(); }).then(function(){ alert('Saved'); }).catch(function(e){ alert('Save failed: '+e); });
  };

  function destroyMaterial(id){
    fetch(@json(url('admin/qc/materials'))+'/'+id, { method:'DELETE', headers:{ 'Accept':'application/json', 'X-CSRF-TOKEN':@json(csrf_token()) } }).then(function(r){ if(!r.ok) return r.text().then(t=>Promise.reject(t)); return r.json(); }).then(function(){
      // Remove from assigned if present, then reload lists
      var idx = assigned.indexOf(id); if(idx!==-1) assigned.splice(idx,1);
      try { localStorage.setItem('qc_assign_update', JSON.stringify({ analyte_id: analyteId, change: -1, ts: Date.now() })); } catch(_){ }
      loadMaterials();
    }).catch(function(e){ alert('Delete failed: '+e); });
  }

  function removeAssignment(materialId) {
    if (confirm('Are you sure you want to remove this material from the assignment?')) {
      var index = assigned.indexOf(materialId);
      if (index !== -1) {
        assigned.splice(index, 1);
        renderAssign();
        try { localStorage.setItem('qc_assign_update', JSON.stringify({ analyte_id: analyteId, change: -1, ts: Date.now() })); } catch(_){ }
      }
    }
  }

  // Ensure assignments section is always visible
  loadAnalyte();
  
  // Fallback: render assignments section even if nothing else loads
  setTimeout(function() {
    if (document.getElementById('assignments') && document.getElementById('assignments').children.length === 0) {
      renderAssign();
    }
  }, 1000);
})();
</script>
@endsection

