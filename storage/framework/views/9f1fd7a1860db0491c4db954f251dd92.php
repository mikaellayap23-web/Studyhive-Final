<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trashed Modules - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo e(asset('css/sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/modules.css')); ?>">
    <style>
        .trash-badge {
            background: #fef2f2;
            color: #991b1b;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .deleted-info {
            color: #991b1b;
            font-size: 0.85rem;
        }
    </style>
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
                    <h1 class="page-title">Trashed Modules</h1>
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

                <?php if($errors->any()): ?>
                    <div class="alert alert-error">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <!-- Page Header -->
                <div class="page-header">
                    <h1>Trashed Modules</h1>
                    <a href="<?php echo e(route('modules.index')); ?>" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Back to Modules
                    </a>
                </div>

                <!-- Filters -->
                <form method="GET" action="<?php echo e(route('modules.trashed')); ?>" style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: flex-end;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                        <label for="filter_search" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Search</label>
                        <input type="text" id="filter_search" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by title" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
                    </div>
                    <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                        <label for="filter_teacher" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Assigned Teacher</label>
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
                    <button type="submit" class="btn btn-secondary btn-sm" style="align-self: flex-end;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 0.25rem;">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                        Filter
                    </button>
                    <?php if(request('search') || request('teacher')): ?>
                        <a href="<?php echo e(route('modules.trashed')); ?>" class="btn btn-secondary btn-sm" style="align-self: flex-end;">Clear</a>
                    <?php endif; ?>
                </form>

                <?php if($modules->count() > 0): ?>
                    <!-- Bulk Actions -->
                    <form id="bulk-action-form" method="POST" action="<?php echo e(route('modules.trash.bulkRestore')); ?>" style="margin-bottom: 1rem;">
                        <?php echo csrf_field(); ?>
                        <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" id="select-all" style="width: auto;">Select All
                            </label>
                            <button type="submit" class="btn btn-success btn-sm" onclick="document.getElementById('bulk-action-form').action = '<?php echo e(route('modules.trash.bulkRestore')); ?>'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/>
                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                    <path d="M8 16H3v5"/>
                                </svg>
                                Restore Selected
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure you want to permanently delete all selected modules? This action cannot be undone.')) { document.getElementById('bulk-action-form').action = '<?php echo e(route('modules.trash.bulkForceDelete')); ?>'; document.getElementById('bulk-action-form').submit(); }">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Selected Permanently
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

                <!-- Modules Grid -->
                <div class="modules-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="module-card" style="border-left: 4px solid #991b1b;">
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
                                        <span class="trash-badge">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Trashed
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="module-body">
                                <p class="module-description"><?php echo e(Str::limit($module->description, 100)); ?></p>
                                <div class="module-footer">
                                    <span class="deleted-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="8" x2="12" y2="12"/>
                                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                        Deleted <?php echo e($module->deleted_at->format('M d, Y')); ?>

                                    </span>
                                    <div class="module-actions">
                                        <form method="POST" action="<?php echo e(route('modules.trash.restore', $module->id)); ?>" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                                    <path d="M21 3v5h-5"/>
                                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                                    <path d="M8 16H3v5"/>
                                                </svg>
                                                Restore
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('modules.trash.forceDelete', $module->id)); ?>" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to permanently delete this module? This action cannot be undone.')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete Permanently
                                            </button>
                                        </form>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin-left: 0.5rem;">
                                            <input type="checkbox" name="module_ids[]" value="<?php echo e($module->id); ?>" class="module-checkbox" style="width: auto;">
                                            <span style="font-size: 0.8rem;">Select</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <h3>No Trashed Modules</h3>
                            <p>There are no trashed modules.</p>
                            <a href="<?php echo e(route('modules.index')); ?>" class="btn btn-primary">Back to Modules</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.module-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\Mikaella\Studyhive-Final\resources\views/modules/trashed.blade.php ENDPATH**/ ?>