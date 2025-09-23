<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QcAnalyte;
use App\Models\QcControlMaterial;
use App\Models\QcReferenceValue;
use Illuminate\Support\Facades\Schema;
use Gate;

class QcReferenceValuesController extends Controller
{
    
    private function ensureTables(): void
    {
        if (!Schema::hasTable('qc_reference_values')) {
            Schema::create('qc_reference_values', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('analyte_id');
                $table->unsignedInteger('control_id');
                $table->decimal('mean', 10, 4);
                $table->decimal('sd', 10, 4);
                $table->decimal('plus_1sd', 10, 4)->nullable();
                $table->decimal('plus_2sd', 10, 4)->nullable();
                $table->decimal('plus_3sd', 10, 4)->nullable();
                $table->decimal('minus_1sd', 10, 4)->nullable();
                $table->decimal('minus_2sd', 10, 4)->nullable();
                $table->decimal('minus_3sd', 10, 4)->nullable();
                $table->timestamps();
                $table->unique(['analyte_id','control_id'], 'unique_analyte_control');
            });
        }
    }
    public function index()
    {
        // Redirect to the new combined page
        return redirect()->route('admin.qc.materials.combined');
    }

    public function options()
    {
        try {
            if (!Schema::hasTable('qc_analytes')) {
                return response()->json(['ok'=>true,'analytes'=>[], 'controls'=>[]]);
            }
            $analytes = QcAnalyte::orderBy('name')->get(['id','name','unit','decimals']);
            $controls = Schema::hasTable('qc_control_materials')
                ? QcControlMaterial::orderBy('name')->get(['id','name','lot_number','level'])
                : collect([]);
            return response()->json(['ok'=>true,'analytes'=>$analytes,'controls'=>$controls]);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false,'error'=>$e->getMessage()], 500);
        }
    }

    public function load(Request $request)
    {
        try {
            $data = $request->validate([
                'analyte_id' => 'required|integer',
                'control_ids' => 'required|array',
                'control_ids.*' => 'integer',
            ]);
            if (!Schema::hasTable('qc_reference_values')) {
                return response()->json(['ok'=>true,'data'=>[]]);
            }
            $out = [];
            foreach ($data['control_ids'] as $cid) {
                $rv = QcReferenceValue::where('analyte_id',$data['analyte_id'])->where('control_id',$cid)->first();
                $out[$cid] = $rv ? $rv->only(['mean','sd','plus_1sd','plus_2sd','plus_3sd','minus_1sd','minus_2sd','minus_3sd']) : null;
            }
            return response()->json(['ok'=>true,'data'=>$out]);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false,'error'=>$e->getMessage()], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            $this->ensureTables();
            $payload = $request->validate([
                'analyte_id' => 'required|integer',
                'items' => 'required|array',
                'items.*.control_id' => 'required|integer',
                'items.*.mean' => 'required|numeric',
                'items.*.sd' => 'required|numeric',
                'items.*.plus_1sd' => 'nullable|numeric',
                'items.*.plus_2sd' => 'nullable|numeric',
                'items.*.plus_3sd' => 'nullable|numeric',
                'items.*.minus_1sd' => 'nullable|numeric',
                'items.*.minus_2sd' => 'nullable|numeric',
                'items.*.minus_3sd' => 'nullable|numeric',
            ]);

            foreach ($payload['items'] as $item) {
                $sd = (float)$item['sd'];
                $mean = (float)$item['mean'];
                if ($sd <= 0) {
                    return response()->json(['ok'=>false,'error'=>'SD must be positive'], 422);
                }
                $plus1 = array_key_exists('plus_1sd',$item) && $item['plus_1sd'] !== null ? (float)$item['plus_1sd'] : $mean + $sd;
                $plus2 = array_key_exists('plus_2sd',$item) && $item['plus_2sd'] !== null ? (float)$item['plus_2sd'] : $mean + 2*$sd;
                $plus3 = array_key_exists('plus_3sd',$item) && $item['plus_3sd'] !== null ? (float)$item['plus_3sd'] : $mean + 3*$sd;
                $minus1 = array_key_exists('minus_1sd',$item) && $item['minus_1sd'] !== null ? (float)$item['minus_1sd'] : $mean - $sd;
                $minus2 = array_key_exists('minus_2sd',$item) && $item['minus_2sd'] !== null ? (float)$item['minus_2sd'] : $mean - 2*$sd;
                $minus3 = array_key_exists('minus_3sd',$item) && $item['minus_3sd'] !== null ? (float)$item['minus_3sd'] : $mean - 3*$sd;
                QcReferenceValue::updateOrCreate(
                    ['analyte_id'=>$payload['analyte_id'], 'control_id'=>$item['control_id']],
                    [
                        'mean'=>$mean,
                        'sd'=>$sd,
                        'plus_1sd'=>$plus1,
                        'plus_2sd'=>$plus2,
                        'plus_3sd'=>$plus3,
                        'minus_1sd'=>$minus1,
                        'minus_2sd'=>$minus2,
                        'minus_3sd'=>$minus3,
                    ]
                );
            }
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false,'error'=>$e->getMessage()], 500);
        }
    }
}

