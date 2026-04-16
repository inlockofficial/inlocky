<?php if($errors->any()): ?>
    <div style="color:red;">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INLOCK - Shop in Algeria</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/landing.css')); ?>">

</head>
<body>
<h1>Fill Product Details</h1>

<form action="<?php echo e(route('admin.request.update', $request->id)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <label>Title *</label>
    <input type="text" name="title" value="<?php echo e($request->title); ?>" required>

    <label>Product Image *</label>
    <input type="file" name="image" accept="image/*" required>

    <label>Price USD *</label>
    <input type="number" step="0.01" name="price_usd" value="<?php echo e($request->price_usd); ?>" required>

    <label>Shipping USD</label>
    <input type="number" step="0.01" name="shipping_usd" value="<?php echo e($request->shipping_usd ?? 0); ?>">

    <label>Service Margin (optional, DZD)</label>
    <input type="number" step="0.01" name="service_margin" value="0">

    <button type="submit">Update Product</button>
</form>
</body>
</html><?php /**PATH C:\xampp\htdocs\dashboard\inlocky\resources\views\admin\request-show.blade.php ENDPATH**/ ?>