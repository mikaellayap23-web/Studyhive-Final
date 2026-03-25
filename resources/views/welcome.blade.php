<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8faf9;
            color: #2d3748;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Header */
        header {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8e4;
            padding: 1.25rem 0;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d5a3d;
        }

        .auth-links {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.625rem 1.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .btn-login {
            background-color: transparent;
            color: #2d5a3d;
            border: 1px solid #2d5a3d;
        }

        .btn-login:hover {
            background-color: #f0f7f2;
        }

        .btn-register {
            background-color: #2d5a3d;
            color: #ffffff;
            border: 1px solid #2d5a3d;
        }

        .btn-register:hover {
            background-color: #234a31;
        }

        /* Hero Section */
        .hero {
            padding: 4rem 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.75rem;
            font-weight: 700;
            color: #2d5a3d;
            margin-bottom: 0.5rem;
        }

        .hero .subtitle {
            font-size: 1.1rem;
            color: #4a7c59;
            font-weight: 600;
            margin-bottom: 1.25rem;
        }

        .hero .description {
            max-width: 680px;
            margin: 0 auto 2rem;
            color: #4a5568;
            font-size: 1rem;
            line-height: 1.7;
        }

        /* Features Section */
        .features {
            background-color: #ffffff;
            padding: 4rem 0;
            border-top: 1px solid #e2e8e4;
            border-bottom: 1px solid #e2e8e4;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            padding: 1.5rem;
            text-align: left;
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background-color: #e8f5e9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: #2d5a3d;
        }

        .feature-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d5a3d;
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            color: #4a5568;
            font-size: 0.95rem;
            line-height: 1.7;
        }

        /* Footer */
        footer {
            background-color: #2d5a3d;
            color: #ffffff;
            padding: 2rem 0;
            text-align: center;
        }

        footer p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .footer-accent {
            color: #f4e89c;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero .subtitle {
                font-size: 1.1rem;
            }

            .container {
                padding: 0 1.25rem;
            }

            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-hero {
                width: 100%;
                max-width: 280px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Studyhive</div>
                <nav class="auth-links">
                    <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-register">Register</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Studyhive</h1>
            <p class="subtitle">Computer Servicing System NC II (CSS NC II)</p>
            <p class="description">
                The Computer Systems Servicing NC II is a TESDA-accredited program that equips
                learners with essential competencies in computer hardware and software maintenance.
                This course covers system installation, network setup, troubleshooting, and
                preventive maintenance. Ideal for individuals seeking a career in IT support
                and computer systems maintenance.
            </p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                    </div>
                    <h3>Hardware Servicing</h3>
                    <p>Learn to assemble, disassemble, and maintain computer hardware components following industry standards and safety protocols.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <h3>System Installation</h3>
                    <p>Master the installation and configuration of operating systems, drivers, and essential software applications.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="12" x="3" y="3" rx="2"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
                    </div>
                    <h3>Network Configuration</h3>
                    <p>Gain skills in setting up and troubleshooting local area networks (LAN) and internet connectivity.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    </div>
                    <h3>Troubleshooting</h3>
                    <p>Develop diagnostic and problem-solving skills for identifying and resolving hardware and software issues.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Studyhive <span class="footer-accent">|</span> CSS NC II Training Program</p>
        </div>
    </footer>
</body>
</html>
