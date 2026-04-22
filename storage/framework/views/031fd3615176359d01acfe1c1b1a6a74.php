<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Studyhive</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <a href="<?php echo e(route('welcome')); ?>" class="logo">Studyhive</a>
                <a href="<?php echo e(route('welcome')); ?>" class="back-link">← Back to Home</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="auth-card">
                <div class="auth-header">
                    <h1>Create Account</h1>
                    <p>Join the CSS NC II Training Program</p>
                </div>

                <?php if($errors->any()): ?>
                    <div style="background-color: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p style="color: #991b1b; font-size: 0.9rem; margin-bottom: 0.25rem;"><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <div style="background-color: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                    <p style="color: #92400e; font-size: 0.85rem; line-height: 1.5;">
                        <strong>Note:</strong> After registration, your account will need admin approval before you can log in.
                    </p>
                </div>

                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo e(old('first_name')); ?>" required autofocus pattern="[a-zA-Z\s]+" title="Only letters and spaces are allowed">
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo e(old('last_name')); ?>" required pattern="[a-zA-Z\s]+" title="Only letters and spaces are allowed">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>

                <div class="auth-footer">
                    <p>Already have an account? <a href="<?php echo e(route('login')); ?>">Sign in here</a></p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?php echo e(date('Y')); ?> Studyhive <span class="footer-accent">|</span> CSS NC II Training Program</p>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\Users\Mikaella\Studyhive-Final\resources\views/auth/register.blade.php ENDPATH**/ ?>