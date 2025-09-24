<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class NetworkSpeedTestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active']);
    }

    public function index()
    {
        return view('speedtest.index');
    }

    public function ping(Request $request)
    {
        $host = $request->query('host');
        $clientIp = $request->ip();
        $serverIp = $request->server('SERVER_ADDR');
        if (!$serverIp) {
            $serverIp = @gethostbyname(gethostname());
        }

        // Allow limited external target hosts for server-side ICMP: 8.8.8.8 and 103.21.205.66
        if (in_array($host, ['8.8.8.8', '103.21.205.66'], true)) {
            try {
                if (!function_exists('shell_exec')) {
                    return response()->json([
                        'host' => $host,
                        'error' => 'shell_exec disabled on server',
                        'client_ip' => $clientIp,
                        'server_ip' => $serverIp,
                        'target_host' => $host,
                        'target_ip' => $host,
                        'timestamp' => (int) round(microtime(true) * 1000),
                    ], 200, $this->corsHeaders($request));
                }

                $avgLatencyMs = $this->pingHost($host, 4, 1000);
                return response()->json([
                    'host' => $host,
                    'avg_ms' => $avgLatencyMs,
                    'client_ip' => $clientIp,
                    'server_ip' => $serverIp,
                    'target_host' => $host,
                    'target_ip' => $host,
                    'timestamp' => (int) round(microtime(true) * 1000),
                ], 200, $this->corsHeaders($request));
            } catch (\Throwable $e) {
                return response()->json([
                    'host' => $host,
                    'error' => 'ping not available: ' . $e->getMessage(),
                    'client_ip' => $clientIp,
                    'server_ip' => $serverIp,
                    'target_host' => $host,
                    'target_ip' => $host,
                    'timestamp' => (int) round(microtime(true) * 1000),
                ], 200, $this->corsHeaders($request));
            }
        }

        $targetHost = $request->getHost();
        $targetIp = @gethostbyname($targetHost);
        return response()->json([
            'timestamp' => (int) round(microtime(true) * 1000),
            'client_ip' => $clientIp,
            'server_ip' => $serverIp,
            'target_host' => $targetHost,
            'target_ip' => $targetIp,
        ], 200, $this->corsHeaders($request));
    }

    private function pingHost($host, $count = 4, $timeoutMs = 1000)
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        if ($isWindows) {
            $cmd = sprintf('ping -n %d -w %d %s', $count, $timeoutMs, escapeshellarg($host));
        } else {
            $timeoutSec = max(1, (int) ceil($timeoutMs / 1000));
            $cmd = sprintf('ping -c %d -W %d %s', $count, $timeoutSec, escapeshellarg($host));
        }

        $output = @shell_exec($cmd);
        if (!$output) {
            return null;
        }

        // Try parse Windows: Average = 12ms (may appear in different locales; also check Averag(e|e) and Rata-rata)
        if ($isWindows) {
            if (preg_match('/Average\s*=\s*(\d+)ms/i', $output, $m)) {
                return (int) $m[1];
            }
            if (preg_match('/Rata-rata\s*=\s*(\d+)ms/i', $output, $m)) {
                return (int) $m[1];
            }
        } else {
            // Linux/macOS: rtt min/avg/max/mdev = 11.123/12.345/...
            if (preg_match('/=\s*([\d\.]+)\/([\d\.]+)\/([\d\.]+)/', $output, $m)) {
                return (int) round((float) $m[2]);
            }
        }

        return null;
    }

    public function download(Request $request)
    {
        $sizeKb = (int) $request->query('size_kb', 1024); // default 1 MB
        $sizeKb = max(64, min($sizeKb, 8192)); // clamp between 64KB and 8MB

        $bytes = $sizeKb * 1024;
        $random = random_bytes(1024);

        $chunks = intdiv($bytes, 1024);
        $remainder = $bytes % 1024;

        $content = '';
        for ($i = 0; $i < $chunks; $i++) {
            $content .= $random;
        }
        if ($remainder > 0) {
            $content .= substr($random, 0, $remainder);
        }

        $resp = response($content, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => strlen($content),
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
        foreach ($this->corsHeaders($request) as $k => $v) {
            $resp->headers->set($k, $v);
        }
        return $resp;
    }

    public function upload(Request $request)
    {
        $start = microtime(true);

        // Read raw body to avoid multipart overhead
        $rawContent = $request->getContent();
        $bytes = strlen($rawContent);

        $elapsedMs = (int) round((microtime(true) - $start) * 1000);

        return response()->json([
            'received_bytes' => $bytes,
            'elapsed_ms' => $elapsedMs,
            'timestamp' => (int) round(microtime(true) * 1000),
        ])->withHeaders($this->corsHeaders($request));
    }

    public function optionsUpload(Request $request)
    {
        return response('', 204, $this->corsHeaders($request));
    }

    private function corsHeaders(Request $request)
    {
        $origin = $request->headers->get('Origin', '*');
        return [
            'Access-Control-Allow-Origin' => $origin,
            'Vary' => 'Origin',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-CSRF-TOKEN',
            'Access-Control-Max-Age' => '86400',
        ];
    }
}


