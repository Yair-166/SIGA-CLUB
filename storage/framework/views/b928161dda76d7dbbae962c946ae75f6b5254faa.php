<?php
    //Obtener dia actual
    $dia = date('d');
    //Obtener mes actual
    $mes = date('m');
    $meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $mes = $meses[$mes];
    //Obtener el año actual
    $anio = date('Y');
    //Obtener los datos de la base de datos de la tabla asistencias con el id
    $asistencia = DB::table('asistencias')->where('id', $id)->first();
    //Obtener el evento de la tabla eventos con el id del evento de la tabla asistencias
    $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
    //Obtener el club con el id_club de la tabla eventos
    $club = DB::table('clubes')->where('id', $evento->id_club)->first();
    //Obtener los datos del usuario con el idUsuario de la tabla asistencias
    $usuario = DB::table('users')->where('id', $asistencia->idUsuario)->first();
    //Obteneer las autoridades del club
    $autoridades = DB::table('autoridades')->where('idClub', $club->id)->get();
    
    $nuevasAutoridades = $request->input('autoridades_externo');
    //Convertir el string de autoridades en un json
    $autoridades_externo = json_decode($nuevasAutoridades);

    //Obtener todos los registros de la tabla confi_eventos con el id del evento
    $configuracion = DB::table('confi_eventos')->where('idEvento', $evento->id)->get();
    //Verificar si en algún registro de $configuracion el campo 'id_coordinador' es igual al id del usuario
    $coordinador = false;
    foreach($configuracion as $confi){
        if($confi->id_coordinador == $usuario->id){
            $coordinador = true;
        }
    }
    if($coordinador){
        $rolactividad = 'Coordinador';
    }else{
        $rolactividad = 'Participante';
    }


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="">

