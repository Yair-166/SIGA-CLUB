
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.profile'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.css')); ?>">
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
    //Recibir datos via get
    $clubid = $_GET['club'];
    //Obtener los datos del club con el id del club
    $club = DB::table('clubes')->where('id', $clubid)->first();
    //Obtener todos los eventos del club
    $eventos = DB::table('eventos')->where('id_club', $clubid)->get();
    //Obtener el numero de eventos del club
    $totalEventos = DB::table('eventos')->where('id_club', $clubid)->count();
    //Obtener de la tabla users el nombre del administrador del club
    $admin = DB::table('users')->where('id', $club->idAdministrador)->first();
    //Obtener todas las inscripciones del club
    $inscripciones = DB::table('inscripciones')->where('id_club', $clubid)->where('active', 1)->get();
    //Obtener el numero de inscripciones activas
    $totalInscripciones = DB::table('inscripciones')->where('id_club', $clubid)->where('active', 1)->count();
    
    //Obtener todos los usuarios de la tabla users donde su id sea igual a inscripciones->id_alumno
    $usuarios = array();
    foreach ($inscripciones as $inscripcion) {
        //Obtener de la tabla users el nombre del administrador del club
        $usuario = DB::table('users')->where('id', $inscripcion->id_alumno)->first();
        //push a un arreglo de usuarios
        array_push($usuarios, $usuario);
    }

    //Obtener las asistencias de los eventos del club
    $asistencias = array();
    foreach ($eventos as $evento) {
        //Obtener de la tabla asistencias los registros que tengan el id del evento
        $asistenciasEvento = DB::table('asistencias')->where('idEvento', $evento->id)->get();
        //push a un arreglo de asistencias
        foreach ($asistenciasEvento as $asistencia) {
            array_push($asistencias, $asistencia);
        }
    }
    /*Imprimir las asistencias
    foreach ($asistencias as $asistencia) {
        print_r($asistencia);
    }*/

    $asistencias_previstas = array();
    foreach ($eventos as $evento) {
        //Obtener de la tabla asistencias los registros que tengan el id del evento
        $asistenciasPrevistas = DB::table('asistencias_previstas')->where('id_evento', $evento->id)->get();
        //push a un arreglo de asistencias
        foreach ($asistenciasPrevistas as $asistenciasPrevista) {
            array_push($asistencias_previstas, $asistenciasPrevista);
        }    
    }
    /*Imprimir las asistencias
    foreach ($asistencias_previstas as $asistencias_prevista) {
        print_r($asistencias_prevista);
    }*/

    //Obtener tags del club
    $tags = explode(",", $club->tags);
    //Eliminar el ultimo elemento del array
    array_pop($tags);
    //Quitar espacios en blanco
    $tags = array_map('trim', $tags);
    //Obtener el numero de tags
    $totalTags = count($tags);
    //Crear arreglo con tipos de eventos campamento, clase, Concurso, Conferencia, Curso, Entrenamiento, Evaluación, Exhibición, Exposición, Seminario, Torneo
    $tipos = "Campamento,Clase,Concurso,Conferencia,Curso,Entrenamiento,Evaluación,Exhibición,Exposición,Seminario,Torneo,Otro,";
    //Convertir el string en un arreglo
    $tipos = explode(",", $tipos);
    //Eliminar el ultimo elemento del array
    array_pop($tipos);
    
    $tiposString = implode(",", $tipos);
    $cantdidadEventos = array();
    foreach($tipos as $tipo){
        //Obtener la cantidad de eventos por tipo
        $cantidad = DB::table('eventos')->where('tipo', $tipo)->where('id_club', $clubid)->count();
        //Agregar la cantidad de eventos al arreglo
        array_push($cantdidadEventos, $cantidad);
    }
    //Convertir $cantdidadEventos a string
    $cantdidadEventos = implode(",", $cantdidadEventos);

    $numTags = array();
    foreach($tags as $tag)
    {
        $totalTagInscripciones = 0;
        foreach($inscripciones as $inscripcion)
        {
            if(strpos($inscripcion->tags, $tag) !== false)
            {
                $totalTagInscripciones++;
            }
        }
        //Agregar el numero de inscripciones al arreglo
        array_push($numTags, $totalTagInscripciones);
    }

    $tagsclub = implode(",", $tags);
    $numTags = implode(",", $numTags);

    $asistenciasxusuario = array();
    foreach($usuarios as $usuario)
    {
        //Obtener el numero de asistencias del usuario
        $totalAsistencias = 0;
        foreach($asistencias as $asistencia)
        {
            if($asistencia->idUsuario == $usuario->id)
            {
                $totalAsistencias++;
            }
        }
        //Agregar el numero de asistencias al arreglo
        array_push($asistenciasxusuario, $totalAsistencias);
    }

    //Obtener la fecha actual - 6 horas
    $fecha_actual = date("Y-m-d H:i:s", strtotime("-6 hours"));
    //Quitar las horas de la fecha actual
    $fecha_actual = date("Y-m-d", strtotime($fecha_actual));

    //Crear un mapa con los usuarios y sus asistencias
    $mapa = array();
    $edades = array();
    for($i = 0; $i < count($usuarios); $i++)
    {
        $mapa[$usuarios[$i]->name." ".$usuarios[$i]->apaterno." ".$usuarios[$i]->amaterno] = $asistenciasxusuario[$i];
        //Guardar en un tipo date la fecha de nacimiento
        $fecha_nacimiento = date("Y-m-d", strtotime($usuarios[$i]->fechaNac));
        //Restar la fecha actual - la fecha de nacimiento
        $dateDifference = abs(strtotime($fecha_actual) - strtotime($fecha_nacimiento));
        //Convertir la edad a años
        $edad  = floor($dateDifference / (365 * 60 * 60 * 24));
        //Agregar la edad al arreglo
        array_push($edades, $edad);
    }
    //Ordenar el mapa de mayor a menor
    arsort($mapa);
    asort($edades);


