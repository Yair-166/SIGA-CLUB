
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.list-view'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Super
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Dashboard
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php
        //Obtener todos los clubes de la base de datos
        $clubes = DB::table('clubes')->get();
        $totalClubes = count($clubes);
        //Obtener todos los administradores de la base de datos
        $administradores = DB::table('users')->where('rol', 'administrador')->get();
        $totalAdministradores = count($administradores);
        //Obtener todos los colaboradores de la base de datos
        $colaboradores = DB::table('users')->where('rol', 'colaborador')->get();
        $totalColaboradores = count($colaboradores);
        //Obtener todos los usuarios de la base de datos
        $usuarios = DB::table('users')->where('rol', 'usuario')->get();
        $totalUsuarios = count($usuarios);
        //Obtener todos los eventos de la base de datos
        $eventos = DB::table('eventos')->get();
        $totalEventos = count($eventos);
    ?>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <a href='admin-dashboard'>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total de clubes</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e($totalClubes); ?></h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded fs-3">
                                    <i data-feather="file-text" class="text-primary icon-dual-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </a>
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <a href='apps-crm-users'>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total de administradores</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e($totalAdministradores); ?></h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded fs-3">
                                    <i data-feather="user-check" class="text-primary icon-dual-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </a>
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <a href='apps-crm-users'>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total de participantes</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e($totalColaboradores); ?></h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded fs-3">
                                    <i data-feather="users" class="text-primary icon-dual-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </a>
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <a href='apps-eventos-list?club=all'>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total de eventos</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e($totalEventos); ?></h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded fs-3">
                                    <i data-feather="calendar" class="text-primary icon-dual-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </a>
            </div><!-- end card -->
        </div><!-- end col -->
    </div> <!-- end row-->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('assets/libs/list.js/list.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/list.pagination.js/list.pagination.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/js/pages/invoiceslist.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/super-dashboard.blade.php ENDPATH**/ ?>