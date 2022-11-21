@extends('layouts.master')
@section('title')
    @lang('translation.overview')
@endsection
@section('content')
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
                                    Archivos
                                </a>
                            </li>
                            @if(Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id)
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-activities" role="tab">
                                        Evidencias
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-team" role="tab">
                                        Configuración
                                    </a>
                                </li>
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
                                                        <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">
                                                        {{$coordinador->name . ' ' . $coordinador->apaterno . ' ' . $coordinador->amaterno}}</a></h5>
                                                        </a></h5>
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
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <h5 class="card-title flex-grow-1">Archivos</h5>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive table-card">
                                        <table class="table table-borderless align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">Nombre del archivo</th>
                                                    @if(Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id)
                                                        <th scope="col">Ocultar</th>
                                                        <th scope="col">Eliminar</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($archivos as $archivo)
                                                    @if($archivo->isPrivate == 0)
                                                        @if (Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id)
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
                                                            @if(Auth::user()->rol == "administrador" || Auth::user()->id == $coordinador->id)
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
                <!-- end tab pane -->
                <div class="tab-pane fade" id="project-activities" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <h5 class="card-title flex-grow-1">Evidencias</h5>
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
                                                            <a href="{{ route('eliminarEvidencia', $evidencia->id) }}" class="btn btn-sm btn-soft-danger"><i
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
                                                        <label for="formrow-firstname-input" class="form-label">Coordinador</label>
                                                        <select name="id_coordinador" class="form-select" aria-label="Default select example">
                                                            <option selected>Selecciona un coordinador</option>
                                                            @foreach ($usuarios as $usuario)
                                                                <option value="{{ $usuario->id }}">
                                                                    {{ $usuario->name . ' ' . $usuario->apaterno . ' ' . $usuario->amaterno }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <br>
                                                        <button type="submit" class="btn btn-primary ">Asignar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div class="pt-3 border-top border-top-dashed mt-4">
                                            <form action="{{ route('subirArchivo') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <h4 class="card-title mb-0 flex-grow-1">Recursos</h4>
                                                <div class="row g-3">
                                                    <div class="flex-shrink-0">
                                                        <input type="hidden" name="idClub" value="{{ $evento->id_club }}">
                                                        <input type="hidden" name="idEvento" value="{{ $evento->id }}">
                                                        <input type="hidden" name="isPrivate" value="0">
                                                        <label class="form-label">Agregar recurso para descargar</label>
                                                        <input name="nombreArchivo" type="text" class="form-control col-sm-6" placeholder="Nombre del recurso">
                                                        <hr>
                                                        <input name="archivo" type="file" class="form-control col-sm-6" id="formFile">
                                                        <hr>
                                                        <button type="submit" class="btn btn-soft-primary btn-sm"><i
                                                                class="ri-upload-2-fill me-1 align-bottom"></i> Subir</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- end card body -->

                                        <div class="pt-3 border-top border-top-dashed mt-4">
                                            <form action="{{ route('subirEvidencia') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <h6 class="mb-3 fw-semibold text-uppercase">Subir evidencias</h6>
                                                <div class="row g-3">
                                                    <div class="flex-shrink-0">
                                                        <input type="hidden" name="idClub_ev" value="{{ $evento->id_club }}">
                                                        <input type="hidden" name="idEvento_ev" value="{{ $evento->id }}">
                                                        <label class="form-label">Agregar evidencia</label>
                                                        <textarea name="nota_ev" class="form-control" id="exampleFormControlTextarea1" rows="3" 
                                                        placeholder="Notas sobre la evidencia"></textarea>
                                                        <hr>
                                                        <input name="archivo_ev" type="file" class="form-control col-sm-6" id="formFile">
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
                                                <h5 class="fs-13 mb-0"><a href="#" class="text-body d-block">
                                                {{$coordinador->name . ' ' . $coordinador->apaterno . ' ' . $coordinador->amaterno}}</a></h5>
                                                </a></h5>
                                            </div>
                                        </div>
                                        <!-- end member item -->
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                            <!-- end card -->
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
                            <div class="card  align-items-center">
                                <div class="card-header align-items-center d-flex border-bottom-dashed">
                                    <h5 class="fs-13 mb-0">QR Privado</h5>
                                </div>
                                <div class="card-body">
                                    <div class="vstack gap-3">
                                        <div class="d-flex align-items-center">
                                            <a href="pages-qr" target="_blank" class="btn btn-primary btn-sm">Activar</a>
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
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/project-overview.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