?>

    <input type="hidden" id="tiposString" name="tiposString" value="<?php echo e($tiposString); ?>">
    <input type="hidden" id="cantdidadEventosString" name="cantdidadEventos" value="<?php echo e($cantdidadEventos); ?>">

    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="<?php echo e(URL::asset('assets/images/profile-bg.jpg')); ?>" alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
        <div class="row g-4">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img src="<?php if($club->foto != ''): ?> <?php echo e(URL::asset('images/' . $club->foto)); ?><?php else: ?><?php echo e(URL::asset('assets/images/users/avatar-1.jpg')); ?> <?php endif; ?>"
                        alt="user-img" class="img-thumbnail rounded-circle" />
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1"><?php echo e($club->nombre); ?></h3>
                    <p class="text-white"><?php echo e($admin->name." ".$admin->apaterno." ".$admin->amaterno); ?></p>
                    <div class="hstack text-white gap-1">
                        <div class="me-2"><i
                                class="ri-mail-line me-1 text-white fs-16 align-middle"></i>
                                <?php echo e($admin->email); ?>

                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            
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
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Total de constancias expedidas</h5>
                                        <h5>
                                            <?php echo e($club->constanciasExpedidas); ?>

                                        </h5>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Total de eventos</h5>
                                        <h5>
                                            <?php echo e($totalEventos); ?>

                                        </h5>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3">
                                <a href="pages-team?club=<?php echo e($club->id); ?>">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Total de participantes inscritos</h5>
                                            <h5>
                                                <?php echo e($totalInscripciones); ?>

                                            </h5>
                                            <!--end row-->
                                        </div>
                                        <!--end card-body-->
                                    </div><!-- end card -->
                                </a>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Total de tags</h5>
                                        <h5>
                                            <?php echo e($totalTags); ?>

                                        </h5>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->
                            </div>
                            <!--end col-->

                            <div><p> </p></div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="genques-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseOne" aria-expanded="false" aria-controls="genques-collapseOne">
                                        Eventos
                                    </button>
                                </h2>
                                <div id="genques-collapseOne" class="accordion-collapse collapse collapsed" aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body row">
                                        <div class="col-lg-8">
                                            <div class="table-responsive">
                                                <table class="table table-borderless align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Tipo de evento</th>
                                                            <th scope="col">Cantidad de eventos</th>
                                                            <th scope="col">Participaciones confirmadas promedio</th>
                                                            <th scope="col">Participaciones promedio</th>
                                                            <th scope="col">Participantes promedio por género <b style="color:red;" title="Ver al pie de página">*</b></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $eventosTipo = array();
                                                                foreach ($eventos as $evento) {
                                                                    //Meter en un arreglo los eventos de cada tipo
                                                                    if ($evento->tipo == $tipo) {
                                                                        array_push($eventosTipo, $evento);
                                                                    }
                                                                }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo e($tipo); ?></td>
                                                                <td>
                                                                    <?php
                                                                        echo count($eventosTipo);
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        //Recorrer todo el array $eventosTipo y obtener aquellos donde el id del evento sea igual al id del evento en $asistencias_previstas
                                                                        $asistencias_previstasTipo = array();
                                                                        foreach ($eventosTipo as $eventoTipo) {
                                                                            foreach ($asistencias_previstas as $asistencia) {
                                                                                if ($asistencia->id_evento == $eventoTipo->id) {
                                                                                    array_push($asistencias_previstasTipo, $asistencia);
                                                                                }
                                                                            }
                                                                        }

                                                                        if(count($eventosTipo)==0){
                                                                            echo 0;
                                                                        }else{
                                                                            echo count($asistencias_previstasTipo)/count($eventosTipo);
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                         //Recorrer todo el array $eventosTipo y obtener aquellos donde el id del evento sea igual al id del evento en $asistencias
                                                                        $asistenciasTipo = array();
                                                                        foreach ($eventosTipo as $eventoTipo) {
                                                                            foreach ($asistencias as $asistencia) {
                                                                                if ($asistencia->idEvento == $eventoTipo->id) {
                                                                                    array_push($asistenciasTipo, $asistencia);
                                                                                }
                                                                            }
                                                                        }

                                                                        if(count($eventosTipo)==0){
                                                                            echo 0;
                                                                        }else{
                                                                            echo count($asistenciasTipo)/count($eventosTipo);
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        //Obtener el usuario de cada asistencia
                                                                        $usuariosTipo = array();
                                                                        foreach ($asistenciasTipo as $asistenciaTipo) {
                                                                            foreach ($usuarios as $usuario) {
                                                                                if ($usuario->id == $asistenciaTipo->idUsuario) {
                                                                                    array_push($usuariosTipo, $usuario);
                                                                                }
                                                                            }
                                                                        }

                                                                        //Obtener el género de cada usuario
                                                                        $generosTipo = array();
                                                                        foreach ($usuariosTipo as $usuarioTipo) {
                                                                            array_push($generosTipo, $usuarioTipo->genero);
                                                                        }

                                                                        $hombresTipo=0;
                                                                        $mujeresTipo=0;
                                                                        $neTipo=0;
                                                                        $nobTipo=0;

                                                                        //Obtener cuantos "Masculino" hay en el arreglo
                                                                        for($i=0; $i<count($generosTipo); $i++){
                                                                            if($generosTipo[$i] == 'Masculino'){
                                                                                $hombresTipo++;
                                                                            }
                                                                            else if ($generosTipo[$i] == 'Femenino') {
                                                                                $mujeresTipo++;
                                                                            }
                                                                            else if ($generosTipo[$i] == 'No binario') {
                                                                                $nobTipo++;
                                                                            }
                                                                            else {
                                                                                $neTipo++;
                                                                            }
                                                                        }

                                                                        if(count($eventosTipo)==0){
                                                                            echo 0;
                                                                        }else{
                                                                            echo "H: ".$hombresTipo/count($eventosTipo). " | ";
                                                                            echo "M: ".$mujeresTipo/count($eventosTipo). " | ";
                                                                            echo "N.E.: ".$nobTipo/count($eventosTipo). " | ";
                                                                            echo "N.E.: ".$neTipo/count($eventosTipo);
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title mb-0">Total de tipos de eventos</h4>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="Eventosclub" class="chartjs-chart" data-colors='["#344D67", "#6ECCAF"]'></canvas>
                                                </div>
                                            </div> 
                                        </div> <!-- end col -->
                                    </div>
                                </div>
                            </div>
                            <!--end eventos-->

                            <div><p> </p></div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="genques-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseTwo" aria-expanded="false" aria-controls="genques-collapseTwo">
                                        Participantes
                                    </button>
                                </h2>
                                <div id="genques-collapseTwo" class="accordion-collapse collapse" aria-labelledby="genques-headingTwo" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body row">
                                        <div class="col-lg-8">
                                            <div class="table-responsive">
                                                <table class="table table-borderless align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Etiquetas</th>
                                                            <th scope="col">Hombres</th>
                                                            <th scope="col">Mujeres</th>
                                                            <th scope="col">No binario</th>
                                                            <th scope="col">No especificado</th>
                                                            <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $masculinos = array();
                                                            $femeninos = array();
                                                            $nob = array();
                                                            $nes = array();

                                                            foreach ($usuarios as $usuario) {
                                                                $inscripcionTMP = DB::table('inscripciones')->where('id_alumno', $usuario->id)->first();
                                                                if($usuario->genero == 'Masculino'){
                                                                    array_push($masculinos, $inscripcionTMP);
                                                                }
                                                                else if ($usuario->genero == 'Femenino') {
                                                                    array_push($femeninos, $inscripcionTMP);
                                                                }
                                                                else if($usuario->genero == 'No binario'){
                                                                    array_push($nob, $inscripcionTMP);
                                                                }
                                                                else {
                                                                    array_push($nes, $inscripcionTMP);
                                                                }
                                                            }
                                                            $mConTag = 0;
                                                            $fConTag = 0;
                                                            $nobConTag = 0;
                                                            $nesConTag = 0;
                                                            $totalConTag = 0;

                                                            //Escribir los tags
                                                            foreach($tags as $tag){
                                                                $thismtag = 0;
                                                                $thisftag = 0;
                                                                $thisnobtag = 0;
                                                                $thisnetag = 0;

                                                                foreach($masculinos as $masculino){
                                                                    if(strpos($masculino->tags, $tag) !== false){
                                                                        $thismtag++;
                                                                    }
                                                                }

                                                                foreach($femeninos as $femenino){
                                                                    if(strpos($femenino->tags, $tag) !== false){
                                                                        $thisftag++;
                                                                    }
                                                                }

                                                                foreach($nob as $no){
                                                                    if(strpos($no->tags, $tag) !== false){
                                                                        $thisnobtag++;
                                                                    }
                                                                }

                                                                foreach($nes as $ne){
                                                                    if(strpos($ne->tags, $tag) !== false){
                                                                        $thisnetag++;
                                                                    }
                                                                }

                                                                //Crear arreglo bidimensional donde 

                                                                echo "<tr>";
                                                                echo "<td>".$tag."</td>";
                                                                echo "<td>" . $thismtag . "</td>";
                                                                echo "<td>" . $thisftag . "</td>";
                                                                echo "<td>" . $thisnobtag . "</td>";
                                                                echo "<td>" . $thisnetag . "</td>";
                                                                echo "<td>" . $thismtag + $thisftag + $thisnobtag + $thisnetag . "</td>";

                                                                $mConTag += $thismtag;
                                                                $fConTag += $thisftag;
                                                                $nobConTag += $thisnobtag;
                                                                $nesConTag += $thisnetag;
                                                                $totalConTag += $thismtag + $thisftag + $thisnobtag + $thisnetag;


                                                            }
                                                        ?>
                                                        <tr>
                                                            <td>Sin tag</td>
                                                            <td><?php echo e(count($masculinos) - $mConTag); ?></td>
                                                            <td><?php echo e(count($femeninos) - $fConTag); ?></td>
                                                            <td><?php echo e(count($nob) - $nobConTag); ?></td>
                                                            <td><?php echo e(count($nes) - $nesConTag); ?></td>
                                                            <td><?php echo e($totalInscripciones - $totalConTag); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Totales *Incluye sin tag</td>
                                                            <td><?php echo e(count($masculinos)); ?></td>
                                                            <td><?php echo e(count($femeninos)); ?></td>
                                                            <td><?php echo e(count($nob)); ?></td>
                                                            <td><?php echo e(count($nes)); ?></td>
                                                            <td><?php echo e($totalInscripciones); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <h5 class="card-title mb-4">Participantes más activos</h5>
                                            <?php
                                                //Si el mapa es mayor a 0, entonces hay participantes
                                                if(count($mapa) > 0){
                                                    //Ordenar el mapa de mayor a menor
                                                    arsort($mapa);
                                                    //Obtener los 5 primeros
                                                    $mapa = array_slice($mapa, 0, 3);
                                                    //Imprimir los 5 primeros
                                                    foreach($mapa as $key => $value){
                                                        echo "<p class='text-muted mb-4'><span class='text-primary'>". $key . "</span> - " . $value . " participaciones</p>";
                                                    }
                                                }
                                                else{
                                                    echo "<p class='text-muted mb-4'>No hay participantes</p>";
                                                }
                                            ?>
                                            <h5 class="card-title mb-4">Edades de participantes</h5>
                                            <?php
                                                //Si el mapa es mayor a 0, entonces hay participantes
                                                if(count($edades) > 0){
                                                    //Ordenar el mapa de mayor a menor
                                                    arsort($edades);                                               
                                                    //Imprimir el mayor
                                                    echo "<p class='text-muted mb-4'><span class='text-primary'>Mayor</span> - " . $edades[count($edades) - 1] . " años</p>";
                                                    echo "<p class='text-muted mb-4'><span class='text-primary'>Menor</span> - " . $edades[0] . " años</p>";
                                                    //Imprimir el promedio
                                                    $promedio = 0;
                                                    foreach($edades as $edad){
                                                        $promedio += $edad;
                                                    }
                                                    $promedio = $promedio / count($edades);
                                                    echo "<p class='text-muted mb-4'><span class='text-primary'>Promedio</span> - " . $promedio . " años</p>";
                                                }
                                                else{
                                                    echo "<p class='text-muted mb-4'>No hay participantes</p>";
                                                }
                                            ?>
                                        </div>
                                        <input type="hidden" id="masculinos" value="<?php echo e(count($masculinos)); ?>">
                                        <input type="hidden" id="femeninos" value="<?php echo e(count($femeninos)); ?>">
                                        <input type="hidden" id="nes" value="<?php echo e(count($nes)); ?>">
                                        <input type="hidden" id="nob" value="<?php echo e(count($nob)); ?>">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title mb-0">Participantes por género</h4>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="genero" class="chartjs-chart" data-colors='["#344D67", "#6ECCAF", "#ADE792", "#F3ECB0"]'></canvas>
                                                </div>
                                            </div> 
                                        </div> <!-- end col -->
                                        <?php if($totalTags > 0): ?>
                                            <input type="hidden" id="tagsclub" value="<?php echo e($tagsclub); ?>">
                                            <input type="hidden" id="numTags" value="<?php echo e($numTags); ?>">
                                            <div class="col-sm-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title mb-0">Participantes por Tag</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="tags" class="chartjs-chart" data-colors='["#344D67", "#6ECCAF", "#ADE792"]'></canvas>
                                                    </div>
                                                </div> 
                                            </div> <!-- end col -->
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <!--end Participantes-->

                            <div><br><p>
                            <b style="color:red;">*</b> H: Hombres, M: Mujeres, N.B.: No binario, N.E.: No especificado.
                            </p></div>

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
    <script src="<?php echo e(URL::asset('assets/libs/list.js/list.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/list.pagination.js/list.pagination.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/js/pages/invoiceslist.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/chart.js/chart.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/js/pages/chartjs.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/dashboard.blade.php ENDPATH**/ ?>