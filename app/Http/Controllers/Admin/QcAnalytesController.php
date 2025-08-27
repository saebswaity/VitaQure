<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QcAnalyte;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QcAnalytesController extends Controller
{
    public function index()
    {
        return view('admin.qc.analytes');
    }

    public function list()
    {
        if (!Schema::hasTable('qc_analytes')) {
            return response()->json(['ok' => true, 'data' => []]);
        }

        $query = DB::table('qc_analytes as a')->select('a.id','a.name','a.unit','a.decimals');

        if (Schema::hasTable('qc_control_analyte_assignments') && Schema::hasTable('qc_control_materials')) {
            $query = DB::table('qc_analytes as a')
                ->leftJoin('qc_control_analyte_assignments as p', 'p.analyte_id', '=', 'a.id')
                ->leftJoin('qc_control_materials as c', 'c.id', '=', 'p.control_id')
                ->select(
                    'a.id','a.name','a.unit','a.decimals',
                    DB::raw("GROUP_CONCAT(DISTINCT c.level ORDER BY c.level SEPARATOR ', ') as levels"),
                    DB::raw('COUNT(DISTINCT c.id) as levels_count')
                )
                ->groupBy('a.id','a.name','a.unit','a.decimals');
        }

        $analytes = $query->orderBy('a.name')->get();

        return response()->json(['ok' => true, 'data' => $analytes]);
    }

    public function store(Request $request)
    {
        try {
            if (!Schema::hasTable('qc_analytes')) {
                Schema::create('qc_analytes', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->increments('id');
                    $table->string('name', 15);
                    $table->string('unit', 6);
                    $table->tinyInteger('decimals')->default(0);
                    $table->timestamp('created_at')->useCurrent();
                });
            }

            $data = $request->validate([
                'name' => 'required|string|max:15',
                'unit' => 'required|string|max:6',
                'decimals' => 'required|integer|min:0|max:3',
            ]);
            // Insert without forcing created_at to avoid errors if column doesn't exist
            $row = QcAnalyte::create($data);
            return response()->json(['ok' => true, 'id' => $row->id]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $row = QcAnalyte::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:15',
            'unit' => 'required|string|max:6',
            'decimals' => 'required|integer|min:0|max:3',
        ]);
        $row->update($data);
        return response()->json(['ok' => true]);
    }

    public function destroy($id)
    {
        $row = QcAnalyte::findOrFail($id);
        $row->delete();
        return response()->json(['ok' => true]);
    }
}

