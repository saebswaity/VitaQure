<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class AIChatController extends Controller
{
    public function index()
    {
        $path = base_path('docs/SystemContext.md');
        $defaultContext = is_file($path) ? file_get_contents($path) : '';
        $adminId = auth('admin')->id();
        $userKey = $adminId ? ('admin_'.$adminId) : 'guest';
        return view('ai_chat.index', ['defaultContext' => $defaultContext, 'userKey' => $userKey]);
    }

    private function userAskedForTechnicalDetails(string $message): bool
    {
        $m = mb_strtolower($message);
        $needles = [
            'technical details', 'backend details', 'developer steps', 'show code', 'show routes',
            'error log', 'stack trace', 'config', 'env', 'api key', 'server path', 'file path',
            'permissions', 'chmod', 'storage', 'mpdf', 'temp dir', 'debug', 'how to fix error',
            'why 500', 'why 404', 'why 403', 'curl', 'composer', 'artisan'
        ];
        foreach ($needles as $n) {
            if (str_contains($m, $n)) { return true; }
        }
        return false;
    }

    private function filterAnswerForEndUser(string $text): string
    {
        $original = $text;
        // Remove code fences and stack traces
        $text = preg_replace('/```[\s\S]*?```/u', '', $text) ?? $text;
        // Remove explicit file paths and server paths
        $text = preg_replace('#(/var/www|storage/[^\s]+|public/uploads/[^\s]+|config/[^\s]+|\.env|/etc/[^\s]+)#iu', '', $text) ?? $text;
        // Remove route/controller/method references
        $text = preg_replace('#\b(POST|GET|PUT|DELETE)\s+/[\w\-/{}]+#i', '', $text) ?? $text;
        $text = preg_replace('#\b(App\\Http\\Controllers\\[\w\\]+|Controller::[\w]+)\b#', '', $text) ?? $text;
        // Remove chmod/permission and debug-specific suggestions
        $text = preg_replace('#\b(chmod|chown|permissions?|temp(orary)?\s+folder|mpdf|storage_path|public_path|artisan|composer|npm|gunicorn|nginx|apache|log|error log|stack trace)\b#i', '', $text) ?? $text;
        // Normalize excessive blank lines
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;
        $text = trim($text);

        // If we stripped too much, fall back to a concise help
        if ($text === '' || mb_strlen($text) < 10) {
            return 'Here are the steps:\n1) Open the related page.\n2) Follow the on-screen fields and actions.\n3) Save or Print as needed.';
        }

        // If reply is too long, summarize to key steps
        if (mb_strlen($text) > 1500) {
            // crude summarization: keep first 10 lines and any numbered lists
            $lines = preg_split("/\r?\n/", $text) ?: [];
            $kept = [];
            $count = 0;
            foreach ($lines as $ln) {
                if ($count < 10 || preg_match('/^\s*\d+\./', $ln)) { $kept[] = $ln; $count++; }
                if ($count >= 20) break;
            }
            $text = trim(implode("\n", $kept));
        }

        return $text;
    }

    public function models()
    {
        $available = [];
        if (env('OPENAI_API_KEY')) { $available[] = 'chatgpt'; }
        if (env('GOOGLE_API_KEY')) { $available[] = 'gemini'; }
        if (empty($available)) { $available = ['chatgpt']; }
        return response()->json(['models' => $available]);
    }

    public function chat(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:2000',
            'model_type' => 'required|string|in:chatgpt,gemini',
            'save' => 'nullable|boolean',
            'initial_context' => 'nullable|string|max:20000',
            'show_technical' => 'nullable|boolean',
        ]);

        $sessionId = $validated['session_id'];
        $modelType = $validated['model_type'];
        $message = $validated['message'];
        $save = (bool)($validated['save'] ?? false);
        $initialContext = $validated['initial_context'] ?? '';
        // Hard cap context length to protect token budget
        if (mb_strlen($initialContext) > 20000) {
            $initialContext = mb_substr($initialContext, 0, 20000);
        }
        $showTechnical = (bool)($validated['show_technical'] ?? false);

        $historyKey = "ai_chat_history_{$sessionId}_{$modelType}";
        $history = Session::get($historyKey, []);

        // Build prompt with optional context and short history
        $contextText = trim($initialContext);
        $historyText = '';
        if ($save && !empty($history)) {
            $chunks = array_slice($history, max(0, count($history) - 10));
            foreach ($chunks as $h) {
                $historyText .= "User: {$h['user']}\nAssistant: {$h['bot']}\n";
            }
        }

        try {
            if ($modelType === 'gemini') {
                $reply = $this->callGemini($contextText, $historyText, $message);
            } else {
                $reply = $this->callOpenAI($contextText, $historyText, $message);
            }

            // Apply user-facing filter unless technical details are explicitly requested
            $shouldFilter = !$showTechnical && !$this->userAskedForTechnicalDetails($message);
            if ($shouldFilter) {
                $reply = $this->filterAnswerForEndUser($reply);
            }

            if ($save) {
                $history[] = ['user' => $message, 'bot' => $reply, 'ts' => time()];
                Session::put($historyKey, $history);
            }

            return response()->json(['success' => true, 'response' => $reply]);
        } catch (\Throwable $e) {
            Log::error('AI chat error: '.$e->getMessage());
            return response()->json(['error' => 'Server error: '.$e->getMessage()], 500);
        }
    }

    public function clear(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'model_type' => 'nullable|string|in:chatgpt,gemini',
        ]);
        $sessionId = $request->input('session_id');
        $model = $request->input('model_type');
        if ($model) {
            Session::forget("ai_chat_history_{$sessionId}_{$model}");
        } else {
            Session::forget("ai_chat_history_{$sessionId}_chatgpt");
            Session::forget("ai_chat_history_{$sessionId}_gemini");
        }
        return response()->json(['success' => true]);
    }

    private function callOpenAI(string $context, string $history, string $message): string
    {
        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) { throw new \RuntimeException('Missing OPENAI_API_KEY'); }
        $client = new Client();
        $model = env('OPENAI_MODEL', 'gpt-5-nano');

        $messages = [];
        if ($context !== '') { $messages[] = ['role' => 'system', 'content' => $context]; }
        if ($history !== '') { $messages[] = ['role' => 'system', 'content' => "Previous conversation:\n".$history]; }
        $messages[] = ['role' => 'user', 'content' => $message];

        $payload = [
            'model' => $model,
            'messages' => $messages,
        ];
        // gpt-5-* may not support arbitrary temperature; default to 1
        if (preg_match('/^gpt-5/i', $model)) {
            $payload['temperature'] = 1;
        } else {
            $payload['temperature'] = 0.7;
        }

        $res = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
            'http_errors' => false,
            'timeout' => 60,
        ]);

        $status = $res->getStatusCode();
        $body = json_decode((string)$res->getBody(), true);
        if ($status >= 400) {
            $err = $body['error']['message'] ?? 'OpenAI API error';
            throw new \RuntimeException($err);
        }
        return $body['choices'][0]['message']['content'] ?? '';
    }

    private function callGemini(string $context, string $history, string $message): string
    {
        $apiKey = env('GOOGLE_API_KEY');
        if (!$apiKey) { throw new \RuntimeException('Missing GOOGLE_API_KEY'); }
        $client = new Client();
        $model = env('GEMINI_MODEL', 'gemini-1.5-pro');

        $parts = [];
        if ($context !== '') { $parts[] = ['text' => $context]; }
        if ($history !== '') { $parts[] = ['text' => "Previous conversation:\n".$history]; }
        $parts[] = ['text' => $message];

        $res = $client->post('https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent', [
            'query' => ['key' => $apiKey],
            'json' => [ 'contents' => [ ['parts' => $parts] ] ],
            'http_errors' => false,
            'timeout' => 60,
        ]);
        $status = $res->getStatusCode();
        $body = json_decode((string)$res->getBody(), true);
        if ($status >= 400) {
            $err = $body['error']['message'] ?? 'Gemini API error';
            throw new \RuntimeException($err);
        }
        return $body['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }
}

