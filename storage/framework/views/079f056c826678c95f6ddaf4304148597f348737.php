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
                        <img align="right" src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" height="150" />
                        </br></br></br></br></br></br></br></br></br>
                        <span class="ft14" style='margin-left: auto; margin-right: auto; text-align: center;'>
                            CONSTANCIA
                        </span>
                    </td>
                    <td style='margin-left: auto; margin-right: auto; text-align: right; width: 64%;'>
                        	<span class="ft15" style='margin-left: auto; margin-right: auto; text-align: right;'>
                            <?php echo e($club->nomenclatura); ?><?php echo e($club->nombre); ?>/<?php echo e($id); ?>/<?php echo e($anio); ?>

                        </span>
                    </td>
             
                </tr>
            </table>
        </div>

        </br>
        
        <div id="cuerpo" style='height:400px; width: 100%;'>

			<p align="left">
                <span style="font-size:30px;font-family:Times;color:#000000;">
                    Participó en el <b>"<?php echo e($club->nombre); ?>”</b> en la actividad <b><?php echo e($evento->nombre); ?></b>
                </span>
        
            </p>

            <p align="left">
                <span class="ft17">
                    <?php echo e($usuario->name); ?> <?php echo e($usuario->apaterno); ?> <?php echo e($usuario->amaterno); ?>

				</br></br>
					<?php if($evento->fechaInicio == $evento->fechaFin): ?>
                        El día <b><?php echo e($evento->fechaInicio); ?></b>
                    <?php else: ?>
                        En el periodo del <b><?php echo e($evento->fechaInicio); ?></b> al <b><?php echo e($evento->fechaFin); ?></b>
                    <?php endif; ?>
                    con un horario de <b><?php echo e($evento->horaInicio); ?> a <?php echo e($evento->horaFin); ?></b> horas. Sumando un total de horas de <b><?php echo e($asistencia->asistenciaTotal); ?> horas</b>.
                </span>
            </p>

            



        </br></br></br></br></br></br>

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

        </br>

        <table style="width: 100%;">
            <tr>
                <td style='margin-left: auto; margin-right: auto; text-align: right;'>
                    Verifica la autenticidad de esta constancia en:
                    <?php
                        $qr = QrCode::size(300)->margin(0)->generate("http://panel.sigaclub.com/checkasistencia/".$asistencia->id);
                    ?>
                    
                    <img src="data:image/png;base64, <?php echo base64_encode($qr); ?> " width="150" height="150" />
                </td>
                
            </tr>
        </table>
        
        
    </div>

</body>

</html><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/generar-constancia-externa.blade.php ENDPATH**/ ?>