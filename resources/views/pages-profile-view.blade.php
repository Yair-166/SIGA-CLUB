@extends('layouts.master')
@section('title')
    @lang('translation.profile')
@endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/swiper/swiper.min.css') }}">
@endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1')
            Perfil
        @endslot
        @slot('title')
            Perfil
        @endslot
    @endcomponent
@php
    //Recibir datos via get
    $uid = $_GET['uid'];
    //Obtener los datos de la tabla inscripciones con el uid
    $inscripcion = DB::table('inscripciones')->where('id', $uid)->first();
    //Obtener los datos del club con el id del club
    $club = DB::table('clubes')->where('id', $inscripcion->id_club)->first();
    //Obtener todos los eventos del club
    $eventos = DB::table('eventos')->where('id_club', $inscripcion->id_club)->get();

    //Obtener todas las asistencias a los eventos que se tienen
    $asistencias = array();
    foreach ($eventos as $evento) {
        $asistencia = DB::table('asistencias')->where('idEvento', $evento->id)->where('idUsuario', $inscripcion->id_alumno)->first();
        if($asistencia){
            array_push($asistencias, $asistencia);
        }
    }

    //Obtener todas las asistencias del usuario con el id del usuario en el club $inscripcion->id_club
    //$asistencias = DB::table('asistencias')->where('idUsuario', $inscripcion->id_alumno)->get();
    //Obtener el usuario de la base de datos por el id
    $user = DB::table('users')->where('id', $inscripcion->id_alumno)->first();
    $texto = $numero = "";

    //Crear arreglo con tipos de eventos campamento, clase, Concurso, Conferencia, Curso, Entrenamiento, Evaluación, Exhibición, Exposición, Seminario, Torneo
    $tipos = "Campamento,Clase,Concurso,Conferencia,Curso,Entrenamiento,Evaluación,Exhibición,Exposición,Seminario,Torneo,";
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
    //Obtener el total de horas de asistencia del usuario
    $totalHoras = 0;
    foreach($asistencias as $asistencia){
        $totalHoras = $totalHoras + $asistencia->asistenciaTotal;
    }

@endphp
    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="{{ URL::asset('assets/images/profile-bg.jpg') }}" alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
        <div class="row g-4">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img src="@if ($user->avatar != '') {{ URL::asset('images/' . $user->avatar) }}@else{{ URL::asset('assets/images/users/avatar-1.jpg') }} @endif"
                        alt="user-img" class="img-thumbnail rounded-circle" />
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">{{$user->name . " " . $user->apaterno  . " " . $user->amaterno}}</h3>
                    <p class="text-white-75">{{$user->rol}}</p>
                    <div class="hstack text-white-50 gap-1">
                        <div class="me-2"><i
                                class="ri-mail-line me-1 text-white-75 fs-16 align-middle"></i>
                                {{$user->email}}
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            @if($uid != Auth::user()->id)
                                @if(Auth::user()->rol == 'administrador')
                                    @php
                                        if($numero==1){
                                            $texto = "Club administrado";
                                        }else{
                                            $texto = "Clubes administrados";
                                        }
                                        
                                        //obtener el numero de clubes administrados por el usuario
                                        $numero = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->count();
                                    @endphp
                                @elseif(Auth::user()->rol == 'colaborador')
                                    @php
                                        if($numero==1){
                                            $texto = "Club inscrito";
                                        }else{
                                            $texto = "Clubes inscritos";   
                                        }
                                        //obtener el numero de clubes en los que el usuario esta inscrito
                                        $numero = DB::table('inscripciones')->where('id_alumno', Auth::user()->id)->count();
                                    @endphp
                                @endif
                            @endif
                            <h4 class="text-white mb-1">{{$numero}}</h4>
                            <p class="fs-14 mb-0">{{$texto}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->

        </div>
        <!--end row-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="d-flex">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                        <li class="nav-item">
                            @if($inscripcion->tags != '')
                                <h3>Tags del usuario</h3>
                                @php
                                    $tags = explode(",", $inscripcion->tags);
                                    //Eliminar el ultimo elemento del array
                                    array_pop($tags);
                                @endphp
                                @foreach ($tags as $tag)
                                    <span class="badge bg-primary">{{$tag}}
                                        <a type="button" class="badge btn-danger" href={{ route('eliminarTagInscripcion', ['id' => $uid, 'tag' => $tag])}}>
                                            <i class="mdi mdi-close"></i>
                                        </a>
                                    </span>
                                @endforeach
                            @endif
                        </li>
                    </ul>
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-5">
                                            {{$club->nombre}}
                                        </h4>
                                        
                                        @if($club->tags != '')
                                            @php
                                                $tagsDisponibles = explode(",", $club->tags);
                                                //Eliminar el ultimo elemento del array
                                                array_pop($tagsDisponibles);
                                            @endphp
                                            <form action="{{ route('agregarTagInscripcion', ['id' => $uid]) }}" method="POST">
                                                @csrf
                                                <h4 class="card-title mb-4">Agregar TAG</h4>
                                                <select class="form-select" name="tag">
                                                    @foreach ($tagsDisponibles as $tag)
                                                        <option value="{{$tag}}">{{$tag}}</option>
                                                    @endforeach
                                                </select>
                                                <br>
                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-9">
                                <div class="card">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="genques-headingOne">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseOne" aria-expanded="false" aria-controls="genques-collapseOne">
                                                Desgloce de horas por tipo de evento en el club (Hotas totales: {{$totalHoras}})
                                            </button>
                                        </h2>
                                        <input type="hidden" id="tiposAlumnoString" value="{{$tiposAlumnoString}}">
                                        <input type="hidden" id="asistenciasTotalesString" value="{{$asistenciasTotalesString}}">
                                        <div id="genques-collapseOne" class="accordion-collapse collapse collapsed" aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion">
                                            <div class="accordion-body row">
                                                <center>
                                                    <div class="col-sm-7">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="card-title mb-0">Horas por tipo de evento</h4>
                                                            </div>
                                                            <div class="card-body">
                                                                <canvas id="HorasxEvento" class="chartjs-chart" data-colors='["#344D67", "#6ECCAF"]'></canvas>
                                                            </div>
                                                        </div> 
                                                    </div> <!-- end col -->
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card -->

                                
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <h5 class="card-title flex-grow-1 mb-0">Asistencias</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-borderless align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Nombre del evento</th>
                                                            <th scope="col">Fecha inicio del evento</th>
                                                            <th scope="col">Fecha final del evento</th>
                                                            <th scope="col">Total de horas registradas</th>
                                                            <th scope="col">Constancias</th>
                                                            <th scope="col">Acuses</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($asistencias as $asistencia)
                                                            @php
                                                                $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
                                                                //Obtener la constancia con el idAsistencia
                                                                $constancia = DB::table('constancias')->where('idAsistencia', $asistencia->id)->first();
                                                            @endphp
                                                            <tr>
                                                                <td>{{$evento->nombre}}</td>
                                                                <td>{{$evento->fechaInicio}}</td>
                                                                <td>{{$evento->fechaFin}}</td>
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
                                            {{-- <div class="text-center mt-3">
                                                <a href="javascript:void(0);" class="text-primary "><i
                                                        class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i>
                                                    Load more </a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                                

                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end tab-pane-->
                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/swiper/swiper.min.js') }}"></script>

    <script src="{{ URL::asset('assets/js/pages/profile.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/chart.js/chart.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/chartjs.init.js') }}"></script>
@endsection
