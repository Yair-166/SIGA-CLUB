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
                            ESCUELA SUPERIOR DE CÓMPUTO
                        </span>
                    </td>
                    <td style='width: 18%; float: left'>
                        <img src="https://pbs.twimg.com/profile_images/1423089146/escom_400x400.png" height="150" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style='margin-left: auto; margin-right: auto; text-align: center;'>

                        <span class="ft14">
                            CONSTANCIA
                        </span>

                        </br>

                        <span class="ft15" style='margin-left: auto; margin-right: auto; text-align: center;'>
                            <?php echo e($club->nomenclatura); ?><?php echo e($club->nombre); ?>/<?php echo e($id); ?>/<?php echo e($anio); ?>

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

            <p align="left">
                <span class="ft11">
                    Con el número de boleta <b><?php echo e($usuario->boleta); ?></b>, participo en el <b>"<?php echo e($club->nombre); ?>”</b> en la actividad <b><?php echo e($evento->nombre); ?></b>
                    <?php if($evento->fechaInicio == $evento->fechaFin): ?>
                        el día <b><?php echo e($evento->fechaInicio); ?></b>
                    <?php else: ?>
                        en el periodo del <b><?php echo e($evento->fechaInicio); ?></b> al <b><?php echo e($evento->fechaFin); ?></b>
                    <?php endif; ?>
                    con un horario de <b><?php echo e($evento->horaInicio); ?> a <?php echo e($evento->horaFin); ?></b> horas. Sumando un total de horas de <b><?php echo e($asistencia->asistenciaTotal); ?></b> horas.
                </span>
        
            </p>

            <p align="left">
                <span class="ft11">
                    Las actividades del <?php echo e($club->nombre); ?> son extracurriculares y giran en torno a desarrollar un alumno integral y complementar su formación académica.
                </span>
            </p>

            <p align="left">
                <span class="ft11">
                    No existiendo inconveniente alguno, se emite la presente a los <b><?php echo e($dia); ?> días del mes de <?php echo e($mes); ?> del 
                    <?php echo e($anio); ?></b> a solicitud expresa y para los fines que el (la) interesado (a) convengan.
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

        </br></br></br></br></br>

        <div id="pie" style='width: 100%;'>
            <table>
                <tr>
                <?php $__currentLoopData = $autoridades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autoridad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </table>
        </div>
    
    </div>

</body>

</html><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/generar-constancia-ipn.blade.php ENDPATH**/ ?>