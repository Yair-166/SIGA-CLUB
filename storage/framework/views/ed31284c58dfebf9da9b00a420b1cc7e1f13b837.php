
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.profile'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php
    //Recibir datos via get
    $uid = $_GET['uid'];
    //Obtener los datos de la tabla inscripciones con el uid
    $inscripcion = DB::table('inscripciones')->where('id', $uid)->first();
    //Obtener todas las asistencias del usuario con el id del usuario
    $asistencias = DB::table('asistencias')->where('idUsuario', $inscripcion->id_alumno)->get();
    //Obtener los datos del club con el id del club
    $club = DB::table('clubes')->where('id', $inscripcion->id_club)->first();
    //Obtener el usuario de la base de datos por el id
    $user = DB::table('users')->where('id', $inscripcion->id_alumno)->first();
    $texto = $numero = "";
?>
    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="<?php echo e(URL::asset('assets/images/profile-bg.jpg')); ?>" alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
        <div class="row g-4">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img src="<?php if($user->avatar != ''): ?> <?php echo e(URL::asset('images/' . $user->avatar)); ?><?php else: ?><?php echo e(URL::asset('assets/images/users/avatar-1.jpg')); ?> <?php endif; ?>"
                        alt="user-img" class="img-thumbnail rounded-circle" />
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1"><?php echo e($user->name . " " . $user->apaterno  . " " . $user->amaterno); ?></h3>
                    <p class="text-white-75"><?php echo e($user->rol); ?></p>
                    <div class="hstack text-white-50 gap-1">
                        <div class="me-2"><i
                                class="ri-mail-line me-1 text-white-75 fs-16 align-middle"></i>
                                <?php echo e($user->email); ?>

                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            <?php if($uid != Auth::user()->id): ?>
                                <?php if(Auth::user()->rol == 'administrador'): ?>
                                    <?php
                                        if($numero==1){
                                            $texto = "Club administrado";
                                        }else{
                                            $texto = "Clubes administrados";
                                        }
                                        
                                        //obtener el numero de clubes administrados por el usuario
                                        $numero = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->count();
                                    ?>
                                <?php elseif(Auth::user()->rol == 'colaborador'): ?>
                                    <?php
                                        if($numero==1){
                                            $texto = "Club inscrito";
                                        }else{
                                            $texto = "Clubes inscritos";   
                                        }
                                        //obtener el numero de clubes en los que el usuario esta inscrito
                                        $numero = DB::table('inscripciones')->where('id_alumno', Auth::user()->id)->count();
                                    ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <h4 class="text-white mb-1"><?php echo e($numero); ?></h4>
                            <p class="fs-14 mb-0"><?php echo e($texto); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->

        </div>
        <!--end row-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="d-flex">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link fs-14 active border" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Sobre <?php echo e($user->name); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-5">
                                            <?php echo e($club->nombre); ?>

                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Descripción</h5>
                                        <p>
                                            <?php echo e($user->descripcion); ?>

                                        </p>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->

                                
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <h5 class="card-title flex-grow-1 mb-0">Asistencias</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-borderless align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Nombre del evento</th>
                                                            <th scope="col">Fecha inicio del evento</th>
                                                            <th scope="col">Fecha final del evento</th>
                                                            <th scope="col">Total de horas registradas</th>
                                                            <th scope="col">Constancias</th>
                                                            <th scope="col">Acuses</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $asistencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
                                                            ?>
                                                            <tr>
                                                                <td><?php echo e($evento->nombre); ?></td>
                                                                <td><?php echo e($evento->fechaInicio); ?></td>
                                                                <td><?php echo e($evento->fechaFin); ?></td>
                                                                <td><?php echo e($asistencia->asistenciaTotal); ?></td>
                                                                <td>
                                                                    <a href="pages-constancias-form?uid=<?php echo e($asistencia->id); ?>"  class="btn btn-primary btn-sm">Generar constancia</a>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        //Obtener la constancia con el idAsistencia
                                                                        $constancia = DB::table('constancias')->where('idAsistencia', $asistencia->id)->first();
                                                                    ?>
                                                                    <?php if($constancia != NULL): ?>
                                                                        <a href="<?php echo e(URL::asset('acuses/' . $constancia->redaccion)); ?>" target="_blank" class="btn btn-primary btn-sm">
                                                                            Descargar acuse
                                                                        </a>
                                                                    <?php else: ?>
                                                                        <form action="<?php echo e(route('createAcuse')); ?>" method="POST" enctype="multipart/form-data">
                                                                            <?php echo csrf_field(); ?>
                                                                            <input type="hidden" name="idAsistencia_acuse" value="<?php echo e($asistencia->id); ?>">
                                                                            <input name="acuse_file" type="file" name="acuse" id="acuse">
                                                                            <button type="submit" class="btn btn-primary btn-sm">Subir acuse</button>
                                                                        </form>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                                

                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end tab-pane-->
                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('assets/js/pages/profile.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/pages-profile-view.blade.php ENDPATH**/ ?>