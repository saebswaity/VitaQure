<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QcControlMaterial;
use App\Models\QcAnalyte;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QcControlMaterialsController extends Controller
{
    private function ensureTables(): void
    {
        // Create qc_control_materials table if it doesn't exist
        if (!Schema::hasTable('qc_control_materials')) {
            Schema::create('qc_control_materials', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->increments('id');
                $table->string('name', 50);
                $table->string('lot_number', 50)->unique();
                $table->enum('level', ['Low','Normal','High']);
                $table->date('expiry_date');
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // Create pivot table used by models if it doesn't exist
        if (!Schema::hasTable('qc_control_analyte_assignments')) {
            Schema::create('qc_control_analyte_assignments', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('analyte_id');
                $table->unsignedInteger('control_id');
                $table->unique(['analyte_id','control_id'], 'unique_assignment');
            });
        }
    }

    public function index()
    {
        return view('admin.qc.materials');
    }

    public function list(Request $request)
    {
        if (!Schema::hasTable('qc_control_materials')) {
            return response()->json(['ok'=>true,'data'=>[]]);
        }
        $q = trim((string)$request->query('q'));
        $model = QcControlMaterial::query();
        if($q !== ''){
            $model->where(function($qq) use ($q){
                $qq->where('name','like','%'.$q.'%')->orWhere('lot_number','like','%'.$q.'%');
            });
        }
        $materials = $model->orderBy('name')->get(['id','name','lot_number','level','expiry_date']);
        return response()->json(['ok'=>true,'data'=>$materials]);
    }

    public function show($id)
    {
        $material = QcControlMaterial::findOrFail($id);
        $analyteIds = $material->analytes()->pluck('analyte_id');
        $analytes = QcAnalyte::orderBy('position')->orderBy('name')->get(['id','name','unit','decimals']);
        return response()->json(['ok'=>true,'material'=>$material,'assigned'=>$analyteIds,'analytes'=>$analytes]);
    }

    public function store(Request $request)
    {
        try {
            $this->ensureTables();
            $data = $request->validate([
                'name' => 'required|string|max:50',
                'lot_number' => 'required|string|max:50|unique:qc_control_materials,lot_number',
                'level' => 'required|in:Low,Normal,High',
                'expiry_date' => 'required|date',
                'analyte_id' => 'nullable|integer',
            ]);
            $mat = QcControlMaterial::create($data + ['created_at'=>now()]);
            // If analyte_id provided, auto-assign this new material to that analyte
            if (!empty($data['analyte_id'])) {
                DB::table('qc_control_analyte_assignments')->updateOrInsert(
                    ['analyte_id' => (int)$data['analyte_id'], 'control_id' => $mat->id],
                    []
                );
            }
            return response()->json(['ok'=>true,'id'=>$mat->id]);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false, 'error'=>$e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->ensureTables();
            $mat = QcControlMaterial::findOrFail($id);
            $data = $request->validate([
                'name' => 'required|string|max:50',
                'lot_number' => 'required|string|max:50|unique:qc_control_materials,lot_number,'.$mat->id,
                'level' => 'required|in:Low,Normal,High',
                'expiry_date' => 'required|date',
            ]);
            $mat->update($data);
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false, 'error'=>$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->ensureTables();
            $mat = QcControlMaterial::findOrFail($id);
            if (Schema::hasTable('qc_control_analyte_assignments')) {
                DB::table('qc_control_analyte_assignments')->where('control_id',$mat->id)->delete();
            }
            $mat->delete();
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false, 'error'=>$e->getMessage()], 500);
        }
    }

    public function assign(Request $request, $id)
    {
        $mat = QcControlMaterial::findOrFail($id);
        $data = $request->validate([
            'analyte_ids' => 'array',
            'analyte_ids.*' => 'integer'
        ]);
        $ids = $data['analyte_ids'] ?? [];
        $mat->analytes()->sync($ids);
        return response()->json(['ok'=>true]);
    }

    // New: analyte-centric assignment APIs
    public function assignedForAnalyte(Request $request)
    {
        try {
            $this->ensureTables();
            $analyteId = (int)$request->query('analyte_id');
            if(!$analyteId){ return response()->json(['ok'=>false,'error'=>'Missing analyte_id'], 422); }
            $materials = QcControlMaterial::orderBy('name')->get(['id','name','lot_number','level']);
            $assigned = Schema::hasTable('qc_control_analyte_assignments')
                ? DB::table('qc_control_analyte_assignments')->where('analyte_id',$analyteId)->pluck('control_id')
                : collect([]);
            return response()->json(['ok'=>true,'materials'=>$materials,'assigned'=>$assigned]);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false, 'error'=>$e->getMessage()], 500);
        }
    }

    public function assignForAnalyte(Request $request)
    {
        try {
            $this->ensureTables();
            $data = $request->validate([
                'analyte_id' => 'required|integer',
                'control_ids' => 'array',
                'control_ids.*' => 'integer'
            ]);
            $analyte = \App\Models\QcAnalyte::findOrFail($data['analyte_id']);
            $ids = $data['control_ids'] ?? [];
            // sync from analyte side
            $analyte->controlMaterials()->sync($ids);
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false, 'error'=>$e->getMessage()], 500);
        }
    }
}

