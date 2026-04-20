<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subjectLine ?? 'Bulk Email' }}</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #1f2937; max-width: 600px; margin: 0 auto; padding: 1rem; }
        .header { background: #3b82f6; color: white; padding: 1.5rem; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 1.5rem; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px; }
        .footer { margin-top: 1.5rem; font-size: 0.875rem; color: #6b7280; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 1rem; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 1.25rem;">{{ $subjectLine ?? 'Notification' }}</h1>
    </div>
    <div class="content">
        <p>Hello,</p>
        <p>{{ $messageBody }}</p>
    </div>
    <div class="footer">
        <p>Sent by {{ $senderName ?? 'Studyhive Administrator' }} via Studyhive LMS</p>
        <p><small>If you believe this email was sent in error, please contact your administrator.</small></p>
    </div>
</body>
</html>