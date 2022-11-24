@php
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
                        <img align="right" src="{{ URL::asset('images/' . $club->foto) }}" height="150" />
                        </br></br></br></br></br></br></br></br></br>
                        <span class="ft14" style='margin-left: auto; margin-right: auto; text-align: center;'>
                            CONSTANCIA
                        </span>
                    </td>
                    <td style='margin-left: auto; margin-right: auto; text-align: right; width: 64%;'>
                        	<span class="ft15" style='margin-left: auto; margin-right: auto; text-align: right;'>
                            {{$club->nomenclatura}}{{$club->nombre}}/{{$id}}/{{ $anio }}
                        </span>
                    </td>
             
                </tr>
            </table>
        </div>

        </br>
        
        <div id="cuerpo" style='height:400px; width: 100%;'>

			<p align="left">
                <span style="font-size:30px;font-family:Times;color:#000000;">
                    Participo en el <b>"{{$club->nombre}}”</b> en la actividad <b>{{$evento->nombre}}</b>
                </span>
        
            </p>

            <p align="left">
                <span class="ft17">
                    {{$usuario->name}} {{$usuario->apaterno}} {{$usuario->amaterno}}
				</br></br>
					@if($evento->fechaInicio == $evento->fechaFin)
                        El día <b>{{$evento->fechaInicio}}</b>
                    @else
                        En el periodo del <b>{{$evento->fechaInicio}}</b> al <b>{{$evento->fechaFin}}</b>
                    @endif
                    con un horario de <b>{{$evento->horaInicio}} a {{$evento->horaFin}}</b> horas. Sumando un total de horas de <b>{{$asistencia->asistenciaTotal}} horas</b>.
                </span>
            </p>

            



        </br></br></br></br></br></br>

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

        </br>

        <table style="width: 100%;">
            <tr>
                <td style='margin-left: auto; margin-right: auto; text-align: right;'>
                    Verifica la autenticidad de esta constancia en:
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