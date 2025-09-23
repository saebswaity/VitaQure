@extends('layouts.app')

@section('title', 'Daily QC Entry')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .westgard-violations {
    margin: 10px 0;
  }
  
  .violations-list {
    max-height: 200px;
    overflow-y: auto;
  }
  
  .violations-list .alert {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.25rem;
  }
  
  .chart-stats {
    display: flex;
    justify-content: space-around;
    background: #f8f9fa;
    padding: 10px;
    border-radius: 6px;
    margin: 10px 0;
  }
  
  .stat-item {
    text-align: center;
  }
  
  .stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .stat-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #495057;
    margin-top: 2px;
  }
  
  .chart-canvas-container {
    position: relative;
    height: 300px;
    margin: 15px 0;
  }
  
  .chart-actions {
    padding: 10px 0;
    border-top: 1px solid #e9ecef;
    margin-top: 10px;
  }
  
  /* Back button styles to match Define Analytes page */
  .btn-pill { border-radius:9999px; padding:6px 14px; font-weight:700; }
  .btn-back { border-color:#6b7280; color:#374151; }
  .btn-back:hover { background:#374151; color:#fff; }
</style>
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <i class="fas fa-calendar-check"></i> Daily QC Entry
        </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Admin Main</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.qc') }}">QC</a></li>
          <li class="breadcrumb-item active">Daily Entries</li>
        </ol>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn btn-back btn-sm btn-pill" onclick="window.location.href='{{ route('admin.qc.analytes.index') }}'">
                <i class="fas fa-arrow-left"></i> {{ __('Back') }}
            </button>
        </div>
    </div>

    <!-- Control Settings Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-cogs text-primary me-2"></i>
                        Control Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="date-input" class="form-label fw-semibold required">
                                <i class="fas fa-calendar text-success me-1"></i>
                                Date
                            </label>
                            <input type="date" id="date-input" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label for="time-input" class="form-label fw-semibold required">
                                <i class="fas fa-clock text-warning me-1"></i>
                                Time
                            </label>
                            <input type="time" id="time-input" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="control-select" class="form-label fw-semibold required">
                                <i class="fas fa-flask text-info me-1"></i>
                                Control
                            </label>
                            <select id="control-select" class="form-control" required>
                                <option value="">Select Control</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="operator-input" class="form-label fw-semibold required">
                                <i class="fas fa-user text-secondary me-1"></i>
                                Operator
                            </label>
                            <div class="operator-autocomplete-container">
                                <input type="text" id="operator-input" class="form-control" placeholder="Type operator name..." required autocomplete="off">
                                <div id="operator-suggestions" class="operator-suggestions-dropdown"></div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="qc-value-input" class="form-label fw-semibold required">
                                <i class="fas fa-flask text-primary me-1"></i>
                                QC Value
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.001" class="form-control" id="qc-value-input" placeholder="Enter QC value" required>
                                <button type="button" class="btn btn-success" id="add-qc-btn">
                                    <i class="fas fa-save me-1"></i>Save
                                </button>
                            </div>
                        </div>
                    </div>
                    
                        </div>
                        </div>
        </div>
    </div>

    <!-- Date Range Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-filter text-primary me-2"></i>
                        Date Range Filter
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="from-date" class="form-label fw-semibold">
                                <i class="fas fa-calendar text-success me-1"></i>
                                From Date
                            </label>
                            <input type="date" id="from-date" class="form-control" onchange="filterChartsByDate()">
                        </div>
                        <div class="col-md-4">
                            <label for="to-date" class="form-label fw-semibold">
                                <i class="fas fa-calendar text-success me-1"></i>
                                To Date
                            </label>
                            <input type="date" id="to-date" class="form-control" onchange="filterChartsByDate()">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-primary" onclick="loadAllData()">
                                <i class="fas fa-sync-alt me-1"></i>
                                Load All Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark">
                            <i class="fas fa-chart-area text-primary me-2"></i>
                            Quality Control Charts
                        </h5>
                        <div class="d-flex align-items-center">
                            <label for="charts-control-limits-source" class="form-label fw-semibold me-2 mb-0">
                                <i class="fas fa-chart-line text-info me-1"></i>
                                Control Limits Based On:
                            </label>
                            <select id="charts-control-limits-source" class="form-control form-control-sm" style="width: 200px;" onchange="updateChartsControlLimitsSource()">
                                <option value="cumulative">Cumulative Values (Lab)</option>
                                <option value="reference">Reference Values (Manufacturer)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="charts" class="p-4">
                        <!-- Charts will be dynamically generated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

<!-- Add Entry Modal -->
<div class="modal fade" id="addEntryModal" tabindex="-1" aria-labelledby="addEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addEntryModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add New QC Entry
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="modal-date" class="form-label fw-semibold required">Date</label>
                        <input type="date" id="modal-date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="modal-time" class="form-label fw-semibold required">Time</label>
                        <input type="time" id="modal-time" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="modal-analyte" class="form-label fw-semibold required">Analyte</label>
                        <select id="modal-analyte" class="form-select" required>
                            <option value="">Select Analyte</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="modal-operator" class="form-label fw-semibold required">Operator</label>
                        <div class="operator-autocomplete-container">
                            <input type="text" id="modal-operator" class="form-control" placeholder="Type operator name..." required autocomplete="off">
                            <div id="modal-operator-suggestions" class="operator-suggestions-dropdown"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="modal-comment" class="form-label fw-semibold">Comment</label>
                        <textarea id="modal-comment" class="form-control" rows="3" placeholder="Optional comment..."></textarea>
                    </div>
                </div>
                
                <div id="control-inputs" class="mt-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-flask text-info me-2"></i>
                        Control Values
                    </h6>
                    <!-- Dynamic control inputs will be generated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="addEntry()">
                    <i class="fas fa-plus me-1"></i>
                    Add Entry
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Data Point Edit/Delete Modal -->
<div class="modal fade" id="dataPointModal" tabindex="-1" aria-labelledby="dataPointModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content qc-modal">
            <div class="modal-header qc-modal-header">
                <h5 class="modal-title" id="dataPointModalLabel">
                    <i class="fas fa-edit me-2"></i>
                    Edit Data Point
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeDataPointModal()" aria-label="Close">
                    <i class="fas fa-times"></i>
    </button>
            </div>
            <div class="modal-body qc-modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="qc-info-section">
                            <h6 class="qc-section-title">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Current Information
                            </h6>
                            <div class="qc-info-item">
                                <i class="fas fa-flask text-primary me-2"></i>
                                <strong>Current Value:</strong> 
                                <span id="current-value-display" class="qc-value">0.000</span>
                            </div>
                            <div class="qc-info-item">
                                <i class="fas fa-calendar text-info me-2"></i>
                                <strong>Date:</strong> 
                                <span id="current-date-display" class="qc-date">-</span>
                            </div>
                            <div class="qc-info-item">
                                <i class="fas fa-clock text-success me-2"></i>
                                <strong>Time:</strong> 
                                <span id="current-time-display" class="qc-time">-</span>
                            </div>
                            <div class="qc-info-item">
                                <i class="fas fa-user text-secondary me-2"></i>
                                <strong>Operator:</strong> 
                                <span id="current-operator-display" class="qc-operator">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="qc-edit-section">
                            <h6 class="qc-section-title">
                                <i class="fas fa-edit text-primary me-2"></i>
                                Edit Value
                            </h6>
                            <label for="edit-value-input" class="qc-label">
                                <i class="fas fa-pencil-alt text-primary me-1"></i>
                                New Value
                            </label>
                            <input type="number" step="0.001" class="form-control qc-input" id="edit-value-input" placeholder="Enter new value" required>
                            <small class="qc-help-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Enter the corrected QC value (3 decimal places)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer qc-modal-footer">
                <button type="button" class="btn qc-btn qc-btn-cancel" onclick="closeDataPointModal()" id="cancel-edit-btn">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </button>
                <button type="button" class="btn qc-btn qc-btn-delete" id="delete-data-point-btn">
                    <i class="fas fa-trash me-1"></i>
                    Delete
                </button>
                <button type="button" class="btn qc-btn qc-btn-update" id="update-data-point-btn">
                    <i class="fas fa-check me-1"></i>
                    Update
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content qc-modal">
            <div class="modal-header qc-modal-header qc-modal-header-danger">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeDeleteConfirmModal()" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body qc-modal-body text-center">
                <div class="qc-delete-icon">
                    <i class="fas fa-trash"></i>
                </div>
                <h6 class="qc-delete-title">Are you sure you want to delete this data point?</h6>
                <p class="qc-delete-message">This action cannot be undone and will affect all statistical calculations.</p>
            </div>
            <div class="modal-footer qc-modal-footer">
                <button type="button" class="btn qc-btn qc-btn-cancel" onclick="closeDeleteConfirmModal()" id="cancel-delete-btn">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </button>
                <button type="button" class="btn qc-btn qc-btn-delete" id="confirm-delete-btn">
                    <i class="fas fa-trash me-1"></i>
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>


<style>
/* Custom Styles */

.card {
    border-radius: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 1rem 1rem 0 0 !important;
}

.form-label {
    color: #495057;
    font-weight: 600;
}

.form-control, .form-select {
    border-radius: 0.75rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
    border-radius: 0.75rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    border-color: #6c757d;
}

.chart-container {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid #e9ecef;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f8f9fa;
}

.chart-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.chart-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.75rem;
    border: 1px solid #e9ecef;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
}

.chart-canvas-container {
    position: relative;
    height: 400px;
    margin-bottom: 1.5rem;
}

.chart-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.input-group {
    border-radius: 0.75rem;
    overflow: hidden;
}

.input-group-text {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    color: #6c757d;
}

.required::after {
    content: ' *';
    color: #dc3545;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
}

.modal-content {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

.modal-header {
    border-radius: 1rem 1rem 0 0;
}

.floating-btn {
    transition: all 0.3s ease;
}

.floating-btn:hover {
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .chart-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .chart-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .floating-btn {
        bottom: 1rem;
        right: 1rem;
    }
}

/* Chart specific styles */
canvas {
    border-radius: 0.5rem;
    cursor: pointer;
}

/* Interactive QC Modal Styles */
.qc-modal {
    border: none;
    border-radius: 1.5rem;
    box-shadow: 0 2rem 4rem rgba(0, 0, 0, 0.25);
    font-family: 'Segoe UI', Arial, sans-serif;
    overflow: hidden;
}

.qc-modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
    padding: 1.5rem 2rem;
    position: relative;
}

.qc-modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    pointer-events: none;
}

.qc-modal-header .modal-title {
    font-weight: 600;
    font-size: 1.25rem;
    position: relative;
    z-index: 1;
}

.qc-modal-header .btn-close {
    position: relative;
    z-index: 1;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.qc-modal-header .btn-close:hover {
    opacity: 1;
}

.qc-modal-header-danger {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
}

.qc-modal-body {
    padding: 2rem;
    background: #ffffff;
    position: relative;
}

.qc-modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Info Section */
.qc-info-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #dee2e6;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
}

.qc-section-title {
    color: #495057;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.qc-info-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 0.5rem;
    transition: background-color 0.3s ease;
}

.qc-info-item:hover {
    background: rgba(255, 255, 255, 0.9);
}

.qc-info-item:last-child {
    margin-bottom: 0;
}

.qc-value {
    color: #495057;
    font-weight: 600;
    margin-left: 0.5rem;
    font-size: 1.1rem;
}

.qc-date, .qc-time, .qc-operator {
    color: #6c757d;
    margin-left: 0.5rem;
    font-weight: 500;
}

/* Operator Input Styling */
#operator-input, #modal-operator {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

#operator-input:focus, #modal-operator:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    background: #ffffff;
    transform: translateY(-1px);
}

#operator-input::placeholder, #modal-operator::placeholder {
    color: #6c757d;
    font-style: italic;
    opacity: 0.8;
}

/* Custom Autocomplete Container */
.operator-autocomplete-container {
    position: relative;
    display: inline-block;
    width: 100%;
}

/* Custom Autocomplete Dropdown */
.operator-suggestions-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.operator-suggestions-dropdown.show {
    display: block;
}

.operator-suggestion-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f8f9fa;
    transition: all 0.2s ease;
    font-weight: 500;
    color: #495057;
}

.operator-suggestion-item:hover {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    transform: translateX(2px);
}

.operator-suggestion-item:last-child {
    border-bottom: none;
}

.operator-suggestion-item.highlighted {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

/* Validation states for operator input */
#operator-input.is-valid, #modal-operator.is-valid {
    border-color: #28a745;
    background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%);
}

#operator-input.is-invalid, #modal-operator.is-invalid {
    border-color: #dc3545;
    background: linear-gradient(135deg, #fff8f8 0%, #ffffff 100%);
}

#operator-input.is-valid:focus, #modal-operator.is-valid:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

#operator-input.is-invalid:focus, #modal-operator.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Control Limits Source Dropdown Styling */
#control-limits-source {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

