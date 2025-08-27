@extends('layouts.app')

@section('title')
{{ __('QC - Daily Entries & Charts') }}
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
  .chart-container { position: relative; height: 320px; margin-bottom: 20px; }
  .stats-panel { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
  .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; }
  .stat-item { text-align: center; padding: 8px; }
  .stat-value { font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 4px; }
  .stat-label { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
  .limit-toggle { background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 15px; margin-bottom: 20px; }
  .limit-toggle select { border: none; background: transparent; font-weight: 600; color: #475569; font-size: 14px; }
  .limit-toggle label { font-weight: 600; color: #475569; margin-bottom: 0; }
  .form-group label { font-weight: 600; color: #374151; margin-bottom: 8px; }
  .form-control { border-radius: 8px; border: 1px solid #d1d5da; padding: 10px 12px; }
  .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
  .btn { border-radius: 8px; font-weight: 600; padding: 10px 20px; }
  .btn-sm { padding: 8px 16px; font-size: 14px; }
  .badge { border-radius: 6px; font-weight: 600; padding: 6px 10px; }
  .badge-info { background: #dbeafe; color: #1e40af; }
  .charts-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0; padding: 24px; }
  .charts-header h3 { color: white; margin: 0; font-size: 1.25rem; font-weight: 700; }
  .charts-header .controls-section { display: flex; align-items: center; gap: 20px; }
  .charts-header .limit-toggle { background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.28); border-radius: 6px; padding: 8px 12px; min-width: 140px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
  .charts-header .limit-toggle label { color: rgba(255,255,255,0.98); font-weight: 600; margin-bottom: 0; margin-right: 8px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.2px; }
  .charts-header .limit-toggle select { color: white; background: transparent; border: none; font-weight: 600; font-size: 12px; min-width: 80px; cursor: pointer; }
  .charts-header .limit-toggle select:focus { outline: none; box-shadow: 0 0 0 3px rgba(255,255,255,0.4); }
  .charts-header .limit-toggle select option { background: #4f46e5; color: white; padding: 8px; }
  .charts-header .date-section { display: flex; align-items: center; gap: 14px; }
  .charts-header .date-section label { color: rgba(255,255,255,0.95); font-weight: 700; font-size: 13px; margin-bottom: 0; text-transform: uppercase; letter-spacing: 0.3px; }
  .charts-header .form-control { background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.28); color: white; border-radius: 8px; padding: 10px 14px; font-size: 14px; min-width: 150px; font-weight: 500; }
  .charts-header .form-control:focus { outline: none; box-shadow: 0 0 0 3px rgba(255,255,255,0.4); border-color: rgba(255,255,255,0.5); }
  .charts-header .form-control::placeholder { color: rgba(255,255,255,0.7); }
  .charts-header .btn { background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.15) 100%); border: 1px solid rgba(255,255,255,0.35); color: white; border-radius: 10px; padding: 12px 24px; font-weight: 700; font-size: 14px; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
  .charts-header .btn:hover { background: linear-gradient(135deg, rgba(255,255,255,0.35) 0%, rgba(255,255,255,0.25) 100%); border-color: rgba(255,255,255,0.45); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.2); }
  .charts-header .btn:active { transform: translateY(0); box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
</style>
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0 text-dark"><i class="fas fa-calendar-check"></i> {{ __('Daily QC Entry') }}</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('Admin Main')}}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.qc') }}">QC</a></li>
          <li class="breadcrumb-item active">{{ __('Daily Entries') }}</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="d-flex align-items-center justify-content-between mt-4">
    <button type="button" class="btn btn-back btn-sm btn-pill" onclick="if(history.length>1){history.back();}else{window.location.href='{{ route('admin.home') }}';}">
      <i class="fas fa-arrow-left"></i> {{ __('Back') }}
    </button>
  </div>
  <div class="mt-3 text-center hint">{{ __('View Levey-Jennings charts with real-time statistics and sigma zones.') }}</div>
</div>
@endsection

@section('content')
<!-- Enhanced Levey-Jennings Charts Table -->
<div class="card qc-card">
  <div class="charts-header">
    <div class="d-flex align-items-center justify-content-between">
      <h3 class="qc-title mb-0"><i class="fas fa-chart-line"></i> {{ __('Levey-Jennings Charts') }}</h3>
      <div class="controls-section">
        <div class="limit-toggle">
          <label class="mb-0"><i class="fas fa-eye"></i> {{ __('View Mode') }}</label>
          <select id="limit-mode" class="form-control-sm">
            <option value="original">{{ __('Original Limits') }}</option>
            <option value="cumulative">{{ __('Cumulative Limits') }}</option>
          </select>
        </div>
        <div class="date-section">
          <label for="from" class="mb-0"><i class="fas fa-calendar-alt"></i> {{ __('From') }}</label>
          <input type="date" id="from" class="form-control form-control-sm" placeholder="From Date"> 
        </div>
        <div class="date-section">
          <label for="to" class="mb-0"><i class="fas fa-calendar-alt"></i> {{ __('To') }}</label>
          <input type="date" id="to" class="form-control form-control-sm" placeholder="To Date">
        </div>
        <button class="btn btn-sm" id="btn-load"><i class="fas fa-sync-alt"></i> {{ __('Load Charts') }}</button>
      </div>
    </div>
  </div>
  <div class="card-body" id="charts">
    <!-- Charts will be dynamically generated here -->
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
  var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
  var controls = [];
  var referenceValues = {};
  var currentCharts = {};

  // In-memory storage and helpers exposed globally so chart handlers can access them
  if(!window._qc_temp_entries) window._qc_temp_entries = {};
  if(!window._qc_sampleData) window._qc_sampleData = {};

  window._qc_mergeEntries = function(sampleArr, tempArr){
    var map = {};
    (sampleArr||[]).forEach(function(e){ map[e.date] = {date: e.date, measured_value: e.measured_value}; });
    (tempArr||[]).forEach(function(te){ if(te && te.date){ if(te.measured_value === null){ delete map[te.date]; } else { map[te.date] = {date: te.date, measured_value: te.measured_value}; } }});
    var dates = Object.keys(map).sort();
    return dates.map(function(d){ return {date: d, measured_value: map[d].measured_value}; });
  };

  window._qc_setTempEntry = function(cid, date, value){
    if(!window._qc_temp_entries[cid]) window._qc_temp_entries[cid]=[];
    var arr = window._qc_temp_entries[cid];
    var idx = arr.findIndex(function(x){ return x.date === date; });
    if(idx >= 0){ arr[idx].measured_value = value; } else { arr.push({date: date, time: '00:00', measured_value: value}); }
  };

  function opt(v,t){ var o=document.createElement('option'); o.value=v; o.textContent=t; return o; }

  function loadOptions(){
    // Since we removed the analyte selector, we'll use the URL parameter
    if(analyteId) {
      // Load controls for the specific analyte
      loadControlsForAnalyte(analyteId);
    }
  }

  function loadControlsForAnalyte(aid){
    // This would typically load controls from the backend
    // For now, we'll create some sample controls
    controls = [
      {id: 1, name: 'Lyphocheck 1', level: 'Low', lot_number: '12345'},
      {id: 2, name: 'Lyphocheck 2', level: 'Normal', lot_number: '67890'},
      {id: 3, name: 'Lyphocheck 3', level: 'High', lot_number: '11111'}
    ];
    buildCharts();
    loadReferenceValues();
    // Automatically load data and render charts
    loadData();
  }

  function loadReferenceValues(){
    var aid = analyteId;
    if(!aid) return;
    
    // Load reference values for the selected analyte
    fetch(@json(route('admin.qc.reference.load')), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': @json(csrf_token())
      },
      body: JSON.stringify({ analyte_id: aid })
    }).then(r=>r.json()).then(function(res){
      referenceValues = res.data || {};
      updateCharts();
    }).catch(function(e){
      // If no reference values exist, continue without them
      console.log('No reference values found');
    });
  }

  function buildCharts(){
    var charts=document.getElementById('charts'); charts.innerHTML='';
    controls.forEach(function(c){
      var card=document.createElement('div'); card.className='mb-4';
      card.innerHTML=`
        <div class="stats-panel">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">${c.name} (${c.level})</h5>
            <span class="badge badge-info">${c.lot_number}</span>
          </div>
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-value" id="mean-${c.id}">-</div>
              <div class="stat-label">Mean</div>
            </div>
            <div class="stat-item">
              <div class="stat-value" id="sd-${c.id}">-</div>
              <div class="stat-label">SD</div>
            </div>
            <div class="stat-item">
              <div class="stat-value" id="cv-${c.id}">-</div>
              <div class="stat-label">CV %</div>
            </div>
            <div class="stat-item">
              <div class="stat-value" id="n-${c.id}">-</div>
              <div class="stat-label">N</div>
            </div>
          </div>
        </div>
        <div class="chart-container">
          <canvas id="chart-${c.id}"></canvas>
        </div>
        <div class="mt-3 d-flex gap-2 align-items-center">
          <input type="date" class="form-control form-control-sm" id="new-date-${c.id}" style="max-width:140px;" />
          <input type="time" class="form-control form-control-sm" id="new-time-${c.id}" style="max-width:120px;" />
          <input type="number" step="0.001" class="form-control form-control-sm" id="new-value-${c.id}" placeholder="Value" style="max-width:120px;" />
          <button class="btn btn-sm btn-primary" id="add-entry-${c.id}">Add</button>
          <span class="ml-2 hint" id="status-${c.id}"></span>
        </div>
      `;
      charts.appendChild(card);
    });
  }

  function loadData(){
    var from = document.getElementById('from').value; 
    var to = document.getElementById('to').value;
    var cids = controls.map(function(c){return c.id});
    
    if(!cids.length){ return; }
    
    // For demo purposes, create sample data (stored in-memory)
    var sampleData = {};
    controls.forEach(function(c){
      var dates = [];
      var values = [];
      var currentDate = new Date(from || '2024-01-01');
      var endDate = new Date(to || '2024-01-31');
      
      while(currentDate <= endDate) {
        dates.push(currentDate.toISOString().split('T')[0]);
        // Generate sample values around a mean with some variation
        var mean = 5.0 + (c.level === 'Low' ? -1 : c.level === 'High' ? 1 : 0);
        var value = mean + (Math.random() - 0.5) * 0.4;
        values.push(parseFloat(value.toFixed(3)));
        currentDate.setDate(currentDate.getDate() + 1);
      }
      
      sampleData[c.id] = dates.map(function(date, i){
        return {date: date, measured_value: values[i]};
      });
    });
    
    // Ensure we have a place to store temporary user-added entries
    if(!window._qc_temp_entries) window._qc_temp_entries = {};
    controls.forEach(function(c){ if(!window._qc_temp_entries[c.id]) window._qc_temp_entries[c.id]=[]; });

    // Save sample data globally so edit handlers can access original samples
    window._qc_sampleData = sampleData;

    // Helper: merge sample array and temp overrides (temp entries override by date; null value => deletion)
    function mergeEntries(sampleArr, tempArr){
      var map = {};
      (sampleArr||[]).forEach(function(e){ map[e.date] = {date: e.date, measured_value: e.measured_value}; });
      (tempArr||[]).forEach(function(te){ if(te && te.date){ if(te.measured_value === null){ delete map[te.date]; } else { map[te.date] = {date: te.date, measured_value: te.measured_value}; } }});
      var dates = Object.keys(map).sort();
      return dates.map(function(d){ return {date: d, measured_value: map[d].measured_value}; });
    }

    function setTempEntry(cid, date, value){
      if(!window._qc_temp_entries[cid]) window._qc_temp_entries[cid]=[];
      var arr = window._qc_temp_entries[cid];
      var idx = arr.findIndex(function(x){ return x.date === date; });
      if(idx >= 0){ arr[idx].measured_value = value; } else { arr.push({date: date, time: '00:00', measured_value: value}); }
    }

    // Render charts with sample data merged with temp entries
    controls.forEach(function(c){
      var merged = mergeEntries(sampleData[c.id] || [], window._qc_temp_entries[c.id] || []);
      renderChart(c.id, merged);
    });

    // Wire up add-entry buttons
    controls.forEach(function(c){
      var btn = document.getElementById('add-entry-'+c.id);
      if(btn) btn.onclick = function(){
        var d = document.getElementById('new-date-'+c.id).value;
        var t = document.getElementById('new-time-'+c.id).value || '00:00';
        var v = document.getElementById('new-value-'+c.id).value;
        if(!d || !v){ document.getElementById('status-'+c.id).textContent = 'Enter date and value'; return; }
        var val = parseFloat(parseFloat(v).toFixed(3));
        window._qc_setTempEntry(c.id, d, val);
        var merged = window._qc_mergeEntries(window._qc_sampleData[c.id] || [], window._qc_temp_entries[c.id] || []);
        renderChart(c.id, merged);
        document.getElementById('status-'+c.id).textContent = 'Added (unsaved)';
        setTimeout(function(){ document.getElementById('status-'+c.id).textContent = ''; }, 2000);
      };
    });
  }

  function calculateCumulativeStats(entries){
    if(!entries.length) return {mean:0, sd:0, cv:0, n:0};
    
    var values = entries.map(function(e){ return parseFloat(e.measured_value); });
    var n = values.length;
    var mean = values.reduce((s,x)=>s+x,0)/n;
    var variance = values.reduce((s,x)=>s+Math.pow(x-mean,2),0)/(n-1);
    var sd = Math.sqrt(variance);
    var cv = mean ? (sd/mean*100) : 0;
    
    return {mean:mean, sd:sd, cv:cv, n:n};
  }

  function renderChart(cid, entries){
    var cumulative = calculateCumulativeStats(entries);
    var limitMode = document.getElementById('limit-mode').value;
    
    // Update stats display
    document.getElementById('mean-'+cid).textContent = cumulative.mean.toFixed(3);
    document.getElementById('sd-'+cid).textContent = cumulative.sd.toFixed(3);
    document.getElementById('cv-'+cid).textContent = cumulative.cv.toFixed(1);
    document.getElementById('n-'+cid).textContent = cumulative.n;
    
    // Get reference values for this control
    var ref = referenceValues[cid] || {};
    var chartMean = limitMode === 'original' ? (ref.mean || cumulative.mean) : cumulative.mean;
    var chartSD = limitMode === 'original' ? (ref.sd || cumulative.sd) : cumulative.sd;
    
    // Calculate sigma levels
    var plus1sd = chartMean + chartSD;
    var plus2sd = chartMean + (2 * chartSD);
    var plus3sd = chartMean + (3 * chartSD);
    var minus1sd = chartMean - chartSD;
    var minus2sd = chartMean - (2 * chartSD);
    var minus3sd = chartMean - (3 * chartSD);
    
    var ctx = document.getElementById('chart-'+cid).getContext('2d');
    
    // Destroy existing chart if it exists
    if(currentCharts[cid]) {
      currentCharts[cid].destroy();
    }
    
    // Create new chart
    currentCharts[cid] = new Chart(ctx, {
      type: 'line',
      data: { 
        labels: entries.map(function(e){return e.date}), 
        datasets: [
          { 
            label:'Value', 
            data: entries.map(function(e){return parseFloat(e.measured_value)}), 
            borderColor:'#2563eb', 
            backgroundColor:'rgba(37,99,235,.2)', 
            pointRadius:4, 
            fill:false 
          },
          {
            label: 'Mean',
            data: entries.map(() => chartMean),
            borderColor: '#22c55e',
            borderWidth: 2,
            fill: false,
            pointRadius: 0
          },
          {
            label: '+1σ',
            data: entries.map(() => plus1sd),
            borderColor: '#f59e0b',
            borderWidth: 1,
            borderDash: [3, 3],
            fill: false,
            pointRadius: 0
          },
          {
            label: '+2σ',
            data: entries.map(() => plus2sd),
            borderColor: '#f59e0b',
            borderWidth: 1,
            borderDash: [3, 3],
            fill: false,
            pointRadius: 0
          },
          {
            label: '+3σ',
            data: entries.map(() => plus3sd),
            borderColor: '#ef4444',
            borderWidth: 1,
            borderDash: [6, 6],
            fill: false,
            pointRadius: 0
          },
          {
            label: '-1σ',
            data: entries.map(() => minus1sd),
            borderColor: '#f59e0b',
            borderWidth: 1,
            borderDash: [3, 3],
            fill: false,
            pointRadius: 0
          },
          {
            label: '-2σ',
            data: entries.map(() => minus2sd),
            borderColor: '#f59e0b',
            borderWidth: 1,
            borderDash: [3, 3],
            fill: false,
            pointRadius: 0
          },
          {
            label: '-3σ',
            data: entries.map(() => minus3sd),
            borderColor: '#ef4444',
            borderWidth: 1,
            borderDash: [6, 6],
            fill: false,
            pointRadius: 0
          }
        ] 
      },
      options: { 
        responsive: true,
        maintainAspectRatio: false,
        scales:{ 
          y:{ 
            beginAtZero: false,
            grid: {
              color: 'rgba(0,0,0,0.1)'
            }
          },
          x: {
            grid: {
              color: 'rgba(0,0,0,0.1)'
            }
          }
        },
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        }
      }
    });

    // Add click handler to points to allow edit/delete
    ctx.canvas.onclick = function(evt){
      var points = currentCharts[cid].getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
      if(!points || !points.length) return;
      var pt = points[0];
      var idx = pt.index;
      var dataDate = currentCharts[cid].data.labels[idx];
      var existingVal = currentCharts[cid].data.datasets[0].data[idx];
      // prompt for action
      var action = prompt('Edit value for ' + dataDate + ' (leave empty to delete). Current: ' + existingVal, existingVal);
      if(action === null) return; // cancelled
      if(action === ''){
        // mark deletion in temp entries
        window._qc_setTempEntry(cid, dataDate, null);
      } else {
        var nv = parseFloat(parseFloat(action).toFixed(3));
        if(isNaN(nv)){ alert('Invalid number'); return; }
        window._qc_setTempEntry(cid, dataDate, nv);
      }
      // re-merge and re-render
      var merged = window._qc_mergeEntries(window._qc_sampleData[cid] || [], window._qc_temp_entries[cid] || []);
      renderChart(cid, merged);
    };
  }

  function updateCharts(){
    // Re-render all charts with current limit mode
    controls.forEach(function(c){
      var chart = currentCharts[c.id];
      if(chart) {
        // Trigger chart update by changing data
        var data = chart.data.datasets[0].data;
        chart.data.datasets[0].data = [...data];
        chart.update();
      }
    });
  }

  // Event listeners
  document.getElementById('btn-load').onclick=loadData;
  
  document.getElementById('limit-mode').addEventListener('change', function(){
    updateCharts();
  });

  // Set default dates
  document.getElementById('from').value = new Date(Date.now() - 30*24*60*60*1000).toISOString().split('T')[0];
  document.getElementById('to').value = new Date().toISOString().split('T')[0];

  // Auto-load everything when page loads
  loadOptions();
})();
</script>
@endsection

