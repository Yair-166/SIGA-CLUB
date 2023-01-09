@extends('layouts.master')
@section('title')
    @lang('translation.companies')
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('css')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Clubes
        @endslot
        @slot('title')
            Asistencias
        @endslot
    @endcomponent
    @php
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
        //Contar $asistencias
        $asistenciasCount = DB::table('asistencias')->where('idUsuario', $idUsuario)->count();
        //Contar todas aquellas asistencias que tengan el valor de constanciaGenerada en 1
        $constanciasgeneradas = DB::table('asistencias')->where('idUsuario', $idUsuario)->where('constanciaGenerada', 1)->count();
        
        $horasTotales = 0;
        foreach($asistencias as $asistencia){
            $horasTotales = $horasTotales + $asistencia->asistenciaTotal;
        }

        //Contar $inscripciones
        $inscripcionesCount = DB::table('inscripciones')->where('id_alumno', $idUsuario)->count();
        //Obtener todas las filas de la tabla inscripciones donde el id_alumno sea igual a $idUsuario
        $inscripciones = DB::table('inscripciones')->where('id_alumno', $idUsuario)->get();
        //Obtener los nombres de los clubes a los que está inscrito el usuario
        $clubes = array();
        $clubesid = array();
        foreach($inscripciones as $inscripcion){
            $club = DB::table('clubes')->where('id', $inscripcion->id_club)->first();
            array_push($clubes, $club->nombre);
            array_push($clubesid, $club->id);
        }
        
        $horasxclub = array();
        foreach($clubesid as $club){
            $horasClub = 0;
            foreach($asistencias as $asistencia){
                $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
                if($evento->id_club == $club){
                    $horasClub = $horasClub + $asistencia->asistenciaTotal;
                }
            }
            array_push($horasxclub, $horasClub);
        }
        //Pasar a string el arreglo de clubes y horasxclub
        $clubes = implode(",", $clubes);
        $horasxclub = implode(",", $horasxclub);


        //Crear arreglo con tipos de eventos campamento, clase, Concurso, Conferencia, Curso, Entrenamiento, Evaluación, Exhibición, Exposición, Seminario, Torneo
        $tipos = "Campamento,Clase,Concurso,Conferencia,Curso,Entrenamiento,Evaluación,Exhibición,Exposición,Seminario,Torneo,Otro,";
        //Convertir el string en un arreglo
        $tipos = explode(",", $tipos);
        //Eliminar el ultimo elemento del array
        array_pop($tipos);
        $tiposCount = count($tipos);
        //De cada asistencia obtener el evento y asistenciaTotal
        $asistenciasTotales = array();
        foreach($tipos as $tipo){
            $asistenciaTotal = 0;
            foreach($asistencias as $asistencia){
                //Obtener el evento con el id del evento de la asistencia
                $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
                //Si el tipo del evento es igual al tipo del arreglo $tipos
                if($evento->tipo == $tipo){
                    //Sumar la asistencia total de cada evento
                    $asistenciaTotal = $asistenciaTotal + $asistencia->asistenciaTotal;
                }
            }
            //Agregar la asistencia total al arreglo $asistenciasTotales
            array_push($asistenciasTotales, $asistenciaTotal);
        }
        $asistenciasTotalesString =  implode(",", $asistenciasTotales);
        $tiposAlumnoString = implode(",", $tipos);

    @endphp
    <div class="row">
    @if (Auth::user()->rol == 'administrador')
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
    @endif  

        <div class="col-xxl-12">

            <div class="row col-xxl-12">
                <div class="card col-xl-4">
                    <div class="card-body text-center p-1">
                        <h5 class="mb-1 mt-1">
                        Clubes a los que perteneces:
                        </h5>
                        <p class="text-muted mb-1">
                            {{$inscripcionesCount}}
                        </p>
                    </div>
                </div>

                <div class="card col-xl-4">
                    <div class="card-body text-center p-1">
                        <h5 class="mb-1 mt-1">
                        Eventos en los que has participado:
                        </h5>
                        <p class="text-muted mb-1">
                            {{$asistenciasCount}}
                        </p>
                    </div>
                </div>

                <div class="card col-xl-4">
                    <div class="card-body text-center p-1">
                        <h5 class="mb-1 mt-1">
                        Constancias generadas:
                        </h5>
                        <p class="text-muted mb-1">
                            {{$constanciasgeneradas}}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card" id="companyList">
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th data-sort="name" scope="col">Nombre del club</th>
                                        <th data-sort="owner" scope="col">Evento</th>
                                        <th data-sort="date" scope="col">Horas asistidas</th>
                                        <th data-sort="date" scope="col">Rol del usuario</th>
                                        <th data-sort="date" scope="col">Tipo de evento</th>
                                        <th data-sort="location" scope="col">Constancias</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                
                                @foreach ($asistencias as $asistencia)
                                    @php
                                        //Obtener los datos del evento
                                        $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
                                        //Obtener los datos del club
                                        $club = DB::table('clubes')->where('id', $evento->id_club)->first();
                                    @endphp
                                    <tbody class="list form-check-all">
                                    <tr>
                                        <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('images/' . $club->foto) }}" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                </div>
                                                <div class="flex-grow-1 ms-2 name">
                                                    {{ $club->nombre }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="owner">
                                            <a href="/apps-eventos-overview?evento={{$asistencia->idEvento}}" class="fw-medium link-primary">
                                                {{ $evento->nombre }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $asistencia->asistenciaTotal }}
                                        </td>
                                        <td>
                                            {{ $asistencia->rolUsuario }}
                                        </td>
                                        <td>
                                            {{ $evento->tipo }}
                                        </td>
                                        <td class="location">
                                            @if ($asistencia->constanciaGenerada == 0)
                                                La constancia está en proceso.
                                            @else
                                                @php
                                                    //Obtener la constancia con el idAsistencia
                                                    $constancia = DB::table('constancias')->where('idAsistencia', $asistencia->id)->first();
                                                @endphp
                                                @if($constancia->redaccion == "True")
                                                    <a href="{{ URL::asset('acuses/' . $constancia->acuse) }}" class="btn btn-primary" target="_blank">
                                                        Descargar constancia / acuse
                                                    </a>
                                                @else
                                                    <h6>
                                                        Obtén tu constancia con el administrador del club.<i class="ri-check-line"></i>
                                                    </h6>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!--end card-->
            <div class="accordion-item">
                <h2 class="accordion-header" id="genques-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseOne" aria-expanded="false" aria-controls="genques-collapseOne">
                        Desglose de horas por tipo de evento (Horas totales: {{$horasTotales}})
                    </button>
                </h2>
                <div id="genques-collapseOne" class="accordion-collapse collapse collapsed" aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion">
                    <div class="accordion-body row">
                        <input type="hidden" id="tiposAlumnoString" value="{{$tiposAlumnoString}}">
                        <input type="hidden" id="asistenciasTotalesString" value="{{$asistenciasTotalesString}}">
                        <center>
                            <div class="col-sm-7">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Horas por tipo de evento</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="HorasxEvento" class="chartjs-chart" data-colors='["#344D67", "#6ECCAF", "#ADE792"]'></canvas>
                                    </div>
                                </div> 
                            </div> <!-- end col -->
                        </center>
                    </div>
                </div>
            </div>

            <div id="extension"><p> </p></div>
            <input type="hidden" id="clubesAlumno" value="{{$clubes}}">
            <input type="hidden" id="horasxclub" value="{{$horasxclub}}">


        </div><!--end col-->
    </div><!--end row-->

@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/list.js/list.js.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/list.pagination.js/list.pagination.js.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/crm-companies.init.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/chart.js/chart.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/chartjs.init.js') }}"></script>
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
@endsection
