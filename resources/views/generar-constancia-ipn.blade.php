@php
    //Obtener dia actual
    $dia = date('d');
    //Obtener mes actual
    $mes = date('m');
    //Obtener el tamaño de la cadena del mes
    $tamanoMes = strlen($mes);
    //Si el tamaño del mes es igual a 2 y el primer caracter es un 0
    if($tamanoMes == 2 && substr($mes, 0, 1) == '0'){
        //Obtener el mes sin el 0
        $mes = substr($mes, 1, 1);
    }
    //Crear arreglo con los meses del año
    $meses = array();
    $meses[1] = 'Enero';
    $meses[2] = 'Febrero';
    $meses[3] = 'Marzo';
    $meses[4] = 'Abril';
    $meses[5] = 'Mayo';
    $meses[6] = 'Junio';
    $meses[7] = 'Julio';
    $meses[8] = 'Agosto';
    $meses[9] = 'Septiembre';
    $meses[10] = 'Octubre';
    $meses[11] = 'Noviembre';
    $meses[12] = 'Diciembre';
    //$meses = array(1 => 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
    //'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
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
                            {{$request->input('escuela')}}
                        </span>
                    </td>
                    <td style='width: 18%; float: left'>
                        @php
                            $escuela = str_replace(' ', '', $request->input('escuela'));
                            //unir https://sigaclub.com/assets/images/escuelas/ con el nombre de la escuela
                            $url = 'https://sigaclub.com/assets/images/escuelas/'.$escuela.'.png';
                        @endphp

                        <img src="{{$url}}" height="150"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style='margin-left: auto; margin-right: auto; text-align: center;'>

                        <span class="ft14">
                            CONSTANCIA
                        </span>

                        </br>

                        <span class="ft15" style='margin-left: auto; margin-right: auto; text-align: center;'>
                            {{$club->nomenclatura}}/{{$id}}/{{ $anio }}
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

            <p align="left" style='text-align: justify;'>
                <span class="ft11">
                    Con el número de boleta <b>{{$usuario->boleta}}</b>, participó en el <b>"{{$club->nombre}}”</b> en la actividad <b>{{$evento->nombre}}</b>
                    @if($evento->fechaInicio == $evento->fechaFin)
                        el día <b>{{$evento->fechaInicio}}</b>
                    @else
                        en el periodo del <b>{{$evento->fechaInicio}}</b> al <b>{{$evento->fechaFin}}</b>
                    @endif
                    con el rol de <b>{{$rolactividad}}</b> y con un horario de <b>{{$evento->horaInicio}} a {{$evento->horaFin}}</b> horas. Sumando un total de horas de <b>{{$asistencia->asistenciaTotal}} horas</b>.
                </span>
        
            </p>

            <p align="left">
                <span class="ft11">
                    @if($rolactividad == 'Coordinador')
                        {{$evento->redaccionCoordinador}}
                    @else
                        {{$evento->redaccionParticipante}}
                    @endif
                </span>
            </p>

            <p align="left">
                <span class="ft11">
                    No existiendo inconveniente alguno, se emite la presente a los <b>{{$dia}} días del mes de {{$mes}} del 
                    {{$anio}}</b> a solicitud expresa y para los fines que el (la) interesado (a) convengan.
                </span>
            </p>

            <p align="left">
                <span class="ft11">
                    {{$request->input('redaccion_ipn')}}
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
                @foreach ($autoridades_externo as $autoridad)
                    @if($request->input('autoridad_'.$autoridad->id) == $autoridad->id)
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
                    @endif
                @endforeach
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
                    @php
                        $qr = QrCode::size(300)->margin(0)->generate("https://panel.sigaclub.com/checkasistencia/".$asistencia->id);
                    @endphp
                    
                    <img src="data:image/png;base64, {!! base64_encode($qr) !!} " width="120" height="120" />
                </td>
                <td align="right" style="width: 50%;">
                    <img src="{{ URL::asset('images/' . $club->foto) }}" width="120" height="120" style="float:right" />
                </td>
            </tr>
        </table>
        
        
    </div>

</body>

</html>