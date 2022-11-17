
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
    ?>

    <div class="row mt-4">
        
        <?php $__currentLoopData = $clubes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php
                //Contar el total de filas de la tabla inscripciones donde id_club sea igual al id del club
                $totalInscritos = DB::table('inscripciones')->where('id_club', $club->id)->count();
            ?>

            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body text-center p-4">
                        <img class="rounded-circle header-profile-user" src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" alt="" height="45">
                        <h5 class="mb-1 mt-4"><a href="<?php echo e(URL::asset('/apps-clubes-admin-details')); ?>" class="link-secondary">
                            <?php echo e($club->nombre); ?>

                        </a></h5>
                        <p class="text-muted mb-4"><?php echo e($club->descripcion); ?></p>
                        <div class="row mt-4">
                            <div class="col-lg-6 border-end-dashed border-end">
                                <h5><?php echo e($totalInscritos); ?></h5>
                                <span class="text-muted">Participantes</span>
                            </div>
                            <div class="col-lg-6">
                                <h5><?php echo e($club->constanciasExpedidas); ?></h5>
                                <span class="text-muted">Constancias expedidas</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="<?php echo e(URL::asset('/apps-clubes-admin-details')); ?>" class="btn btn-light w-100">View Details</a>
                        </div>
                    </div>
                </div>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/apps-clubes-admin.blade.php ENDPATH**/ ?>