#control-limits-source:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    background: #ffffff;
    transform: translateY(-1px);
}

#control-limits-source:hover {
    border-color: #007bff;
    background: #ffffff;
}

/* Edit Section */
.qc-edit-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #dee2e6;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
    margin-bottom: 0;
}

.qc-label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
    font-size: 1rem;
}

.qc-input {
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    padding: 1rem 1.25rem;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    background: #ffffff;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
}

.qc-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15), 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    outline: none;
    transform: translateY(-1px);
}

.qc-help-text {
    display: block;
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.75rem;
    font-style: italic;
}

/* Buttons */
.qc-btn {
    border-radius: 1rem;
    font-weight: 600;
    padding: 0.875rem 1.5rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: none;
    min-width: 120px;
    position: relative;
    overflow: hidden;
}

.qc-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.qc-btn:hover::before {
    left: 100%;
}

.qc-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.2);
}

.qc-btn:active {
    transform: translateY(0);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
}

.qc-btn-cancel {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

.qc-btn-cancel:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
    color: white;
}

.qc-btn-update {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
}

.qc-btn-update:hover {
    background: linear-gradient(135deg, #45a049 0%, #3d8b40 100%);
    color: white;
}

.qc-btn-delete {
    background: linear-gradient(135deg, #f44336 0%, #da190b 100%);
    color: white;
}

.qc-btn-delete:hover {
    background: linear-gradient(135deg, #da190b 0%, #c62828 100%);
    color: white;
}

/* Delete Confirmation Styles */
.qc-delete-icon {
    font-size: 3rem;
    color: #f44336;
    margin-bottom: 1rem;
}

.qc-delete-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.qc-delete-message {
    color: #6c757d;
    margin-bottom: 0;
    font-size: 0.9rem;
}

/* Chart Interaction Hints */
.chart-container {
    position: relative;
}

.chart-container::after {
    content: 'Click on any data point to edit or delete';
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    font-family: 'Segoe UI', Arial, sans-serif;
}

.chart-container:hover::after {
    opacity: 1;
}

/* Responsive Design */
@media (max-width: 576px) {
    .qc-modal {
        margin: 1rem;
        max-width: none;
    }
    
    .qc-modal-header,
    .qc-modal-body,
    .qc-modal-footer {
        padding: 1rem;
    }
    
    .qc-btn {
        min-width: 80px;
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
    }
}

/* Loading States */
.qc-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.qc-btn:disabled:hover {
    transform: none;
    box-shadow: none;
}



/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 2rem;
    height: 2rem;
    margin: -1rem 0 0 -1rem;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Global variables
var controls = [];
var currentCharts = {};
var referenceValues = {};
var users = [];
var analytes = [];



// Initialize temporary storage
window._qc_temp_entries = {};
window._qc_sampleData = {};

// Initialize global variables to prevent undefined errors
if (typeof controls === 'undefined') {
  var controls = [];
}
if (typeof currentCharts === 'undefined') {
  var currentCharts = {};
}
if (typeof referenceValues === 'undefined') {
  var referenceValues = {};
}
if (typeof users === 'undefined') {
  var users = [];
}
if (typeof analytes === 'undefined') {
  var analytes = [];
}

// Utility function to merge entries
window._qc_mergeEntries = function(realEntries, tempEntries) {
  console.log('Merging entries - Real:', realEntries?.length || 0, 'Temp:', tempEntries?.length || 0);
  
  if (!tempEntries || tempEntries.length === 0) return realEntries || [];
  if (!realEntries || realEntries.length === 0) return tempEntries || [];
  
  var merged = [...realEntries];
  tempEntries.forEach(function(temp) {
    // Check if this temp entry already exists in real data
    var exists = merged.some(function(real) {
      return real.date === temp.date && real.time === temp.time && real.measured_value === temp.measured_value;
    });
    if (!exists) {
      console.log('Adding temp entry:', temp);
      merged.push(temp);
    } else {
      console.log('Skipping duplicate temp entry:', temp);
    }
  });
  
  console.log('Final merged entries count:', merged.length);
  return merged.sort(function(a, b) {
    return new Date(a.date + ' ' + a.time) - new Date(b.date + ' ' + b.time);
  });
};

// Load options (analytes, controls, users)
function loadOptions() {
  var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
  
  if (analyteId > 0) {
    // Load controls for the specific analyte
    loadControlsForAnalyte(analyteId);
  } else {
    console.error('No analyte ID provided');
  }
}

    // Load controls for specific analyte
    function loadControlsForAnalyte(aid) {
      console.log('Loading controls for analyte:', aid);
      
      // Load assigned control materials for this analyte from server
      fetch('/admin/qc/materials-assigned?analyte_id=' + encodeURIComponent(aid), {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
      }).then(function(r){ 
        console.log('Materials response status:', r.status);
        if (!r.ok) {
          throw new Error('HTTP ' + r.status + ': ' + r.statusText);
        }
        return r.json(); 
      }).then(function(res){
        console.log('Materials response:', res);
        if(res && res.ok){
          // res.materials is array of control materials, res.assigned is array of assigned control ids
          var materials = res.materials || [];
          var assigned = res.assigned || [];
          var assignedSet = {};
          for(var i=0;i<assigned.length;i++){ 
            // Convert to number in case it's a string
            var assignedId = parseInt(assigned[i]);
            assignedSet[assignedId] = true;
            assignedSet[assigned[i]] = true; // Also keep original in case it's a string
          }
          
          console.log('🔍 DEBUGGING CONTROL FILTERING:');
          console.log('All materials:', materials.length, materials);
          console.log('Assigned control IDs for this analyte:', assigned);
          console.log('Assigned set:', assignedSet);
          
          // Check if no controls are assigned to this analyte
          if (assigned.length === 0) {
            console.warn('⚠️ No control materials are assigned to this analyte!');
            console.log('This means the qc_control_analyte_assignments table is empty or the analyte has no assigned controls.');
            controls = [];
          } else {
          // only include materials that are assigned for this analyte
            var filteredMaterials = materials.filter(function(m){ 
              var isAssigned = assignedSet[m.id];
              console.log('Material', m.id, m.name, 'is assigned:', isAssigned);
              return isAssigned; 
            });
            
            controls = filteredMaterials.map(function(m){ 
              return { 
                id: m.id, 
                name: m.name, 
                material: m.material || m.name, 
                level: m.level || 'Normal', 
                lot_number: m.lot_number 
              }; 
            });
          }
          
          console.log('✅ Filtered controls (assigned to this analyte):', controls.length, 'out of', materials.length, 'total materials');
          console.log('Available controls:', controls);
        } else {
          controls = [];
          console.log('No materials found or response not ok');
        }
        
        // Populate control selector dropdown
        populateControlSelector();
        
        // Build charts immediately, then load reference values and data
        buildCharts();
        
        // Load reference values for these controls, then load data
        loadReferenceValues().then(function(){ 
          loadData(); 
        });
      }).catch(function(e){
        // Fallback to demo controls on error
        console.error('Failed to load assigned materials, using demo controls', e);
        controls = [
          {id: 1, name: 'Lyphocheck 1', material: 'Low Level', level: 'Low', lot_number: '12345'},
          {id: 2, name: 'Lyphocheck 2', material: 'Normal Level', level: 'Normal', lot_number: '67890'},
          {id: 3, name: 'Lyphocheck 3', material: 'High Level', level: 'High', lot_number: '11111'}
        ];
        
        // Populate control selector dropdown
        populateControlSelector();
        
        // Build charts immediately, then load reference values and data
        buildCharts();
        
        // Load reference values for these controls, then load data
        loadReferenceValues().then(function(){ 
          loadData(); 
        });
      });
    }


  // Populate control selector dropdowns (both add and delete)
  function populateControlSelector() {
    console.log('populateControlSelector called, controls:', controls);
    
    var controlSelect = document.getElementById('control-select');
    var deleteControlSelect = document.getElementById('delete-control-select');
    
    console.log('Found elements - controlSelect:', controlSelect, 'deleteControlSelect:', deleteControlSelect);
    
    if (controlSelect) {
      controlSelect.innerHTML = '<option value="">Select Control</option>';
      
      if (controls && controls.length > 0) {
        console.log('Populating add control selector with', controls.length, 'controls');
        controls.forEach(function(control) {
          var option = document.createElement('option');
          option.value = control.id;
          option.textContent = control.name + ' - ' + control.material;
          controlSelect.appendChild(option);
        });
      } else {
        console.warn('No controls available for add selector');
        // Add a helpful message when no controls are assigned
        var option = document.createElement('option');
        option.value = '';
        option.textContent = '⚠️ No controls assigned to this analyte';
        option.disabled = true;
        controlSelect.appendChild(option);
        
        // Add another option with instructions
        var instructionOption = document.createElement('option');
        instructionOption.value = '';
        instructionOption.textContent = 'Go to Control Materials to assign controls';
        instructionOption.disabled = true;
        controlSelect.appendChild(instructionOption);
      }
    } else {
      console.warn('Add control selector not found');
    }
    
    if (deleteControlSelect) {
      deleteControlSelect.innerHTML = '<option value="">Select Control</option>';
      
      if (controls && controls.length > 0) {
        console.log('Populating delete control selector with', controls.length, 'controls');
        controls.forEach(function(control) {
          var option = document.createElement('option');
          option.value = control.id;
          option.textContent = control.name + ' - ' + control.material;
          deleteControlSelect.appendChild(option);
        });
      } else {
        console.warn('No controls available for delete selector');
      }
    } else {
      console.warn('Delete control selector not found');
    }
  }

  // Populate modal dropdowns
  function populateModalDropdowns() {
    var analyteSelect = document.getElementById('modal-analyte');
    var operatorSelect = document.getElementById('modal-operator');
    
    if (analyteSelect) {
      analyteSelect.innerHTML = '<option value="">Select Analyte</option>';
      analytes.forEach(function(analyte) {
        var option = document.createElement('option');
        option.value = analyte.id;
        option.textContent = analyte.name;
        analyteSelect.appendChild(option);
      });
    }
    
    if (operatorSelect) {
      operatorSelect.innerHTML = '<option value="">Select Operator</option>';
      users.forEach(function(user) {
        var option = document.createElement('option');
        option.value = user.id;
        option.textContent = user.name;
        operatorSelect.appendChild(option);
      });
    }
  }

  // Add QC value from Control Settings section - Auto-save to database
  window.addQcValue = function() {
    console.log('addQcValue function called');
    
    // Prevent multiple simultaneous calls
    if (window._qcAddingValue) {
      console.log('addQcValue already in progress, ignoring duplicate call');
      return;
    }
    window._qcAddingValue = true;
    
    try {
      var value = document.getElementById('qc-value-input').value;
      var date = document.getElementById('date-input').value;
      var time = document.getElementById('time-input').value;
      var selectedControl = document.getElementById('control-select').value;
      var operator = document.getElementById('operator-input').value;
      
      console.log('Input values:', { value: value, date: date, time: time, control: selectedControl, operator: operator });
      
      // Validate all required fields
      if (!value || !date || !time || !selectedControl || !operator || operator.trim().length < 2) {
        showStatusMessage('Please fill in all required fields (Date, Time, Control, Operator name, and QC Value). Operator name must be at least 2 characters.', 'warning');
        return;
      }
      
      // Validate numeric value
      var numericValue = parseFloat(value);
      if (isNaN(numericValue)) {
        showStatusMessage('Please enter a valid numeric value', 'warning');
        return;
      }
      
      // Validate date format (HTML date input returns yyyy-mm-dd)
      if (!date || !/^\d{4}-\d{2}-\d{2}$/.test(date)) {
        showStatusMessage('Please select a valid date', 'warning');
        return;
      }
      
      // Validate time format
      if (!time || !/^\d{2}:\d{2}$/.test(time)) {
        showStatusMessage('Please select a valid time', 'warning');
        return;
      }
      
      var controlId = parseInt(selectedControl);
      var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
      
      if (!analyteId) {
        showStatusMessage('No analyte selected. Please navigate from the analytes page.', 'danger');
        return;
      }
      
      console.log('Using control ID:', controlId, 'analyte ID:', analyteId);
      
      var entry = {
        date: date,
        time: time,
        measured_value: numericValue,
        analyte_id: analyteId,
        operator: operator, // Use the operator entered by user
        comment: 'Added via Control Settings',
        control_id: controlId
      };
      
      console.log('Created entry:', entry);
      
      // Show loading state on button
      var addBtn = document.getElementById('add-qc-btn');
      var originalBtnContent = addBtn.innerHTML;
      addBtn.disabled = true;
      addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
      
      // Save directly to database - format expected by controller
      var payload = {
        date: entry.date,
        time: entry.time,
        operator: operator, // Use selected operator
        analyte_id: entry.analyte_id,
        items: [{
          control_id: entry.control_id,
          value: entry.measured_value
        }],
        comment: entry.comment
      };
      
      fetch('/admin/qc/entries/save', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify(payload)
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        return response.json();
      })
      .then(data => {
        if (data.ok) {
          // Clear inputs
          document.getElementById('qc-value-input').value = '';
          document.getElementById('operator-input').value = '';
          
          // Clear any temporary entries to prevent duplication
          window._qc_temp_entries = {};
          
          // Show success message with more details
          var controlName = '';
          if (controls && controls.length > 0) {
            var control = controls.find(function(c) { return c.id === controlId; });
            if (control) {
              controlName = control.name + ' - ' + control.material;
            }
          }
          
          showStatusMessage('✅ QC value ' + numericValue + ' saved successfully for ' + controlName + '!', 'success');
          
          // Instantly add the new entry to the chart without reloading
          console.log('Instantly updating chart with new QC value');
          
          
          // Add visual feedback that chart is updating
          var chartContainer = document.querySelector('#chart-' + controlId);
          if (chartContainer) {
            chartContainer.style.opacity = '0.7';
            chartContainer.style.transition = 'opacity 0.3s ease';
          }
          
          addEntryToChart(controlId, entry);
          
          // Restore chart visibility and show completion
          setTimeout(function() {
            if (chartContainer) {
              chartContainer.style.opacity = '1';
            }
            console.log('Chart update completed for control:', controlId);
          }, 300);
          
        } else {
          showStatusMessage('Failed to save QC value: ' + (data.error || 'Unknown error'), 'danger');
        }
      })
      .catch(function(error) {
        console.error('Error saving QC value:', error);
        showStatusMessage('Error saving QC value: ' + error.message, 'danger');
      })
      .finally(function() {
        // Reset button state
        addBtn.disabled = false;
        addBtn.innerHTML = originalBtnContent;
        // Reset the flag to allow future calls
        window._qcAddingValue = false;
      });
      
    } catch (error) {
      console.error('Error in addQcValue:', error);
      showStatusMessage('Error adding QC value: ' + error.message, 'danger');
      // Reset the flag to allow future calls
      window._qcAddingValue = false;
    }
  };

  // Add new entry to chart instantly without page reload
  function addEntryToChart(controlId, newEntry) {
    try {
      console.log('Adding entry to chart:', controlId, newEntry);
      
      // Ensure the data structure exists
      if (!window._qc_sampleData) {
        window._qc_sampleData = {};
      }
      
      // Get current chart data for this control
      var currentData = window._qc_sampleData[controlId] || [];
      
      // Ensure the new entry has the correct format
      var formattedEntry = {
        date: newEntry.date,
        time: newEntry.time || '00:00',
        measured_value: parseFloat(newEntry.measured_value),
        analyte_id: newEntry.analyte_id,
        operator: newEntry.operator || 'Unknown',
        comment: newEntry.comment || '',
        control_id: newEntry.control_id
      };
      
      // Add the new entry to the data
      currentData.push(formattedEntry);
      
      // Mark this entry as the most recently added
      formattedEntry.isMostRecent = true;
      
      // Remove the most recent flag from other entries
      currentData.forEach(function(entry) {
        if (entry !== formattedEntry) {
          entry.isMostRecent = false;
        }
      });
      
      // Update the stored data
      window._qc_sampleData[controlId] = currentData;
      
      // Sort entries by date and time
      currentData.sort(function(a, b) {
        var dateA = new Date(a.date + ' ' + (a.time || '00:00'));
        var dateB = new Date(b.date + ' ' + (b.time || '00:00'));
        return dateA - dateB;
      });
      
      // Re-render the chart with updated data
      renderChart(controlId, currentData);
      
      console.log('Chart updated instantly with new entry. Total entries:', currentData.length);
      console.log('New entry details:', formattedEntry);
      
    } catch (error) {
      console.error('Error adding entry to chart instantly:', error);
      // Fallback to reloading data if instant update fails
      console.log('Falling back to data reload...');
      loadData();
    }
  }
  
  // Remove entry from chart instantly without page reload
  function removeEntryFromChart(controlId, deletedEntry) {
    try {
      console.log('Removing entry from chart:', controlId, deletedEntry);
      
      // Get current chart data for this control
      var currentData = window._qc_sampleData[controlId] || [];
      
      // Remove the deleted entry from the data (match by date and value, ignore time)
      var updatedData = currentData.filter(function(entry) {
        return !(entry.date === deletedEntry.date && 
                Math.abs(parseFloat(entry.measured_value) - parseFloat(deletedEntry.measured_value)) < 0.001);
      });
      
      // Update the stored data
      window._qc_sampleData[controlId] = updatedData;
      
      // Re-render the chart with updated data
      renderChart(controlId, updatedData);
      
      console.log('Chart updated instantly after deletion');
      
    } catch (error) {
      console.error('Error removing entry from chart instantly:', error);
      // Fallback to reloading data if instant update fails
      loadData();
    }
  }
  
  
  // Add temporary entry to chart instantly for immediate visual feedback
  function addTempEntryToChart(controlId, tempEntry) {
    try {
      console.log('Adding temporary entry to chart:', controlId, tempEntry);
      
      // Get current real data and temporary entries
      var realData = window._qc_sampleData[controlId] || [];
      var tempData = window._qc_temp_entries[controlId] || [];
      
      // Merge and render immediately
      var mergedData = window._qc_mergeEntries(realData, tempData);
      renderChart(controlId, mergedData);
      
      console.log('Chart updated instantly with temporary entry');
      
    } catch (error) {
      console.error('Error adding temporary entry to chart instantly:', error);
    }
  }
  
  // Delete QC entry function
  function deleteQcEntry(entry, controlId, analyteId) {
    console.log('Deleting QC entry:', entry);
    console.log('Control ID:', controlId);
    console.log('Analyte ID:', analyteId);
    
    // Show loading state
    showStatusMessage('Deleting QC entry...', 'info');
    
    // Prepare delete request with proper data types
    var payload = {
      entry_id: entry.id ? parseInt(entry.id) : null,
      date: entry.date,
      time: entry.time || '',
      measured_value: parseFloat(entry.measured_value),
      control_id: parseInt(controlId),
      analyte_id: parseInt(analyteId)
    };
    
    console.log('Delete payload:', payload);
    
    fetch('/admin/qc/entries/delete', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(payload)
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('HTTP ' + response.status + ': ' + response.statusText);
      }
      return response.json();
    })
    .then(data => {
      if (data.ok) {
        showStatusMessage('QC entry deleted successfully!', 'success');
        
        // Instantly remove the entry from the chart without reloading
        console.log('Instantly updating chart after deletion');
        removeEntryFromChart(controlId, entry);
        
      } else {
        showStatusMessage('Failed to delete QC entry: ' + (data.error || 'Unknown error'), 'danger');
      }
    })
    .catch(function(error) {
      console.error('Error deleting QC entry:', error);
      showStatusMessage('Error deleting QC entry: ' + error.message, 'danger');
    });
  }

