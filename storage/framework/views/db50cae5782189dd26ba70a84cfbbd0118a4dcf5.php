<!doctype html>
<html lang="sp" data-topbar="light">

    <head>
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?> | SIGA CLUB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="SIGA-CLUB" name="description" />
    <meta content="TT" name="2021-B004" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(URL::asset('assets/images/favicon.ico')); ?>">
    
        <?php echo $__env->make('layouts.head-css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </head>

    <?php echo $__env->yieldContent('body'); ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make('layouts.vendor-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </body>
</html>
<?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/layouts/master-without-nav.blade.php ENDPATH**/ ?>