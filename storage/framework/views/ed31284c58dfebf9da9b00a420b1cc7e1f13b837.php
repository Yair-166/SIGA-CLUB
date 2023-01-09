
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.profile'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?>
            Perfil
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Perfil
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
<?php
    //Recibir datos via get
    $uid = $_GET['uid'];
    //Obtener los datos de la tabla inscripciones con el uid
    $inscripcion = DB::table('inscripciones')->where('id', $uid)->first();
    //Obtener los datos del club con el id del club
    $club = DB::table('clubes')->where('id', $inscripcion->id_club)->first();
    //Obtener todos los eventos del club
    $eventos = DB::table('eventos')->where('id_club', $inscripcion->id_club)->get();

    //Obtener todas las asistencias a los eventos que se tienen
    $asistencias = array();
    foreach ($eventos as $evento) {
        $asistencia = DB::table('asistencias')->where('idEvento', $evento->id)->where('idUsuario', $inscripcion->id_alumno)->first();
        if($asistencia){
            array_push($asistencias, $asistencia);
        }
    }

    //Obtener todas las asistencias del usuario con el id del usuario en el club $inscripcion->id_club
    //$asistencias = DB::table('asistencias')->where('idUsuario', $inscripcion->id_alumno)->get();
    //Obtener el usuario de la base de datos por el id
    $user = DB::table('users')->where('id', $inscripcion->id_alumno)->first();
    $texto = $numero = "";

    //Crear arreglo con tipos de eventos campamento, clase, Concurso, Conferencia, Curso, Entrenamiento, Evaluación, Exhibición, Exposición, Seminario, Torneo
    $tipos = "Campamento,Clase,Concurso,Conferencia,Curso,Entrenamiento,Evaluación,Exhibición,Exposición,Seminario,Torneo,Otro,";
    //Convertir el string en un arreglo
    $tipos = explode(",", $tipos);
    //Eliminar el ultimo elemento del array
    array_pop($tipos);
    $tiposCount = count($tipos);
    //De cada asistencia obtener el evento y asistenciaTotal
    $asistenciasTotales = array();
    foreach($tipos as $tipo){
        $asistenciaTotal = 0;
        foreach($asistencias as $asistencia){
            //Obtener el evento con el id del evento de la asistencia
            $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
            //Si el tipo del evento es igual al tipo del arreglo $tipos
            if($evento->tipo == $tipo){
                //Sumar la asistencia total de cada evento
                $asistenciaTotal = $asistenciaTotal + $asistencia->asistenciaTotal;
            }
        }
        //Agregar la asistencia total al arreglo $asistenciasTotales
        array_push($asistenciasTotales, $asistenciaTotal);
    }
    $asistenciasTotalesString =  implode(",", $asistenciasTotales);
    $tiposAlumnoString = implode(",", $tipos);
    //Obtener el total de horas de asistencia del usuario
    $totalHoras = 0;
    foreach($asistencias as $asistencia){
        $totalHoras = $totalHoras + $asistencia->asistenciaTotal;
    }

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
                            <?php if($inscripcion->tags != ''): ?>
                                <h3>Tags del usuario</h3>
                                <?php
                                    $tags = explode(",", $inscripcion->tags);
                                    //Eliminar el ultimo elemento del array
                                    array_pop($tags);
                                ?>
                                <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-primary"><?php echo e($tag); ?>

                                        <a type="button" class="badge btn-danger" href=<?php echo e(route('eliminarTagInscripcion', ['id' => $uid, 'tag' => $tag])); ?>>
                                            <i class="mdi mdi-close"></i>
                                        </a>
                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
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
                                        <h4 class="card-title mb-5">
                                            <?php echo e($club->nombre); ?>

                                        </h4>
                                        
                                        <?php if($club->tags != ''): ?>
                                            <?php
                                                $tagsDisponibles = explode(",", $club->tags);
                                                //Eliminar el ultimo elemento del array
                                                array_pop($tagsDisponibles);
                                            ?>
                                            <form action="<?php echo e(route('agregarTagInscripcion', ['id' => $uid])); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <h4 class="card-title mb-4">Agregar TAG</h4>
                                                <select class="form-select" name="tag">
                                                    <?php $__currentLoopData = $tagsDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($tag); ?>"><?php echo e($tag); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <br>
                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-9">
                                <div class="card">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="genques-headingOne">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseOne" aria-expanded="false" aria-controls="genques-collapseOne">
                                                Desgloce de horas por tipo de evento en el club (Hotas totales: <?php echo e($totalHoras); ?>)
                                            </button>
                                        </h2>
                                        <input type="hidden" id="tiposAlumnoString" value="<?php echo e($tiposAlumnoString); ?>">
                                        <input type="hidden" id="asistenciasTotalesString" value="<?php echo e($asistenciasTotalesString); ?>">
                                        <div id="genques-collapseOne" class="accordion-collapse collapse collapsed" aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion">
                                            <div class="accordion-body row">
                                                <center>
                                                    <div class="col-sm-7">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="card-title mb-0">Horas por tipo de evento</h4>
                                                            </div>
                                                            <div class="card-body">
                                                                <canvas id="HorasxEvento" class="chartjs-chart" data-colors='["#344D67", "#6ECCAF"]'></canvas>
                                                            </div>
                                                        </div> 
                                                    </div> <!-- end col -->
                                                </center>
                                            </div>
                                        </div>
                                    </div>
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
                                                                //Obtener la constancia con el idAsistencia
                                                                $constancia = DB::table('constancias')->where('idAsistencia', $asistencia->id)->first();
                                                            ?>
                                                            <tr>
                                                                <td><?php echo e($evento->nombre); ?></td>
                                                                <td><?php echo e($evento->fechaInicio); ?></td>
                                                                <td><?php echo e($evento->fechaFin); ?></td>
                                                                <td><?php echo e($asistencia->asistenciaTotal); ?></td>
                                                                <td>
                                                                    <?php if($constancia == NULL): ?>
                                                                        <a href="pages-constancias-form?uid=<?php echo e($asistencia->id); ?>"  class="btn btn-primary btn-sm">Generar constancia</a>
                                                                    <?php else: ?>
                                                                        <?php if($constancia->redaccion == "False"): ?>
                                                                            <a href="<?php echo e(URL::asset('toogleAcuse/' . $constancia->id . '/False' )); ?>" class="btn btn-primary btn-sm">
                                                                                <i class="ri-eye-fill"> Permitir descarga</i>
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <a href="<?php echo e(URL::asset('toogleAcuse/' . $constancia->id . '/True' )); ?>" class="btn btn-primary btn-sm">
                                                                                <i class="ri-eye-off-fill"> No permitir descarga</i>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php if($constancia != NULL): ?>
                                                                        <a href="<?php echo e(URL::asset('acuses/' . $constancia->acuse)); ?>" target="_blank" class="btn btn-primary btn-sm">
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
    <script src="<?php echo e(URL::asset('assets/libs/chart.js/chart.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/js/pages/chartjs.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/pages-profile-view.blade.php ENDPATH**/ ?>