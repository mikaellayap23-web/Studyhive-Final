<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Assessment - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
    <style>
        .question-builder {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .question-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .option-row {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .option-row input[type="radio"] {
            width: auto;
            margin-right: 0.5rem;
        }
        .btn-add {
            background: #10b981;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-add:hover {
            background: #059669;
        }
        .btn-remove {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-remove:hover {
            background: #dc2626;
        }
        #questions-json {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header>
            <div class="container">
                <div class="header-content">
                    <h1 class="page-title">Create Assessment</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                <div class="page-header">
                    <h1>Create New Assessment</h1>
                    <a href="{{ route('assessments.index') }}" class="btn btn-secondary">Back to Assessments</a>
                </div>

                <div class="card" style="background: white; border: 1px solid #e2e8e4; border-radius: 8px; padding: 1.5rem;">
                    <form action="{{ route('assessments.store') }}" method="POST" id="assessment-form">
                        @csrf

                        <div class="form-group">
                            <label for="module_id">Select Module *</label>
                            <select id="module_id" name="module_id" required>
                                <option value="">-- Select Module --</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                        {{ $module->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="title">Assessment Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="form-row" style="gap: 0.5rem;">
                                <div class="form-group">
                                    <label for="duration_minutes">Duration (minutes) *</label>
                                    <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="1" required>
                                </div>

                                <div class="form-group">
                                    <label for="passing_score">Passing Score (%) *</label>
                                    <input type="number" id="passing_score" name="passing_score" value="{{ old('passing_score', 75) }}" min="0" max="100" required>
                                </div>

                                <div class="form-group">
                                    <label for="max_attempts">Max Attempts (0 = unlimited) *</label>
                                    <input type="number" id="max_attempts" name="max_attempts" value="{{ old('max_attempts', 1) }}" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Questions *</label>
                            <div id="questions-container">
                                <!-- Questions will be added here -->
                            </div>
                            <button type="button" class="btn-add" onclick="addQuestion()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                Add Question
                            </button>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label" style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                Publish Assessment (make available to students)
                            </label>
                        </div>

                        <!-- Hidden field to store questions as JSON -->
                        <input type="hidden" name="questions" id="questions-json" required>

                        <div class="form-actions" style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('assessments.index') }}'">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Assessment</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        let questionCount = 0;

        function addQuestion() {
            questionCount++;
            const container = document.getElementById('questions-container');
            const questionHtml = `
                <div class="question-item" id="question-${questionCount}">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4 style="margin: 0;">Question ${questionCount}</h4>
                        <button type="button" class="btn-remove" onclick="removeQuestion(${questionCount})">Remove</button>
                    </div>
                    <div class="form-group">
                        <label>Question Text *</label>
                        <input type="text" class="question-text" placeholder="Enter your question" required>
                    </div>
                    <div class="form-group">
                        <label>Points *</label>
                        <input type="number" class="question-points" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Options (select the correct answer) *</label>
                        <div class="options-container">
                            <div class="option-row">
                                <input type="radio" name="correct-${questionCount}" value="0" required>
                                <input type="text" class="option-text" placeholder="Option A" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="correct-${questionCount}" value="1">
                                <input type="text" class="option-text" placeholder="Option B" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="correct-${questionCount}" value="2">
                                <input type="text" class="option-text" placeholder="Option C" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="correct-${questionCount}" value="3">
                                <input type="text" class="option-text" placeholder="Option D" required>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', questionHtml);
        }

        function removeQuestion(id) {
            document.getElementById(`question-${id}`).remove();
        }

        // Collect questions and submit as JSON
        document.getElementById('assessment-form').addEventListener('submit', function(e) {
            const questions = [];
            const questionItems = document.querySelectorAll('.question-item');
            
            questionItems.forEach((item, index) => {
                const questionText = item.querySelector('.question-text').value;
                const points = parseInt(item.querySelector('.question-points').value);
                const options = [];
                const optionInputs = item.querySelectorAll('.option-text');
                const correctRadio = item.querySelector('input[type="radio"]:checked');
                
                optionInputs.forEach((opt, i) => {
                    options.push(opt.value);
                });
                
                if (!correctRadio) {
                    alert(`Please select the correct answer for question ${index + 1}`);
                    e.preventDefault();
                    return;
                }
                
                questions.push({
                    id: `question-${index + 1}`,
                    text: questionText,
                    points: points,
                    options: options,
                    correct_answer: correctRadio.value
                });
            });
            
            if (questions.length === 0) {
                alert('Please add at least one question');
                e.preventDefault();
                return;
            }
            
            document.getElementById('questions-json').value = JSON.stringify(questions);
        });

        // Add first question by default
        addQuestion();
    </script>
</body>
</html>