// Open add entry modal
function openAddEntryModal() {
  // Set current date and time
  var now = new Date();
  document.getElementById('modal-date').value = now.toISOString().split('T')[0];
  document.getElementById('modal-time').value = now.toTimeString().slice(0, 5);
  
  // Generate control inputs
  generateControlInputs();
  
  // Show modal
  var modal = new bootstrap.Modal(document.getElementById('addEntryModal'));
  modal.show();
}

// Generate control input fields in modal
function generateControlInputs() {
  var container = document.getElementById('control-inputs');
  if (!container) return;
  
  container.innerHTML = '<h6 class="fw-semibold mb-3"><i class="fas fa-flask text-info me-2"></i>Control Values</h6>';
  
  controls.forEach(function(control) {
    var controlDiv = document.createElement('div');
    controlDiv.className = 'mb-3';
    controlDiv.innerHTML = `
      <label class="form-label fw-semibold">${control.name} - ${control.material}</label>
      <div class="input-group">
        <input type="number" step="0.001" class="form-control" id="modal-value-${control.id}" placeholder="Enter value" required>
        <span class="input-group-text">${control.unit || ''}</span>
      </div>
    `;
    container.appendChild(controlDiv);
  });
}

// Add entry from modal
function addEntry() {
  var date = document.getElementById('modal-date').value;
  var time = document.getElementById('modal-time').value;
  var analyteId = document.getElementById('modal-analyte').value;
  var operatorId = document.getElementById('modal-operator').value;
  var comment = document.getElementById('modal-comment').value;
  
  if (!date || !time || !analyteId || !operatorId) {
    showStatusMessage('Please fill in all required fields', 'warning');
    return;
  }
  
  // Collect control values
  var controlValues = {};
  controls.forEach(function(control) {
    var value = document.getElementById('modal-value-' + control.id).value;
    if (value) {
      controlValues[control.id] = parseFloat(value);
    }
  });
  
  if (Object.keys(controlValues).length === 0) {
    showStatusMessage('Please enter at least one control value', 'warning');
    return;
  }
  
  // Add entries for each control
  Object.keys(controlValues).forEach(function(controlId) {
    var entry = {
      date: date,
      time: time,
      measured_value: controlValues[controlId],
      analyte_id: analyteId,
      operator_id: operatorId,
      comment: comment,
      control_id: controlId
    };
    
    if (!window._qc_temp_entries[controlId]) {
      window._qc_temp_entries[controlId] = [];
    }
    window._qc_temp_entries[controlId].push(entry);
    
    // Instantly update the chart for this control
    addTempEntryToChart(controlId, entry);
  });
  
  // Update UI
  updateTempCounts();
  showSaveButton();
  
  // Close modal
  var modal = bootstrap.Modal.getInstance(document.getElementById('addEntryModal'));
  modal.hide();
  
  // Show success message
  showStatusMessage('Entry added successfully!', 'success');
}



  // Refresh charts with current data and temporary entries
  function refreshCharts() {
    console.log('Refreshing charts with temporary entries...');
    
    if (!controls || controls.length === 0) {
      console.warn('No controls available for chart refresh');
      return;
    }
    
    controls.forEach(function(control) {
      try {
        var realData = window._qc_sampleData && window._qc_sampleData[control.id] ? window._qc_sampleData[control.id] : [];
        var tempData = window._qc_temp_entries && window._qc_temp_entries[control.id] ? window._qc_temp_entries[control.id] : [];
        
        // Merge real data with temporary entries for immediate display
        var mergedData = window._qc_mergeEntries(realData, tempData);
        
        console.log('Refreshing chart for control:', control.id, 'with', realData.length, 'real entries and', tempData.length, 'temp entries');
        
        // Render chart with merged data
        renderChart(control.id, mergedData);
        
      } catch (error) {
        console.error('Error refreshing chart for control:', control.id, error);
      }
    });
    
    console.log('Charts refresh completed with temporary entries');
  }

// Show status message
function showStatusMessage(message, type) {
  // Create toast notification
  var toast = document.createElement('div');
  toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
  toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
  
  // Map Bootstrap alert types to appropriate colors
  var alertClass = 'alert-info';
  if (type === 'success') alertClass = 'alert-success';
  else if (type === 'warning') alertClass = 'alert-warning';
  else if (type === 'danger') alertClass = 'alert-danger';
  
  toast.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
  
  toast.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  
  document.body.appendChild(toast);
  
  // Auto remove after 5 seconds
  setTimeout(function() {
    if (toast.parentNode) {
      toast.parentNode.removeChild(toast);
    }
  }, 5000);
}

// Build charts function (updated for new design)
function buildCharts(){
  var charts=document.getElementById('charts'); 
  if (!charts) {
    console.error('Charts container not found');
    return;
  }
  charts.innerHTML='';
  
  if (!controls || !Array.isArray(controls)) {
    console.warn('No controls to build charts for');
    return;
  }
  
  controls.forEach(function(c){
    var card=document.createElement('div'); 
    card.className='chart-container';
    card.innerHTML=`
      <div class="chart-header">
        <h5 class="chart-title">
          <i class="fas fa-flask text-primary me-2"></i>
          ${c.name} - ${c.material}
        </h5>

      </div>
      
      <div class="chart-stats">
        <div class="stat-item">
          <div class="stat-label">Mean</div>
          <div class="stat-value" id="mean-${c.id}">0.000</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">SD</div>
          <div class="stat-value" id="sd-${c.id}">0.000</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">CV%</div>
          <div class="stat-value" id="cv-${c.id}">0.0</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">N</div>
          <div class="stat-value" id="n-${c.id}">0</div>
        </div>
      </div>
      
      <div class="westgard-violations" id="violations-${c.id}" style="display: none;">
        <div class="alert alert-warning mb-2">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Westgard Rules Violations Detected:</strong>
        </div>
        <div class="violations-list" id="violations-list-${c.id}">
          <!-- Violations will be populated here -->
        </div>
      </div>
      
      <div class="chart-canvas-container">
        <canvas id="chart-${c.id}"></canvas>
      </div>
      
      <div class="chart-actions">
        <div class="d-flex align-items-center gap-3">
        <small class="text-muted">
          <i class="fas fa-info-circle me-1"></i>
            Use the Control Settings panel above to add new QC values
        </small>
                  <div class="d-flex align-items-center gap-2">
          <div class="d-flex align-items-center">
            <div style="width: 12px; height: 12px; background: #2563eb; border-radius: 50%; margin-right: 4px;"></div>
            <small class="text-muted">QC Values</small>
          </div>
          <div class="d-flex align-items-center">
            <div style="width: 12px; height: 12px; background: #10b981; border-radius: 50%; margin-right: 4px;"></div>
            <small class="text-muted">Newest</small>
          </div>
        </div>
        </div>
      </div>
    `;
    charts.appendChild(card);
  });
}

