<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f8faf9;
            margin: 0;
            padding: 0;
            color: #2d3748;
            line-height: 1.6;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 2rem auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .email-header {
            background-color: #2d5a3d;
            color: #ffffff;
            padding: 2rem;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .email-body {
            padding: 2rem;
        }
        .email-body h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin-top: 0;
            margin-bottom: 1rem;
        }
        .email-body p {
            color: #4a5568;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        .info-box {
            background-color: #f0f7f2;
            border: 1px solid #c8e6c9;
            border-radius: 6px;
            padding: 1rem;
            margin: 1.5rem 0;
        }
        .info-box p {
            margin: 0.25rem 0;
            font-size: 0.9rem;
        }
        .info-box strong {
            color: #2d3748;
        }
        .btn-login {
            display: inline-block;
            background-color: #2d5a3d;
            color: #ffffff;
            padding: 0.75rem 2rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            margin-top: 1rem;
            text-align: center;
        }
        .email-footer {
            background-color: #f8faf9;
            padding: 1.5rem 2rem;
            text-align: center;
            border-top: 1px solid #e2e8e4;
        }
        .email-footer p {
            font-size: 0.8rem;
            color: #706f6c;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>Studyhive</h1>
        </div>
        <div class="email-body">
            <h2>Account Approved!</h2>
            <p>Hi {{ $user->first_name }} {{ $user->last_name }},</p>
            <p>Great news! Your Studyhive account has been approved by the administrator. You can now log in and start using the platform.</p>

            <div class="info-box">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
            </div>

            <p style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn-login">Log In Now</a>
            </p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Studyhive | CSS NC II Training Program</p>
        </div>
    </div>
</body>
</html>
