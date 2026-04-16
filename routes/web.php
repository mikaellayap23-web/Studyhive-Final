<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModuleProgressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Profile Routes
Route::get('/profile', [ProfileController::class, 'show'])->name('profile')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password')->middleware('auth');

// Announcements Routes
Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index')->middleware('auth');
Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store')->middleware('auth');
Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update')->middleware('auth');
Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy')->middleware('auth');
Route::post('/announcements/{announcement}/mark-read', [AnnouncementController::class, 'markAsRead'])->name('announcements.mark-read')->middleware('auth');

// Modules Routes
Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index')->middleware('auth');
Route::get('/modules/create', [ModuleController::class, 'create'])->name('modules.create')->middleware('auth');
Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store')->middleware('auth');

// Module Student Management (Admin & Teachers) - Must be before /modules/{module}
Route::middleware(['auth'])->group(function () {
    Route::get('/modules/{module}/students', [ModuleController::class, 'students'])->name('modules.students');
    Route::post('/modules/{module}/students', [ModuleController::class, 'addStudent'])->name('modules.students.add');
    Route::delete('/modules/{module}/students/{studentId}', [ModuleController::class, 'removeStudent'])->name('modules.students.remove');
});

// Edit/Update/Delete routes - Must be before /modules/{module}
Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit')->middleware('auth');
Route::put('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update')->middleware('auth');
Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy')->middleware('auth');

// Student Module Routes (must be before /modules/{module})
Route::middleware(['auth'])->group(function () {
    Route::get('/modules/all', [ModuleController::class, 'allModules'])->name('modules.all');
    Route::get('/modules/my', [ModuleController::class, 'myModules'])->name('modules.my');
    Route::post('/modules/{module}/enroll', [EnrollmentController::class, 'enroll'])->name('modules.enroll');
    Route::delete('/modules/{module}/unenroll', [EnrollmentController::class, 'unenroll'])->name('modules.unenroll');
});

// Single module view (must be last to avoid catching other routes)
Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show')->middleware('auth');

// Module Progress Tracking Routes (AJAX)
Route::middleware(['auth'])->group(function () {
    Route::post('/modules/{module}/progress/pdf', [ModuleProgressController::class, 'trackPdfPage'])->name('modules.progress.pdf');
    Route::get('/modules/{module}/progress', [ModuleProgressController::class, 'getProgress'])->name('modules.progress');
});

// Assessment Routes
Route::middleware(['auth'])->group(function () {
    // Assessment CRUD (Admin/Teacher)
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/assessments/create', [AssessmentController::class, 'create'])->name('assessments.create');
    Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
    Route::get('/assessments/{assessment}/edit', [AssessmentController::class, 'edit'])->name('assessments.edit');
    Route::put('/assessments/{assessment}', [AssessmentController::class, 'update'])->name('assessments.update');
    Route::delete('/assessments/{assessment}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');

    // Student assessment routes (must be after CRUD routes)
    Route::get('/assessments/{assessment}/take', [AssessmentController::class, 'take'])->name('assessments.take');
    Route::post('/assessments/{assessment}/submit', [AssessmentController::class, 'submit'])->name('assessments.submit');
    Route::get('/assessments/submissions/{submission}', [AssessmentController::class, 'results'])->name('assessments.results');
    Route::get('/assessments/{assessment}/submissions', [AssessmentController::class, 'submissions'])->name('assessments.submissions');
});

// Certificate Verification (public, no auth)
Route::get('/certificates/verify', [CertificateController::class, 'verify'])->name('certificates.verify');

// Certificate Routes (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');

    // Audit Trail
    Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit-trail');

    // Certificate Management
    Route::get('/certificates', [CertificateController::class, 'adminIndex'])->name('certificates.index');
});
