<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QcAnalyte;
use App\Models\QcControlMaterial;
use App\Models\QcEntry;

class QcEntriesController extends Controller
{
    public function index(Request $request)
    {
        $analyte_id = $request->get('analyte_id');
        return view('admin.qc.entries', compact('analyte_id'));
    }

    public function test()
    {
        try {
            // Test database connection
            $count = QcEntry::count();
            return response()->json([
                'ok' => true,
                'message' => 'Database connection working',
                'total_entries' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => 'Database connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testDelete(Request $request)
    {
        try {
            \Log::info('Test delete called with data:', $request->all());
            
            $data = $request->validate([
                'entry_id' => 'nullable|integer',
                'date' => 'required|date',
                'time' => 'required|string',
                'measured_value' => 'required',
                'control_id' => 'required|integer',
                'analyte_id' => 'required|integer'
            ]);

            return response()->json([
                'ok' => true,
                'message' => 'Validation passed',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Test delete error: ' . $e->getMessage());
            return response()->json([
                'ok' => false,
                'error' => 'Test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function options()
    {
        $analytes = QcAnalyte::orderBy('position')->orderBy('name')->get(['id','name','unit','decimals']);
        $controls = QcControlMaterial::orderBy('name')->get(['id','name','lot_number','level']);
        $operators = \App\Models\User::orderBy('name')->get(['id','name']);
        return response()->json(['ok'=>true,'analytes'=>$analytes,'controls'=>$controls,'operators'=>$operators]);
    }

    public function load(Request $request)
    {
        try {
            $data = $request->validate([
                'analyte_id' => 'required|integer',
                'control_ids' => 'required|array',
                'control_ids.*' => 'integer',
                'from' => 'nullable|date',
                'to' => 'nullable|date',
            ]);
            
            $from = $data['from'] ?? null;
            $to = $data['to'] ?? null;
            $out = [];
            
            foreach ($data['control_ids'] as $cid) {
                try {
                    $q = QcEntry::where('analyte_id', $data['analyte_id'])->where('control_id', $cid);
                    if($from){ $q->where('date','>=',$from); }
                    if($to){ $q->where('date','<=',$to); }
                    $entries = $q->orderBy('date')->orderBy('time')->get(['id','date','time','measured_value','comment','operator']);
                    $out[$cid] = $entries;
                } catch (\Exception $e) {
                    \Log::error('Error loading QC entries for control ' . $cid . ': ' . $e->getMessage());
                    $out[$cid] = [];
                }
            }
            
            return response()->json(['ok'=>true,'data'=>$out]);
            
        } catch (\Exception $e) {
            \Log::error('QC Entries load error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'ok' => false, 
                'error' => 'Failed to load QC entries: ' . $e->getMessage()
            ], 500);
        }
    }

    public function save(Request $request)
    {
        $payload = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'operator' => 'required|string|max:50',
            'analyte_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.control_id' => 'required|integer',
            'items.*.value' => 'required|numeric',
            'comment' => 'nullable|string'
        ]);
        foreach ($payload['items'] as $item) {
            QcEntry::create([
                'date' => $payload['date'],
                'time' => $payload['time'],
                'operator' => $payload['operator'],
                'analyte_id' => $payload['analyte_id'],
                'control_id' => $item['control_id'],
                'measured_value' => $item['value'],
                'comment' => $payload['comment'] ?? null,
                'created_at' => now(),
            ]);
        }
        return response()->json(['ok'=>true]);
    }

    public function update(Request $request)
    {
        try {
            // Basic validation
            $entryId = $request->input('entry_id');
            $date = $request->input('date');
            $time = $request->input('time');
            $newValue = $request->input('measured_value');
            $originalValue = $request->input('original_value');
            $controlId = $request->input('control_id');
            $analyteId = $request->input('analyte_id');

            // Validate required fields
            if (empty($date) || empty($newValue) || empty($controlId) || empty($analyteId)) {
                return response()->json(['ok' => false, 'error' => 'Missing required fields'], 422);
            }

            // Convert values to proper types
            $controlId = intval($controlId);
            $analyteId = intval($analyteId);
            $newValue = floatval($newValue);
            $originalValue = floatval($originalValue);

            // Try to find the entry to update
            $query = QcEntry::where('analyte_id', $analyteId)
                           ->where('control_id', $controlId)
                           ->where('date', $date)
                           ->where('measured_value', $originalValue);
            
            // Add time filter only if provided
            if (!empty($time)) {
                $query->where('time', $time);
            }

            if (!empty($entryId)) {
                $query->where('id', intval($entryId));
            }

            $entry = $query->first();

            if (!$entry) {
                return response()->json(['ok' => false, 'error' => 'Entry not found'], 404);
            }

            // Update the entry
            $entry->measured_value = $newValue;
            $entry->save();

            return response()->json([
                'ok' => true, 
                'message' => 'Entry updated successfully',
                'updated_entry' => [
                    'id' => $entry->id,
                    'date' => $entry->date,
                    'time' => $entry->time,
                    'measured_value' => $entry->measured_value,
                    'control_id' => $entry->control_id,
                    'analyte_id' => $entry->analyte_id
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('QC Entry update error: ' . $e->getMessage());
            return response()->json(['ok' => false, 'error' => 'Failed to update entry: ' . $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            // Get raw input data for debugging
            $rawData = $request->all();
            \Log::info('QC Delete request data:', $rawData);
            
            // Basic validation without strict rules
            $entryId = $request->input('entry_id');
            $date = $request->input('date');
            $time = $request->input('time');
            $measuredValue = $request->input('measured_value');
            $controlId = $request->input('control_id');
            $analyteId = $request->input('analyte_id');

            // Validate required fields (time is optional for deletion)
            if (empty($date) || empty($measuredValue) || empty($controlId) || empty($analyteId)) {
                return response()->json(['ok' => false, 'error' => 'Missing required fields'], 422);
            }

            // Convert values to proper types
            $controlId = intval($controlId);
            $analyteId = intval($analyteId);
            $measuredValue = floatval($measuredValue);

            // Try to find and delete by ID first, then by other criteria
            $query = QcEntry::where('analyte_id', $analyteId)
                           ->where('control_id', $controlId)
                           ->where('date', $date)
                           ->where('measured_value', $measuredValue);
            
            // Add time filter only if provided
            if (!empty($time)) {
                $query->where('time', $time);
            }

            if (!empty($entryId)) {
                $query->where('id', intval($entryId));
            }

            $deleted = $query->delete();

            if ($deleted > 0) {
                return response()->json(['ok' => true, 'message' => 'Entry deleted successfully']);
            } else {
                return response()->json(['ok' => false, 'error' => 'Entry not found'], 404);
            }

        } catch (\Exception $e) {
            \Log::error('QC Entry delete error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['ok' => false, 'error' => 'Failed to delete entry: ' . $e->getMessage()], 500);
        }
    }
}

