@php
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
    
@endphp

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
                            {{$club->nomenclatura}}{{$club->nombre}}/{{$id}}/{{ $anio }}
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
                    {{$usuario->name}} {{$usuario->apaterno}} {{$usuario->amaterno}}
                </span>
            </p>

            <p align="left">
                <span class="ft11">
                    Con el número de boleta <b>{{$usuario->boleta}}</b>, participo en el <b>"{{$club->nombre}}”</b> en la actividad <b>{{$evento->nombre}}</b>
                    @if($evento->fechaInicio == $evento->fechaFin)
                        el día <b>{{$evento->fechaInicio}}</b>
                    @else
                        en el periodo del <b>{{$evento->fechaInicio}}</b> al <b>{{$evento->fechaFin}}</b>
                    @endif
                    con un horario de <b>{{$evento->horaInicio}} a {{$evento->horaFin}}</b> horas. Sumando un total de horas de <b>{{$asistencia->asistenciaTotal}}</b> horas.
                </span>
        
            </p>

            <p align="left">
                <span class="ft11">
                    Las actividades del {{$club->nombre}} son extracurriculares y giran en torno a desarrollar un alumno integral y complementar su formación académica.
                </span>
            </p>

            <p align="left">
                <span class="ft11">
                    No existiendo inconveniente alguno, se emite la presente a los <b>{{$dia}} días del mes de {{$mes}} del 
                    {{$anio}}</b> a solicitud expresa y para los fines que el (la) interesado (a) convengan.
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
                @foreach($autoridades as $autoridad)
                    <td style='margin-left: auto; margin-right: auto; text-align: center;'>
                        <span class="ft15">
                            <b>
                                ________________________________
                            </b>
                        </span>
                        </br>
                        <span class="ft13">
                            {{$autoridad->nombre}} {{$autoridad->aPaterno}} {{$autoridad->aMaterno}}
                        </span>
                        </br>
                        <span class="ft13">
                            {{$autoridad->cargo}}
                        </span>
                    </td>
                @endforeach
                </tr>
            </table>
        </div>

        </br></br>

        <table>
            <tr>
                <td>
                    Verifica la autenticidad de esta constancia en:
                </td>
            </tr>
            <tr>
                <td>
                    @php
                        $qr = QrCode::size(300)->margin(0)->generate("http://panel.sigaclub.com/checkasistencia/".$asistencia->id);
                    @endphp
                    
                    <img src="data:image/png;base64, {!! base64_encode($qr) !!} " width="150" height="150" />
                </td>
            </tr>
        </table>
        
        
    </div>

</body>

</html>