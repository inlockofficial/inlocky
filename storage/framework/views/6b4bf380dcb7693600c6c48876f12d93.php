<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INLOCK - Shop in Algeria</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/landing.css')); ?>">
    <style>
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            font-size: 16px;
            border-radius: 6px;
            text-decoration: none;
            color: #fff;
            cursor: pointer;
        }
        .btn-back { background-color: #6c757d; }
        .btn-buy { background-color: #28a745; }
        .btn-cancel { background-color: #dc3545; }
        .btn:disabled { background-color: #aaa; cursor: not-allowed; }
    </style>
</head>
<body>

<h2>Product Details 🛒</h2>

<p><strong>Title:</strong> <?php echo e($product->title); ?></p>
<img src="<?php echo e(asset('storage/'.$product->image)); ?>" alt="<?php echo e($product->title); ?>" width="250" loading="lazy">

<p><strong>Price:</strong> <?php echo e(number_format($product->final_price_dzd, 2)); ?> DZD</p>
<p><strong>Quantity:</strong> <?php echo e($product->quantity); ?></p>

<?php if($product->color): ?>
<p><strong>Color:</strong> <?php echo e($product->color); ?></p>
<?php endif; ?>

<?php if($product->size): ?>
<p><strong>Size:</strong> <?php echo e($product->size); ?></p>
<?php endif; ?>

<?php if($product->custom_note): ?>
<p><strong>Extra Details:</strong> <?php echo e($product->custom_note); ?></p>
<?php endif; ?>

<?php if($product->screenshot): ?>
<p><strong>Screenshot:</strong></p>
<img src="<?php echo e(asset('storage/'.$product->screenshot)); ?>" alt="Screenshot" width="250">
<?php endif; ?>

<!-- Buttons -->
<div>
    <a href="<?php echo e(route('welcome')); ?>" class="btn btn-back">⬅ Back to Home Page</a>

    <?php if($product->status == 'priced'): ?>
        <?php if(auth()->guard()->check()): ?>
            <!-- Form to create order -->
            <form action="<?php echo e(route('orders.store')); ?>" method="POST" style="display:inline;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                <button type="submit" class="btn btn-buy">💳 Buy Now</button>
            </form>
        <?php else: ?>
            <!-- User not logged in -->
            <a href="<?php echo e(route('login')); ?>" class="btn btn-buy">💳 Login to Buy</a>
        <?php endif; ?>
    <?php else: ?>
        <button class="btn btn-buy" disabled>💳 Buy Now (Not Ready)</button>
    <?php endif; ?>

    <?php if($product->status === 'pending_review'): ?>
        <form action="<?php echo e(route('cancel.request', $product->id)); ?>" method="POST" style="display:inline;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-cancel">❌ Cancel Request</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html><?php /**PATH C:\xampp\htdocs\dashboard\inlocky\resources\views\product-result.blade.php ENDPATH**/ ?>