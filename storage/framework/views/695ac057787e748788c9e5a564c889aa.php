<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .status { font-size: 24px; margin: 20px 0; }
        .success { color: green; }
        .failed { color: red; }
        .pending { color: orange; }
    </style>
</head>
<body>
    <h1>Payment Confirmation</h1>

    <p>Checkout ID: <strong><?php echo e($checkout_id); ?></strong></p>

    <p class="status 
        <?php if($status === 'paid'): ?> success 
        <?php elseif($status === 'canceled'): ?> failed 
        <?php else: ?> pending 
        <?php endif; ?>">
        Status: <?php echo e(ucfirst($status)); ?>

    </p>

    <?php if($amount): ?>
        <p>Amount: <strong><?php echo e(number_format($amount)); ?> DZD</strong></p>
    <?php endif; ?>

    <a href="<?php echo e(url('/')); ?>">Return to Home</a>
</body>
</html><?php /**PATH C:\xampp\htdocs\dashboard\inlocky\resources\views\payments\back.blade.php ENDPATH**/ ?>