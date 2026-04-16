<nav x-data="{ open: false }"
     class="sticky top-0 z-50 bg-[#0a0a0a]/90 backdrop-blur border-b border-[#242833]">

    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16 items-center">

            <!-- LEFT SIDE -->
            <div class="flex items-center space-x-10">

                <!-- Logo -->
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center">
                    <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'block h-9 w-auto fill-current text-[#e9c38c] transition hover:scale-105']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block h-9 w-auto fill-current text-[#e9c38c] transition hover:scale-105']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
                </a>

                <!-- Desktop Links -->
                <div class="hidden sm:flex items-center space-x-8">

                    <a href="<?php echo e(route('dashboard')); ?>"
                       class="text-sm tracking-wide <?php echo e(request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400'); ?>

                       hover:text-white transition">
                        My Orders
                    </a>

                    <a href="<?php echo e(route('profile.edit')); ?>"
                       class="text-sm tracking-wide <?php echo e(request()->routeIs('profile.edit') ? 'text-white' : 'text-gray-400'); ?>

                       hover:text-white transition">
                        Profile
                    </a>

                </div>
            </div>


            <!-- RIGHT SIDE -->
            <div class="hidden sm:flex items-center space-x-4">

                <!-- New Order CTA -->
                <a href="<?php echo e(route('orders.create')); ?>"
                   class="inline-flex items-center px-5 py-2.5
                   bg-[#e9c38c] text-black text-xs font-semibold uppercase tracking-widest
                   rounded-full
                   hover:scale-105 hover:shadow-lg hover:shadow-[#e9c38c]/20
                   transition-all duration-300">
                    New Order
                </a>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ dropdown: false }">

                    <button @click="dropdown = !dropdown"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm
                        bg-[#171a21] border border-[#242833]
                        rounded-full text-gray-300
                        hover:text-white hover:border-[#e9c38c]/40
                        transition">

                        <span><?php echo e(Auth::user()->name); ?></span>

                        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="dropdown"
                         @click.outside="dropdown = false"
                         x-transition
                         class="absolute right-0 mt-3 w-48
                         bg-[#171a21] border border-[#242833]
                         rounded-xl shadow-xl overflow-hidden">

                        <a href="<?php echo e(route('profile.edit')); ?>"
                           class="block px-4 py-3 text-sm text-gray-300 hover:bg-[#1f232c] hover:text-white transition">
                            Profile
                        </a>

                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:bg-[#1f232c] hover:text-white transition">
                                Log Out
                            </button>
                        </form>

                    </div>
                </div>
            </div>


            <!-- MOBILE BUTTON -->
            <div class="sm:hidden">
                <button @click="open = ! open"
                    class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-[#171a21] transition">

                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />

                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>


    <!-- MOBILE MENU -->
    <div x-show="open" x-transition class="sm:hidden border-t border-[#242833] bg-[#0f1115]">

        <div class="px-6 py-4 space-y-3">

            <a href="<?php echo e(route('dashboard')); ?>"
               class="block text-gray-300 hover:text-white transition">
                My Orders
            </a>

            <a href="<?php echo e(route('profile.edit')); ?>"
               class="block text-gray-300 hover:text-white transition">
                Profile
            </a>

            <a href="<?php echo e(route('orders.create')); ?>"
               class="block text-[#e9c38c] font-semibold">
                New Order
            </a>

            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button class="text-gray-400 hover:text-white transition">
                    Log Out
                </button>
            </form>

        </div>
    </div>
</nav><?php /**PATH C:\xampp\htdocs\dashboard\inlocky\resources\views\layouts\navigation.blade.php ENDPATH**/ ?>