<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Module - Studyhive</title>
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
                    <h1 class="page-title">Add Module</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                <div class="page-header">
                    <h1>Create New Module</h1>
                    <a href="{{ route('modules.index') }}" class="btn btn-secondary">Back to Modules</a>
                </div>

                <div class="card" style="background: white; border: 1px solid #e2e8e4; border-radius: 8px; padding: 1.5rem;">
                    <form action="{{ route('modules.store') }}" method="POST" enctype="multipart/form-data" id="module-form">
                        @csrf

                        <div class="form-row">
                            <div class="form-group">
                                <label for="title">Module Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="order">Order *</label>
                                <input type="number" id="order" name="order" value="{{ old('order', 0) }}" min="0" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Module Image *</label>
                            <p style="font-size: 0.8rem; color: #706f6c; margin-bottom: 0.5rem;">Upload a cover image for this module. JPG, PNG, or WebP (max 5MB)</p>
                            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" required onchange="previewImage(this, 'imagePreview')">
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
                                         <option value="{{ $teacher->id }}" {{ old('assigned_teacher_id') == $teacher->id ? 'selected' : '' }}>
                                             {{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->username }})
                                         </option>
                                     @endforeach
                                 </select>
                             </div>

                             <div class="form-group">
                                 <label for="prerequisite_module_id">Prerequisite Module (Optional)</label>
                                 <select id="prerequisite_module_id" name="prerequisite_module_id">
                                     <option value="">-- No Prerequisite --</option>
                                     @foreach(\App\Models\Module::where('status', 'published')->whereNull('deleted_at')->orderBy('title')->get() as $preModule)
                                         <option value="{{ $preModule->id }}" {{ old('prerequisite_module_id') == $preModule->id ? 'selected' : '' }}>
                                             {{ $preModule->title }}
                                         </option>
                                     @endforeach
                                 </select>
                                 <p class="help-text">Students must complete the selected module before they can enroll in this one.</p>
                             </div>
                         @endif

                        <div class="form-group">
                            <label>Upload File (Optional)</label>
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
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
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
                                Create Assessment
                            </h3>
                            <button type="button" class="toggle-assessment" onclick="toggleAssessmentFields()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                Add Assessment
                            </button>
                        </div>

                        <div class="assessment-fields" id="assessment-fields">
                            <input type="hidden" name="create_assessment" id="create_assessment" value="0">

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="assessment_title">Assessment Title *</label>
                                    <input type="text" id="assessment_title" name="assessment[title]" placeholder="e.g., Module 1 Quiz">
                                </div>

                                <div class="form-group">
                                    <label for="assessment_duration">Duration (minutes) *</label>
                                    <input type="number" id="assessment_duration" name="assessment[duration_minutes]" value="60" min="1">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="assessment_passing">Passing Score (%) *</label>
                                    <input type="number" id="assessment_passing" name="assessment[passing_score]" value="75" min="0" max="100">
                                </div>

                                <div class="form-group">
                                    <label for="assessment_attempts">Max Attempts (0 = unlimited) *</label>
                                    <input type="number" id="assessment_attempts" name="assessment[max_attempts]" value="1" min="0">
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
                                    <input type="checkbox" name="assessment[is_published]" value="1">
                                    Publish Assessment (make available to students)
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" name="assessment[show_correct_answer]" value="1">
                                    Show Correct Answers (students will see correct answers after they use all attempts)
                                </label>
                            </div>

                            <!-- Hidden field to store questions as JSON -->
                            <input type="hidden" name="assessment[questions]" id="questions-json">
                        </div>
                    </div>

                        <div class="form-group" style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                            <button type="submit" class="btn btn-primary">
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
            const createInput = document.getElementById('create_assessment');
            
            fields.classList.toggle('active');
            createInput.value = fields.classList.contains('active') ? '1' : '0';
            
            if (fields.classList.contains('active')) {
                toggleBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                    Cancel
                `;
                if (questionCount === 0) {
                    addQuestion();
                }
            } else {
                toggleBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Add Assessment
                `;
            }
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
            const createAssessment = document.getElementById('create_assessment').value;
            
            if (createAssessment === '1') {
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
            }
        });
    </script>
</body>
</html>
