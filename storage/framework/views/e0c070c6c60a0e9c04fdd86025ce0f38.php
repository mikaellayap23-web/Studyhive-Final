<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Announcements - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo e(asset('css/sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/announcements.css')); ?>">
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
                    <h1 class="page-title">Announcements</h1>
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
                <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher'): ?>
                    <div class="page-header">
                        <h1>Announcements</h1>
                        <button class="btn btn-primary" onclick="openAddModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            Add Announcement
                        </button>
                    </div>
                <?php else: ?>
                    <div class="page-header">
                        <h1>Announcements</h1>
                    </div>
                <?php endif; ?>

                <!-- Filters -->
                <form method="GET" action="<?php echo e(route('announcements.index')); ?>" style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: flex-end;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                        <label for="filter_search" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Search</label>
                        <input type="text" id="filter_search" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by title" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
                    </div>
                    <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                        <label for="filter_author" style="font-size: 0.8rem; margin-bottom: 0.25rem;">Author</label>
                        <select id="filter_author" name="author" style="padding: 0.5rem 0.75rem; border: 1px solid #dfe3e8; border-radius: 6px; font-size: 0.875rem; font-family: inherit; background-color: #fafbfc;">
                            <option value="">All Authors</option>
                            <?php
                                $authors = \App\Models\User::whereIn('role', ['admin', 'teacher'])->orderBy('first_name')->get();
                            ?>
                            <?php $__currentLoopData = $authors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $author): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($author->id); ?>" <?php echo e(request('author') == $author->id ? 'selected' : ''); ?>>
                                    <?php echo e($author->first_name); ?> <?php echo e($author->last_name); ?>

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
                    <?php if(request('search') || request('author')): ?>
                        <a href="<?php echo e(route('announcements.index')); ?>" class="btn btn-secondary btn-sm" style="align-self: flex-end;">Clear</a>
                    <?php endif; ?>
                </form>

                <!-- Announcements Grid -->
                <div class="announcements-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="announcement-card <?php echo e($announcement->user_id !== auth()->id() && !$announcement->is_read ? 'unread' : ''); ?>">
                            <div class="announcement-header">
                                <div>
                                    <h3 class="announcement-title">
                                        <?php if($announcement->user_id !== auth()->id() && !$announcement->is_read): ?>
                                            <span class="unread-dot" title="Unread"></span>
                                        <?php endif; ?>
                                        <?php echo e($announcement->title); ?>

                                    </h3>
                                    <div class="announcement-meta">
                                        <span class="announcement-author">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            <?php echo e($announcement->user->first_name); ?> <?php echo e($announcement->user->last_name); ?>

                                        </span>
                                        <span class="role-badge role-<?php echo e($announcement->user->role); ?>">
                                            <?php echo e(ucfirst($announcement->user->role)); ?>

                                        </span>
                                        <?php if($announcement->user_id !== auth()->id()): ?>
                                            <?php if($announcement->is_read): ?>
                                                <span class="read-status">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                                    </svg>
                                                    Read
                                                </span>
                                            <?php else: ?>
                                                <span class="unread-status">Unread</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="announcement-body">
                                <p class="announcement-content"><?php echo e(Str::limit($announcement->content, 150)); ?></p>
                                <div class="announcement-footer">
                                    <span class="announcement-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle;">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        <?php echo e($announcement->created_at->format('M d, Y')); ?>

                                    </span>
                                    <div class="announcement-actions">
                                        <button class="btn btn-secondary btn-sm" onclick="openViewModal(this)" data-title="<?php echo e($announcement->title); ?>" data-author="<?php echo e($announcement->user->first_name); ?> <?php echo e($announcement->user->last_name); ?>" data-role="<?php echo e($announcement->user->role); ?>" data-content="<?php echo e($announcement->content); ?>" data-date="<?php echo e($announcement->created_at->format('M d, Y')); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            View
                                        </button>
                                        <?php if(!$announcement->is_read): ?>
                                            <form action="<?php echo e(route('announcements.mark-read', $announcement->id)); ?>" method="POST" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                                    </svg>
                                                    Mark as Read
                                                </button>
                                            </form>
                                        <?php elseif($announcement->user_id !== auth()->id()): ?>
                                            <span class="read-status">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                                </svg>
                                                Read
                                            </span>
                                        <?php endif; ?>
                                        <?php if(auth()->user()->role === 'admin' || (auth()->user()->role === 'teacher' && auth()->user()->id === $announcement->user_id)): ?>
                                            <button class="btn btn-warning btn-sm" onclick="openEditModal(this)" data-id="<?php echo e($announcement->id); ?>" data-title="<?php echo e($announcement->title); ?>" data-content="<?php echo e($announcement->content); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                                Edit
                                            </button>
                                            <form action="<?php echo e(route('announcements.destroy', $announcement->id)); ?>" method="POST" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                            <h3>No Announcements</h3>
                            <p>There are no announcements at the moment.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if($announcements->hasPages()): ?>
                    <div style="margin-top: 1.5rem;">
                        <?php echo e($announcements->appends(request()->query())->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- View Announcement Modal -->
    <div class="modal-overlay" id="viewModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="view_modal_title">Announcement</h3>
                <button class="modal-close" onclick="closeViewModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="announcement-view-meta" style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8e4;">
                    <span style="display: flex; align-items: center; gap: 0.375rem; font-size: 0.85rem; color: #706f6c;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span id="view_modal_author"></span>
                    </span>
                    <span class="role-badge" id="view_modal_role"></span>
                    <span style="display: flex; align-items: center; gap: 0.375rem; font-size: 0.85rem; color: #706f6c;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span id="view_modal_date"></span>
                    </span>
                </div>
                <div id="view_modal_content" style="font-size: 0.95rem; color: #2d3748; line-height: 1.7; white-space: pre-wrap;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Add Announcement Modal -->
    <?php if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher'): ?>
        <div class="modal-overlay" id="addModal">
            <div class="modal">
                <div class="modal-header">
                    <h3>Add Announcement</h3>
                    <button class="modal-close" onclick="closeAddModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form action="<?php echo e(route('announcements.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea id="content" name="content" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Post Announcement</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Announcement Modal -->
        <div class="modal-overlay" id="editModal">
            <div class="modal">
                <div class="modal-header">
                    <h3>Edit Announcement</h3>
                    <button class="modal-close" onclick="closeEditModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form id="editForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_title">Title</label>
                            <input type="text" id="edit_title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_content">Content</label>
                            <textarea id="edit_content" name="content" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Announcement</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script>
        function openViewModal(el) {
            var title = el.dataset.title;
            var author = el.dataset.author;
            var role = el.dataset.role;
            var content = el.dataset.content;
            var date = el.dataset.date;
            document.getElementById('view_modal_title').textContent = title;
            document.getElementById('view_modal_author').textContent = author;
            document.getElementById('view_modal_role').textContent = role.charAt(0).toUpperCase() + role.slice(1);
            document.getElementById('view_modal_role').className = 'role-badge role-' + role;
            document.getElementById('view_modal_date').textContent = date;
            document.getElementById('view_modal_content').textContent = content;
            document.getElementById('viewModal').classList.add('active');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.remove('active');
        }

        function openAddModal() {
            document.getElementById('addModal').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
        }

        function openEditModal(el) {
            var id = el.dataset.id;
            var title = el.dataset.title;
            var content = el.dataset.content;
            document.getElementById('editForm').action = '/announcements/' + id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_content').value = content;
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('viewModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeViewModal();
            }
        });

        document.getElementById('addModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });

        document.getElementById('editModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\Mikaella\Studyhive-Final\resources\views/announcements/index.blade.php ENDPATH**/ ?>