@extends('layout')

@section('title')
    Home
@stop

@section('top')
    @include('assets_css_1')
@stop

@section('navbar')
    @include('navbar_top')
    @include('navbar_left')
@stop

@push('style')
    <style>
        .card {
            border: 1px solid rgba(148, 163, 184, 0.24);
            border-radius: 14px;
            padding: 20px;
            max-width: 860px;
            background: linear-gradient(180deg, rgba(255, 255, 255, .04), rgba(255, 255, 255, .02));
            box-shadow: 0 12px 28px rgba(2, 6, 23, .15);
        }

        .row {
            display: flex;
            gap: 16px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .metric {
            flex: 1 1 220px;
            background: linear-gradient(180deg, rgba(56, 189, 248, 0.06), rgba(2, 6, 23, 0.02));
            border: 1px solid rgba(56, 189, 248, 0.25);
            border-radius: 12px;
            padding: 14px 14px 12px;
            text-align: center;
        }

        .metric h3 {
            margin: 0 0 8px 0;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        .metric .value {
            font-size: 32px;
            font-weight: 800;
        }

        .metric.latency {
            border-color: rgba(34, 197, 94, .35);
            background: linear-gradient(180deg, rgba(34, 197, 94, .10), rgba(2, 6, 23, 0.02));
        }

        .metric.download {
            border-color: rgba(59, 130, 246, .35);
            background: linear-gradient(180deg, rgba(59, 130, 246, .10), rgba(2, 6, 23, 0.02));
        }

        .metric.upload {
            border-color: rgba(245, 158, 11, .35);
            background: linear-gradient(180deg, rgba(245, 158, 11, .10), rgba(2, 6, 23, 0.02));
        }

        button {
            padding: 10px 16px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #06121f;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(34, 197, 94, .35);
            transition: transform .08s ease;
        }

        button:active {
            transform: translateY(1px);
        }

        button:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        small {
            color: #6b7280;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(2, 132, 199, .24);
            border-top-color: #38bdf8;
            border-radius: 50%;
            display: inline-block;
            animation: spin .8s linear infinite;
            vertical-align: -4px;
            margin-left: 8px;
            opacity: 0;
            transition: opacity .15s;
        }

        .spinner.show {
            opacity: 1;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .toast {
            position: fixed;
            left: 50%;
            bottom: 24px;
            transform: translateX(-50%) translateY(20px);
            background: rgba(2, 6, 23, .92);
            color: #e2e8f0;
            border: 1px solid rgba(148, 163, 184, .20);
            border-radius: 10px;
            padding: 10px 14px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .25);
            opacity: 0;
            transition: opacity .2s ease, transform .2s ease;
            z-index: 9999;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        /* Mobile tweaks */
        @media (max-width: 640px) {
            .card {
                padding: 14px;
                border-radius: 12px;
            }

            .row {
                gap: 10px;
            }

            .metric {
                flex: 1 1 100%;
                padding: 12px;
            }

            .metric h3 {
                font-size: 11px;
            }

            .metric .value {
                font-size: 26px;
            }

            #pingList,
            #pingStats,
            #pingFail {
                display: block;
                word-wrap: break-word;
                overflow-wrap: anywhere;
            }

            .spinner {
                margin-left: 6px;
            }
        }
    </style>
@endpush

@section('body')
    <div id="preloader"></div>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Network Speed Test</h1>
        </div>
    </div>

    <body>
        <div class="card">
            <p style="margin:0 0 12px 0;color:#6b7280;">Measure latency, download, and upload from your device to the
                selected target.</p>
            <div class="row" style="align-items:center;">
                <div>
                    <label for="target"><small>Target</small></label>
                    <select id="target">
                        <option value="server">This server</option>
                        <option value="google">8.8.8.8 (Google DNS)</option>
                        <option value="public103">103.21.205.66 (Public)</option>
                    </select>
                </div>
                <div>
                    <button id="run" onclick="runTest()">Run Test</button>
                    <span id="spin" class="spinner"></span>
                </div>
                <div style="margin-left:auto; color:#6b7280; font-size:12px;">
                    <span>Elapsed:</span> <strong id="elapsed">0.0 s</strong>
                </div>
            </div>
            <div class="row">
                <div class="metric latency">
                    <h3>Latency</h3>
                    <div class="value" id="latency">-</div>
                    <small>Median of 20 pings</small>
                    <div
                        style="margin-top:6px; font-size:12px; color:#6b7280; max-width:520px; margin-left:auto; margin-right:auto;">
                        <span id="pingList">&nbsp;</span>
                    </div>
                    <div
                        style="margin-top:6px; font-size:12px; color:#6b7280; max-width:520px; margin-left:auto; margin-right:auto;">
                        <span id="pingStats">Stats: -</span>
                    </div>
                    <div
                        style="margin-top:4px; font-size:12px; color:#b91c1c; max-width:520px; margin-left:auto; margin-right:auto;">
                        <span id="pingFail">Failed: 0</span>
                    </div>
                </div>
                <div class="metric download">
                    <h3>Download</h3>
                    <div class="value" id="download">-</div>
                    <small>~8 MB per iteration, ≥ 5 s (3 streams)</small>
                </div>
                <div class="metric upload">
                    <h3>Upload</h3>
                    <div class="value" id="upload">-</div>
                    <small>~2 MB per iteration, ≥ 5 s (2 streams)</small>
                </div>
            </div>
            <div style="margin-top:12px; color:#555;">
                <small id="netinfo">-</small>
            </div>
        </div>
        <div id="toast" class="toast" role="status" aria-live="polite"></div>
    </body>
@stop

@section('bottom')
    @include('assets_script_1')
@stop

@push('js')
    <script>
        function showToast(message, timeoutMs = 2500) {
            const t = document.getElementById('toast');
            if (!t) return;
            t.textContent = message;
            t.classList.add('show');
            clearTimeout(window.__toastTimer);
            window.__toastTimer = setTimeout(() => t.classList.remove('show'), timeoutMs);
        }

        function formatMbps(bitsPerSecond) {
            return (bitsPerSecond / 1_000_000).toFixed(2);
        }

        async function measurePing(url, attempts = 20, host = null) {
            let latencies = [];
            let failures = 0;
            let lastResponse = null;
            for (let i = 0; i < attempts; i++) {
                const t0 = performance.now();
                const qs = host ? ('?host=' + encodeURIComponent(host) + '&_=' + Date.now()) : ('?_=' + Date.now());
                try {
                    lastResponse = await fetch(url + qs, {
                        cache: 'no-store'
                    });
                    const t1 = performance.now();
                    latencies.push(t1 - t0);
                } catch (_) {
                    failures++;
                }
            }
            const sorted = latencies.slice().sort((a, b) => a - b);
            const median = sorted.length ? sorted[Math.floor(sorted.length / 2)] : NaN;
            const min = sorted.length ? sorted[0] : NaN;
            const max = sorted.length ? sorted[sorted.length - 1] : NaN;
            const avg = sorted.length ? (sorted.reduce((s, v) => s + v, 0) / sorted.length) : NaN;
            let jitter = NaN;
            if (latencies.length > 1) {
                let sumDiff = 0;
                for (let i = 1; i < latencies.length; i++) sumDiff += Math.abs(latencies[i] - latencies[i - 1]);
                jitter = sumDiff / (latencies.length - 1);
            }
            return {
                median,
                min,
                max,
                avg,
                jitter,
                series: latencies,
                failures,
                lastResponse
            };
        }

        async function measureDownload(url, sizeKb = 8192, durationMsMin = 5000, parallel = 3, warmupMs = 1000) {
            const warmEnd = performance.now() + warmupMs;
            while (performance.now() < warmEnd) {
                const r = await fetch(url + '?size_kb=' + sizeKb + '&_=' + Math.random(), {
                    cache: 'no-store'
                });
                await r.blob();
            }

            const start = performance.now();
            let downloadedBytes = 0;
            let samples = [];
            async function worker() {
                let local = 0;
                while (performance.now() - start < durationMsMin) {
                    const t0 = performance.now();
                    const resp = await fetch(url + '?size_kb=' + sizeKb + '&_=' + Math.random(), {
                        cache: 'no-store'
                    });
                    const blob = await resp.blob();
                    const t1 = performance.now();
                    local += blob.size;
                    samples.push({
                        bytes: blob.size,
                        ms: t1 - t0
                    });
                }
                return local;
            }
            const results = await Promise.all(Array.from({
                length: parallel
            }, () => worker()));
            for (const b of results) downloadedBytes += b;
            const elapsedSec = (performance.now() - start) / 1000;
            const rawMbps = (downloadedBytes * 8) / elapsedSec;
            const throughputs = samples.map(s => (s.bytes * 8) / (s.ms / 1000));
            throughputs.sort((a, b) => a - b);
            const drop = Math.floor(throughputs.length * 0.1);
            const trimmed = throughputs.slice(drop, throughputs.length - drop);
            const trimmedAvg = trimmed.length ? trimmed.reduce((a, v) => a + v, 0) / trimmed.length : rawMbps;
            return {
                mbps: formatMbps(trimmedAvg),
                runs: throughputs.length
            };
        }

        async function measureUpload(url, sizeKb = 2048, durationMsMin = 5000, parallel = 2, warmupMs = 1000) {
            const buffer = new Uint8Array(sizeKb * 1024);
            const quota = 65536;
            for (let offset = 0; offset < buffer.length; offset += quota) {
                const end = Math.min(offset + quota, buffer.length);
                crypto.getRandomValues(buffer.subarray(offset, end));
            }
            const body = buffer;
            const warmEndUp = performance.now() + warmupMs;
            while (performance.now() < warmEndUp) {
                await fetch(url + '?_=' + Math.random(), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/octet-stream'
                    },
                    body
                });
            }

            const start = performance.now();
            let uploadedBytes = 0;
            let samples = [];
            const tokenEl = document.querySelector('meta[name="csrf-token"]');
            const token = tokenEl ? tokenEl.getAttribute('content') : null;
            async function workerUp() {
                let local = 0;
                while (performance.now() - start < durationMsMin) {
                    const t0 = performance.now();
                    await fetch(url + '?_=' + Math.random(), {
                        method: 'POST',
                        headers: {
                            ...(token ? {
                                'X-CSRF-TOKEN': token
                            } : {}),
                            'Content-Type': 'application/octet-stream'
                        },
                        body
                    });
                    const t1 = performance.now();
                    local += body.length;
                    samples.push({
                        bytes: body.length,
                        ms: t1 - t0
                    });
                }
                return local;
            }
            const resultsUp = await Promise.all(Array.from({
                length: parallel
            }, () => workerUp()));
            for (const u of resultsUp) uploadedBytes += u;
            const elapsedSec = (performance.now() - start) / 1000;
            const rawMbps = (uploadedBytes * 8) / elapsedSec;
            const throughputs = samples.map(s => (s.bytes * 8) / (s.ms / 1000));
            throughputs.sort((a, b) => a - b);
            const drop = Math.floor(throughputs.length * 0.1);
            const trimmed = throughputs.slice(drop, throughputs.length - drop);
            const trimmedAvg = trimmed.length ? trimmed.reduce((a, v) => a + v, 0) / trimmed.length : rawMbps;
            return {
                mbps: formatMbps(trimmedAvg),
                runs: throughputs.length
            };
        }

        async function runTest() {
            const btn = document.getElementById('run');
            const spin = document.getElementById('spin');
            const elapsedEl = document.getElementById('elapsed');
            btn.disabled = true;
            btn.textContent = 'Testing...';
            if (spin) spin.classList.add('show');
            const tStart = performance.now();
            let timer = setInterval(() => {
                if (elapsedEl) elapsedEl.textContent = ((performance.now() - tStart) / 1000).toFixed(1) + ' s';
            }, 100);
            const pingEl = document.getElementById('latency');
            const downEl = document.getElementById('download');
            const upEl = document.getElementById('upload');
            const infoEl = document.getElementById('netinfo');
            const target = document.getElementById('target') ? document.getElementById('target').value : 'server';
            try {
                let pingTarget = null;
                if (target === 'google') pingTarget = '8.8.8.8';
                if (target === 'public103') pingTarget = '103.21.205.66';
                const ping = await measurePing('{{ route('network/speedtest/ping') }}', 20, pingTarget);
                pingEl.textContent = isFinite(ping.median) ? ping.median.toFixed(0) + ' ms' : '-';
                const list = document.getElementById('pingList');
                if (list) list.textContent = (ping.series || []).map(v => Math.round(v)).join(' ms, ') + ((ping
                    .series && ping.series.length) ? ' ms' : '');
                const stats = document.getElementById('pingStats');
                if (stats) stats.textContent =
                    `Stats: min ${isFinite(ping.min)?ping.min.toFixed(0):'-'} ms • avg ${isFinite(ping.avg)?ping.avg.toFixed(0):'-'} ms • max ${isFinite(ping.max)?ping.max.toFixed(0):'-'} ms • jitter ${isFinite(ping.jitter)?ping.jitter.toFixed(0):'-'} ms`;
                const fail = document.getElementById('pingFail');
                if (fail) fail.textContent = `Failed: ${ping.failures}`;
                try {
                    const meta = await ping.lastResponse.json();
                    infoEl.textContent = 'Client: ' + (meta.client_ip || '-') + ' | Server: ' + (meta.server_ip ||
                        '-') + ' | Target: ' + (meta.target_host || '-') + ' [' + (meta.target_ip || '-') + ']';
                } catch (_) {
                    infoEl.textContent = '-';
                }
                const down = await measureDownload('{{ route('network/speedtest/download') }}');
                downEl.textContent = down.mbps + ' Mbps';
                const up = await measureUpload('{{ route('network/speedtest/upload') }}');
                upEl.textContent = up.mbps + ' Mbps';
                const targetLabel = (document.querySelector('#target option:checked') || {}).text || 'This server';
                const summary =
                    `Done • ${targetLabel} — Lat ${isFinite(ping.median)?ping.median.toFixed(0):'-'} ms (jitter ${isFinite(ping.jitter)?ping.jitter.toFixed(0):'-'} ms) • Down ${down.mbps} Mbps • Up ${up.mbps} Mbps`;
                showToast(summary);
            } catch (e) {
                console.error(e);
                alert('Speed test failed. See console.');
            } finally {
                clearInterval(timer);
                if (elapsedEl) elapsedEl.textContent = ((performance.now() - tStart) / 1000).toFixed(1) + ' s';
                btn.disabled = false;
                btn.textContent = 'Run Test';
                if (spin) spin.classList.remove('show');
            }
        }
    </script>
@endpush
