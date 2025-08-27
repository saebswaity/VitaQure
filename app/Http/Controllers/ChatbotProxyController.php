<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ChatbotProxyController extends Controller
{
    public function handle(Request $request, string $path = null)
    {
        $baseUrl = 'http://127.0.0.1:5001/';

        $targetPath = $path ? ltrim($path, '/') : '';
        $query = $request->getQueryString();
        $targetUrl = rtrim($baseUrl, '/') . '/' . $targetPath . ($query ? ('?' . $query) : '');

        $client = new Client(['http_errors' => false, 'timeout' => 30]);
        $method = strtoupper($request->getMethod());
        $options = ['headers' => $this->filterHeaders($request->headers->all())];
        if (!in_array($method, ['GET','HEAD'])) {
            $options['body'] = $request->getContent();
        }

        try {
            $resp = $client->request($method, $targetUrl, $options);
            $headers = [];
            foreach ($resp->getHeaders() as $name => $values) {
                if (in_array(strtolower($name), ['transfer-encoding','content-encoding','connection','keep-alive'])) continue;
                $headers[$name] = implode(', ', $values);
            }
            return response($resp->getBody()->getContents(), $resp->getStatusCode(), $headers);
        } catch (\Throwable $e) {
            Log::error('Chatbot proxy error: '.$e->getMessage());
            return response('Chatbot service unavailable', SymfonyResponse::HTTP_BAD_GATEWAY);
        }
    }

    private function filterHeaders(array $headers): array
    {
        $forward = [];
        foreach ($headers as $name => $values) {
            $lname = strtolower($name);
            if (in_array($lname, ['host','content-length'])) continue;
            $forward[$name] = implode(', ', $values);
        }
        return $forward;
    }
}

