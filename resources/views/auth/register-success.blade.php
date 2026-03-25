<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Successful - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <a href="{{ route('welcome') }}" class="logo">Studyhive</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="auth-card">
                <div class="auth-header">
                    <div style="width: 64px; height: 64px; background-color: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2d5a3d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
                    </div>
                    <h1>Registration Successful</h1>
                    <p>Your account has been created</p>
                </div>

                <div style="text-align: center; padding: 1rem 0;">
                    <div style="background-color: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                        <p style="color: #92400e; font-size: 0.9rem; line-height: 1.6;">
                            <strong>Account Pending Approval</strong><br>
                            Your registration is awaiting admin confirmation. You will be able to log in once your account has been approved.
                        </p>
                    </div>
                    
                    <a href="{{ route('login') }}" class="btn btn-primary" style="display: inline-block; width: auto; padding: 0.75rem 2rem;">
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Studyhive <span class="footer-accent">|</span> CSS NC II Training Program</p>
        </div>
    </footer>
</body>
</html>
