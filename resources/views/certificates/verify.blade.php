<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Certificate - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8faf9; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; }
        .container { max-width: 520px; width: 100%; }
        .brand { text-align: center; margin-bottom: 2rem; }
        .brand h1 { font-size: 1.75rem; color: #2d5a3d; }
        .brand p { color: #706f6c; font-size: 0.9rem; margin-top: 0.25rem; }
        .card { background: #fff; border: 1px solid #e2e8e4; border-radius: 12px; padding: 2rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 500; color: #4a5568; margin-bottom: 0.375rem; }
        .form-group input { width: 100%; padding: 0.625rem 0.875rem; border: 1px solid #dfe3e8; border-radius: 8px; font-size: 0.9rem; font-family: monospace; }
        .btn { width: 100%; padding: 0.625rem; background: #2d5a3d; color: #fff; border: none; border-radius: 8px; font-size: 0.9rem; font-weight: 500; cursor: pointer; font-family: inherit; }
        .btn:hover { background: #234a31; }
        .result { margin-top: 1.5rem; padding: 1.25rem; border-radius: 8px; }
        .result-valid { background: #e8f5e9; border: 1px solid #a5d6a7; }
        .result-invalid { background: #fef2f2; border: 1px solid #fca5a5; }
        .result h3 { font-size: 1rem; margin-bottom: 0.75rem; }
        .result-valid h3 { color: #2d5a3d; }
        .result-invalid h3 { color: #dc2626; }
        .detail { display: flex; justify-content: space-between; padding: 0.375rem 0; font-size: 0.85rem; border-bottom: 1px solid rgba(0,0,0,0.05); }
        .detail:last-child { border-bottom: none; }
        .detail-label { color: #706f6c; }
        .detail-value { color: #2d3748; font-weight: 500; }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand">
            <h1>Studyhive</h1>
            <p>Certificate Verification</p>
        </div>

        <div class="card">
            <form method="GET" action="{{ route('certificates.verify') }}">
                <div class="form-group">
                    <label for="certificate_number">Certificate Number</label>
                    <input type="text" id="certificate_number" name="certificate_number" placeholder="e.g. CERT-2026-ABC123" value="{{ request('certificate_number') }}" required>
                </div>
                <button type="submit" class="btn">Verify Certificate</button>
            </form>

            @if($searched)
                @if($certificate)
                    <div class="result result-valid">
                        <h3>&#10003; Certificate is Valid</h3>
                        <div class="detail">
                            <span class="detail-label">Certificate No.</span>
                            <span class="detail-value">{{ $certificate->certificate_number }}</span>
                        </div>
                        <div class="detail">
                            <span class="detail-label">Student</span>
                            <span class="detail-value">{{ $certificate->user->first_name }} {{ $certificate->user->last_name }}</span>
                        </div>
                        <div class="detail">
                            <span class="detail-label">Module</span>
                            <span class="detail-value">{{ $certificate->module->title }}</span>
                        </div>
                        <div class="detail">
                            <span class="detail-label">Issue Date</span>
                            <span class="detail-value">{{ $certificate->issue_date->format('M d, Y') }}</span>
                        </div>
                        <div class="detail">
                            <span class="detail-label">Status</span>
                            <span class="detail-value" style="color: #16a34a;">Issued</span>
                        </div>
                    </div>
                @else
                    <div class="result result-invalid">
                        <h3>&#10007; Certificate Not Found</h3>
                        <p style="font-size: 0.85rem; color: #706f6c;">No valid certificate was found with that number. Please check the number and try again.</p>
                    </div>
                @endif
            @endif
        </div>

        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.8rem; color: #a0a0a0;">
            CSS NC II Training Program
        </p>
    </div>
</body>
</html>
