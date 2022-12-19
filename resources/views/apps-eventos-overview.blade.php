@extends('layouts.master')
@section('title')
    @lang('translation.overview')
@endsection
@section('css')
    <link href="{{ URL::asset('/assets/libs/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Clubes
        @endslot
        @slot('title')
            Eventos
        @endslot
    @endcomponent
@php
    //Obtener el valor de la variable evento desde la url
    $getId = $_GET['evento'];
    //Obtener el evento con el id obtenido
    $evento = DB::table('eventos')->where('id', $getId)->first();
    //Obtener el club del evento
    $club = DB::table('clubes')->where('id', $evento->id_club)->first();
    //Verificar si la fecha de inicio es igual a la fecha de fin
    if($evento->fechaInicio == $evento->fechaFin){
        //Si es igual, mostrar solo la fecha de inicio
        $fecha = date("d/m/Y", strtotime($evento->fechaInicio));
    }else{
       //Si no, mostrar la fecha de inicio y la fecha de fin
       $fecha = date("d/m/Y", strtotime($evento->fechaInicio))." al ".date("d/m/Y", strtotime($evento->fechaFin));
    }
    //Verificar si horaInicio es 00:00 y horaFin es 23:59
    if($evento->horaInicio == "00:00:00" && $evento->horaFin == "23:59:00"){
        //Si es igual, mostrar solo la fecha de inicio
        $hora = "Todo el día";
    }else{
         //Si no, mostrar la fecha de inicio y la fecha de fin
         $hora = date("h:i a", strtotime($evento->horaInicio))." - ".date("h:i a", strtotime($evento->horaFin));
    }

    //Obtener el numero de filas de la tabla confi_eventos donde idEvento sea igual al id del evento
    $confi_eventos = DB::table('confi_eventos')->where('idEvento', $getId)->count();
    
    //Array para guardar los coordinadores
    $coordinadoresNombres = array();

    //Guardar el admin del club
    $coordinador = DB::table('users')->where('id', $club->idAdministrador)->first();
    array_push($coordinadoresNombres, $coordinador);

    //Obtener la fila con id_coordinador = -1
    $confipriv = DB::table('confi_eventos')->where('idEvento', $getId)->where('id_coordinador', "-1")->first();

    //Si el numero de filas es igual a 1
    if($confi_eventos > 1){
        //Obtener los coordinadores del evento en la tabla confi_eventos
        $coordinadores = DB::table('confi_eventos')->where('idEvento', $getId)->get();
    
        foreach($coordinadores as $coordinador){
            if($coordinador->id_coordinador != "-1"){
                $coordinador = DB::table('users')->where('id', $coordinador->id_coordinador)->first();
                array_push($coordinadoresNombres, $coordinador);
            }
        }
    }
    
    //Obtener todas las inscripciones del club
    $inscripciones = DB::table('inscripciones')->where('id_club', $club->id)->get();
    //Guardar en un array los usuarios que estan inscritos al club
    $usuarios = array();
    foreach($inscripciones as $inscripcion){
        //Obtener el usuario de la inscripcion
        $usuario = DB::table('users')->where('id', $inscripcion->id_alumno)->first();
        //Guardar el usuario en el array
        array_push($usuarios, $usuario);
    }
    //Obtener todos los registros de la tabla archivos donde idEvento sea igual a getId
    $archivos = DB::table('archivos')->where('idEvento', $getId)->get();
    //Obtener todos los registros de la tabla evidencias donde idEvento sea igual a getId
    $evidencias = DB::table('evidencias')->where('idEvento', $getId)->get();

    //Obtener las filas de la tabla asistencias_previstas donde id_evento sea igual a getId
    $asistencias_previstas = DB::table('asistencias_previstas')->where('id_evento', $getId)->get();

    //Obtener las asistencias del evento
    $asistencias = DB::table('asistencias')->where('idEvento', $getId)->get();

    $correos = "";

    //Obtener la fecha actual con todo y hora
    $fechaActualConHora = date("Y-m-d H:i:s", strtotime("-6 hours"));
    //Truncar la fecha actual a solo el año, mes y dia
    $fechaActual = date("Y-m-d", strtotime($fechaActualConHora));
    //Obtener la hora actual en formato 24 horas de México
    $horaActual = date("H:i:s", strtotime("-6 hours"));

@endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-n4 mx-n4 border-0">
                <div class="bg-soft-primary">
                    <div class="card-body pb-0 px-4">
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-auto">
                                        <div class="avatar-md">
                                            <div class="avatar-title bg-white rounded-circle">
                                                <img src="{{ URL::asset('images/'.$club->foto) }}" alt="" class="avatar-xs">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div>
                                            <h4 class="fw-bold">{{$evento->nombre}}</h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                <div class="vr"></div>
                                                <div>Fecha : <span class="fw-medium">{{$fecha}}</span></div>
                                                <div class="vr"></div>
                                                <div>Horario : <span class="fw-medium">{{$hora}}</span></div>
                                                @if($fechaActual > $evento->fechaFin && $horaActual > $evento->horaFin)
                                                    <div class="vr"></div>
                                                    <div class="text-danger">Evento terminado</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs-custom border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#project-overview"
                                    role="tab">
                                    Sobre el evento
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-documents" role="tab">
                                    Material de apoyo
                                </a>
                            </li>
                            @if(Auth::user()->rol == "colaborador" && $confipriv->isPrivate == 0)
                                @if($evento->fechaInicio <= $fechaActual && $evento->fechaFin >= $fechaActual && $evento->horaInicio <= $horaActual && $evento->horaFin >= $horaActual)
                                    <li class="nav-item">
                                        <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#asistencia" role="tab">
                                            Tomar asistencia
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if(Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id || Auth::user()->rol == "super")
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-activities" role="tab">
                                        Evidencias de participación
                                    </a>
                                </li>
                                @if($fechaActual <= $evento->fechaFin && $horaActual <= $evento->horaFin)
                                    <li class="nav-item">
                                        <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#participantes" role="tab">
                                            Participantes confirmados
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#edit-event" role="tab">
                                        Editar evento
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-team" role="tab">
                                        Configuración
                                    </a>
                                </li>
                                @if($fechaActual > $evento->fechaFin && $horaActual > $evento->horaFin)
                                    <li class="nav-item">
                                        <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#asistentes" role="tab">
                                            Participantes del evento
                                        </a>
                                    </li>
                                @endif
                            @endif
                            
                            
                        </ul>
                    </div>
                    <!-- end card body -->
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content text-muted">
            
                <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>¡Éxito!</strong> {{session('success')}}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @elseif(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>¡Error!</strong> {{session('error')}}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @elseif(session('warning'))
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <strong>¡Advertencia!</strong> {{session('warning')}}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <div class="text-muted">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Descripción</h6>
                                        <p>
                                            {{$evento->descripcion}}
                                        </p>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- ene col -->
                        <div class="col-xl-3 col-lg-4">
                            
                            <div class="card">
                                <div class="card-header align-items-center d-flex border-bottom-dashed">
                                    <h4 class="card-title mb-0 flex-grow-1">Coordinador (es)</h4>
                                </div>
                                    @foreach($coordinadoresNombres as $coordinador)
                                            <div class="vstack gap-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs flex-shrink-0 me-3">
                                                        <img src="{{ URL::asset('images/'.$coordinador->avatar) }}" alt=""
                                                            class="img-fluid rounded-circle" style="aspect-ratio: 1/1;;">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-13 mb-0">
                                                        {{$coordinador->name . ' ' . $coordinador->apaterno . ' ' . $coordinador->amaterno}}
                                                        </h5>
                                                    </div>
                                                </div>
                                                <!-- end member item -->
                                            </div>
                                            <br>
                                        @endforeach
                                <div class="card-body">
                                    <div data-simplebar style="height: 235px;" class="mx-n3 px-3">
                                        
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end tab pane -->

                <div class="tab-pane fade" id="project-documents" role="tabpanel">
                    <div class="row">
                        @if(Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id || Auth::user()->rol == "super")
                            <div class="col-xl-9 col-lg-8">
                        @else
                            <div class="col-xl-12 col-lg-12">
                        @endif
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <h5 class="card-title flex-grow-1">Material de apoyo</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive table-card">
                                                <table class="table table-borderless align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Nombre del archivo</th>
                                                            <th scope="col">Descripción</th>
                                                            @if(Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id || Auth::user()->rol == "super")
                                                                <th scope="col">Ocultar</th>
                                                                <th scope="col">Eliminar</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($archivos as $archivo)
                                                            @if($archivo->isPrivate == 0)
                                                                @if (Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id || Auth::user()->rol == "super")
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="avatar-sm">
                                                                                    <div
                                                                                        class="avatar-title bg-light text-primary rounded fs-24">
                                                                                        <i class="ri-file-fill"></i>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="ms-3 flex-grow-1">
                                                                                    <h5 class="fs-14 mb-0"><a href="/files/{{$club->id}}/archivos/{{$archivo->archivo}}"
                                                                                            class="text-dark">{{$archivo->archivo}}</a>
                                                                                    </h5>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <p class="mb-0">{{$archivo->nombreArchivo}}</p>
                                                                        </td>
                                                                        <td>
                                                                            {{-- Aqui va una condicion de si esta oculto que diga mostrar y si esta visible que diga ocultar --}}
                                                                            @if ($archivo->isPrivate == 1)
                                                                                <a href="{{ route('toogleArchivo', $archivo->id) }}" class="btn btn-soft-primary btn-sm">
                                                                                <i class="ri-eye-off-fill me-1 align-bottom"></i>Ocultar</a>
                                                                            @else
                                                                                <a href="{{ route('toogleArchivo', $archivo->id) }}" class="btn btn-soft-primary btn-sm">
                                                                                <i class="ri-eye-fill me-1 align-bottom"></i>Mostrar</a>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('eliminarArchivo', $archivo->id) }}" class="btn btn-sm btn-soft-danger"><i
                                                                                class="ri-delete-bin-2-line"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @else
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar-sm">
                                                                                <div
                                                                                    class="avatar-title bg-light text-primary rounded fs-24">
                                                                                    <i class="ri-file-fill"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="ms-3 flex-grow-1">
                                                                                <h5 class="fs-14 mb-0"><a href="/files/{{$club->id}}/archivos/{{$archivo->archivo}}"
                                                                                        class="text-dark">{{$archivo->archivo}}</a>
                                                                                </h5>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <p class="mb-0">{{$archivo->nombreArchivo}}</p>
                                                                    </td>
                                                                    @if(Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id || Auth::user()->rol == "super")
                                                                        <td>
                                                                            {{-- Aqui va una condicion de si esta oculto que diga mostrar y si esta visible que diga ocultar --}}
                                                                            @if ($archivo->isPrivate == 1)
                                                                                <a href="{{ route('toogleArchivo', $archivo->id) }}" class="btn btn-soft-primary btn-sm">
                                                                                <i class="ri-eye-off-fill me-1 align-bottom"></i>Ocultar</a>
                                                                            @else
                                                                                <a href="{{ route('toogleArchivo', $archivo->id) }}" class="btn btn-soft-primary btn-sm">
                                                                                <i class="ri-eye-fill me-1 align-bottom"></i>Mostrar</a>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <a href="{{ route('eliminarArchivo', $archivo->id) }}" class="btn btn-sm btn-soft-danger"><i
                                                                                class="ri-delete-bin-2-line"></i></a>
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id || Auth::user()->rol == "super")
                            <div class="col-xl-3 col-lg-4">
                                <div class="card">
                                    <form action="{{ route('subirArchivo') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <h6 class="mb-3 fw-semibold text-uppercase">  Material de apoyo</h6>
                                        <div class="row g-3">
                                            <div class="flex-shrink-0">
                                                <input type="hidden" name="idClub" value="{{ $evento->id_club }}">
                                                <input type="hidden" name="idEvento" value="{{ $evento->id }}">
                                                <input type="hidden" name="isPrivate" value="0">
                                                <label class="form-label">Descripción del material de apoyo</label>
                                                <input name="nombreArchivo" type="text" class="form-control col-sm-6" placeholder="Agrega una descripción al material">
                                                <hr>
                                                <input name="archivo[]" type="file" class="form-control col-sm-6" id="formFile" multiple="">
                                                <hr>
                                                <button type="submit" class="btn btn-soft-primary btn-sm"><i
                                                    class="ri-upload-2-fill me-1 align-bottom"></i> Subir</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- end tab pane -->
                <div class="tab-pane fade" id="project-activities" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <h5 class="card-title flex-grow-1">Evidencias de participación</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive table-card">
                                                <table class="table table-borderless align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Nombre del archivo</th>
                                                            <th scope="col">Nota</th>
                                                            <th scope="col">Eliminar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($evidencias as $evidencia)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar-sm">
                                                                            <div
                                                                                class="avatar-title bg-light text-primary rounded fs-24">
                                                                                <i class="ri-file-fill"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="ms-3 flex-grow-1">
                                                                            <h5 class="fs-14 mb-0"><a href="/files/{{$club->id}}/evidencias/{{$evidencia->archivo}}"
                                                                                    class="text-dark">{{$evidencia->archivo}}</a>
                                                                            </h5>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{$evidencia->nota}}</td>
                                                                <td>
                                                                    <a href="{{route('eliminarEvidencia', $evidencia->id) }}" class="btn btn-sm btn-soft-danger"><i
                                                                            class="ri-delete-bin-2-line"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-->
                        </div>
                        <div class="col-xl-3 col-lg-4">
                            <div class="card">
                                <form action="{{ route('subirEvidencia') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <h6 class="mb-3 fw-semibold text-uppercase">Subir evidencias de participación</h6>
                                    <div class="row g-3">
                                        <div class="flex-shrink-0">
                                            <input type="hidden" name="idClub_ev" value="{{ $evento->id_club }}">
                                            <input type="hidden" name="idEvento_ev" value="{{ $evento->id }}">
                                            <label class="form-label">Agregar evidencia</label>
                                            <textarea name="nota_ev" class="form-control" id="exampleFormControlTextarea1" rows="3" 
                                            placeholder="Notas sobre la evidencia"></textarea>
                                            <hr>
                                            <input name="archivo_ev[]" type="file" class="form-control col-sm-6" id="formFile" multiple="">
                                            <hr>
                                            <button type="submit" class="btn btn-soft-primary btn-sm"><i
                                                    class="ri-upload-2-fill me-1 align-bottom"></i> Subir</button>
                                        </div>
                                    </div>
                                    <!-- end row -->
                                </form>
                            </div>
                             <!-- end subir evidencia -->
                        </div>
                    </div>
                </div>
                <!-- end tab pane -->
                <div class="tab-pane fade" id="project-team" role="tabpanel">

                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Asignar coordinador</h6>
                                        <div class="row">
                                            <form action="{{ route('asignarCoordinador') }}" method="POST">
                                                @csrf
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <input type="hidden" name="idEvento" value="{{ $evento->id }}">
                                                        <label for="formrow-firstname-input" class="form-label">Agrega coordinadores</label>
                                                        <select id="idcords" name="id_coordinador" class="form-select" aria-label="Default select example">
                                                            @foreach ($usuarios as $usuario)
                                                                @if($usuario->active == 1)
                                                                    <option value="{{ $usuario->id }}">
                                                                        {{ $usuario->name . ' ' . $usuario->apaterno . ' ' . $usuario->amaterno }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <br>
                                                        <button onclick="agregarcoord()" class="btn btn-primary ">Agregar</button>
                                                        <button type="submit" class="btn btn-primary" style="display: none;" id="btnGuardar">Guardar</button>
                                                    </div>
                                                </div>
                                                <div id="labels"></div>
                                            </form>
                                        </div>
                                       
                                        <!-- end card body -->

                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- ene col -->
                        <div class="col-xl-3 col-lg-4">
                            
                            <div class="card">
                                <div class="card-header align-items-center d-flex border-bottom-dashed">
                                    <h4 class="card-title mb-0 flex-grow-1">Coordinador (es)</h4>
                                </div>
                                @foreach($coordinadoresNombres as $coordinador)
                                    <div class="vstack gap-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0 me-3">
                                                <img src="{{ URL::asset('images/'.$coordinador->avatar) }}" alt=""
                                                    class="img-fluid rounded-circle" style="aspect-ratio: 1/1;;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0">
                                                    {{$coordinador->name . ' ' . $coordinador->apaterno . ' ' . $coordinador->amaterno}}
                                                    
                                                </h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('eliminarCoordinador', ['id' => $coordinador->id, 'idevento' => $evento->id]) }}" class="btn btn-sm btn-soft-danger">
                                                    <i class="ri-delete-bin-2-line"></i>
                                                </a>
                                                {{-- <a  class="btn btn-danger btn-sm" ><i class="ri-delete-bin-2-fill me-1 align-bottom"></i></a> --}}
                                            </div>
                                        </div>
                                        <!-- end member item -->
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                            <!-- end card -->
                            @if($evento->fechaInicio <= $fechaActual && $evento->fechaFin >= $fechaActual && $evento->horaInicio <= $horaActual && $evento->horaFin >= $horaActual)
                                <div class="card  align-items-center">
                                    <div class="card-header align-items-center d-flex border-bottom-dashed">
                                        <h5 class="fs-13 mb-0">Activar asistencia</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="vstack gap-3">
                                            <div class="d-flex align-items-center">
                                                <a href="{{URL::asset('/pages-qr?uid='.$evento->id)}}" class="btn btn-primary btn-sm">Activar</a>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            @endif
                                        
                            <div class="card  align-items-center">
                                <div class="card-header align-items-center d-flex border-bottom-dashed">
                                    @if($confipriv->isPrivate == "1")
                                        <h5 class="fs-13 mb-0">Cambiar a QR Público</h5>
                                    @else
                                        <h5 class="fs-13 mb-0">Cambiar a QR Privado</h5>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="vstack gap-3">
                                        <div class="d-flex align-items-center">
                                            <a href="{{route('tooglePrivate', [$confipriv->id, $confipriv->isPrivate])}}" class="btn btn-primary btn-sm">Cambiar</a>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                    <!-- end team list -->

                </div>
                <!-- end tab pane -->

                <div class="tab-pane fade" id="edit-event" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Editar evento</h6>
                                        <div class="row">
                                            <form action="{{ route('reglasEvento') }}" method="POST">
                                                @csrf

                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nombre del evento</label>
                                                        <input class="form-control" placeholder="Nombre del evento" type="text" name="title" value="{{ $evento->nombre }}" required/>
                                                        <div class="invalid-feedback">Proporcione un nombre de evento válido</div>
                                                    </div>
                                                </div><!--end col-->
                                                
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tipo</label>
                                                        <select class="form-select" name="category" id="event-category" required>
                                                            <option value="Clase">Clase</option>
                                                            <option value="Campamento">Campamento</option>
                                                            <option value="Curso">Curso</option>
                                                            <option value="Seminario">Seminario</option>
                                                            <option value="Entrenamiento">Entrenamiento</option>
                                                            <option value="Evaluación">Evaluación</option>
                                                            <option value="Concurso">Concurso</option>
                                                            <option value="Torneo">Torneo</option>
                                                            <option value="Conferencia">Conferencia</option>
                                                            <option value="Exposición">Exposición</option>
                                                            <option value="Exhibición">Exhibición</option>
                                                        </select>
                                                        <div class="invalid-feedback">Por favor, seleccione una categoria valida</div>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Tipo de asitencia</label>
                                                                <select class="form-select" name="tipoAsistencia" id="event-astype" required>
                                                                    <option value="Total">Total</option>
                                                                    <option value="Parcial">Parcial</option>
                                                                </select>
                                                                <div class="invalid-feedback">Por favor, seleccione un tipo valido</div>
                                                            </div>
                                                        </div><!--end col-->
                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Modalidad</label>
                                                                <select class="form-select" name="modalidad" id="event-modalidad" required>
                                                                    <option value="Presencial">Presencial</option>
                                                                    <option value="A distancia">A distancia</option>
                                                                    <option value="Híbrida">Híbrida</option>
                                                                </select>
                                                                <div class="invalid-feedback">Por favor, seleccione una modalidad valida</div>
                                                            </div>
                                                        </div><!--end col-->
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label>Fecha del evento</label>
                                                        <div class="input-group">
                                                            <input name="fecha" type="text" id="event-start-date" class="form-control flatpickr flatpickr-input" placeholder="Seleccione una fecha" readonly required>
                                                            <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-12" id="time">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Hora de inicio</label>
                                                                <div class="input-group">
                                                                    <input name="horaInicio" id="timepicker1" type="text"
                                                                        class="form-control flatpickr flatpickr-input" placeholder="Seleccione la hora de inicio" value="{{ $evento->horaInicio }}" readonly>
                                                                    <span class="input-group-text"><i class="ri-time-line"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Hora de fin</label>
                                                                <div class="input-group">
                                                                    <input name="horaFin" id="timepicker2" type="text" class="form-control flatpickr flatpickr-input" placeholder="Seleccione la hora de fin" value="{{ $evento->horaFin }}" readonly>
                                                                    <span class="input-group-text"><i class="ri-time-line"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <div class="col-12" hidden>
                                                    <div class="mb-3">
                                                        <label for="event-location">Lugar del evento</label>
                                                        <div>
                                                            <input type="text" class="form-control" name="event-location" id="event-location" placeholder="Lugar del evento">
                                                        </div>
                                                    </div>
                                                </div><!--end col-->
                                                <input type="hidden" id="eventid" name="eventid" value="" />
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Descripción</label>
                                                        <textarea name="descripcion" class="form-control" id="event-description" placeholder="Descripción del evento" rows="3" spellcheck="false" >{{ $evento->descripcion }}</textarea>
                                                    </div>
                                                </div><!--end col-->

                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <input type="hidden" name="idEvento_reglas" value="{{ $evento->id }}">
                                                        <label for="formrow-firstname-input" class="form-label">Reglas de asistencia al evento</label>
                                                        <textarea name="reglas" class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Añade los requisitos que debe cumplir el participante para formar parte de esta actividad.">{{$evento->reglas}}</textarea>
                                                    </div>
                                                </div>

                                                 <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="formrow-firstname-input" class="form-label">Redaccion para constancias de coordinadores</label>
                                                        <textarea name="redaccionCoordinador" class="form-control" id="exampleFormControlTextarea1" rows="3" 
                                                        placeholder="Añade una redacción que se verá cuando se generen las constancias de los coordinadores.">{{$evento->redaccionCoordinador}}</textarea>
                                                    </div>
                                                </div>

                                                 <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="formrow-firstname-input" class="form-label">Redaccion para constancias de participantes</label>
                                                        <textarea name="redaccionParticipante" class="form-control" id="exampleFormControlTextarea1" rows="3" 
                                                        placeholder="Añade una redacción que se verá cuando se generen las constancias de los participantes.">{{$evento->redaccionParticipante}}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <br>
                                                        <button type="submit" class="btn btn-primary ">Guardar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        


                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- ene col -->
                        <div class="col-xl-3 col-lg-4">
                            
                            <div class="card">
                                <div class="card-header align-items-center d-flex border-bottom-dashed">
                                    <h4 class="card-title mb-0 flex-grow-1">Coordinador (es)</h4>
                                </div>
                                @foreach($coordinadoresNombres as $coordinador)
                                    <div class="vstack gap-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs flex-shrink-0 me-3">
                                                <img src="{{ URL::asset('images/'.$coordinador->avatar) }}" alt=""
                                                    class="img-fluid rounded-circle" style="aspect-ratio: 1/1;;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-13 mb-0">
                                                {{$coordinador->name . ' ' . $coordinador->apaterno . ' ' . $coordinador->amaterno}}
                                                </h5>
                                            </div>
                                        </div>
                                        <!-- end member item -->
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                    <!-- end team list -->
                </div>
                <!-- end tab pane -->

                <div class="tab-pane fade" id="participantes" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-12 col-lg-8">
                            <div class="d-flex align-items-center mb-4">
                                <h5 class="card-title flex-grow-1">Participantes confirmados</h5>
                                <button type="button" class="btn btn-primary" onclick="copiarAlPortapapeles('correos')">
                                    Copiar correos
                                </button>
                            </div>
                            <div class="table-responsive table-card">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Correo electrónico</th>
                                            <th scope="col">Copiar correo electrónico</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asistencias_previstas as $aspre)
                                            @php
                                                //Obtener los datos del usuario que asistirá al evento
                                                $userpre = DB::table('users')->where('id', $aspre->id_alumno)->first();
                                                $correos = $correos . "," . $userpre->email;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ URL::asset('images/' . $userpre->avatar) }}" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                        </div>
                                                        <div class="flex-grow-1 ms-2 name">
                                                            {{$userpre->name . ' ' . $userpre->apaterno . ' ' . $userpre->amaterno}}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td id="{{$loop->iteration}}" value="{{$userpre->email}}"><a href="mailto:{{$userpre->email}}">{{$userpre->email}}</a></td>
                                                <td>
                                                    <button type="button" class="btn btn-soft-primary btn-sm" onclick="copiarAlPortapapeles({{$loop->iteration}})"><i
                                                            class="ri-copy-2-fill me-1 align-bottom"></i> Copiar</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                            <input type="hidden" id="correos" value="{{$correos}}">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- ene col -->
                    </div>
                    <!-- end row -->
                    <!-- end team list -->
                </div>
                <!-- end tab pane -->

                <div class="tab-pane fade" id="asistencia" role="tabpanel">
                    <div class="row">
                        <div class="card-header ">
                            <h4 class="card-title mb-0">Escanea el QR para pasar tu asistencia</h4>
                        </div>
                        <div class="card-body">
                            <div class="sitemap-content">
                                <div class="visible-print text-center">
                                    @php
                                        //Obtener la primer fila de la tabla confi_eventos donde el idEvento sea ia igual a $idEvento
                                        $eventoqr = DB::table('confi_eventos')->where('idEvento', $getId)->first();

                                        $qr = QrCode::size(400)->margin(0)->generate("http://panel.sigaclub.com/asistencias?evento=".$getId."&token=".$eventoqr->qrActual);
                                        echo $qr;

                                    @endphp
                                </div>
                            </div>
                            <!--end sitemap-content-->
                        </div>
                        <!-- ene col -->
                    </div>
                    <!-- end row -->
                    <!-- end team list -->
                </div>
                <!-- end tab pane -->

                <div class="tab-pane fade" id="asistentes" role="tabpanel">
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Nombre del participante</th>
                                            <th scope="col">Total de horas registradas</th>
                                            <th scope="col">Constancias</th>
                                            <th scope="col">Acuses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asistencias as $asistencia)
                                            @php
                                                $constancia = DB::table('constancias')->where('idAsistencia', $asistencia->id)->first();
                                                //Obtener el usuario que asistió al evento
                                                $user = DB::table('users')->where('id', $asistencia->idUsuario)->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ URL::asset('images/' . $user->avatar) }}" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                        </div>
                                                        <div class="flex-grow-1 ms-2 name">
                                                            {{$user->name . ' ' . $user->apaterno . ' ' . $user->amaterno}}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{$asistencia->asistenciaTotal}}</td>
                                                <td>
                                                    @if($constancia == NULL)
                                                        <a href="pages-constancias-form?uid={{$asistencia->id}}"  class="btn btn-primary btn-sm">Generar constancia</a>
                                                    @else
                                                        @if($constancia->redaccion == "False")
                                                            <a href="{{ URL::asset('toogleAcuse/' . $constancia->id . '/False' ) }}" class="btn btn-primary btn-sm">
                                                                <i class="ri-eye-fill"> Permitir descarga</i>
                                                            </a>
                                                        @else
                                                            <a href="{{ URL::asset('toogleAcuse/' . $constancia->id . '/True' ) }}" class="btn btn-primary btn-sm">
                                                                <i class="ri-eye-off-fill"> No permitir descarga</i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($constancia != NULL)
                                                        <a href="{{ URL::asset('acuses/' . $constancia->acuse) }}" target="_blank" class="btn btn-primary btn-sm">
                                                            Descargar acuse
                                                        </a>
                                                    @else
                                                        <form action="{{route('createAcuse')}}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="hidden" name="idAsistencia_acuse" value="{{$asistencia->id}}">
                                                            <input name="acuse_file" type="file" name="acuse" id="acuse">
                                                            <button type="submit" class="btn btn-primary btn-sm">Subir acuse</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end tab pane -->


            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/project-overview.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/calendar.init.js') }}"></script>
    <script>

        let conteo = 0;

        function copiarAlPortapapeles(id) {
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById(id).getAttribute("value"));
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
        }
        function agregarcoord(){
            //Cancel submit
            event.preventDefault();

            //Obtener el valor del select 
            var idcords = document.getElementById("idcords").value;
            //Obtener el texto del select
            var namecords = document.getElementById("idcords").options[document.getElementById("idcords").selectedIndex].text;

            //Checar si existe un label con id = namecords
            if(document.getElementById(namecords)){
                //Si existe, no hacer nada
                return;
            }
            else{
                conteo++;
                //Agregar un input hidden con name=idcords y value=idcords
                var newInput = document.createElement('input');
                //Poner type hidden
                newInput.setAttribute("type", "hidden");
                //agrego la clase deseada
                newInput.className += "form-control";
                //Poner name al input igual al id del select
                newInput.setAttribute("name", namecords);
                //Poner id al input igual al id del select
                newInput.setAttribute("id", namecords);
                //Poner value al input igual al id del select
                newInput.setAttribute("value", idcords);
                //agregando el input
                var contenedor = document.getElementById('labels');
                contenedor.appendChild(newInput);

                //Agregar un label con el nombre del coordinador
                var newLabel = document.createElement('label');
                //agrego la clase deseada
                newLabel.className += "col-md-3 control-label";
                //Poner id al label igual al id del select
                newLabel.setAttribute("id", namecords);
                //Obtener de la base de datos el nombre del user con el idcords
                newLabel.innerHTML = namecords;
                //agregando el label
                var contenedor = document.getElementById('labels');
                contenedor.appendChild(newLabel);

                //Agregar boton para eliminar el coordinador
                var newButton = document.createElement('button');
                //agrego la clase deseada
                newButton.className += "btn btn-danger";
                //Poner id al label igual al id del select
                newButton.setAttribute("id", namecords);
                //Poner value al label igual al id del select
                newButton.setAttribute("value", idcords);
                //Poner onclick al boton
                newButton.setAttribute("onclick", "eliminarcoord(this.id, this.value)");
                //Poner el texto del boton
                newButton.innerHTML = "🗑️";
                //agregando el label
                var contenedor = document.getElementById('labels');
                contenedor.appendChild(newButton);

                //Poner un br
                var newBr = document.createElement('br');
                newBr.setAttribute("id", namecords + "br");
                contenedor.appendChild(newBr);
            }
            if(conteo > 0){
                //Mostrar el boton de guardar
                document.getElementById("btnGuardar").style.display = "inline-block";
            }
        }

        function eliminarcoord(namecords, idcords){
                //Eliminar el input hidden con name=namecords y value=idcords
                var input = document.getElementById(namecords);
                input.parentNode.removeChild(input);

                //Eliminar el label con id=namecords
                var label = document.getElementById(namecords);
                label.parentNode.removeChild(label);

                //Eliminar el boton con id=namecords
                var button = document.getElementById(namecords);
                button.parentNode.removeChild(button);

                //Eliminar el br
                var br = document.getElementById(namecords + "br");
                br.parentNode.removeChild(br);

                conteo--;

                if(conteo == 0){
                    //Ocultar el boton de guardar
                    document.getElementById("btnGuardar").style.display = "none";
                }
            }
    </script>
@endsection
