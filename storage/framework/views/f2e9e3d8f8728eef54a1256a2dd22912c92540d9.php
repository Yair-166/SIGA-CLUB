
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.sellers'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Admin
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Clubes
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <?php
        //Obtener todos los clubes de la base de datos donde el usuario sea el administrador
        $clubes = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->get();
        //si el usuario tiene rol super
        if(Auth::user()->rol == 'super'){
            //Obtener todos los clubes de la base de datos
            $clubes = DB::table('clubes')->get();
        }
        $totalClubes = count($clubes);
    ?>

    <div class="row mt-4">
        <div class="card">
            <div class="card-body text-center p-1">
                <h5 class="mb-1 mt-1">
                    Clubes administrados:
                </h5>
                <p class="text-muted mb-1">
                    <?php echo e($totalClubes); ?>

                </p>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        
        <?php $__currentLoopData = $clubes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php
                //Contar el total de filas de la tabla inscripciones donde id_club sea igual al id del club y que active sea igual a 1
                $totalInscritos = DB::table('inscripciones')->where('id_club', $club->id)->where('active', 1)->count();
            ?>

            <div class="col-xl-3 col-lg-6">
                <a href="<?php echo e(URL::asset('/dashboard?club='.$club->id)); ?>">
                    <div class="card">
                        <div class="card-body text-center p-4">
                            <img class="rounded-circle header-profile-user" src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" alt="" height="45">
                            <h5 class="mb-1 mt-4">
                                <?php echo e($club->nombre); ?>

                            </h5>
                            <p class="text-muted mb-4"><?php echo e($club->descripcion); ?></p>
                            <div class="mt-4">
                                <a href="<?php echo e(URL::asset('/dashboard?club='.$club->id)); ?>" class="btn btn-light w-100">Ver estadísticas</a>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!--end col-->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
    </div>
    <!--end row-->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('assets/libs/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/js/pages/sellers.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/admin-dashboard.blade.php ENDPATH**/ ?>