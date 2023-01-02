
<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.dashboards'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/jsvectormap/jsvectormap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> SIGA <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> Inicio <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<?php
    //Obtener todos los clubes del usuario
    $clubs = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->get();
?>
<div class="row">
    <div class="col">

        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="mb-3 mb-lg-0">Hola, <?php echo e(Auth::user()->name); ?></h4>
                            <p class="text-muted mb-0">
                                Esta es tu página de inicio.
                            </p>
                        </div>
                    </div>
                    <!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <a href="/clubes-catalogo" class="link-secondary">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p
                                            class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                            Clubes</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        Checa el catalogo de clubes.
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-primary rounded fs-3">
                                            <?php if(Auth::user()->rol == ('colaborador')): ?>
                                                <i class="bx bx-group text-primary"></i>
                                            <?php else: ?>
                                                <i class="bx bx-user-plus text-primary"></i>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </a>
                </div><!-- end col -->

                <?php if(Auth::user()->rol == ('administrador')): ?>

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <a href="/apps-calendar" class="link-secondary">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p
                                                class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Crear evento</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            Crea un nuevo evento.
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                                <i class="bx bx-calendar text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <a href="/pages-team?club=all" class="link-secondary">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p
                                                class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Participantes</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            Ver participantes.
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                                <i class="bx bx-user-circle text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <a href="/apps-clubes-admin" class="link-secondary">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p
                                                class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Mis clubes</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            Administra tus clubes.
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                                <i class="bx bx-user-plus text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->

                <?php elseif(Auth::user()->rol == ('super')): ?>

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <a href="/apps-calendar" class="link-secondary">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p
                                                class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Crear evento</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            Crea un nuevo evento.   
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                                <i class="bx bx-calendar text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <a href="/apps-crm-users" class="link-secondary">
                            <!-- card -->
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p
                                                class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Usuarios</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            Vista de los usuarios registrados.
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                                <i class="bx bx-user-circle text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <a href="/apps-eventos-list?club=all" class="link-secondary">
                            <!-- card -->
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p
                                                class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Todos mis eventos</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            Lista de todos tus eventos
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                                <i class="bx bx-calendar-event text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->

                <?php else: ?>
                    
                        <div class="col-xl-3 col-md-6">
                            <a href="/apps-calendario-participante" class="link-secondary">
                                <!-- card -->
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p
                                                    class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                    Eventos de mis clubes</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                Eventos organizados por los clubes a los que perteneces.
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                                    <i class="bx bx-calendar text-primary"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </a>
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <a href="/asistencias" class="link-secondary">
                                <!-- card -->
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p
                                                    class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                    Mis asistencias</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                Ve tus asistencias y constancias.
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                                    <i class="bx bx-file text-primary"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </a>
                        </div><!-- end col -->

                <?php endif; ?>


            </div> <!-- end row-->

            <?php if(Auth::user()->rol == ('administrador')): ?>

                <div class="row">

                    <div class="col-xl-3 col-md-6">
                        <a href="/apps-eventos-list?club=all" class="link-secondary">
                            <!-- card -->
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p
                                                class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Todos mis eventos</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            Lista de todos tus eventos
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                                <i class="bx bx-calendar-event text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <a href="admin-dashboard" class="link-secondary">
                            <!-- card -->
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p
                                                class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                                Estadisticas</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-4">
                                        <div>
                                            Estadisticas de tus clubes
                                        </div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                                <i class="bx bx-stats text-primary"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </a>
                    </div><!-- end col -->
                </div>

            <?php endif; ?>

        </div> <!-- end .h-100-->

    </div> <!-- end col -->

    
</div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<!-- apexcharts -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="<?php echo e(URL::asset('/assets/libs/jsvectormap/jsvectormap.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.js')); ?>"></script>
<!-- dashboard init -->
<script src="<?php echo e(URL::asset('/assets/js/pages/dashboard-ecommerce.init.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/index.blade.php ENDPATH**/ ?>