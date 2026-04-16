<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased bg-[#0a0a0a] text-white">
    <!-- Global Background Glow -->
    <div class="fixed inset-0 -z-10 pointer-events-none">
        <div class="absolute top-[-200px] left-1/2 -translate-x-1/2 w-[900px] h-[900px]
            bg-[#e9c38c]/5 blur-[160px] rounded-full"></div>
    </div>

    <div class="min-h-screen flex flex-col">
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php if(isset($header)): ?>
            <header class="bg-[#0f1115]/80 backdrop-blur border-b border-[#242833]">
                <div class="max-w-7xl mx-auto py-6 px-6">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>

        
        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
            <?php echo e($slot); ?>

        </main>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\dashboard\inlocky\resources\views\layouts\app.blade.php ENDPATH**/ ?>