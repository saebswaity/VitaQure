<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class RenameBrand extends Command
{
    protected $signature = 'app:rename-brand {from} {to}';

    protected $description = 'Replace brand name across settings JSON and .env APP_NAME';

    public function handle(): int
    {
        $from = (string) $this->argument('from');
        $to   = (string) $this->argument('to');

        $this->info("Replacing '{$from}' with '{$to}' in settings...");

        $updated = 0;
        Setting::query()->chunk(100, function ($settings) use ($from, $to, &$updated) {
            foreach ($settings as $setting) {
                $value = $setting->value;
                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $replaced = $this->replaceRecursive($decoded, $from, $to);
                    if ($replaced !== $decoded) {
                        $setting->value = json_encode($replaced, JSON_UNESCAPED_UNICODE);
                        $setting->save();
                        $updated++;
                    }
                } elseif (is_string($value)) {
                    $new = str_ireplace($from, $to, $value);
                    if ($new !== $value) {
                        $setting->value = $new;
                        $setting->save();
                        $updated++;
                    }
                }
            }
        });

        // Ensure site display name is set explicitly
        $info = Setting::where('key', 'info')->first();
        if ($info) {
            $data = json_decode($info->value, true) ?: [];
            if (!empty($data)) {
                $data['name'] = $to;
                $info->value = json_encode($data, JSON_UNESCAPED_UNICODE);
                $info->save();
            }
        }

        $this->info("Updated {$updated} setting record(s).");

        // Update APP_NAME in .env if present
        $envPath = base_path('.env');
        if (file_exists($envPath) && is_writable($envPath)) {
            $env = file_get_contents($envPath);
            if ($env !== false) {
                if (preg_match('/^APP_NAME=.*$/m', $env)) {
                    $env = preg_replace('/^APP_NAME=.*$/m', 'APP_NAME="' . addslashes($to) . '"', $env);
                } else {
                    $env .= "\nAPP_NAME=\"" . addslashes($to) . "\"\n";
                }
                file_put_contents($envPath, $env);
                $this->info('Updated APP_NAME in .env');
            }
        } else {
            $this->warn('.env not writable or missing; APP_NAME not updated.');
        }

        $this->info('Brand rename completed.');
        return Command::SUCCESS;
    }

    private function replaceRecursive($data, string $from, string $to)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = $this->replaceRecursive($value, $from, $to);
                } elseif (is_string($value)) {
                    $data[$key] = str_ireplace($from, $to, $value);
                }
            }
        }
        return $data;
    }
}