// Load data function
function loadData() {
    // Use date range inputs for filtering, not the single date input
    var from = document.getElementById('from-date').value;
    var to = document.getElementById('to-date').value;
    var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
    
    console.log('Loading data for analyte:', analyteId, 'date range:', from, 'to', to);
    
    // If no date range is set, load all data (use a wide range)
    if (!from || !to) {
      console.warn('Date range not set, loading all data');
      from = '2020-01-01'; // Start from a very early date
      to = '2030-12-31';   // End at a very late date
    }
    
    if (!analyteId || !controls || controls.length === 0) {
      console.warn('No analyte ID or controls available');
      return;
    }
    
    var controlIds = controls.map(function(c) { return c.id; });
    
    console.log('Making request to /admin/qc/entries/load with control IDs:', controlIds);
    
    fetch('/admin/qc/entries/load', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        analyte_id: analyteId,
        control_ids: controlIds,
        from: from,
        to: to
      })
    })
    .then(response => response.json())
    .then(data => {
      console.log('Load data response:', data);
      if (data.ok) {
        window._qc_sampleData = data.data || {};
        console.log('Loaded QC data for controls:', Object.keys(window._qc_sampleData));
        
        // Clear "most recent" flags for loaded data (not newly added)
        Object.keys(window._qc_sampleData).forEach(function(controlId) {
          var entries = window._qc_sampleData[controlId] || [];
          entries.forEach(function(entry) {
            entry.isMostRecent = false;
          });
        });
        
        // Render charts with real data (or empty if no data)
        controls.forEach(function(c){
          try {
            var data = window._qc_sampleData[c.id] || [];
            renderChart(c.id, data);
          } catch(chartError) {
            console.error('Error rendering chart for control:', c.id, chartError);
          }
        });
        
      } else {
        console.error('Failed to load data:', data.error || data.message);
        // Show empty charts if no data
        controls.forEach(function(c){
          try {
            renderChart(c.id, []);
          } catch(chartError) {
            console.error('Error rendering empty chart for control:', c.id, chartError);
          }
        });
      }
    }).catch(function(e){
      console.error('Failed to load data:', e);
      console.error('Error details:', e.message, e.stack);
      // Show empty charts if no data
      controls.forEach(function(c){
        try {
          renderChart(c.id, []);
        } catch(chartError) {
          console.error('Error rendering empty chart for control:', c.id, chartError);
        }
      });
    });
  }
  
  
  // Operator suggestions list
  var operatorSuggestions = [
    'Ahmed', 'Sara', 'Mohamed', 'Fatima', 'Ali', 'Aisha', 
    'Omar', 'Khadija', 'Hassan', 'Nour', 'Youssef', 'Layla',
    'Mahmoud', 'Zeinab', 'Tarek', 'Mona', 'Hassan', 'Dina'
  ];
  
  // Enhanced operator input validation
  function validateOperatorInput(inputElement) {
    var value = inputElement.value.trim();
    var isValid = value.length >= 2; // Minimum 2 characters
    
    if (isValid) {
      inputElement.classList.remove('is-invalid');
      inputElement.classList.add('is-valid');
    } else {
      inputElement.classList.remove('is-valid');
      inputElement.classList.add('is-invalid');
    }
    
    return isValid;
  }
  
  // Show operator suggestions
  function showOperatorSuggestions(inputElement, suggestionsContainer) {
    var value = inputElement.value.toLowerCase();
    if (value.length < 1) {
      suggestionsContainer.style.display = 'none';
      return;
    }
    
    var filteredSuggestions = operatorSuggestions.filter(function(name) {
      return name.toLowerCase().includes(value);
    });
    
    if (filteredSuggestions.length === 0) {
      suggestionsContainer.style.display = 'none';
      return;
    }
    
    suggestionsContainer.innerHTML = '';
    filteredSuggestions.forEach(function(suggestion) {
      var item = document.createElement('div');
      item.className = 'operator-suggestion-item';
      item.textContent = suggestion;
      item.addEventListener('click', function() {
        inputElement.value = suggestion;
        suggestionsContainer.style.display = 'none';
        validateOperatorInput(inputElement);
      });
      suggestionsContainer.appendChild(item);
    });
    
    suggestionsContainer.style.display = 'block';
  }
  
  // Initialize operator autocomplete
  function initializeOperatorAutocomplete(inputId, suggestionsId) {
    var input = document.getElementById(inputId);
    var suggestions = document.getElementById(suggestionsId);
    
    if (!input || !suggestions) return;
    
    input.addEventListener('input', function() {
      showOperatorSuggestions(this, suggestions);
      validateOperatorInput(this);
    });
    
    input.addEventListener('focus', function() {
      if (this.value.length >= 1) {
        showOperatorSuggestions(this, suggestions);
      }
    });
    
    input.addEventListener('blur', function() {
      // Delay hiding to allow click on suggestions
      setTimeout(function() {
        suggestions.style.display = 'none';
      }, 200);
      validateOperatorInput(input);
    });
    
    // Keyboard navigation
    input.addEventListener('keydown', function(e) {
      var items = suggestions.querySelectorAll('.operator-suggestion-item');
      var highlighted = suggestions.querySelector('.operator-suggestion-item.highlighted');
      var highlightedIndex = -1;
      
      if (highlighted) {
        highlightedIndex = Array.from(items).indexOf(highlighted);
      }
      
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (highlightedIndex < items.length - 1) {
          if (highlighted) highlighted.classList.remove('highlighted');
          items[highlightedIndex + 1].classList.add('highlighted');
        } else if (items.length > 0) {
          if (highlighted) highlighted.classList.remove('highlighted');
          items[0].classList.add('highlighted');
        }
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (highlightedIndex > 0) {
          if (highlighted) highlighted.classList.remove('highlighted');
          items[highlightedIndex - 1].classList.add('highlighted');
        } else if (items.length > 0) {
          if (highlighted) highlighted.classList.remove('highlighted');
          items[items.length - 1].classList.add('highlighted');
        }
      } else if (e.key === 'Enter') {
        e.preventDefault();
        if (highlighted) {
          input.value = highlighted.textContent;
          suggestions.style.display = 'none';
          validateOperatorInput(input);
        }
      } else if (e.key === 'Escape') {
        suggestions.style.display = 'none';
      }
    });
  }
  
  // Global variable to store reference values
  var referenceValues = {};
  
  // Update control limits source
  function updateControlLimitsSource() {
    var source = document.getElementById('control-limits-source').value;
    console.log('Control limits source changed to:', source);
    
    if (source === 'reference') {
      loadReferenceValues();
    } else {
      // Use cumulative values (lab data)
      recalculateAllCharts();
    }
  }
  
  // Load reference values from database
  function loadReferenceValues() {
    var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id') || '0');
    var controlIds = controls ? controls.map(function(c) { return c.id; }) : [];
    
    if (!analyteId || controlIds.length === 0) {
      console.warn('Cannot load reference values: missing analyte ID or controls');
      return Promise.resolve(); // Return resolved promise
    }
    
    console.log('Loading reference values for analyte:', analyteId, 'controls:', controlIds);
    
    return fetch('/admin/qc/reference/load', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        analyte_id: analyteId,
        control_ids: controlIds
      })
    })
    .then(response => response.json())
    .then(data => {
      console.log('Reference values response:', data);
      if (data.ok) {
        referenceValues = data.data || {};
        console.log('Loaded reference values:', referenceValues);
        recalculateAllCharts();
      } else {
        console.error('Failed to load reference values:', data.error);
        showStatusMessage('Failed to load reference values: ' + (data.error || 'Unknown error'), 'warning');
      }
    })
    .catch(function(e) {
      console.error('Error loading reference values:', e);
      showStatusMessage('Error loading reference values: ' + e.message, 'danger');
    });
  }
  
  // Recalculate all charts with current control limits source
  function recalculateAllCharts() {
    // Determine control limits source - use charts dropdown
    var chartsDropdown = document.getElementById('charts-control-limits-source');
    var source = 'cumulative'; // default
    
    if (chartsDropdown) {
      source = chartsDropdown.value;
    }
    
    console.log('Recalculating all charts with source:', source);
    
    if (!controls || controls.length === 0) {
      console.warn('No controls available for chart recalculation');
      return;
    }
    
    controls.forEach(function(control) {
      try {
        var entries = window._qc_sampleData[control.id] || [];
        renderChart(control.id, entries);
      } catch (error) {
        console.error('Error recalculating chart for control:', control.id, error);
      }
    });
  }
  
  // Function to refresh reference values and recalculate charts
  // This can be called when reference values are updated in the reference page
  function refreshReferenceValues() {
    var source = document.getElementById('control-limits-source');
    if (source && source.value === 'reference') {
      console.log('Refreshing reference values due to external update');
      loadReferenceValues();
    }
  }
  
  // Make refreshReferenceValues available globally so it can be called from other pages
  window.refreshReferenceValues = refreshReferenceValues;
  
  // Add real-time validation to operator inputs
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize autocomplete for both operator inputs
    initializeOperatorAutocomplete('operator-input', 'operator-suggestions');
    initializeOperatorAutocomplete('modal-operator', 'modal-operator-suggestions');
  });
  
  // Save entries function
  function saveEntries() {
    var entries = [];
    
    // Collect all temporary entries
    Object.keys(window._qc_temp_entries).forEach(function(controlId) {
      if (window._qc_temp_entries[controlId] && window._qc_temp_entries[controlId].length > 0) {
        window._qc_temp_entries[controlId].forEach(function(entry) {
          // Convert date format for database if needed
          var dbEntry = { ...entry };
          if (entry.dbDate) {
            dbEntry.date = entry.dbDate; // Use database format
          }
          entries.push(dbEntry);
        });
      }
    });
    
    if (entries.length === 0) {
      showStatusMessage('No entries to save', 'warning');
      return;
    }
    
    // Prepare payload
    var payload = {
      entries: entries
    };
    
    // Show loading state
    var saveBtn = document.getElementById('save-entries');
    if (saveBtn) {
      saveBtn.disabled = true;
      saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
    }
    
    fetch('/admin/qc/entries/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Clear temporary entries immediately
        window._qc_temp_entries = {};
        
        // Update UI
        updateTempCounts();
        hideSaveButton();
        
        // Show success message
        showStatusMessage('Entries saved successfully!', 'success');
        
        // Force update charts with cleared temp entries before reloading data
        controls.forEach(function(c){
          try {
            var realData = window._qc_sampleData[c.id] || [];
            renderChart(c.id, realData); // Render with only real data, no temp entries
          } catch(chartError) {
            console.error('Error updating chart for control:', c.id, chartError);
          }
        });
        
        // Reload data with current date range filter
        var fromDate = document.getElementById('from-date').value;
        var toDate = document.getElementById('to-date').value;
        if (fromDate && toDate) {
          loadDataWithDateRange(fromDate, toDate);
        } else {
          loadData();
        }
      } else {
        showStatusMessage('Failed to save entries: ' + (data.message || 'Unknown error'), 'danger');
      }
    })
    .catch(function(error) {
      console.error('Error saving entries:', error);
      showStatusMessage('Error saving entries: ' + error.message, 'danger');
    })
    .finally(function() {
      // Reset button state
      if (saveBtn) {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>Save to Database';
      }
    });
  }
  
  // Calculate cumulative statistics
  // Calculate Mean from user-input data
  function calculateMean(values) {
    if (!values || values.length === 0) return 0;
    var sum = values.reduce(function(a, b) { return a + b; }, 0);
    return sum / values.length;
  }
  
  // Calculate Sample Standard Deviation (using n-1) from user-input data
  function calculateSD(values) {
    if (!values || values.length < 2) return 0;
    var mean = calculateMean(values);
    var variance = values.reduce(function(sum, val) {
      return sum + Math.pow(val - mean, 2);
    }, 0) / (values.length - 1); // Sample SD: divide by n-1
    return Math.sqrt(variance);
  }
  
  // Calculate cumulative statistics from user-input data
  function calculateCumulativeStats(entries) {
    if (!entries || entries.length === 0) {
      return { mean: 0, sd: 0, cv: 0, n: 0 };
    }
    
    // Extract and validate measured values
    var values = entries.map(function(e) { 
      return parseFloat(e.measured_value); 
    }).filter(function(v) { 
      return !isNaN(v) && isFinite(v); 
    });
    
    if (values.length === 0) {
      return { mean: 0, sd: 0, cv: 0, n: 0 };
    }
    
    var n = values.length;
    var mean = calculateMean(values);
    var sd = calculateSD(values); // Sample SD (n-1)
    
    // Calculate CV% with proper validation
    var cv = 0;
    if (mean !== 0 && isFinite(mean)) {
      cv = (sd / Math.abs(mean)) * 100; // Use absolute value of mean for CV calculation
    }
    
    // Ensure all values are finite
    if (!isFinite(mean)) mean = 0;
    if (!isFinite(sd)) sd = 0;
    if (!isFinite(cv)) cv = 0;
    
    console.log('Statistical calculations:', {
      values: values.slice(0, 5), // Show first 5 values for debugging
      n: n,
      mean: mean,
      sd: sd,
      cv: cv
    });
    
    return {
      mean: mean,
      sd: sd,
      cv: cv,
      n: n
    };
  }

  // Enhanced Westgard Rules Detection System
  function detectWestgardRules(entries, mean, sd) {
    if (!entries || entries.length < 2) {
      return { violations: [], outOfControlPoints: [], pointStatus: [] };
    }
    
    // Extract and validate values first
    var values = entries.map(function(e) { 
      return parseFloat(e.measured_value); 
    }).filter(function(v) { 
      return !isNaN(v) && isFinite(v); 
    });
    
    // Validate input parameters
    if (!isFinite(mean) || !isFinite(sd) || sd < 0) {
      console.warn('Invalid mean or SD for Westgard Rules:', { mean: mean, sd: sd });
      return { violations: [], outOfControlPoints: [], pointStatus: [] };
    }
    
    // Handle case where SD is very small (e.g., 0.001 for identical values)
    if (sd < 0.01) {
      console.log('SD is very small for Westgard Rules, skipping complex rule detection');
      // For very small SD, only check basic 12s rule (values outside ±2SD)
      var violations = [];
      var outOfControlPoints = [];
      var pointStatus = [];
      
      values.forEach(function(value, index) {
        var zScore = Math.abs(value - mean) / sd;
        if (zScore >= 2) {
          violations.push({
            rule: '12s',
            type: 'rejection',
            message: 'Value ' + value.toFixed(3) + ' is outside ±2SD (Z-score: ' + zScore.toFixed(2) + ')',
            index: index,
            value: value
          });
          outOfControlPoints.push(index);
          pointStatus[index] = 'rejected';
        } else {
          pointStatus[index] = 'in';
        }
      });
      
      return { violations: violations, outOfControlPoints: outOfControlPoints, pointStatus: pointStatus };
    }
    
    if (values.length === 0) {
      return { violations: [], outOfControlPoints: [], pointStatus: [] };
    }
    
    var violations = [];
    var outOfControlPoints = [];
    var pointStatus = []; // Track status for each point: 'in', 'warning', 'rejected'
    
    console.log('Westgard Rules detection:', {
      mean: mean,
      sd: sd,
      values: values.slice(0, 5), // Show first 5 values for debugging
      count: values.length
    });
    
    // First pass: Categorize individual points based on SD thresholds
    values.forEach(function(value, index) {
      var zScore = Math.abs(value - mean) / sd;
      var status = 'in'; // Default to in control
      
      // Enhanced categorization based on SD thresholds
      if (zScore >= 2) {
        status = 'rejected';
        violations.push({
          rule: '12s',
          type: 'rejection',
          message: 'Value ' + value.toFixed(3) + ' is outside ±2SD (Z-score: ' + zScore.toFixed(2) + ')',
          index: index,
          value: value
        });
        outOfControlPoints.push(index);
      } else if (zScore >= 1) {
        status = 'warning';
        violations.push({
          rule: '12s',
          type: 'warning',
          message: 'Value ' + value.toFixed(3) + ' is outside ±1SD (Z-score: ' + zScore.toFixed(2) + ')',
          index: index,
          value: value
        });
        outOfControlPoints.push(index);
      }
      
      pointStatus[index] = status;
    });
    
    // Rule 22s: Two consecutive values beyond same ±2SD → Rejection
    for (var i = 0; i < values.length - 1; i++) {
      var val1 = values[i];
      var val2 = values[i + 1];
      var z1 = (val1 - mean) / sd;
      var z2 = (val2 - mean) / sd;
      
      if (Math.abs(z1) >= 2 && Math.abs(z2) >= 2 && 
          ((z1 > 0 && z2 > 0) || (z1 < 0 && z2 < 0))) {
        violations.push({
          rule: '22s',
          type: 'rejection',
          message: 'Two consecutive values (' + val1 + ', ' + val2 + ') beyond same ±2SD',
          index: i,
          value: val1
        });
        outOfControlPoints.push(i, i + 1);
      }
    }
    
    // Rule R4s: One value > +2SD and another < -2SD in neighboring points → Rejection
    for (var i = 0; i < values.length - 1; i++) {
      var val1 = values[i];
      var val2 = values[i + 1];
      var z1 = (val1 - mean) / sd;
      var z2 = (val2 - mean) / sd;
      
      if ((z1 > 2 && z2 < -2) || (z1 < -2 && z2 > 2)) {
        violations.push({
          rule: 'R4s',
          type: 'rejection',
          message: 'Random error: ' + val1 + ' and ' + val2 + ' on opposite sides of ±2SD',
          index: i,
          value: val1
        });
        outOfControlPoints.push(i, i + 1);
      }
    }
    
    // Rule 41s: Four consecutive values beyond same ±1SD → Rejection
    for (var i = 0; i < values.length - 3; i++) {
      var consecutive = true;
      var direction = null;
      
      for (var j = 0; j < 4; j++) {
        var z = (values[i + j] - mean) / sd;
        if (Math.abs(z) < 1) {
          consecutive = false;
          break;
        }
        var currentDirection = z > 0 ? 'positive' : 'negative';
        if (direction === null) {
          direction = currentDirection;
        } else if (direction !== currentDirection) {
          consecutive = false;
          break;
        }
      }
      
      if (consecutive) {
        violations.push({
          rule: '41s',
          type: 'rejection',
          message: 'Four consecutive values beyond same ±1SD',
          index: i,
          value: values[i]
        });
        for (var k = 0; k < 4; k++) {
          outOfControlPoints.push(i + k);
        }
      }
    }
    
    // Rule 10x: Ten consecutive values on one side of mean → Rejection
    for (var i = 0; i < values.length - 9; i++) {
      var allSameSide = true;
      var side = values[i] > mean ? 'above' : 'below';
      
      for (var j = 1; j < 10; j++) {
        var currentSide = values[i + j] > mean ? 'above' : 'below';
        if (currentSide !== side) {
          allSameSide = false;
          break;
        }
      }
      
      if (allSameSide) {
        violations.push({
          rule: '10x',
          type: 'rejection',
          message: 'Ten consecutive values on ' + side + ' side of mean',
          index: i,
          value: values[i]
        });
        for (var k = 0; k < 10; k++) {
          outOfControlPoints.push(i + k);
        }
      }
    }
    
    // Rule 2 of 3 2s: 2 out of 3 values beyond same ±2SD → Rejection
    for (var i = 0; i < values.length - 2; i++) {
      var beyond2SD = 0;
      var direction = null;
      
      for (var j = 0; j < 3; j++) {
        var z = (values[i + j] - mean) / sd;
        if (Math.abs(z) >= 2) {
          beyond2SD++;
          var currentDirection = z > 0 ? 'positive' : 'negative';
          if (direction === null) {
            direction = currentDirection;
          } else if (direction !== currentDirection) {
            beyond2SD = 0; // Different directions, reset
            break;
          }
        }
      }
      
      if (beyond2SD >= 2) {
        violations.push({
          rule: '2 of 3 2s',
          type: 'rejection',
          message: '2 out of 3 values beyond same ±2SD',
          index: i,
          value: values[i]
        });
        for (var k = 0; k < 3; k++) {
          var z = (values[i + k] - mean) / sd;
          if (Math.abs(z) >= 2) {
            outOfControlPoints.push(i + k);
          }
        }
      }
    }
    
    // Rule 7T: Seven consecutive values trending up or down → Rejection
    for (var i = 0; i < values.length - 6; i++) {
      var trend = null;
      var consecutive = true;
      
      for (var j = 0; j < 6; j++) {
        var currentTrend = values[i + j + 1] > values[i + j] ? 'up' : 'down';
        if (trend === null) {
          trend = currentTrend;
        } else if (trend !== currentTrend) {
          consecutive = false;
          break;
        }
      }
      
      if (consecutive) {
        violations.push({
          rule: '7T',
          type: 'rejection',
          message: 'Seven consecutive values trending ' + trend,
          index: i,
          value: values[i]
        });
        for (var k = 0; k < 7; k++) {
          outOfControlPoints.push(i + k);
        }
      }
    }
    
    // Remove duplicate indices
    outOfControlPoints = [...new Set(outOfControlPoints)];
    
    // Update point status based on complex rules
    violations.forEach(function(violation) {
      if (violation.type === 'rejection') {
        // Mark all affected points as rejected
        if (violation.rule === '22s' || violation.rule === 'R4s') {
          pointStatus[violation.index] = 'rejected';
          if (violation.index + 1 < pointStatus.length) {
            pointStatus[violation.index + 1] = 'rejected';
          }
        } else if (violation.rule === '41s') {
          for (var k = 0; k < 4; k++) {
            if (violation.index + k < pointStatus.length) {
              pointStatus[violation.index + k] = 'rejected';
            }
          }
        } else if (violation.rule === '10x') {
          for (var k = 0; k < 10; k++) {
            if (violation.index + k < pointStatus.length) {
              pointStatus[violation.index + k] = 'rejected';
            }
          }
        } else if (violation.rule === '7T') {
          for (var k = 0; k < 7; k++) {
            if (violation.index + k < pointStatus.length) {
              pointStatus[violation.index + k] = 'rejected';
            }
          }
        } else if (violation.rule === '2 of 3 2s') {
          for (var k = 0; k < 3; k++) {
            var z = (values[violation.index + k] - mean) / sd;
            if (Math.abs(z) >= 2 && violation.index + k < pointStatus.length) {
              pointStatus[violation.index + k] = 'rejected';
            }
          }
        } else {
          pointStatus[violation.index] = 'rejected';
        }
      }
    });
    
    return {
      violations: violations,
      outOfControlPoints: outOfControlPoints,
      pointStatus: pointStatus
    };
  }
  
  // Render chart function
  function renderChart(cid, entries) {
    console.log('renderChart called for control:', cid, 'with', entries ? entries.length : 0, 'entries');
    
    // Safety check for entries
    if (!entries || !Array.isArray(entries)) {
      console.warn('Invalid entries for chart:', cid, entries);
      entries = [];
    }
    
    // Log entry details for debugging
    if (entries.length > 0) {
      console.log('Sample entries for control', cid, ':', entries.slice(0, 3));
    }
    
    // Determine control limits source - use charts dropdown
    var chartsDropdown = document.getElementById('charts-control-limits-source');
    var source = 'cumulative'; // default
    
    if (chartsDropdown) {
      source = chartsDropdown.value;
    }
    var chartMean, chartSD, chartCV, chartN;
    
    if (source === 'reference' && referenceValues[cid]) {
      // Use reference values (manufacturer)
      var refValues = referenceValues[cid];
      chartMean = parseFloat(refValues.mean);
      chartSD = parseFloat(refValues.sd);
      chartCV = chartSD > 0 ? (chartSD / chartMean) * 100 : 0;
      chartN = entries.length;
      
      console.log('Using reference values for control', cid, ':', refValues);
    } else {
      // Use cumulative values (lab data)
      var cumulative = calculateCumulativeStats(entries);
      chartMean = cumulative.mean;
      chartSD = cumulative.sd;
      chartCV = cumulative.cv;
      chartN = cumulative.n;
      
      console.log('Using cumulative values for control', cid, ':', cumulative);
    }
    
    // Validate statistics regardless of source
    // Allow SD=0 for cases with 0 or 1 data points, or identical values
    if (!isFinite(chartMean) || !isFinite(chartSD) || chartSD < 0) {
      console.error('Invalid statistics:', {
        mean: chartMean,
        sd: chartSD,
        cv: chartCV,
        n: chartN,
        source: source
      });
      return;
    }
    
    // Handle edge cases where SD=0 (valid scenarios)
    if (chartSD === 0) {
      console.log('SD=0 detected for control', cid, '- this is valid for n≤1 or identical values');
      // Set a small default SD to prevent division by zero in chart calculations
      chartSD = 0.001;
      chartCV = 0; // CV is 0 when SD=0
    }
    
    // Handle case with no data points
    if (chartN === 0) {
      console.log('No data points for control', cid, '- showing empty chart with default limits');
      // Set default values for empty chart
      chartMean = 0;
      chartSD = 1; // Use 1 as default SD for empty charts
      chartCV = 0;
    }
    
    // Update stats display
    try {
      document.getElementById('mean-'+cid).textContent = chartMean.toFixed(3);
      document.getElementById('sd-'+cid).textContent = chartSD.toFixed(3);
      document.getElementById('cv-'+cid).textContent = chartCV.toFixed(1);
      document.getElementById('n-'+cid).textContent = chartN;
    } catch(e) {
      console.error('Error updating stats for chart:', cid, e);
    }
    
    // Update Westgard Rules violations display
    try {
      var violationsContainer = document.getElementById('violations-'+cid);
      var violationsList = document.getElementById('violations-list-'+cid);
      
      if (violations && violations.length > 0) {
        violationsContainer.style.display = 'block';
        violationsList.innerHTML = '';
        
        violations.forEach(function(violation) {
          var alertClass = violation.type === 'rejection' ? 'alert-danger' : 'alert-warning';
          var icon = violation.type === 'rejection' ? 'fa-times-circle' : 'fa-exclamation-triangle';
          
          var violationDiv = document.createElement('div');
          violationDiv.className = 'alert ' + alertClass + ' mb-1';
          violationDiv.innerHTML = '<i class="fas ' + icon + ' me-2"></i><strong>' + violation.rule + ':</strong> ' + violation.message;
          violationsList.appendChild(violationDiv);
        });
      } else {
        violationsContainer.style.display = 'none';
      }
    } catch(e) {
      console.error('Error updating violations display for chart:', cid, e);
    }
    
    // Use the determined chart values (either cumulative or reference)
    
    console.log('Chart calculations for control', cid, ':', {
      entries: entries.length,
      mean: chartMean.toFixed(3),
      sd: chartSD.toFixed(3),
      cv: chartCV.toFixed(1) + '%',
      n: chartN
    });
    
    // Use default limit mode (3 SD)
    var limitMode = '3sd'; // Default to 3 SD limits
    var shadedBands = false; // Shaded bands disabled
    
    // Calculate control limits based on corrected statistics
    // Order from bottom to top: -3SD, -2SD, -1SD, Mean, +1SD, +2SD, +3SD
    var minus3sd = chartMean - (3 * chartSD);
    var minus2sd = chartMean - (2 * chartSD);
    var minus1sd = chartMean - (1 * chartSD);
    var plus1sd = chartMean + (1 * chartSD);
    var plus2sd = chartMean + (2 * chartSD);
    var plus3sd = chartMean + (3 * chartSD);
    
    // Validate control limits
    if (!isFinite(minus3sd) || !isFinite(plus3sd)) {
      console.error('Invalid control limits calculated:', {
        minus3sd: minus3sd,
        minus2sd: minus2sd,
        minus1sd: minus1sd,
        mean: chartMean,
        plus1sd: plus1sd,
        plus2sd: plus2sd,
        plus3sd: plus3sd
      });
      return;
    }
    
    console.log('Control limits calculated from actual data:', {
      minus3sd: minus3sd.toFixed(3),
      minus2sd: minus2sd.toFixed(3),
      minus1sd: minus1sd.toFixed(3),
      mean: chartMean.toFixed(3),
      plus1sd: plus1sd.toFixed(3),
      plus2sd: plus2sd.toFixed(3),
      plus3sd: plus3sd.toFixed(3)
    });
    
    // Detect Westgard Rules violations using the determined mean and SD
    var westgardResults = detectWestgardRules(entries, chartMean, chartSD);
    var violations = westgardResults.violations;
    var outOfControlPoints = westgardResults.outOfControlPoints;
    var pointStatus = westgardResults.pointStatus;
    
    var canvas = document.getElementById('chart-'+cid);
    if (!canvas) {
      console.error('Canvas element not found for chart:', cid);
      // Try to rebuild charts if canvas is missing
      console.log('Attempting to rebuild charts...');
      buildCharts();
      canvas = document.getElementById('chart-'+cid);
      if (!canvas) {
        console.error('Still cannot find canvas after rebuild, aborting chart render');
        return;
      }
    }
    
    var ctx = canvas.getContext('2d');
    
    // Destroy existing chart if it exists
    if(currentCharts[cid]) {
      try {
        console.log('Destroying existing chart for control:', cid);
        currentCharts[cid].destroy();
        currentCharts[cid] = null;
        // Small delay to ensure canvas is properly released
        setTimeout(function() {
          createNewChart();
        }, 100);
        return; // Exit early, chart will be created in setTimeout
      } catch(e) {
        console.log('Chart destruction error:', e);
      currentCharts[cid] = null;
      }
    }
    
    // Function to create the new chart
    function createNewChart() {
    // Always create chart, even if no data
    if (!entries || entries.length === 0) {
      console.log('No data to chart for control:', cid, '- creating empty chart');
      entries = []; // Ensure entries is an empty array
    }

    // Create chart data structure
    var chartData = { 
      labels: entries.length > 0 ? entries.map(function(e){
        if (e && e.date) {
          // Convert yyyy-mm-dd to dd/mm format for display (date only, no time)
          if (e.date.includes('-')) {
            var dateParts = e.date.split('-');
            if (dateParts.length === 3) {
              return dateParts[2] + '/' + dateParts[1];
            }
          }
          return e.date;
        }
        return '';
      }).filter(function(d){return d !== ''}) : ['No QC Data Available'], 
      datasets: [
        // -3SD (red dotted) - bottom line
        {
          label: '-3SD (' + minus3sd.toFixed(3) + ')',
          data: entries.length > 0 ? entries.map(function(){ return minus3sd; }) : [minus3sd],
          borderColor: '#ef4444',
          backgroundColor: 'rgba(239,68,68,0.1)',
          borderDash: [2,2],
          fill: false,
          pointRadius: 0,
          borderWidth: 2
        },
        // -2SD (orange dotted)
        {
          label: '-2SD (' + minus2sd.toFixed(3) + ')',
          data: entries.length > 0 ? entries.map(function(){ return minus2sd; }) : [minus2sd],
          borderColor: '#f59e0b',
          backgroundColor: 'rgba(245,158,11,0.1)',
          borderDash: [3,3],
          fill: false,
          pointRadius: 0,
          borderWidth: 2
        },
        // -1SD (yellow dashed)
        {
          label: '-1SD (' + minus1sd.toFixed(3) + ')',
          data: entries.length > 0 ? entries.map(function(){ return minus1sd; }) : [minus1sd],
          borderColor: '#fbbf24',
          backgroundColor: 'rgba(251,191,36,0.1)',
          borderDash: [4,4],
          fill: false,
          pointRadius: 0,
          borderWidth: 2
        },
        // Mean (green solid) - center line
        {
          label: 'Mean (' + chartMean.toFixed(3) + ')',
          data: entries.length > 0 ? entries.map(function(){ return chartMean; }) : [chartMean],
          borderColor: '#10b981',
          backgroundColor: 'rgba(16,185,129,0.1)',
          borderDash: [], // Solid line
          fill: false,
          pointRadius: 0,
          borderWidth: 3
        },
        // +1SD (yellow dashed)
        {
          label: '+1SD (' + plus1sd.toFixed(3) + ')',
          data: entries.length > 0 ? entries.map(function(){ return plus1sd; }) : [plus1sd],
          borderColor: '#fbbf24',
          backgroundColor: 'rgba(251,191,36,0.1)',
          borderDash: [4,4],
          fill: false,
          pointRadius: 0,
          borderWidth: 2
        },
        // +2SD (orange dotted)
        {
          label: '+2SD (' + plus2sd.toFixed(3) + ')',
          data: entries.length > 0 ? entries.map(function(){ return plus2sd; }) : [plus2sd],
          borderColor: '#f59e0b',
          backgroundColor: 'rgba(245,158,11,0.1)',
          borderDash: [3,3],
          fill: false,
          pointRadius: 0,
          borderWidth: 2
        },
        // +3SD (red dotted) - top line
        {
          label: '+3SD (' + plus3sd.toFixed(3) + ')',
          data: entries.length > 0 ? entries.map(function(){ return plus3sd; }) : [plus3sd],
          borderColor: '#ef4444',
          backgroundColor: 'rgba(239,68,68,0.1)',
          borderDash: [2,2],
          fill: false,
          pointRadius: 0,
          borderWidth: 2
        },
        // QC Values (data points) - rendered on top
        { 
          label:'QC Values', 
          data: entries.length > 0 ? entries.map(function(e){
            if (!e || !e.measured_value) return null;
            return parseFloat(e.measured_value);
          }).filter(function(v){return v !== null}) : [], 
          borderColor:'#2563eb', 
          backgroundColor:'rgba(37,99,235,.2)', 
          fill:false,
          pointRadius: function(context) {
            // Handle empty charts or undefined data
            if (!context || !context.parsed || context.parsed.y === undefined || !entries || entries.length === 0) {
              return 6; // Default radius
            }
            
            // Highlight violation points and most recently added points
            var index = context.dataIndex;
            var value = context.parsed.y;
            var entry = entries[index];
            var isMostRecent = entry && entry.isMostRecent; // Check if this is the most recently added
            
            // Check if this point is flagged by Westgard Rules
            var isViolationPoint = outOfControlPoints && outOfControlPoints.includes(index);
            
            // Use pointStatus array for enhanced sizing
            var status = pointStatus && pointStatus[index] ? pointStatus[index] : 'in';
            
            // Size based on status: larger for violations, medium for most recent, normal for others
            if (status === 'rejected') return 9; // Large for rejection violations
            if (status === 'warning') return 8; // Medium-large for warning violations
            if (isMostRecent) return 7; // Medium for most recently added
            return 6; // Normal size for regular points
          },
          pointBackgroundColor: function(context) {
            // Handle empty charts or undefined data
            if (!context || !context.parsed || context.parsed.y === undefined || !entries || entries.length === 0) {
              return '#3b82f6'; // Default blue
            }
            
            // Color points based on Westgard Rules violations
            var index = context.dataIndex;
            var value = context.parsed.y;
            var entry = entries[index];
            var isMostRecent = entry && entry.isMostRecent; // Check if this is the most recently added
            
            // Check if this point is flagged by Westgard Rules
            var isViolationPoint = outOfControlPoints && outOfControlPoints.includes(index);
            
            // Use pointStatus array for enhanced color coding
            var status = pointStatus && pointStatus[index] ? pointStatus[index] : 'in';
            
            // Enhanced color coding based on Westgard Rules:
            // Blue: Within ±1SD → In control
            // Yellow: Between ±1SD and ±2SD → Warning (12s)
            // Red: Outside ±2SD → Rejection (22s, 13s, R4s, etc.)
            
            if (status === 'rejected') {
              return '#dc2626'; // Red for rejection (outside ±2SD)
            }
            if (status === 'warning') {
              return '#eab308'; // Yellow for warning (between ±1SD and ±2SD)
            }
            if (isMostRecent) {
              return '#10b981'; // Green for most recently added
            }
            return '#3b82f6'; // Blue for normal points (within ±1SD)
          },
          pointBorderColor: function(context) {
            // Handle empty charts or undefined data
            if (!context || !context.parsed || context.parsed.y === undefined || !entries || entries.length === 0) {
              return '#ffffff'; // Default white border
            }
            
            var index = context.dataIndex;
            var entry = entries[index];
            var isMostRecent = entry && entry.isMostRecent;
            return isMostRecent ? '#059669' : '#ffffff'; // Darker green border for most recently added
          },
          pointBorderWidth: function(context) {
            // Handle empty charts or undefined data
            if (!context || !context.parsed || context.parsed.y === undefined || !entries || entries.length === 0) {
              return 2; // Default border width
            }
            
            var index = context.dataIndex;
            var entry = entries[index];
            var isMostRecent = entry && entry.isMostRecent;
            return isMostRecent ? 3 : 2; // Thicker border for most recently added
          },
          borderWidth: 2
        }
      ]
    };
    
    // Add 4 SD lines if using 4 SD mode
    if (limitMode === '4sd') {
      chartData.datasets.push(
        {
          label: '+4 SD',
          data: entries.length > 0 ? entries.map(function(){ return plus4sd; }) : [plus4sd],
          borderColor: '#7c2d12',
          backgroundColor: 'rgba(124,45,18,0.2)',
          borderDash: [1,1],
          fill: false,
          pointRadius: 0
        },
        {
          label: '-4 SD',
          data: entries.length > 0 ? entries.map(function(){ return minus4sd; }) : [minus4sd],
          borderColor: '#7c2d12',
          backgroundColor: 'rgba(124,45,18,0.2)',
          borderDash: [1,1],
          fill: false,
          pointRadius: 0
        }
      );
    }
    
    // Add shaded background bands if enabled
    if (shadedBands) {
      // ±1SD band (light yellow)
      chartData.datasets.push({
        label: '±1SD Band',
        data: entries.length > 0 ? entries.map(function(){ return plus1sd; }) : [plus1sd],
        borderColor: 'transparent',
        backgroundColor: 'rgba(251,191,36,0.1)',
        fill: '+1',
        pointRadius: 0,
        borderWidth: 0
      });
      
      // ±2SD band (light orange)
      chartData.datasets.push({
        label: '±2SD Band',
        data: entries.length > 0 ? entries.map(function(){ return plus2sd; }) : [plus2sd],
        borderColor: 'transparent',
        backgroundColor: 'rgba(245,158,11,0.1)',
        fill: '+1',
        pointRadius: 0,
        borderWidth: 0
      });
      
      // ±3SD band (light red)
      chartData.datasets.push({
        label: '±3SD Band',
        data: entries.length > 0 ? entries.map(function(){ return plus3sd; }) : [plus3sd],
        borderColor: 'transparent',
        backgroundColor: 'rgba(239,68,68,0.1)',
        fill: '+1',
        pointRadius: 0,
        borderWidth: 0
      });
    }
    
    // Create new chart
    currentCharts[cid] = new Chart(ctx, {
      type: 'line',
      data: chartData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              usePointStyle: true,
              padding: 20
            }
          },
          tooltip: {
            mode: 'index',
            intersect: false,
            filter: function(tooltipItem) {
              // Only show tooltip for QC Values dataset (dataset index 7)
              return tooltipItem.datasetIndex === 7;
            },
            callbacks: {
              title: function(context) {
                var index = context[0].dataIndex;
                if (index >= 0 && index < entries.length && entries[index]) {
                  var date = entries[index].date;
                  var time = entries[index].time || '';
                  
                  // Convert yyyy-mm-dd to dd/mm format for display
                  if (date && date.includes('-')) {
                    var dateParts = date.split('-');
                    if (dateParts.length === 3) {
                      return dateParts[2] + '/' + dateParts[1] + ' ' + time;
                    }
                  }
                  return date + ' ' + time;
                }
                return '';
              },
              label: function(context) {
                var value = context.parsed.y;
                return 'QC Value: ' + value.toFixed(3);
              },
              afterLabel: function(context) {
                var datasetIndex = context.datasetIndex;
                var value = context.parsed.y;
                var index = context.dataIndex;
                
                // Add out-of-control rule information
                if (datasetIndex === 7) { // QC Values dataset
                  var triggeredRules = [];
                  
                  // Find specific rules triggered for this point
                  if (violations && violations.length > 0) {
                    violations.forEach(function(violation) {
                      if (violation.index === index) {
                        triggeredRules.push(violation.rule + ' Rule');
                      }
                      // Check for multi-point rules that affect this point
                      else if (violation.rule === '22s' || violation.rule === 'R4s') {
                        if (violation.index + 1 === index) {
                          triggeredRules.push(violation.rule + ' Rule');
                        }
                      } else if (violation.rule === '41s') {
                        for (var k = 0; k < 4; k++) {
                          if (violation.index + k === index) {
                            triggeredRules.push(violation.rule + ' Rule');
                            break;
                          }
                        }
                      } else if (violation.rule === '10x') {
                        for (var k = 0; k < 10; k++) {
                          if (violation.index + k === index) {
                            triggeredRules.push(violation.rule + ' Rule');
                            break;
                          }
                        }
                      } else if (violation.rule === '7T') {
                        for (var k = 0; k < 7; k++) {
                          if (violation.index + k === index) {
                            triggeredRules.push(violation.rule + ' Rule');
                            break;
                          }
                        }
                      } else if (violation.rule === '2 of 3 2s') {
                        for (var k = 0; k < 3; k++) {
                          if (violation.index + k === index) {
                            triggeredRules.push(violation.rule + ' Rule');
                            break;
                          }
                        }
                      }
                    });
                  }
                  
                  if (triggeredRules.length > 0) {
                    return '⚠️ ' + triggeredRules.join(', ');
                  }
                }
                
                return '';
              }
            }
          }
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Date & Time'
            }
          },
          y: {
            display: true,
            title: {
              display: true,
              text: 'Value'
            }
          }
        },
        interaction: {
          mode: 'nearest',
          axis: 'x',
          intersect: false
        },
        // Click handler for interactive data point editing/deleting
        onClick: function(event, elements) {
          console.log('Chart clicked! Elements:', elements);
          
          if (elements.length > 0) {
            // Find the QC Values dataset index
            var qcValuesDatasetIndex = -1;
            for (var i = 0; i < chartData.datasets.length; i++) {
              if (chartData.datasets[i].label === 'QC Values') {
                qcValuesDatasetIndex = i;
                break;
              }
            }
            
            console.log('QC Values dataset index:', qcValuesDatasetIndex);
            
            // Look through all clicked elements to find one on QC Values dataset
            var qcElement = null;
            for (var i = 0; i < elements.length; i++) {
              var element = elements[i];
              console.log('Checking element:', element.datasetIndex, 'label:', chartData.datasets[element.datasetIndex].label);
              
              if (element.datasetIndex === qcValuesDatasetIndex) {
                qcElement = element;
                console.log('Found QC Values element at index:', element.index);
                break;
              }
            }
            
            // If we found a QC Values element, show the popup
            if (qcElement && qcElement.index >= 0 && qcElement.index < entries.length) {
              var clickedEntry = entries[qcElement.index];
              console.log('Calling showDataPointPopup with:', clickedEntry, qcElement.index, cid);
              showDataPointPopup(clickedEntry, qcElement.index, cid);
            } else {
              console.log('No QC Values element found in click');
              console.log('Available elements:', elements.map(e => ({
                datasetIndex: e.datasetIndex,
                label: chartData.datasets[e.datasetIndex].label,
                index: e.index
              })));
            }
          } else {
            console.log('No elements found in click event');
          }
        }
      }
    });
    
    } // End of createNewChart function
    
    // Call createNewChart to create the chart
    createNewChart();
  } // End of renderChart function
  
  // Update charts function
  function updateCharts(){
    try {
    // Recompute mean/sigma lines for all existing charts using latest referenceValues and limit mode
      if (!controls || !Array.isArray(controls)) {
        console.warn('No controls available for chart update');
        return;
      }
      
    controls.forEach(function(c){
        try {
      var chart = currentCharts[c.id];
          if(!chart || !chart.data || !chart.data.labels) {
            return;
          }
      
      var labels = chart.data.labels || [];
      var entries = window._qc_mergeEntries(window._qc_sampleData[c.id] || [], window._qc_temp_entries[c.id] || []);
      
      if(entries.length > 0) {
        renderChart(c.id, entries);
          }
        } catch (error) {
          console.error('Error updating chart for control:', c.id, error);
      }
    });
    } catch (error) {
      console.error('Error in updateCharts function:', error);
    }
  }
  
  // Date and time initialization moved to the main DOMContentLoaded event listener below
  
  // Initialize page
  (function(){
    var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
    if(analyteId>0){
      loadOptions();
    }else{
      console.error('No analyte ID provided');
    }
  })();
  

  
  // Add event listeners
  document.addEventListener('DOMContentLoaded', function() {
    try {
    // Set current date
    var today = new Date();
    var dateInput = document.getElementById('date-input');
    if (dateInput) {
      dateInput.value = today.toISOString().split('T')[0];
    }
    
    // Set current time
    var timeInput = document.getElementById('time-input');
    if (timeInput) {
      timeInput.value = today.toTimeString().slice(0, 5);
    }
    
    // Set current date for delete input
    var deleteDateInput = document.getElementById('delete-date-input');
    if (deleteDateInput) {
      deleteDateInput.value = today.toISOString().split('T')[0];
    }
    
    // Set date range filter defaults (last 30 days)
    var fromDate = new Date();
    fromDate.setDate(fromDate.getDate() - 30);
    var fromDateFormatted = fromDate.toISOString().split('T')[0];
    
    var fromDateInput = document.getElementById('from-date');
    var toDateInput = document.getElementById('to-date');
    if (fromDateInput) fromDateInput.value = fromDateFormatted;
    if (toDateInput) toDateInput.value = today.toISOString().split('T')[0];
    
    // Initialize control limits dropdown
    var chartsDropdown = document.getElementById('charts-control-limits-source');
    
    if (chartsDropdown) {
      // Set dropdown to default value
      var defaultValue = 'cumulative';
      chartsDropdown.value = defaultValue;
      console.log('Initialized control limits dropdown to:', defaultValue);
    }
    
    // Limit mode removed - using default 3 SD limits
    
    // Test the Add button functionality
    var addBtn = document.getElementById('add-qc-btn');
    if (addBtn) {
      console.log('Add button found:', addBtn);
      
      // Remove any existing event listeners by cloning the button
      var newAddBtn = addBtn.cloneNode(true);
      addBtn.parentNode.replaceChild(newAddBtn, addBtn);
      
      // Add the event listener to the new button
      newAddBtn.addEventListener('click', function(e) {
        console.log('Add button clicked via event listener');
        if (typeof window.addQcValue === 'function') {
          window.addQcValue();
        } else {
          console.error('addQcValue function not available');
        }
      });
    } else {
      console.error('Add button not found!');
    }
    
    // Test if function exists
    if (typeof window.addQcValue === 'function') {
      console.log('addQcValue function is defined in window object');
    } else {
      console.error('addQcValue function is NOT defined in window object!');
    }
    
    
    } catch (error) {
      console.error('Error in DOMContentLoaded event listener:', error);
    }
  });
  
  // Date filtering functions
  function filterChartsByDate() {
    var fromDate = document.getElementById('from-date').value;
    var toDate = document.getElementById('to-date').value;
    
    if (fromDate && toDate) {
      console.log('Filtering charts from', fromDate, 'to', toDate);
      loadDataWithDateRange(fromDate, toDate);
    }
  }
  
  function loadAllData() {
    console.log('Loading all data');
    loadData();
  }
  
  function loadDataWithDateRange(fromDate, toDate) {
    var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
    
    if (!analyteId || !controls || controls.length === 0) {
      console.warn('No analyte ID or controls available');
      return;
    }
    
    var controlIds = controls.map(function(c) { return c.id; });
    
    console.log('Loading data with date range:', fromDate, 'to', toDate);
    
    fetch('/admin/qc/entries/load', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        analyte_id: analyteId,
        control_ids: controlIds,
        from: fromDate,
        to: toDate
      })
    })
    .then(response => response.json())
    .then(data => {
      console.log('Load data response:', data);
      if (data.ok) {
        window._qc_sampleData = data.data || {};
        
        // Render charts with filtered data merged with temp entries
        controls.forEach(function(c){
          try {
            var merged = window._qc_mergeEntries(window._qc_sampleData[c.id] || [], window._qc_temp_entries[c.id] || []);
            renderChart(c.id, merged);
          } catch(chartError) {
            console.error('Error rendering chart for control:', c.id, chartError);
          }
        });
        

      } else {
        console.error('Failed to load data:', data.error || data.message);
      }
    }).catch(function(e){
      console.error('Failed to load data:', e);
    });
  }
  

