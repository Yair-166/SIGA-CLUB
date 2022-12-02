<?php
    //Obtener dia actual
    $dia = date('d');
    //Obtener mes actual
    $mes = date('m');
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
		.ft13{font-size:13px;font-family:Times;color:#000000;}
		.ft14{font-size:17px;font-family:Times;font-weight:bold;color:#000000;}
		.ft15{font-size:14px;font-family:Times;font-weight:bold;color:#000000;}
        .ft16{font-size:30px;font-family:Times;color:#000000;}
        .ft17{font-size:14px;font-family:Times;color:#000000;}
        
    </style>
</head>

<body>

    <div class="contenido">

        <div id="cabecera" style='margin-left: auto; margin-right: auto; text-align: center;'>
            <table style="border: 0px solid black; border-collapse: collapse; width: 100%;">
                <tr>
                    <td style='width: 18%; float: left'>
                        <img align="left" src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" height="120" />
                        </br></br></br></br></br></br></br>
                        <span class="ft14" style='margin-left: auto; margin-right: auto; text-align: right;' align="right">
                        CONSTANCIA
                        </span>
                    </td>
                    <td style='margin-left: auto; margin-right: auto; text-align: right; width: 64%;'>
                        	<span class="ft15" style='margin-left: auto; margin-right: auto; text-align: right;'>
                            <?php echo e($club->nomenclatura); ?>/<?php echo e($id); ?>/<?php echo e($anio); ?>

                        </span>
                    </td>
             
                </tr>
            </table>
        </div>
        
        <div id="cuerpo" style='width: 100%;'>

			<p align="left">
                <span style="font-size:22px;font-family:Times;color:#000000;">
                    Participó en el <b>"<?php echo e($club->nombre); ?>”</b> en la actividad <b><?php echo e($evento->nombre); ?></b>
                </span>
                <br>
                <p align="left">
                <?php if($rolactividad == 'Coordinador'): ?>
                    <?php echo e($evento->redaccionCoordinador); ?>

                <?php else: ?>
                    <?php echo e($evento->redaccionParticipante); ?>

                <?php endif; ?>
                <br>
                <?php echo e($request->input('redaccion')); ?>

            </p>
            <p align="left">
                <span style="font-size:28px;font-family:Times;color:#000000;">
                    <?php echo e($usuario->name); ?> <?php echo e($usuario->apaterno); ?> <?php echo e($usuario->amaterno); ?>

                </span>
            </p>
            <p align="left">
				<span class="ft17">
                    Fecha: 
					<?php if($evento->fechaInicio == $evento->fechaFin): ?>
                        <b><?php echo e($evento->fechaInicio); ?></b>
                    <?php else: ?>
                        <b><?php echo e($evento->fechaInicio); ?></b> al <b><?php echo e($evento->fechaFin); ?></b>
                    <?php endif; ?>
                    </br>
                    Duración: <b><?php echo e($asistencia->asistenciaTotal); ?> horas</b>.
                    </br>
                    Rol: <b><?php echo e($rolactividad); ?></b>.
                </span>
            </p>
        
        </div>

        </br></br></br></br></br>

        <div id="pie" style='width: 100%;'>
            <table>
                <tr>
                <?php $__currentLoopData = $autoridades_externo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autoridad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($request->input('autoridad'.$autoridad->id) == $autoridad->id): ?>
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
        


            <table style="width: 100%;">
                <tr>
                    <td style='margin-left: auto; margin-right: auto; text-align: right;'>
                        Verifica la autenticidad de esta constancia en:
                        <?php
                            $qr = QrCode::size(300)->margin(0)->generate("http://panel.sigaclub.com/checkasistencia/".$asistencia->id);
                        ?>
                        
                        <img src="data:image/png;base64, <?php echo base64_encode($qr); ?> " width="120" height="120" />
                    </td>
                    
                </tr>
            </table>
        
        </div>
        
        
    </div>

</body>

</html><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/generar-constancia-externa.blade.php ENDPATH**/ ?>