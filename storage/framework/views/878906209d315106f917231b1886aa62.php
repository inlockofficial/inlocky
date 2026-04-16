<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INLOCK — Product Details</title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>
</head>

<body class="bg-[#0b0f19] text-white min-h-screen flex items-center justify-center p-4 md:p-8">

    <div class="absolute inset-0 opacity-20 blur-3xl pointer-events-none
        bg-[radial-gradient(circle_at_center,#e9c38c_0%,transparent_60%)]">
    </div>

    <div class="
        relative
        bg-[#171a21]
        border border-[#242833]
        rounded-3xl
        shadow-2xl
        p-6 md:p-10
        max-w-4xl
        w-full
        z-10
    ">
        
        <div class="flex justify-between items-center mb-8 border-b border-[#242833] pb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-wide">
                    <span class="text-[#e9c38c]">INLOCK</span>
                </h1>
                <p class="text-gray-400 text-sm">Product Overview</p>
            </div>
            <div class="hidden md:block">
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-[#0f1115] border border-[#242833] text-gray-400">
                    ID: #<?php echo e($product->id); ?>

                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
            
            <div class="md:col-span-5 space-y-6">
                <div class="rounded-2xl overflow-hidden border border-[#242833] bg-[#0f1115] shadow-inner">
                    <img src="<?php echo e(asset('storage/'.$product->image)); ?>" 
                         alt="<?php echo e($product->title); ?>" 
                         class="w-full h-auto object-contain aspect-square hover:scale-105 transition duration-500" 
                         loading="lazy">
                </div>
                
                <?php if($product->screenshot): ?>
                <div class="p-4 rounded-xl bg-[#0f1115] border border-[#242833]">
                    <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] mb-3 font-bold">Reference Screenshot</p>
                    <img src="<?php echo e(asset('storage/'.$product->screenshot)); ?>" 
                         alt="Screenshot" 
                         class="w-full h-auto rounded-lg opacity-80 border border-[#242833]">
                </div>
                <?php endif; ?>
            </div>

            <div class="md:col-span-7 flex flex-col justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-white leading-tight mb-2">
                        <?php echo e($product->title); ?>

                    </h2>
                    
                    <div class="flex items-baseline gap-2 mb-8">
                        <span class="text-4xl font-black text-[#e9c38c]">
                            <?php echo e(number_format($product->final_price_dzd, 2)); ?>

                        </span>
                        <span class="text-lg font-bold text-[#e9c38c]/70 uppercase">DZD</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-[#0f1115]/50 p-4 rounded-xl border border-[#242833]">
                            <span class="text-[10px] text-gray-500 uppercase font-bold block mb-1">Quantity</span>
                            <span class="text-lg text-gray-100 font-semibold"><?php echo e($product->quantity); ?></span>
                        </div>
                        
                        <?php if($product->color): ?>
                        <div class="bg-[#0f1115]/50 p-4 rounded-xl border border-[#242833]">
                            <span class="text-[10px] text-gray-500 uppercase font-bold block mb-1">Color</span>
                            <span class="text-lg text-gray-100 font-semibold"><?php echo e($product->color); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if($product->size): ?>
                        <div class="bg-[#0f1115]/50 p-4 rounded-xl border border-[#242833]">
                            <span class="text-[10px] text-gray-500 uppercase font-bold block mb-1">Size</span>
                            <span class="text-lg text-gray-100 font-semibold"><?php echo e($product->size); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if($product->custom_note): ?>
                    <div class="bg-[#e9c38c]/5 p-4 rounded-xl border border-[#e9c38c]/20 mb-8">
                        <span class="text-[10px] text-[#e9c38c] uppercase font-bold block mb-2">Customer Note</span>
                        <p class="text-gray-300 text-sm leading-relaxed italic">"<?php echo e($product->custom_note); ?>"</p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-4">
                    <?php if($product->status == 'priced'): ?>
                        <?php if(auth()->guard()->check()): ?>
                            <form action="<?php echo e(route('orders.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                <button type="submit" class="w-full py-4 bg-[#e9c38c] hover:bg-[#d4b17a] text-[#0b0f19] text-lg font-black rounded-2xl transition-all duration-300 shadow-xl shadow-[#e9c38c]/10 active:scale-[0.98]">
                                    Buy Product
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="block text-center w-full py-4 bg-[#e9c38c] hover:bg-[#d4b17a] text-[#0b0f19] font-black rounded-2xl transition-all">
                                Login to Purchase
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="w-full py-4 bg-[#242833] text-gray-500 font-bold rounded-2xl cursor-not-allowed border border-dashed border-gray-600" disabled>
                            Review in Progress...
                        </button>
                    <?php endif; ?>

                    <div class="flex gap-4">
                        <a href="<?php echo e(route('welcome')); ?>" class="flex-1 text-center py-3 bg-[#0f1115] hover:bg-[#1c2029] text-gray-400 text-sm font-bold rounded-xl border border-[#242833] transition-all">
                            Back to Store
                        </a>

                        <?php if($product->status === 'pending_review'): ?>
                            <form action="<?php echo e(route('cancel.request', $product->id)); ?>" method="POST" class="flex-1">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="w-full py-3 bg-transparent hover:bg-red-500/10 text-red-500/70 hover:text-red-500 text-sm font-bold rounded-xl border border-red-500/10 hover:border-red-500/30 transition-all">
                                    Cancel Request
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH C:\xampp\htdocs\dashboard\inlocky\resources\views\request-view.blade.php ENDPATH**/ ?>