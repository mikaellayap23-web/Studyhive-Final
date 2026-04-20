<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Module - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
    <style>
        .assessment-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        .assessment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .assessment-header h3 {
            margin: 0;
            color: #2d3748;
        }
        .toggle-assessment {
            background: #2d5a3d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .toggle-assessment:hover {
            background: #234a31;
        }
        .assessment-fields {
            display: none;
        }
        .assessment-fields.active {
            display: block;
        }
        .question-builder {
            background: white;
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
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .checkbox-wrapper input[type="checkbox"] {
            width: auto;
        }
        .existing-assessment-info {
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .existing-assessment-info svg {
            vertical-align: middle;
            margin-right: 0.5rem;
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
                    <h1 class="page-title">Edit Module</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                <div class="page-header">
                    <h1>Edit Module</h1>
                    <a href="{{ route('modules.index') }}" class="btn btn-secondary">Back to Modules</a>
                </div>

                <div class="card" style="background: white; border: 1px solid #e2e8e4; border-radius: 8px; padding: 1.5rem;">
                    <form action="{{ route('modules.update', $module->id) }}" method="POST" enctype="multipart/form-data" id="module-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="save_type" id="save_type" value="module">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="title">Module Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $module->title) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="order">Order *</label>
                                <input type="number" id="order" name="order" value="{{ old('order', $module->order) }}" min="0" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3">{{ old('description', $module->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Module Image</label>
                            <p style="font-size: 0.8rem; color: #706f6c; margin-bottom: 0.5rem;">Leave empty to keep current image. JPG, PNG, or WebP (max 5MB)</p>
                            @if($module->image_path)
                                <div style="margin-bottom: 0.75rem;">
                                    <img src="{{ asset('storage/' . $module->image_path) }}" alt="Current Image" style="max-width: 100%; max-height: 200px; border-radius: 6px; object-fit: cover;">
                                    <p style="font-size: 0.75rem; color: #706f6c; margin-top: 0.25rem;">Current image</p>
                                </div>
                            @endif
                            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" onchange="previewImage(this, 'imagePreview')">
                            <div id="imagePreview" style="margin-top: 0.75rem; display: none;">
                                <img src="" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 6px; object-fit: cover;">
                            </div>
                            @error('image')
                                <p class="error-text" style="color: #991b1b; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        @if(auth()->user()->role === 'admin')
                            <div class="form-group">
                                <label for="assigned_teacher_id">Assign to Teacher</label>
                                <select id="assigned_teacher_id" name="assigned_teacher_id">
                                    <option value="">-- Select Teacher --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('assigned_teacher_id', $module->assigned_teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->username }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Current File</label>
                            @if($module->file_path)
                                <div style="padding: 0.75rem; background: #f8faf9; border-radius: 6px; margin-bottom: 0.75rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                        <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                                        <polyline points="13 2 13 9 20 9"/>
                                    </svg>
                                    <span>{{ basename($module->file_path) }}</span>
                                </div>
                            @endif
                            <label>Upload New File (Optional)</label>
                            <div class="file-upload">
                                <label class="file-upload-label" for="file">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    <span>Click to upload or drag and drop</span>
                                    <span style="font-size: 0.75rem;">PDF (MAX. 100MB)</span>
                                </label>
                                <input type="file" id="file" name="file" accept=".pdf">
                            </div>
                            @error('file')
                                <p class="error-text" style="color: #991b1b; font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <option value="draft" {{ old('status', $module->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $module->status) === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                    <!-- Assessment Section (INSIDE THE FORM) -->
                    <div class="assessment-section">
                        <div class="assessment-header">
                            <h3>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                @if($module->assessment)
                                    Edit Assessment
                                @else
                                    Create Assessment
                                @endif
                            </h3>
                            <button type="button" class="toggle-assessment" onclick="toggleAssessmentFields()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                @if($module->assessment)
                                    Edit Assessment
                                @else
                                    Add Assessment
                                @endif
                            </button>
                        </div>

                        @if($module->assessment)
                            <div class="existing-assessment-info">
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    Existing Assessment:
                                </strong>
                                {{ $module->assessment->title }} 
                                <span style="color: #706f6c; font-size: 0.85rem;">({{ count($module->assessment->questions) }} questions, {{ $module->assessment->duration_minutes }} min)</span>
                            </div>
                        @endif

                        <div class="assessment-fields" id="assessment-fields">
                            <input type="hidden" name="update_assessment" id="update_assessment" value="0">

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="assessment_title">Assessment Title *</label>
                                    <input type="text" id="assessment_title" name="assessment[title]" value="{{ old('assessment.title', $module->assessment->title ?? '') }}" placeholder="e.g., Module 1 Quiz">
                                </div>

                                <div class="form-group">
                                    <label for="assessment_duration">Duration (minutes) *</label>
                                    <input type="number" id="assessment_duration" name="assessment[duration_minutes]" value="{{ old('assessment.duration_minutes', $module->assessment->duration_minutes ?? 60) }}" min="1">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="assessment_passing">Passing Score (%) *</label>
                                    <input type="number" id="assessment_passing" name="assessment[passing_score]" value="{{ old('assessment.passing_score', $module->assessment->passing_score ?? 75) }}" min="0" max="100">
                                </div>

                                <div class="form-group">
                                    <label for="assessment_attempts">Max Attempts (0 = unlimited) *</label>
                                    <input type="number" id="assessment_attempts" name="assessment[max_attempts]" value="{{ old('assessment.max_attempts', $module->assessment->max_attempts ?? 1) }}" min="0">
                                </div>
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
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" name="assessment[is_published]" value="1" {{ old('assessment.is_published', $module->assessment->is_published ?? false) ? 'checked' : '' }}>
                                    Publish Assessment (make available to students)
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" name="assessment[show_correct_answer]" value="1" {{ old('assessment.show_correct_answer', $module->assessment->show_correct_answer ?? false) ? 'checked' : '' }}>
                                    Show Correct Answers (students will see correct answers after they use all attempts)
                                </label>
                            </div>

                            <!-- Hidden field to store questions as JSON -->
                            <input type="hidden" name="assessment[questions]" id="questions-json">
                        </div>
                    </div>

                        <div class="form-group" style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                            <button type="submit" class="btn btn-primary" onclick="document.getElementById('save_type').value = 'module'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                Save Module
                            </button>
                            <button type="submit" class="btn btn-success" onclick="document.getElementById('save_type').value = 'assessment'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.5rem;">
                                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                Save Assessment
                            </button>
                            <a href="{{ route('modules.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        let questionCount = 0;
        const existingQuestions = @json($module->assessment->questions ?? []);

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const img = preview.querySelector('img');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function toggleAssessmentFields() {
            const fields = document.getElementById('assessment-fields');
            const toggleBtn = document.querySelector('.toggle-assessment');
            const updateInput = document.getElementById('update_assessment');
            
            fields.classList.toggle('active');
            updateInput.value = fields.classList.contains('active') ? '1' : '0';
            
            if (fields.classList.contains('active')) {
                toggleBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                    Cancel
                `;
                if (questionCount === 0 && existingQuestions.length > 0) {
                    // Load existing questions
                    existingQuestions.forEach((q, index) => {
                        loadExistingQuestion(q);
                    });
                } else if (questionCount === 0) {
                    addQuestion();
                }
            } else {
                toggleBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    @if($module->assessment)
                        Edit Assessment
                    @else
                        Add Assessment
                    @endif
                `;
            }
        }

        function loadExistingQuestion(question) {
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
                        <input type="text" class="question-text" value="{{ old('questions.${questionCount}.text', '') }}" placeholder="Enter your question" required>
                    </div>
                    <div class="form-group">
                        <label>Points *</label>
                        <input type="number" class="question-points" value="{{ old('questions.${questionCount}.points', 1) }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Options (select the correct answer) *</label>
                        <div class="options-container">
                            <div class="option-row">
                                <input type="radio" name="correct-${questionCount}" value="0" ${parseInt(question.correct_answer) === 0 ? 'checked' : ''} required>
                                <input type="text" class="option-text" value="{{ old('questions.${questionCount}.options.0', '') }}" placeholder="Option A" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="correct-${questionCount}" value="1" ${parseInt(question.correct_answer) === 1 ? 'checked' : ''}>
                                <input type="text" class="option-text" value="{{ old('questions.${questionCount}.options.1', '') }}" placeholder="Option B" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="correct-${questionCount}" value="2" ${parseInt(question.correct_answer) === 2 ? 'checked' : ''}>
                                <input type="text" class="option-text" value="{{ old('questions.${questionCount}.options.2', '') }}" placeholder="Option C" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="correct-${questionCount}" value="3" ${parseInt(question.correct_answer) === 3 ? 'checked' : ''}>
                                <input type="text" class="option-text" value="{{ old('questions.${questionCount}.options.3', '') }}" placeholder="Option D" required>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', questionHtml);
            
            // Set values after inserting
            const lastQuestion = container.querySelector(`#question-${questionCount}`);
            lastQuestion.querySelector('.question-text').value = question.text;
            lastQuestion.querySelector('.question-points').value = question.points;
            question.options.forEach((opt, i) => {
                const optionInputs = lastQuestion.querySelectorAll('.option-text');
                if (optionInputs[i]) {
                    optionInputs[i].value = opt;
                }
            });
        }

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
        document.getElementById('module-form').addEventListener('submit', function(e) {
            const saveType = document.getElementById('save_type').value;
            
            if (saveType === 'assessment') {
                document.getElementById('update_assessment').value = '1';
                
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
            } else {
                document.getElementById('update_assessment').value = '0';
            }
        });
    </script>
</body>
</html>
