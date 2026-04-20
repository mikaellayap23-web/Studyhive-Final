<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Send Bulk Email - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user_management.css') }}">
    <style>
        .recipient-info {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        .recipient-info strong {
            color: #2d3748;
        }
        .help-text {
            color: #718096;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .email-inputs {
            display: none;
            margin-top: 0.75rem;
        }
        .email-inputs.show {
            display: block;
        }
        .email-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #e0f2fe;
            color: #0369a1;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            margin: 0.25rem;
            font-size: 0.875rem;
        }
        .email-tag button {
            background: none;
            border: none;
            color: #0369a1;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <x-sidebar />

    <div class="main-content">
        <header>
            <div class="container">
                <div class="header-content">
                    <h1 class="page-title">Bulk Email</h1>
                </div>
            </div>
        </header>

        <main>
            <div class="container">
                <div class="card" style="max-width: 800px; margin: 0 auto;">
                    <div class="card-header">
                        <h2>Send Email to Multiple Recipients</h2>
                        <p style="color: #64748b; margin-top: 0.25rem;">Compose and send an email to selected users or groups.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success" style="margin: 1rem;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.bulk-email.send') }}" style="padding: 1.5rem;">
                        @csrf

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Send To</label>
                            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="radio" name="recipient_type" value="all" {{ old('recipient_type', 'all') == 'all' ? 'checked' : '' }} onchange="toggleCustomEmails()">
                                    All Users ({{ $recipientCounts['all'] }})
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="radio" name="recipient_type" value="admin" {{ old('recipient_type') == 'admin' ? 'checked' : '' }} onchange="toggleCustomEmails()">
                                    Admins ({{ $recipientCounts['admin'] }})
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="radio" name="recipient_type" value="teacher" {{ old('recipient_type') == 'teacher' ? 'checked' : '' }} onchange="toggleCustomEmails()">
                                    Teachers ({{ $recipientCounts['teacher'] }})
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="radio" name="recipient_type" value="student" {{ old('recipient_type') == 'student' ? 'checked' : '' }} onchange="toggleCustomEmails()">
                                    Students ({{ $recipientCounts['student'] }})
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="radio" name="recipient_type" value="custom" {{ old('recipient_type') == 'custom' ? 'checked' : '' }} onchange="toggleCustomEmails()">
                                    Custom List
                                </label>
                            </div>

                            <div id="custom-emails" class="email-inputs {{ old('recipient_type') == 'custom' ? 'show' : '' }}">
                                <label style="font-size: 0.875rem; color: #4a5568;">Enter email addresses (one per line):</label>
                                <textarea name="custom_emails" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit;" placeholder="email1@example.com&#10;email2@example.com">{{ old('custom_emails') }}</textarea>
                                <p class="help-text">If custom emails are provided, they will override the role selection above.</p>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label for="subject" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Subject</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit;" placeholder="Email subject">
                            @error('subject')
                                <p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label for="message" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Message</label>
                            <textarea id="message" name="message" rows="8" required style="width: 100%; padding: 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; resize: vertical;" placeholder="Write your message here...">{{ old('message') }}</textarea>
                            @error('message')
                                <p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to send this email to the selected recipients?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                    <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                                </svg>
                                Send Bulk Email
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleCustomEmails() {
            const selected = document.querySelector('input[name="recipient_type"]:checked').value;
            const customDiv = document.getElementById('custom-emails');
            if (selected === 'custom') {
                customDiv.classList.add('show');
            } else {
                customDiv.classList.remove('show');
            }
        }
    </script>
</body>
</html>