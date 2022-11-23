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
    
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="">

<head>
	<title>
		Constancia generada
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<style type="text/css">
        
        @udl: "siga-club.test/public/images/";
		p {
            margin: 0; 
            padding: 0;
        }	
		.ft10{font-size:18px;font-family:Times;color:#bf504d;}
		.ft11{font-size:14px;font-family:Times;color:#000000;}
		.ft12{font-size:15px;font-family:Times;color:#365e91;}
		.ft13{font-size:15px;font-family:Times;color:#000000;}
		.ft14{font-size:18px;font-family:Times;font-weight:bold;color:#000000;}
		.ft15{font-size:14px;font-family:Times;font-weight:bold;color:#000000;}
		table, td, tr{border: 1px solid black; border-collapse: collapse;}
        body 
        { 
            height: 842px; 
            width: 595px;
            margin-left: auto; 
            margin-right: auto; 
        }
        .contenido{
            background-image: url("siga-club.test/public/images/1668379450.jpg");
            width: 100%;
            height: auto;
            background-repeat: no-repeat;
            background-size: cover;
        }
        
    </style>
</head>

<body>

    <div class="contenido">

        <div id="cabecera" style='height: 200px; width: 100%;'>

            <div style='width: 20%; float: left'>
                <img align="right" src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_IPN.png" height="150" />
            </div>

            <div style='width: 59%; float: left'>
                <p align="center">
                                <span class="ft10">
                                    INSTITUTO POLITÉCNICO NACIONAL
                                </span>
                            </p>
                            </br>
                            <p align="center">
                                <span class="ft12">
                                        ESCUELA SUPERIOR DE CÓMPUTO
                                </span>
                            </p>
                            </br>
                            
                            </br>
                            <p align="center">
                            <span class="ft14">
                                    CONSTANCIA
                                </span>
                            
                            <p align="center">
                                <span class="ft15">
                                    <?php echo e($club->nomenclatura); ?><?php echo e($club->nombre); ?>/<?php echo e($id); ?>/<?php echo e($anio); ?>

                                </span>
                            </p>
            </div>

            <div style='width: 20%; float: right'>
                <img src="https://pbs.twimg.com/profile_images/1423089146/escom_400x400.png" height="150" />
            </div>

        </div>
        
        <div id="cuerpo" style='height:400px; width: 100%;'>

            <p align="left">
                <span class="ft15">
                    A QUIEN CORRESPONDA.  
                </span>
            </p>

            <p align="left">
                <span class="ft11">
                    Con el número de boleta ______________, participo en el "<?php echo e($club->nombre); ?>” en la actividad <?php echo e($evento->nombre); ?> 
                    en los periodos de Agosto–Diciembre de 2017 (17-18/1), y Enero-Junio de 2018 (17-18/2), 
                    Agosto–Diciembre de 2018 (18-19/1), Enero-Junio de 2019 (18-19/2) en los 
                    entrenamientos y actividades del Club ______________ con actividades de 
                    ___ horas/semana (martes y jueves 13:30 a 15: horas) en la ________________.
                </span>
        
            </p>

            </br>



            <p align="left">
                <span class="ft11">
                    No existiendo inconveniente alguno, se emite la presente a los <?php echo e($dia); ?> días del mes de <?php echo e($mes); ?> del 
                    <?php echo e($anio); ?> a solicitud expresa y para los fines que el (la) interesado (a) convengan.
                </span>
            </p>
        </div>

        <div id="pie" style='width: 100%;'>
            <div style='width: 25%; float: left'>
                <p>
                    <span class="ft15">
                        <b>
                            </br></br></br></br></br>
                            &nbsp ____________________________________
                        </b>
                    </span>
                </p>
                <p>
                    <span class="ft15">
                        <b>
                            &nbsp &nbsp &nbsp Firma del responsable de la actividad
                        </b>
                    </span>
                </p>
            </div>

            <div style='width: 25%; float: left'>
                <p>
                    <span class="ft15">
                        <b>
                            </br></br></br></br></br>
                            ______________________________
                        </b>
                    </span>
                </p>
                <p>
                    <span class="ft15">
                        <b>
                            &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbspFirma del director
                        </b>
                    </span>
                </p>
            
            </div>

        
            </div>

        </div>
    
    </div>

</body>

</html><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/generar-constancia.blade.php ENDPATH**/ ?>