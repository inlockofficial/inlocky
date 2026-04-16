<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

     <?php $__env->slot('header', null, []); ?> 
        <h2 class="text-xl font-semibold text-white">
            My Orders 🛒
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Container -->
            <div class="bg-[#171a21] border border-[#242833] rounded-2xl p-6 shadow-xl">

                <h3 class="text-lg font-semibold mb-6 text-gray-200">
                    Your Orders
                </h3>

                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <?php
                        $product = $order->product;

                        // Status colors
                        $statusColors = [
                            'pending' => 'bg-yellow-500/20 text-yellow-400',
                            'pending_review' => 'bg-yellow-500/20 text-yellow-400',
                            'priced' => 'bg-blue-500/20 text-blue-400',
                            'paid' => 'bg-green-500/20 text-green-400',
                            'shipped' => 'bg-purple-500/20 text-purple-400',
                            'cancelled' => 'bg-red-500/20 text-red-400',
                        ];

                        $badge = $statusColors[$order->status] ?? 'bg-gray-500/20 text-gray-400';
                    ?>

                    <!-- ORDER CARD -->
                    <div class="
                        bg-[#0f1115]
                        border border-[#242833]
                        rounded-xl
                        p-5 mb-5
                        flex flex-col md:flex-row
                        gap-6
                        hover:border-[#e9c38c]/40
                        transition
                    ">

                        <!-- Product Image -->
                        <div class="flex-shrink-0">
                            <?php if($product && $product->image): ?>
                                <img
                                    src="<?php echo e(asset('storage/'.$product->image)); ?>"
                                    class="w-28 h-28 object-cover rounded-lg border border-[#242833]"
                                    loading="lazy">
                            <?php else: ?>
                                <div class="w-28 h-28 bg-[#171a21] rounded-lg flex items-center justify-center text-gray-500 text-sm">
                                    No Image
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Info -->
                        <div class="flex-1">

                            <h4 class="text-lg font-semibold text-white mb-2">
                                <?php echo e($product->title ?? 'Product unavailable'); ?>

                            </h4>

                            <!-- Status -->
                            <span class="px-3 py-1 text-xs rounded-full <?php echo e($badge); ?>">
                                <?php echo e(ucfirst(str_replace('_',' ', $order->status))); ?>

                            </span>

                            <!-- Details -->
                            <div class="mt-4 text-sm text-gray-400 space-y-1">

                                <p>
                                    <span class="text-gray-500">Order ID:</span>
                                    #<?php echo e($order->id); ?>

                                </p>

                                <?php if($product): ?>
                                    <p>
                                        <span class="text-gray-500">Price:</span>
                                        <span class="text-[#e9c38c] font-semibold">
                                            <?php echo e(number_format($product->final_price_dzd ?? 0, 2)); ?> DZD
                                        </span>
                                    </p>
                                <?php endif; ?>

                                <?php if($order->status === 'pending_payment'): ?>
                                    <p class="text-yellow-400 text-sm">
                                        Pay before <?php echo e($order->expires_at->diffForHumans()); ?>

                                    </p>
                                <?php endif; ?>

                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2 justify-center">

                        <!-- View Order -->

                            <?php if($order->status === 'pending_payment'): ?>
                                <a href="<?php echo e(route('orders.payment', $order->id)); ?>"
                                class="px-5 py-2 bg-[#e9c38c] text-black text-sm font-semibold rounded-lg hover:scale-105 transition text-center">
                                    View Order →
                                </a>
                                <form method="POST"
                                    action="<?php echo e(route('orders.cancel', $order->id)); ?>"
                                    onsubmit="return confirm('Cancel this order?')">
                                    <?php echo csrf_field(); ?>

                                    <button type="submit"
                                        class="w-full px-5 py-2 text-sm font-semibold rounded-lg
                                            border border-red-500/40 text-red-400
                                            hover:bg-red-500/10 transition">
                                        Cancel Order
                                    </button>
                                </form>
                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <!-- Empty State -->
                    <div class="text-center py-16">

                        <p class="text-gray-400 mb-4">
                            You haven't placed any orders yet.
                        </p>

                        <a href="<?php echo e(route('welcome')); ?>"
                           class="px-6 py-3 bg-[#e9c38c] text-black rounded-lg font-semibold hover:scale-105 transition">
                            Start Shopping
                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </div>
    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\dashboard\inlocky\resources\views\dashboard.blade.php ENDPATH**/ ?>