<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #1a1a1a;
        }
        .certificate {
            width: 297mm;
            height: 210mm;
            position: relative;
            padding: 20mm 25mm;
            box-sizing: border-box;
        }
        .border-outer {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 3px solid #2d5a3d;
        }
        .border-inner {
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            border: 1px solid #2d5a3d;
        }
        .corner-accent {
            position: absolute;
            width: 30px;
            height: 30px;
            border-color: #c7a252;
            border-style: solid;
        }
        .corner-tl { top: 14mm; left: 14mm; border-width: 3px 0 0 3px; }
        .corner-tr { top: 14mm; right: 14mm; border-width: 3px 3px 0 0; }
        .corner-bl { bottom: 14mm; left: 14mm; border-width: 0 0 3px 3px; }
        .corner-br { bottom: 14mm; right: 14mm; border-width: 0 3px 3px 0; }
        .header {
            text-align: center;
            margin-bottom: 5mm;
        }
        .brand {
            font-size: 14px;
            color: #2d5a3d;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 2mm;
        }
        .subtitle {
            font-size: 11px;
            color: #706f6c;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .title {
            text-align: center;
            margin: 8mm 0 5mm;
        }
        .title h1 {
            font-size: 36px;
            color: #2d5a3d;
            font-weight: normal;
            margin: 0;
            letter-spacing: 3px;
        }
        .gold-line {
            width: 80mm;
            height: 2px;
            background: #c7a252;
            margin: 5mm auto;
        }
        .body {
            text-align: center;
            margin: 5mm 20mm;
        }
        .presented-to {
            font-size: 12px;
            color: #706f6c;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 3mm;
        }
        .student-name {
            font-size: 32px;
            color: #1a1a1a;
            font-weight: bold;
            margin-bottom: 5mm;
            padding-bottom: 3mm;
            border-bottom: 2px solid #c7a252;
            display: inline-block;
        }
        .description {
            font-size: 13px;
            color: #4a5568;
            line-height: 1.8;
            margin: 5mm 15mm;
        }
        .module-name {
            font-weight: bold;
            color: #2d5a3d;
        }
        .score-badge {
            display: inline-block;
            background: #2d5a3d;
            color: #ffffff;
            padding: 2mm 5mm;
            border-radius: 3px;
            font-size: 11px;
            margin-top: 3mm;
        }
        .footer {
            position: absolute;
            bottom: 22mm;
            left: 30mm;
            right: 30mm;
        }
        .signatures {
            width: 100%;
            border-collapse: collapse;
        }
        .signatures td {
            width: 33%;
            text-align: center;
            padding-top: 10mm;
            vertical-align: bottom;
        }
        .sig-line {
            width: 50mm;
            border-top: 1px solid #1a1a1a;
            margin: 0 auto 2mm;
        }
        .sig-name {
            font-size: 11px;
            font-weight: bold;
        }
        .sig-title {
            font-size: 9px;
            color: #706f6c;
        }
        .cert-info {
            position: absolute;
            bottom: 15mm;
            left: 30mm;
            right: 30mm;
            text-align: center;
            font-size: 8px;
            color: #a0a0a0;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-outer"></div>
        <div class="border-inner"></div>
        <div class="corner-accent corner-tl"></div>
        <div class="corner-accent corner-tr"></div>
        <div class="corner-accent corner-bl"></div>
        <div class="corner-accent corner-br"></div>

        <div class="header">
            <div class="brand">Studyhive</div>
            <div class="subtitle">CSS NC II Training Program</div>
        </div>

        <div class="title">
            <h1>CERTIFICATE OF COMPLETION</h1>
        </div>

        <div class="gold-line"></div>

        <div class="body">
            <div class="presented-to">This is proudly presented to</div>
            <div class="student-name">{{ $student_name }}</div>
            <div class="description">
                for successfully completing the module
                <span class="module-name">"{{ $module_title }}"</span>
                in the CSS NC II Training Program.
            </div>
            @if($assessment_score)
                <div class="score-badge">Assessment Score: {{ $assessment_score }}%</div>
            @endif
        </div>

        <div class="footer">
            <table class="signatures">
                <tr>
                    <td>
                        @if($teacher_name)
                            <div class="sig-line"></div>
                            <div class="sig-name">{{ $teacher_name }}</div>
                            <div class="sig-title">Instructor / Trainer</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 11px; color: #706f6c;">{{ $issue_date }}</div>
                        <div style="font-size: 9px; color: #a0a0a0; margin-top: 1mm;">Date Issued</div>
                    </td>
                    <td>
                        <div class="sig-line"></div>
                        <div class="sig-name">School Administrator</div>
                        <div class="sig-title">Studyhive Administration</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="cert-info">
            Certificate No: {{ $certificate_number }} | Verify at: {{ $verify_url }}
        </div>
    </div>
</body>
</html>