// Global variables for data point editing
var currentEditingEntry = null;
var currentEditingIndex = null;
var currentEditingControlId = null;


// Show data point popup when clicking on a chart point
function showDataPointPopup(entry, index, controlId) {
  console.log('showDataPointPopup called with:', entry, index, controlId);
  
  // Store current editing context
  currentEditingEntry = entry;
  currentEditingIndex = index;
  currentEditingControlId = controlId;
  
  console.log('Current editing context set:', {
    entry: currentEditingEntry,
    index: currentEditingIndex,
    controlId: currentEditingControlId
  });
  
  // Update modal content
  var valueDisplay = document.getElementById('current-value-display');
  var dateDisplay = document.getElementById('current-date-display');
  var timeDisplay = document.getElementById('current-time-display');
  var operatorDisplay = document.getElementById('current-operator-display');
  var inputField = document.getElementById('edit-value-input');
  
  console.log('Modal elements found:', {
    valueDisplay: valueDisplay,
    dateDisplay: dateDisplay,
    timeDisplay: timeDisplay,
    operatorDisplay: operatorDisplay,
    inputField: inputField
  });
  
  if (valueDisplay) {
    valueDisplay.textContent = parseFloat(entry.measured_value).toFixed(3);
  }
  if (dateDisplay) {
    dateDisplay.textContent = formatDateForDisplay(entry.date);
  }
  if (timeDisplay) {
    timeDisplay.textContent = entry.time || '-';
  }
  if (operatorDisplay) {
    operatorDisplay.textContent = entry.operator || '-';
  }
  if (inputField) {
    inputField.value = entry.measured_value;
  }
  
  // Show the modal
  var modalElement = document.getElementById('dataPointModal');
  console.log('Modal element found:', modalElement);
  
  if (modalElement) {
    // Try jQuery first since it's working better
    if (typeof $ !== 'undefined') {
      $(modalElement).modal('show');
      console.log('Modal show() called via jQuery');
    } else {
      var modal = new bootstrap.Modal(modalElement);
      console.log('Bootstrap modal created:', modal);
      modal.show();
      console.log('Modal show() called via Bootstrap');
    }
  } else {
    console.error('Modal element not found!');
  }
}