<head>
	<title>
		Constancia generada
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<style type="text/css">
        
		.ft10{font-size:16px;font-family:Times;color:#bf504d;}
		.ft11{font-size:14px;font-family:Times;color:#000000;}
		.ft12{font-size:14px;font-family:Times;color:#365e91;}
		.ft13{font-size:15px;font-family:Times;color:#000000;}
		.ft14{font-size:18px;font-family:Times;font-weight:bold;color:#000000;}
		.ft15{font-size:14px;font-family:Times;font-weight:bold;color:#000000;}
        
    </style>
</head>

<body>

    <div class="contenido">

        <div id="cabecera" style='margin-left: auto; margin-right: auto; text-align: center;'>

            <table style="border: 0px solid black; border-collapse: collapse; width: 100%;">
                <tr>
                    <td style='width: 18%; float: right'>
                        <img align="right" src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_IPN.png" height="150" />
                    </td>
                    <td style='margin-left: auto; margin-right: auto; text-align: center; width: 64%;'>
                        <span class="ft10">
                            INSTITUTO POLITÉCNICO NACIONAL
                        </span>
                                
                        </br>
                                
                        <span class="ft12">
                            <?php echo e($request->input('escuela')); ?>

                        </span>
                    </td>
                    <td style='width: 18%; float: left'>
                        <?php
                            $escuela = str_replace(' ', '', $request->input('escuela'));
                            //unir https://sigaclub.com/assets/images/escuelas/ con el nombre de la escuela
                            $url = 'https://sigaclub.com/assets/images/escuelas/'.$escuela.'.png';
                        ?>

                        <img src="<?php echo e($url); ?>" height="150"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style='margin-left: auto; margin-right: auto; text-align: center;'>

                        <span class="ft14">
                            CONSTANCIA
                        </span>

                        </br>

                        <span class="ft15" style='margin-left: auto; margin-right: auto; text-align: center;'>
                            <?php echo e($club->nomenclatura); ?>/<?php echo e($id); ?>/<?php echo e($anio); ?>

                        </span>
                    </td>
                </tr>
            </table>
        </div>
        </br>
        
        <div id="cuerpo" style='height:400px; width: 100%;'>

            <p align="left">
                <span class="ft11">
                    Por medio de la presente se hace constar que el alumno:
                </span>
            </p>

            <p align="left">
                <span class="ft15">
                    <?php echo e($usuario->name); ?> <?php echo e($usuario->apaterno); ?> <?php echo e($usuario->amaterno); ?>

                </span>
            </p>

            <p align="left" style='text-align: justify;'>
                <span class="ft11">
                    Con el número de boleta <b><?php echo e($usuario->boleta); ?></b>, participó en el <b>"<?php echo e($club->nombre); ?>”</b> en la actividad <b><?php echo e($evento->nombre); ?></b>
                    <?php if($evento->fechaInicio == $evento->fechaFin): ?>
                        el día <b><?php echo e($evento->fechaInicio); ?></b>
                    <?php else: ?>
                        en el periodo del <b><?php echo e($evento->fechaInicio); ?></b> al <b><?php echo e($evento->fechaFin); ?></b>
                    <?php endif; ?>
                    con el rol de <b><?php echo e($rolactividad); ?></b> y con un horario de <b><?php echo e($evento->horaInicio); ?> a <?php echo e($evento->horaFin); ?></b> horas. Sumando un total de horas de <b><?php echo e($asistencia->asistenciaTotal); ?> horas</b>.
                </span>
        
            </p>

            <p align="left">
                <span class="ft11">
                    <?php if($rolactividad == 'Coordinador'): ?>
                        <?php echo e($evento->redaccionCoordinador); ?>

                    <?php else: ?>
                        <?php echo e($evento->redaccionParticipante); ?>

                    <?php endif; ?>
                </span>
            </p>

            <p align="left">
                <span class="ft11">
                    No existiendo inconveniente alguno, se emite la presente a los <b><?php echo e($dia); ?> días del mes de <?php echo e($mes); ?> del 
                    <?php echo e($anio); ?></b> a solicitud expresa y para los fines que el (la) interesado (a) convengan.
                </span>
            </p>

            <p align="left">
                <span class="ft11">
                    <?php echo e($request->input('redaccion_ipn')); ?>

                </span>
            </p>

            </br></br>

            <p align="center">
                <span class="ft14">
                    ATENTAMENTE
                </span>
                </br>
                <span class="ft14">
                    "LA TÉCNICA AL SERVICIO DE LA PATRIA"
                </span>
            </p>

        </div>

        </br></br></br></br>

        <div id="pie" style='width: 100%;'>
            <table>
                <tr>
                <?php $__currentLoopData = $autoridades_externo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autoridad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($request->input('autoridad_'.$autoridad->id) == $autoridad->id): ?>
                        <td style='margin-left: auto; margin-right: auto; text-align: center;'>
                            <span class="ft15">
                                <b>
                                    ________________________________
                                </b>
                            </span>
                            </br>
                            <span class="ft13">
                                <?php echo e($autoridad->nombre); ?> <?php echo e($autoridad->aPaterno); ?> <?php echo e($autoridad->aMaterno); ?>

                            </span>
                            </br>
                            <span class="ft13">
                                <?php echo e($autoridad->cargo); ?>

                            </span>
                        </td>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </table>
        </div>

        </br></br>

        <table style="width: 100%;">
            <tr>
                <td colspan="2">
                    Verifica la autenticidad de esta constancia en:
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <?php
                        $qr = QrCode::size(300)->margin(0)->generate("http://panel.sigaclub.com/checkasistencia/".$asistencia->id);
                    ?>
                    
                    <img src="data:image/png;base64, <?php echo base64_encode($qr); ?> " width="120" height="120" />
                </td>
                <td align="right" style="width: 50%;">
                    <img src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" width="120" height="120" style="float:right" />
                </td>
            </tr>
        </table>
        
        
    </div>

</body>

</html><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/generar-constancia-ipn.blade.php ENDPATH**/ ?>