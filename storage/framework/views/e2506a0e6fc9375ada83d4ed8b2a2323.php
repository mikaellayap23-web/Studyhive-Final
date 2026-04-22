<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Modules - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo e(asset('css/sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/modules.css')); ?>">
</head>
<body>
    <!-- Sidebar -->
    <?php if (isset($component)) { $__componentOriginal2880b66d47486b4bfeaf519598a469d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2880b66d47486b4bfeaf519598a469d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $attributes = $__attributesOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $component = $__componentOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__componentOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header>
            <div class="container">
                <div class="header-content">
                    <h1 class="page-title">All Modules</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="container">
                <!-- Alerts -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('info')): ?>
                    <div class="alert alert-info">
                        <?php echo e(session('info')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-error">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-error">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <!-- Page Header -->
                <div class="page-header">
                    <?php if(auth()->user()->role === 'student'): ?>
                        <h1>Browse Modules</h1>
                        <p style="color: #718096; font-size: 0.9rem;">Explore and enroll in available learning modules</p>
                    <?php else: ?>
                        <h1>All Modules</h1>
                        <div style="display: flex; gap: 0.5rem;">
                            <?php if(auth()->user()->role === 'admin'): ?>
                                <a href="<?php echo e(route('modules.trashed')); ?>" class="btn btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Trash
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('modules.create')); ?>" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                Add Module
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Filters -->
                <form method="GET" action="<?php echo e(route('modules.all')); ?>" style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: flex-end;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                        <label for="filter_search" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Search</label>
                        <input type="text" id="filter_search" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by title" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
                    </div>
                    <?php if(auth()->user()->role === 'student'): ?>
                        <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                            <label for="filter_teacher" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Teacher</label>
                            <select id="filter_teacher" name="teacher" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
                                <option value="">All Teachers</option>
                                <?php
                                    $teachers = \App\Models\User::where('role', 'teacher')->orderBy('first_name')->get();
                                ?>
                                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>" <?php echo e(request('teacher') == $teacher->id ? 'selected' : ''); ?>>
                                        <?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                            <label for="filter_status" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Status</label>
                            <select id="filter_status" name="status" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
                                <option value="">All Statuses</option>
                                <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                <option value="published" <?php echo e(request('status') == 'published' ? 'selected' : ''); ?>>Published</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-secondary btn-sm" style="align-self: flex-end;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                        Filter
                    </button>
                    <?php if(request('search') || request('teacher') || request('status')): ?>
                        <a href="<?php echo e(route('modules.all')); ?>" class="btn btn-secondary btn-sm" style="align-self: flex-end;">Clear</a>
                    <?php endif; ?>
                </form>

                <!-- Modules Grid -->
                <div class="modules-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="module-card">
                            <?php if($module->image_path): ?>
                                <div class="module-image">
                                    <img src="<?php echo e(asset('storage/' . $module->image_path)); ?>" alt="<?php echo e($module->title); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="module-header">
                                <div>
                                    <h3 class="module-title"><?php echo e($module->title); ?></h3>
                                    <div class="module-meta">
                                        <span class="order-badge"><?php echo e($module->order); ?></span>
                                        <span class="module-author">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            <?php echo e($module->user->first_name); ?> <?php echo e($module->user->last_name); ?>

                                        </span>
                                        <?php if($module->assignedTeacher): ?>
                                            <span class="module-teacher" title="Assigned Teacher">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                                    <circle cx="9" cy="7" r="4"/>
                                                    <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                                                    <path d="M16 3.13a4 4 0 010 7.75"/>
                                                </svg>
                                                <?php echo e($module->assignedTeacher->first_name); ?> <?php echo e($module->assignedTeacher->last_name); ?>

                                            </span>
                                        <?php endif; ?>
                                        <span class="status-badge status-<?php echo e($module->status); ?>">
                                            <?php echo e(ucfirst($module->status)); ?>

                                        </span>
                                        <?php if(auth()->user()->role === 'student'): ?>
                                            <?php if($module->is_enrolled): ?>
                                                <span class="enrolled-badge" style="background-color: #48bb78; color: white; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 600;">
                                                    Enrolled
                                                </span>
                                            <?php endif; ?>
                                            <?php if($module->is_completed): ?>
                                                <span class="enrolled-badge" style="background-color: #16a34a; color: white; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 600;">
                                                    Completed
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="module-body">
                                <p class="module-description"><?php echo e(Str::limit($module->description, 100)); ?></p>
                                <div class="module-footer">
                                    <span class="module-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        <?php echo e($module->created_at->format('M d, Y')); ?>

                                    </span>
                                    <div class="module-actions">
                                        <a href="<?php echo e(route('modules.show', $module->id)); ?>" class="btn btn-secondary btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            Preview
                                        </a>
                                        <?php if(auth()->user()->role === 'student'): ?>
                                            <?php if(!$module->is_enrolled && !$module->is_completed): ?>
                                                <form action="<?php echo e(route('modules.enroll', $module->id)); ?>" method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                                            <circle cx="8.5" cy="7" r="4"/>
                                                            <line x1="20" y1="8" x2="20" y2="14"/>
                                                            <line x1="23" y1="11" x2="17" y2="11"/>
                                                        </svg>
                                                        Enroll
                                                    </button>
                                                </form>
                                            <?php elseif($module->is_completed): ?>
                                                <span style="color: #16a34a; font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 0.25rem;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                                    </svg>
                                                    Completed
                                                </span>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('modules.unenroll', $module->id)); ?>" method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Are you sure you want to unenroll from this module?')">
                                                        Unenroll
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if($module->canManage(auth()->user())): ?>
                                                <a href="<?php echo e(route('modules.edit', $module->id)); ?>" class="btn btn-primary btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="<?php echo e(route('modules.destroy', $module->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this module? It will be moved to trash.');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <h3>No Modules Available</h3>
                            <p>There are no modules available at the moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Mikaella\Studyhive-Final\resources\views/modules/all.blade.php ENDPATH**/ ?>