// Close data point modal
function closeDataPointModal() {
  console.log('closeDataPointModal called');
  var modalElement = document.getElementById('dataPointModal');
  if (modalElement) {
    console.log('Modal element found, attempting to close');
    
    // Method 1: Try jQuery modal first (since it's working)
    if (typeof $ !== 'undefined') {
      try {
        $(modalElement).modal('hide');
        console.log('✅ Modal closed successfully using jQuery');
        
        // Double-check if modal is actually closed, if not force close
        setTimeout(function() {
          if (modalElement.classList.contains('show') || modalElement.style.display !== 'none') {
            console.log('jQuery close didn\'t work, forcing direct close');
            closeModalDirectly(modalElement);
          }
        }, 100);
        
      } catch (jqError) {
        console.error('jQuery modal error:', jqError);
        // Fallback to direct DOM manipulation
        closeModalDirectly(modalElement);
      }
    } else {
      // Method 2: Try Bootstrap 5 modal if jQuery not available
      try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
          var modal = bootstrap.Modal.getInstance(modalElement);
          if (!modal) {
            modal = new bootstrap.Modal(modalElement);
          }
          modal.hide();
          console.log('✅ Modal closed successfully using Bootstrap 5');
        } else {
          throw new Error('Bootstrap Modal not available');
        }
      } catch (error) {
        console.log('Bootstrap modal not available, using direct DOM manipulation');
        closeModalDirectly(modalElement);
      }
    }
  } else {
    console.error('Modal element not found');
  }
  
  // Clear editing context
  currentEditingEntry = null;
  currentEditingIndex = null;
  currentEditingControlId = null;
  console.log('Editing context cleared');
}

