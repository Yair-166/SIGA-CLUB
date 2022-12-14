
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.companies'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('assets/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Clubes
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Asistencias
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php
        //Obtener el id del usuario que esta logueado
        $idUsuario = Auth::user()->id;

        //Verificar si se envian datos via get
        if(isset($_GET['evento'])){
            //Guardar en variables los datos enviados via get
            $evento_reg = $_GET['evento'];
            //Guardar en la variable $token el valor de token
            $token_reg = $_GET['token'];

            $evento_as_reg = DB::table('eventos')->where('id', $evento_reg)->first();

            //Obtener todas las filas de la tabla confi_eventos donde el idEvento sea igual a $evento_reg
            $confi_reg = DB::table('confi_eventos')->where('idEvento', $evento_reg)->get();

            //Variable para guardar el rol del usuario en el evento
            $rol_reg = "participante";

            //Variable para verificar si el token es correcto
            $token_validate = false;

            //Verificar si alguna fila de $confi_reg tiene el valor de $idUsuario en el campo id_coordinador
            foreach($confi_reg as $confi){
                if($confi->id_coordinador == $idUsuario){
                    $rol_reg = "coordinador";
                }
                //Verificar si ultimoQR o qrActual de la tabla confi_eventos es igual a $token_reg
                if($confi->ultimoQR == $token_reg || $confi->qrActual == $token_reg){
                    $token_validate = true;
                }
            }

            $tipo_reg = $evento_as_reg->tipoAsistencia;
            if($tipo_reg == "Total"){
                $horaEntrada = $evento_as_reg->horaInicio;
            }else{
                $horaEntrada = date("H:i:s");
            }

            $horaSalida = $evento_as_reg->horaFin;

            //Saber cuantos días hay entre la fecha de inicio y la fecha de fin del evento
            $fechaInicio = $evento_as_reg->fechaInicio;
            $fechaFin = $evento_as_reg->fechaFin;
            $fechaInicio = strtotime($fechaInicio);
            $fechaFin = strtotime($fechaFin);
            $diferencia = $fechaFin - $fechaInicio;
            $dias = $diferencia / (60 * 60 * 24);
            $dias = $dias + 1;

            //Sacar el total de horas del evento restando la hora de salida menos la hora de entrada
            $asistenciaTotal = strtotime($horaSalida) - strtotime($horaEntrada);
            $asistenciaTotal = $asistenciaTotal / (60 * 60);
            $asistenciaTotal = $asistenciaTotal * $dias;
            
            $constanciaGenerada = false;

            //Verificar si el token es correcto
            if($token_validate){
                //Verificar si el usuario ya tiene una asistencia registrada en el evento
                $asistencia = DB::table('asistencias')->where('idEvento', $evento_reg)->where('idUsuario', $idUsuario)->first();
                //Si no tiene una asistencia registrada
                if($asistencia == null){
                    //Insertar datos en la tabla asistencias en los campos idEvento, idUsuario, rolUsuario, tipoAsistencia, horaEntrada, horaSalida, asistenciaTotal, constanciaGenerada 
                    DB::table('asistencias')->insert([
                        'idEvento' => $evento_reg,
                        'idUsuario' => $idUsuario,
                        'rolUsuario' => $rol_reg,
                        'tipoAsistencia' => $tipo_reg,
                        'horaEntrada' => $horaEntrada,
                        'horaSalida' => $horaSalida,
                        'asistenciaTotal' => $asistenciaTotal,
                        'constanciaGenerada' => $constanciaGenerada
                    ]);
                }             
            }
        }

        //Obtener las asistencias del usuario logueado
        $asistencias = DB::table('asistencias')->where('idUsuario', $idUsuario)->get();
    ?>
    <div class="row">
    <?php if(Auth::user()->rol == 'administrador'): ?>
       <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i class="ri-add-fill me-1 align-bottom"></i> Agregar club</button>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
    <?php endif; ?>  

        <div class="col-xxl-12">
            <div class="card" id="companyList">
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th data-sort="name" scope="col">Nombre del club</th>
                                        <th data-sort="owner" scope="col">Evento</th>
                                        <th data-sort="date" scope="col">Rol del usuario</th>
                                        <th data-sort="location" scope="col">Constancias</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                
                                <?php $__currentLoopData = $asistencias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asistencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        //Obtener los datos del evento
                                        $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
                                        //Obtener los datos del club
                                        $club = DB::table('clubes')->where('id', $evento->id_club)->first();
                                    ?>
                                    <tbody class="list form-check-all">
                                    <tr>
                                        <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                </div>
                                                <div class="flex-grow-1 ms-2 name">
                                                    <?php echo e($club->nombre); ?>

                                                </div>
                                            </div>
                                        </td>
                                        <td class="owner">
                                            <a href="/apps-eventos-overview?evento=<?php echo e($asistencia->idEvento); ?>" class="fw-medium link-primary">
                                                <?php echo e($evento->nombre); ?>

                                            </a>
                                        </td>
                                        <td>
                                            <?php echo e($asistencia->rolUsuario); ?>

                                        </td>
                                        <td class="location">
                                            <?php if($asistencia->constanciaGenerada == 0): ?>
                                                La constancia está en proceso.
                                            <?php else: ?>
                                                <?php
                                                    //Obtener la constancia con el idAsistencia
                                                    $constancia = DB::table('constancias')->where('idAsistencia', $asistencia->id)->first();
                                                ?>
                                                <?php if($constancia->redaccion == "True"): ?>
                                                    <a href="<?php echo e(URL::asset('acuses/' . $constancia->acuse)); ?>" class="btn btn-primary" target="_blank">
                                                        Descargar constancia / acuse
                                                    </a>
                                                <?php else: ?>
                                                    <h6>
                                                        Obtén tu constancia con el administrador del club.<i class="ri-check-line"></i>
                                                    </h6>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--end card-->
        </div><!--end col-->

    </div><!--end row-->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('/assets/libs/list.js/list.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/list.pagination.js/list.pagination.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/pages/crm-companies.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
    <script>
        function eliminarid(id){
            let clubid = id;
            console.log(clubid);
            document.getElementById("idelimnar").value = clubid;
        }
        function mostrar(data){
            //Separar los datos
            let datos = data.split(";");
            //Asignar los datos a los campos
            document.getElementById("info-foto").src = "/images/" + datos[0];
            document.getElementById("info-name").innerHTML = datos[1];
            document.getElementById("info-admin").innerHTML = datos[2];
            document.getElementById("info-description").innerHTML = datos[3];
            document.getElementById("info-location").innerHTML = datos[4];
            
        }
        function editarModal(data){
            //Separar los datos
            let dats = data.split(";");
            console.log(dats);

            //Asignar los datos a los campos al placeholder 
            document.getElementById("editar-foto").src = "/images/" + dats[0];
            document.getElementById("editar-name").placeholder = dats[1];
            document.getElementById("editar-description").placeholder = dats[3];
            document.getElementById("editar-location").placeholder = datos[4];
            document.getElementById("editar-nomenclatura").placeholder = datos[5];
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/asistencias.blade.php ENDPATH**/ ?>