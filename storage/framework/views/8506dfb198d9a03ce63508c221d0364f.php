<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo e(asset('css/sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/profile.css')); ?>">
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
                    <h1 style="font-size: 1.25rem; font-weight: 600; color: #2d3748;">Profile</h1>
                </div>
            </div>
        </header>

        <!-- Profile Content -->
        <main>
            <div class="container">
                <div class="profile-container">
                    <!-- Success Message -->
                    <?php if(session('success')): ?>
                        <div class="alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Error Messages -->
                    <?php if($errors->any()): ?>
                        <div class="alert-error">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p><?php echo e($error); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <div class="profile-cards">
                        <!-- Personal Information Card -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <h2>Personal Information</h2>
                            </div>

                            <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" id="first_name" name="first_name" value="<?php echo e(old('first_name', auth()->user()->first_name)); ?>" required pattern="[a-zA-Z\s]+" title="Only letters and spaces are allowed">
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" id="last_name" name="last_name" value="<?php echo e(old('last_name', auth()->user()->last_name)); ?>" required pattern="[a-zA-Z\s]+" title="Only letters and spaces are allowed">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" name="username" value="<?php echo e(old('username', auth()->user()->username)); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" id="email" value="<?php echo e(auth()->user()->email); ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="reset" class="btn btn-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>

                        <!-- Account Information Card -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <h2>Account Information</h2>
                            </div>

                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Role</label>
                                    <span><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                                </div>

                                <div class="info-item">
                                    <label>Status</label>
                                    <span class="status-badge status-<?php echo e(auth()->user()->status); ?>">
                                        <?php echo e(ucfirst(auth()->user()->status)); ?>

                                    </span>
                                </div>

                                <div class="info-item">
                                    <label>Member Since</label>
                                    <span><?php echo e(auth()->user()->created_at->format('M d, Y')); ?></span>
                                </div>

                                <div class="info-item">
                                    <label>Last Updated</label>
                                    <span><?php echo e(auth()->user()->updated_at->format('M d, Y')); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password Card -->
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <h2>Change Password</h2>
                            </div>

                            <form method="POST" action="<?php echo e(route('profile.password')); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" id="new_password" name="new_password" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="new_password_confirmation">Confirm New Password</label>
                                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Update Password</button>
                                </div>
                            </form>
                        </div>

                        <!-- Audit Trail Card -->
                        <?php if(count($auditEntries) > 0 || request('action') || request('date_from') || request('date_to')): ?>
                        <div class="profile-card">
                            <div class="profile-card-header">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h2>Activity Log</h2>
                            </div>

                            <!-- Filters -->
                            <form method="GET" action="<?php echo e(route('profile')); ?>" style="display: flex; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap; align-items: flex-end;">
                                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                                    <label style="font-size: 0.8rem; margin-bottom: 0.25rem;">Action</label>
                                    <select name="action" style="padding: 0.5rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; width: 100%;">
                                        <option value="">All Actions</option>
                                        <option value="created" <?php echo e(request('action') === 'created' ? 'selected' : ''); ?>>Created</option>
                                        <option value="updated" <?php echo e(request('action') === 'updated' ? 'selected' : ''); ?>>Updated</option>
                                        <option value="deleted" <?php echo e(request('action') === 'deleted' ? 'selected' : ''); ?>>Deleted</option>
                                        <option value="approved" <?php echo e(request('action') === 'approved' ? 'selected' : ''); ?>>Approved</option>
                                        <option value="rejected" <?php echo e(request('action') === 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                                        <option value="restored" <?php echo e(request('action') === 'restored' ? 'selected' : ''); ?>>Restored</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                                    <label style="font-size: 0.8rem; margin-bottom: 0.25rem;">Date From</label>
                                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" style="padding: 0.5rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; width: 100%;">
                                </div>
                                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                                    <label style="font-size: 0.8rem; margin-bottom: 0.25rem;">Date To</label>
                                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" style="padding: 0.5rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; width: 100%;">
                                </div>
                                <button type="submit" class="btn btn-secondary btn-sm" style="align-self: flex-end;">Filter</button>
                                <?php if(request('action') || request('date_from') || request('date_to')): ?>
                                    <a href="<?php echo e(route('profile')); ?>" class="btn btn-secondary btn-sm" style="align-self: flex-end;">Clear</a>
                                <?php endif; ?>
                            </form>

                            <div style="overflow-x: auto; max-height: 300px; overflow-y: auto;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid #e2e8e4;">
                                            <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Date</th>
                                            <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Action</th>
                                            <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Target</th>
                                            <th style="padding: 0.75rem; text-align: left; color: #4a5568; font-weight: 600;">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $auditEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                                <td style="padding: 0.75rem; white-space: nowrap; color: #706f6c;"><?php echo e($entry['timestamp']); ?></td>
                                                <td style="padding: 0.75rem;">
                                                    <?php
                                                        $colors = [
                                                            'created' => '#16a34a',
                                                            'updated' => '#2563eb',
                                                            'deleted' => '#dc2626',
                                                            'approved' => '#16a34a',
                                                            'rejected' => '#dc2626',
                                                            'soft deleted' => '#f59e0b',
                                                            'restored' => '#8b5cf6',
                                                            'permanently deleted' => '#dc2626',
                                                            'completed' => '#16a34a',
                                                        ];
                                                        $color = $colors[strtolower($entry['action'])] ?? '#4a5568';
                                                    ?>
                                                    <span style="color: <?php echo e($color); ?>; font-weight: 600;"><?php echo e($entry['action']); ?></span>
                                                </td>
                                                <td style="padding: 0.75rem;"><?php echo e($entry['target']); ?></td>
                                                <td style="padding: 0.75rem; color: #706f6c;"><?php echo e($entry['details']); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\Users\Mikaella\Studyhive-Final\resources\views/pages/profile.blade.php ENDPATH**/ ?>