// Helper function for direct DOM manipulation
function closeModalDirectly(modalElement) {
  // Hide the modal
  modalElement.style.display = 'none';
  modalElement.classList.remove('show', 'fade');
  modalElement.setAttribute('aria-hidden', 'true');
  modalElement.removeAttribute('aria-modal');
  modalElement.removeAttribute('role');
  
  // Clean up body classes and styles
  document.body.classList.remove('modal-open');
  document.body.style.overflow = '';
  document.body.style.paddingRight = '';
  
  // Remove all modal backdrops
  var backdrops = document.querySelectorAll('.modal-backdrop');
  backdrops.forEach(function(backdrop) {
    backdrop.remove();
  });
  
  // Remove any remaining modal-related classes from body
  document.body.classList.remove('modal-open');
  
  console.log('✅ Modal closed using direct DOM manipulation');
}

// Close delete confirmation modal
function closeDeleteConfirmModal() {
  console.log('closeDeleteConfirmModal called');
  var modalElement = document.getElementById('deleteConfirmModal');
  if (modalElement) {
    console.log('Delete confirm modal element found, attempting to close');
    
    // Method 1: Try jQuery modal first (since it's working)
    if (typeof $ !== 'undefined') {
      try {
        $(modalElement).modal('hide');
        console.log('✅ Delete confirm modal closed successfully using jQuery');
        
        // Double-check if modal is actually closed, if not force close
        setTimeout(function() {
          if (modalElement.classList.contains('show') || modalElement.style.display !== 'none') {
            console.log('jQuery close didn\'t work for delete confirm, forcing direct close');
            closeModalDirectly(modalElement);
          }
        }, 100);
        
      } catch (jqError) {
        console.error('jQuery delete confirm modal error:', jqError);
        // Fallback to direct DOM manipulation
        closeModalDirectly(modalElement);
      }
    } else {
      // Method 2: Try Bootstrap 5 modal if jQuery not available
      try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
          var modal = bootstrap.Modal.getInstance(modalElement);
          if (!modal) {
            modal = new bootstrap.Modal(modalElement);
          }
          modal.hide();
          console.log('✅ Delete confirm modal closed successfully using Bootstrap 5');
        } else {
          throw new Error('Bootstrap Modal not available');
        }
      } catch (error) {
        console.log('Bootstrap modal not available, using direct DOM manipulation for delete confirm');
        closeModalDirectly(modalElement);
      }
    }
  } else {
    console.error('Delete confirm modal element not found');
  }
}

