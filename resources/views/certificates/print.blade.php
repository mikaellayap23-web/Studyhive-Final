<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate - {{ $certificate->certificate_number }} - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            primary: #3b82f6;
            text: #1e293b;
            gray: #64748b;
            border: #e2e8e0;
            success: #16a34a;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        @page {
            size: landscape;
            margin: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background: white;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .print-actions {
            position: fixed;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        .btn-secondary {
            background: #e2e8e0;
            color: #1e293b;
        }
        .certificate {
            border: 8px double #3b82f6;
            padding: 3rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            position: relative;
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 2px solid #3b82f6;
            pointer-events: none;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 0.5rem;
        }
        .title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: #64748b;
            font-size: 1rem;
        }
        .content {
            margin: 2rem 0;
        }
        .recipient {
            text-align: center;
            margin: 2rem 0;
        }
        .recipient-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .detail-item {
            padding: 1rem;
            background: white;
            border: 1px solid #e2e8e0;
            border-radius: 8px;
        }
        .detail-label {
            font-size: 0.875rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }
        .detail-value {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
        }
        .signature {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8e0;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #1e293b;
            padding-top: 0.5rem;
            margin-top: 2rem;
        }
        .signature-name {
            font-weight: 600;
            color: #1e293b;
        }
        .signature-title {
            font-size: 0.875rem;
            color: #64748b;
        }
        .footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8e0;
            font-size: 0.875rem;
            color: #64748b;
        }
        .certificate-number {
            position: absolute;
            top: 20px;
            right: 20px;
            font-family: monospace;
            font-size: 0.875rem;
            color: #64748b;
        }
        @media print {
            .print-actions {
                display: none;
            }
            body {
                padding: 0;
            }
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <a href="{{ route('certificates.show', $certificate->id) }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="certificate">
        <div class="certificate-number">{{ $certificate->certificate_number }}</div>
        
        <div class="header">
            <div class="logo">📜 Studyhive</div>
            <h1 class="title">Certificate of Completion</h1>
            <p class="subtitle">Training & Assessment Center • TESDA CSS NC II</p>
        </div>

        <div class="content">
            <p style="text-align: center; font-size: 1.125rem; color: #64748b; margin-bottom: 2rem;">
                This is to certify that
            </p>

            <div class="recipient">
                <div class="recipient-name">{{ $certificate->user->first_name }} {{ $certificate->user->last_name }}</div>
            </div>

            <p style="text-align: center; font-size: 1.125rem; color: #64748b; margin-bottom: 1rem;">
                has successfully completed the module
            </p>
            <p style="text-align: center; font-size: 1.25rem; font-weight: 600; color: #1e293b; margin-bottom: 2rem;">
                "{{ $certificate->module->title }}"
            </p>
        </div>

        <div class="details-grid">
            <div class="detail-item">
                <div class="detail-label">Certificate Number</div>
                <div class="detail-value">{{ $certificate->certificate_number }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Issue Date</div>
                <div class="detail-value">{{ $certificate->issue_date->format('F j, Y') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Assessment Score</div>
                <div class="detail-value">{{ number_format($certificate->metadata['percentage'] ?? 0, 1) }}%</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Trainer</div>
                <div class="detail-value">
                    @if($certificate->module->assignedTeacher)
                        {{ $certificate->module->assignedTeacher->first_name }} {{ $certificate->module->assignedTeacher->last_name }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>

        <div class="signature">
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-name">Assessor</div>
                    <div class="signature-title">TESDA Accredited Assessor</div>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <div class="signature-name">Training Center</div>
                    <div class="signature-title">Authorized Signature</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>This certificate is powered by Studyhive LMS • TESDA CSS NC II Program</p>
            <p style="margin-top: 0.5rem; font-size: 0.75rem;">
                Verify online at: {{ url('/') }}/certificates/verify?certificate_number={{ $certificate->certificate_number }}
            </p>
        </div>
    </div>
</body>
</html>