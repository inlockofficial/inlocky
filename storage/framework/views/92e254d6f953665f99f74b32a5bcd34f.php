<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INLOCK - Shop in Algeria</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/landing.css')); ?>">

</head>
<body>
<h1>Pending Requests</h1>

<?php if(session('success')): ?>
    <p><?php echo e(session('success')); ?></p>
<?php endif; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>AliExpress Link</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($req->id); ?></td>
        <td><a href="<?php echo e($req->ali_link); ?>" target="_blank"><?php echo e($req->ali_link); ?></a></td>
        <td><?php echo e($req->status); ?></td>
        <td>
            <a href="<?php echo e(route('admin.request.show', $req->id)); ?>">Fill Details</a>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
</body>
</html><?php /**PATH C:\xampp\htdocs\dashboard\inlocky\resources\views\admin\requests.blade.php ENDPATH**/ ?>