// Format date for display (convert yyyy-mm-dd to dd/mm/yyyy)
function formatDateForDisplay(dateString) {
  if (!dateString) return '-';
  
  if (dateString.includes('-')) {
    var parts = dateString.split('-');
    if (parts.length === 3) {
      return parts[2] + '/' + parts[1] + '/' + parts[0];
    }
  }
  return dateString;
}

// Update data point value
function updateDataPoint() {
  if (!currentEditingEntry || currentEditingIndex === null || !currentEditingControlId) {
    showStatusMessage('No data point selected for editing', 'warning');
    return;
  }
  
  var newValue = document.getElementById('edit-value-input').value;
  
  // Validate input
  if (!newValue || isNaN(parseFloat(newValue))) {
    showStatusMessage('Please enter a valid numeric value', 'warning');
    return;
  }
  
  var numericValue = parseFloat(newValue);
  var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
  
  if (!analyteId) {
    showStatusMessage('No analyte selected', 'danger');
    return;
  }
  
  // Show loading state
  var updateBtn = document.getElementById('update-data-point-btn');
  var originalContent = updateBtn.innerHTML;
  updateBtn.disabled = true;
  updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
  
  // Prepare update payload
  var payload = {
    entry_id: currentEditingEntry.id || null,
    date: currentEditingEntry.date,
    time: currentEditingEntry.time || '',
    measured_value: numericValue,
    control_id: currentEditingControlId,
    analyte_id: analyteId,
    original_value: parseFloat(currentEditingEntry.measured_value)
  };
  
  // Send update request to backend
  console.log('Sending update request to database:', payload);
  fetch('/admin/qc/entries/update', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    },
    body: JSON.stringify(payload)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('HTTP ' + response.status + ': ' + response.statusText);
    }
    return response.json();
  })
  .then(data => {
    console.log('Update response from database:', data);
    if (data.ok) {
      console.log('✅ Database update successful');
      // Update the entry in local data
      currentEditingEntry.measured_value = numericValue;
      
      // Update the chart data
      var currentData = window._qc_sampleData[currentEditingControlId] || [];
      if (currentEditingIndex >= 0 && currentEditingIndex < currentData.length) {
        currentData[currentEditingIndex].measured_value = numericValue;
        window._qc_sampleData[currentEditingControlId] = currentData;
      }
      
      // Re-render the chart with updated data
      renderChart(currentEditingControlId, currentData);
      
      // Close modal
      closeDataPointModal();
      
      // Show success message
      showStatusMessage('✅ Data point updated successfully! Chart and statistics recalculated.', 'success');
      
    } else {
      console.error('❌ Database update failed:', data.error);
      showStatusMessage('Failed to update data point: ' + (data.error || 'Unknown error'), 'danger');
    }
  })
  .catch(function(error) {
    console.error('Error updating data point:', error);
    showStatusMessage('Error updating data point: ' + error.message, 'danger');
  })
  .finally(function() {
    // Reset button state
    updateBtn.disabled = false;
    updateBtn.innerHTML = originalContent;
  });
}

// Delete data point
function deleteDataPoint() {
  if (!currentEditingEntry || currentEditingIndex === null || !currentEditingControlId) {
    showStatusMessage('No data point selected for deletion', 'warning');
    return;
  }
  
  // Show confirmation modal
  var modalElement = document.getElementById('deleteConfirmModal');
  if (modalElement) {
    // Try jQuery first since it's working better
    if (typeof $ !== 'undefined') {
      $(modalElement).modal('show');
      console.log('Delete confirm modal show() called via jQuery');
    } else {
      var confirmModal = new bootstrap.Modal(modalElement);
      confirmModal.show();
      console.log('Delete confirm modal show() called via Bootstrap');
    }
  }
}

// Confirm deletion
function confirmDeleteDataPoint() {
  if (!currentEditingEntry || currentEditingIndex === null || !currentEditingControlId) {
    return;
  }
  
  var analyteId = parseInt(new URLSearchParams(location.search).get('analyte_id')||'0');
  
  if (!analyteId) {
    showStatusMessage('No analyte selected', 'danger');
    return;
  }
  
  // Show loading state
  var deleteBtn = document.getElementById('confirm-delete-btn');
  var originalContent = deleteBtn.innerHTML;
  deleteBtn.disabled = true;
  deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Deleting...';
  
  // Prepare delete payload
  var payload = {
    entry_id: currentEditingEntry.id || null,
    date: currentEditingEntry.date,
    time: currentEditingEntry.time || '',
    measured_value: parseFloat(currentEditingEntry.measured_value),
    control_id: currentEditingControlId,
    analyte_id: analyteId
  };
  
  // Send delete request to backend
  console.log('Sending delete request to database:', payload);
  fetch('/admin/qc/entries/delete', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    },
    body: JSON.stringify(payload)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('HTTP ' + response.status + ': ' + response.statusText);
    }
    return response.json();
  })
  .then(data => {
    console.log('Delete response from database:', data);
    if (data.ok) {
      console.log('✅ Database delete successful');
      // Remove the entry from local data
      var currentData = window._qc_sampleData[currentEditingControlId] || [];
      if (currentEditingIndex >= 0 && currentEditingIndex < currentData.length) {
        currentData.splice(currentEditingIndex, 1);
        window._qc_sampleData[currentEditingControlId] = currentData;
      }
      
      // Re-render the chart with updated data
      renderChart(currentEditingControlId, currentData);
      
      // Close both modals
      closeDeleteConfirmModal();
      closeDataPointModal();
      
      // Show success message
      showStatusMessage('✅ Data point deleted successfully! Chart and statistics recalculated.', 'success');
      
    } else {
      console.error('❌ Database delete failed:', data.error);
      showStatusMessage('Failed to delete data point: ' + (data.error || 'Unknown error'), 'danger');
    }
  })
  .catch(function(error) {
    console.error('Error deleting data point:', error);
    showStatusMessage('Error deleting data point: ' + error.message, 'danger');
  })
  .finally(function() {
    // Reset button state
    deleteBtn.disabled = false;
    deleteBtn.innerHTML = originalContent;
  });
}

// Add event listeners for the interactive modals
document.addEventListener('DOMContentLoaded', function() {
  // Update button event listener
  var updateBtn = document.getElementById('update-data-point-btn');
  if (updateBtn) {
    updateBtn.addEventListener('click', updateDataPoint);
  }
  
  // Delete button event listener
  var deleteBtn = document.getElementById('delete-data-point-btn');
  if (deleteBtn) {
    deleteBtn.addEventListener('click', deleteDataPoint);
  }
  
  // Confirm delete button event listener
  var confirmDeleteBtn = document.getElementById('confirm-delete-btn');
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', confirmDeleteDataPoint);
  }
  
  // Cancel button event listeners (backup method)
  var cancelEditBtn = document.getElementById('cancel-edit-btn');
  if (cancelEditBtn) {
    cancelEditBtn.addEventListener('click', function(e) {
      e.preventDefault();
      console.log('Cancel edit button clicked via event listener');
      closeDataPointModal();
    });
  }
  
  var cancelDeleteBtn = document.getElementById('cancel-delete-btn');
  if (cancelDeleteBtn) {
    cancelDeleteBtn.addEventListener('click', function(e) {
      e.preventDefault();
      console.log('Cancel delete button clicked via event listener');
      closeDeleteConfirmModal();
    });
  }
  
  // Prevent background scrolling when modals are open
  var modals = document.querySelectorAll('.modal');
  modals.forEach(function(modal) {
    modal.addEventListener('shown.bs.modal', function() {
      document.body.style.overflow = 'hidden';
    });
    modal.addEventListener('hidden.bs.modal', function() {
      document.body.style.overflow = 'auto';
    });
  });
});

// Update control limits source function (deprecated - use updateChartsControlLimitsSource instead)
function updateControlLimitsSource() {
  console.log('updateControlLimitsSource called - redirecting to charts dropdown');
  // Redirect to the charts dropdown function since Control Settings dropdown was removed
  updateChartsControlLimitsSource();
}

// Update charts control limits source function
function updateChartsControlLimitsSource() {
  var source = document.getElementById('charts-control-limits-source').value;
  console.log('Charts control limits source changed to:', source);
  
  if (source === 'reference') {
    loadReferenceValues();
  } else {
    // Use cumulative values (lab data)
    recalculateAllCharts();
  }
}

// Make functions available globally
window.updateControlLimitsSource = updateControlLimitsSource;
window.updateChartsControlLimitsSource = updateChartsControlLimitsSource;

</script>
@endsection

