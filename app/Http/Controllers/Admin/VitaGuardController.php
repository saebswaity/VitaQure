<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VitaGuardController extends Controller
{
    public function index()
    {
        // Load config.json if exists
        $configPath = base_path('dr/config.json');
        $config = [];
        if (file_exists($configPath)) {
            $json = file_get_contents($configPath);
            $config = json_decode($json, true) ?: [];
        }

        // Patients list for left section
        $patients = \App\Models\Patient::orderBy('created_at', 'desc')->take(50)->get();
        $selectedPatient = null;
        if ($pid = request()->query('patient_id')) {
            $selectedPatient = \App\Models\Patient::find($pid);
        }
        
        // Determine selected model (via query string) and normalize
        $modelKeys = array_keys($config);
        $selectedModelKey = request()->query('model');
        if (!$selectedModelKey || !in_array($selectedModelKey, $modelKeys, true)) {
            $selectedModelKey = $modelKeys[0] ?? null;
        }
        $selectedModelCfg = $selectedModelKey ? ($config[$selectedModelKey] ?? []) : [];

        // If model has a path, load its inputs from the individual JSON file
        if (!empty($selectedModelCfg) && !empty($selectedModelCfg['path'])) {
            $relativePath = ltrim($selectedModelCfg['path'], '/');
            $modelJsonPath = base_path('dr/' . $relativePath);
            if (file_exists($modelJsonPath)) {
                $modelJson = json_decode(file_get_contents($modelJsonPath), true) ?: [];
                if (!empty($modelJson['inputs']) && is_array($modelJson['inputs'])) {
                    $selectedModelCfg['inputs'] = $modelJson['inputs'];
                }
            }
        }

        return view('admin.vitaguard.index', compact('patients', 'selectedPatient', 'config', 'selectedModelKey', 'selectedModelCfg', 'modelKeys'));
    }

    public function predict(Request $request)
    {
        $data = $request->validate([
            'model' => 'required|string|in:kidney,heart,liver,blood_disorders,diabetes',
            'inputs' => 'required|array',
        ]);

        // Define model configurations
        $modelConfigs = [
            'kidney' => [
                'csv_path' => base_path('dr/csv/kidney_processed.csv'),
                'model_path' => base_path('dr/KIDNEY.pkl'),
            ],
            'heart' => [
                'csv_path' => base_path('dr/csv/heart_data_with_balance.csv'),
                'model_path' => base_path('dr/heart.pkl'),
            ],
            'liver' => [
                'csv_path' => base_path('dr/csv/livergood_final cleaning.csv'),
                'model_path' => base_path('dr/liver.pkl'),
            ],
            'blood_disorders' => [
                'csv_path' => base_path('dr/csv/cleaned_anemia_data.csv'),
                'model_path' => base_path('dr/anemia.pkl'),
            ],
            'diabetes' => [
                'csv_path' => base_path('dr/csv/filtered_original_data.csv'),
                'model_path' => base_path('dr/diabetes.pkl'),
            ],
        ];

        $selectedModel = $data['model'];
        if (!isset($modelConfigs[$selectedModel])) {
            return response()->json(['ok' => false, 'error' => 'Invalid model selected'], 400);
        }

        $payload = [
            'model' => $data['model'],
            'inputs' => $data['inputs'],
            'csv_path' => $modelConfigs[$selectedModel]['csv_path'],
            'model_path' => $modelConfigs[$selectedModel]['model_path'],
        ];

        $tmpFile = tempnam(sys_get_temp_dir(), 'vg_');
        file_put_contents($tmpFile, json_encode($payload));

        $scriptPath = base_path('dr/predict_once.py');
        if (!file_exists($scriptPath)) {
            @unlink($tmpFile);
            return response()->json(['ok' => false, 'error' => 'Predict script missing'], 500);
        }

        // Prefer python3.10 if present for dependency compatibility
        $python = trim(shell_exec('command -v python3.10') ?: '') ?: 'python3';
        $cmd = escapeshellcmd($python) . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($tmpFile);
        $output = shell_exec($cmd);
        $output = is_string($output) ? trim($output) : '';
        @unlink($tmpFile);

        if (!$output) {
            return response()->json(['ok' => false, 'error' => 'No response from predictor'], 500);
        }
        $json = json_decode($output, true);
        if (!$json) {
            return response()->json(['ok' => false, 'error' => 'Invalid predictor response', 'raw' => $output], 500);
        }
        return response()->json($json);
    }
}

