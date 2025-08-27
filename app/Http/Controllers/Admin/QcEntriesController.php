<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QcAnalyte;
use App\Models\QcControlMaterial;
use App\Models\QcEntry;

class QcEntriesController extends Controller
{
    public function index()
    {
        return view('admin.qc.entries');
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
        $data = $request->validate([
            'analyte_id' => 'required|integer',
            'control_ids' => 'required|array',
            'control_ids.*' => 'integer',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);
        $from = $request->query('from');
        $to = $request->query('to');
        $out = [];
        foreach ($data['control_ids'] as $cid) {
            $q = QcEntry::where('analyte_id',$data['analyte_id'])->where('control_id',$cid);
            if($from){ $q->where('date','>=',$from); }
            if($to){ $q->where('date','<=',$to); }
            $entries = $q->orderBy('date')->orderBy('time')->get(['id','date','time','measured_value','comment','operator']);
            $out[$cid] = $entries;
        }
        return response()->json(['ok'=>true,'data'=>$out]);
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
}

