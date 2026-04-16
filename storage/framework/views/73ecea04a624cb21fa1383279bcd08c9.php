<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redirecting to Payment</title>
</head>
<body>
    <p>Redirecting to payment, please wait...</p>

    <form id="chargilyForm" action="<?php echo e(route('chargilypay.redirect')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="order_id" value="<?php echo e($order_id); ?>">
    </form>

    <script>
        document.getElementById('chargilyForm').submit();
    </script>
</body>
</html>

<noscript>
    <p>JavaScript is disabled. Click below to continue:</p>
    <button type="submit" form="chargilyForm">Pay Now</button>
</noscript><?php /**PATH C:\xampp\htdocs\dashboard\inlocky\resources\views\payments\redirect_to_chargily.blade.php ENDPATH**/ ?>