
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
            Constancia
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
<?php
    //Recibir datos via get
    $uid = $_GET['uid'];
    //Obtener todas las asistencias del usuario con el id del usuario
    $asistencia = DB::table('asistencias')->where('id', $uid)->first();
    //Obtener el evento con el idEvento de la asistencia
    $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
    //Obtener el club 
    $club = DB::table('clubes')->where('id', $evento->id_club)->first();
    //Obtener confi_evento con el idEvento de la asistencia
    $confi_evento = DB::table('confi_eventos')->where('idEvento', $asistencia->idEvento)->first();
    //Obtener las filas de confi_evento donde el idEvento sea el mismo que la asistencia y coordinador sea diferente a -1
    $coordinadores = DB::table('confi_eventos')->where('idEvento', $asistencia->idEvento)->where('id_coordinador', '!=', '-1')->get();

    //Obtener las autoridades del club
    $autoridades = DB::table('autoridades')->where('idClub', $evento->id_club)->get();
    $contador = DB::table('autoridades')->where('idClub', $evento->id_club)->count();
    
    foreach($coordinadores as $coordinador){
        $coordinador = DB::table('users')->where('id', $coordinador->id_coordinador)->first();
        //Guardar datos del coordinador en un array nuevo
        $coordinadores_array[] = new stdClass();
        $coordinadores_array[count($coordinadores_array)-1]->id = $contador + 1;
        $coordinadores_array[count($coordinadores_array)-1]->nombre = $coordinador->name;
        $coordinadores_array[count($coordinadores_array)-1]->aPaterno = $coordinador->apaterno;
        $coordinadores_array[count($coordinadores_array)-1]->aMaterno = $coordinador->amaterno;
        $coordinadores_array[count($coordinadores_array)-1]->cargo = "Coordinador de la actividad";
        //Push coordinadores_array en autoridades
        $autoridades->push($coordinadores_array[count($coordinadores_array)-1]);
    }

    //Obtener el usuario de la base de datos por el id
    $user = DB::table('users')->where('id', $asistencia->idUsuario)->first();
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-n4 mx-n4 border-0">
                <div class="bg-soft-primary">
                    <div class="card-body pb-0 px-4">

                        <ul class="nav nav-tabs-custom border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#externa"
                                    role="tab">
                                    Constancia de evento
                                </a>
                            </li>

                            <?php if($user->boleta != NULL && Auth::user()->boleta != NULL): ?>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#ipn" role="tab">
                                        Constancia de evento IPN
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                        </ul>
                    </div>
                    <!-- end card body -->
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="externa" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-12 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <form action="<?php echo e(route('pdf2', [$asistencia->id, 0])); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                            <h6 class="mb-3 fw-semibold text-uppercase">Descripci??n</h6>
                                            <label for="redaccion">Texto personalizado para la constancia</label>
                                            <textarea class="form-control" name="redaccion" id="redaccion" rows="3" placeholder="Escribe aqu?? tu texto personalizado para la constancia"></textarea>
                                            <br>
                                            <input hidden name="autoridades_externo" value="<?php echo e($autoridades); ?>">
                                            <label for="autoridades" class="form-label">Autoridades que firmar??n la constancia</label>
                                            <?php $__currentLoopData = $autoridades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autoridad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check form-switch form-switch-md" dir="ltr">
                                                    <input type="checkbox" class="form-check-input" name="autoridad<?php echo e($autoridad->id); ?>" 
                                                    value="<?php echo e($autoridad->id); ?>">
                                                    <label class="form-check-label" for="customSwitchsizemd"><?php echo e($autoridad->nombre); ?> <?php echo e($autoridad->aPaterno); ?> <?php echo e($autoridad->aMaterno); ?></label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <br>
                                            <button type="submit" class="btn btn-primary">Generar PDF</button>
                                        </form>
                                        
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- ene col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end tab pane -->

                <div class="tab-pane fade" id="ipn" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-12 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <form action="<?php echo e(route('pdf2', [$asistencia->id, 1])); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                            <h6 class="mb-3 fw-semibold text-uppercase">Descripci??n</h6>
                                            <label for="redaccion">Texto personalizado para la constancia</label>
                                            <textarea class="form-control" name="redaccion_ipn" id="redaccion" rows="3" placeholder="Escribe aqu?? tu texto personalizado para la constancia"></textarea>
                                            <br>
                                            <input hidden name="autoridades_externo" value="<?php echo e($autoridades); ?>">
                                            <label for="autoridades" class="form-label">Autoridades que firmar??n la constancia</label>
                                            <?php $__currentLoopData = $autoridades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autoridad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check form-switch form-switch-md" dir="ltr">
                                                    <input type="checkbox" class="form-check-input" name="autoridad_<?php echo e($autoridad->id); ?>" 
                                                    value="<?php echo e($autoridad->id); ?>">
                                                    <label class="form-check-label" for="customSwitchsizemd"><?php echo e($autoridad->nombre); ?> <?php echo e($autoridad->aPaterno); ?> <?php echo e($autoridad->aMaterno); ?></label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <br>
                                            <label for="Escuela" class="form-label">Escuela</label>
                                            <select class="form-select" aria-label="Default select example" name="escuela">
                                                <option value="Centro Interdisciplinario de Ciencias de la Salud Unidad Santo Tom??s" >CICS Unidad Santo Tom??s</option>
                                                <option value="Centro Interdisciplinario de Ciencias de la Salud Unidad Milpa Alta" >CICS Unidad Milpa Alta</option>
                                                <option value="Escuela Nacional de Biblioteconom??a y Archivonom??a" >ENBA </option>
                                                <option value="Escuela Nacional de Ciencias Biol??gicas" >ENCB </option>
                                                <option value="Escuela Nacional de Medicina y Homeopat??a" >ENMyH </option>
                                                <option value="Escuela Superior de Comercio y Administraci??n Unidad Santo Tom??s" >ESCA Unidad Santo Tom??s</option>
                                                <option value="Escuela Superior de Comercio y Administraci??n Unidad Tepepan" >ESCA Unidad Tepepan</option>
                                                <option value="Escuela Superior de C??mputo" >ESCOM </option>
                                                <option value="Escuela Superior de Econom??a" >ESE </option>
                                                <option value="Escuela Superior de Enfermer??a y Obstetricia" >ESEO </option>
                                                <option value="Escuela Superior de F??sica y Matem??ticas" >ESFM </option>
                                                <option value="Escuela Superior de Ingenier??a Mec??nica y El??ctrica Unidad Zacatenco" >ESIME Unidad Zacatenco</option>
                                                <option value="Escuela Superior de Ingenier??a Mec??nica y El??ctrica Unidad Azcapotzalco" >ESIME Unidad Azcapotzalco</option>
                                                <option value="Escuela Superior de Ingenier??a Mec??nica y El??ctrica Unidad Culhuac??n" >ESIME Unidad Culhuac??n</option>
                                                <option value="Escuela Superior de Ingenier??a Mec??nica y El??ctrica Unidad Ticom??n" >ESIME Unidad Ticom??n</option>
                                                <option value="Escuela Superior de Ingenier??a Qu??mica e Industrias Extractivas" >ESIQIE </option>
                                                <option value="Escuela Superior de Ingenier??a Textil" >ESIT </option>
                                                <option value="Escuela Superior de Ingenier??a y Arquitectura Unidad Tecamachalco" >ESIA Unidad Tecamachalco</option>
                                                <option value="Escuela Superior de Ingenier??a y Arquitectura Unidad Ticom??n" >ESIA Unidad Ticom??n</option>
                                                <option value="Escuela Superior de Ingenier??a y Arquitectura Unidad Zacatenco" >ESIA Unidad Zacatenco</option>
                                                <option value="Escuela Superior de Medicina" >ESM </option>
                                                <option value="Escuela Superior de Turismo" >EST </option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingenier??a Campus Coahuila" >UPIIC Campus Coahuila</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Biotecnolog??a" >UPIBI </option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingenier??a Campus Guanajuato" >UPIIG Campus Guanajuato</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingenier??a Campus Zacatecas" >UPIIZ Campus Zacatecas</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingenier??a Campus Hidalgo" >UPIIH Campus Hidalgo</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingenier??a Campus Palenque" >UPIIP Campus Palenque</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingenier??a Campus Tlaxcala" >UPIIT Campus Tlaxcala</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingenier??a y Ciencias Sociales y Administrativas" >UPIICSA </option>
                                                <option value="Unidad Profesional Interdisciplinaria en Ingenier??a y Tecnolog??as Avanzadas" >UPIITA </option>
                                                <option value="Unidad Profesional Interdisciplinaria de Energ??a y Movilidad" >UPIEM </option>
                                            </select>
                                            <br>
                                            <button type="submit" class="btn btn-primary">Generar PDF</button>
                                        </form>
                                        
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- ene col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end tab pane -->

            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('assets/js/pages/profile.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/pages-constancias-form.blade.php ENDPATH**/ ?>