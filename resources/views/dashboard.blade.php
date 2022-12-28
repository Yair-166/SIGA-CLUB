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
    $clubid = $_GET['club'];
    //Obtener los datos del club con el id del club
    $club = DB::table('clubes')->where('id', $clubid)->first();
    //Obtener todos los eventos del club
    $eventos = DB::table('eventos')->where('id_club', $clubid)->get();
    //Obtener el numero de eventos del club
    $totalEventos = DB::table('eventos')->where('id_club', $clubid)->count();
    //Obtener de la tabla users el nombre del administrador del club
    $admin = DB::table('users')->where('id', $club->idAdministrador)->first();
    //Obtener todas las inscripciones del club
    $inscripciones = DB::table('inscripciones')->where('id_club', $clubid)->where('active', 1)->get();
    //Obtener el numero de inscripciones activas
    $totalInscripciones = DB::table('inscripciones')->where('id_club', $clubid)->where('active', 1)->count();
    
    //Obtener todos los usuarios de la tabla users donde su id sea igual a inscripciones->id_alumno
    $usuarios = array();
    foreach ($inscripciones as $inscripcion) {
        //Obtener de la tabla users el nombre del administrador del club
        $usuario = DB::table('users')->where('id', $inscripcion->id_alumno)->first();
        //push a un arreglo de usuarios
        array_push($usuarios, $usuario);
    }

    //Obtener las asistencias de los eventos del club
    $asistencias = array();
    foreach ($eventos as $evento) {
        //Obtener de la tabla asistencias los registros que tengan el id del evento
        $asistenciasEvento = DB::table('asistencias')->where('idEvento', $evento->id)->get();
        //push a un arreglo de asistencias
        foreach ($asistenciasEvento as $asistencia) {
            array_push($asistencias, $asistencia);
        }
    }
    /*Imprimir las asistencias
    foreach ($asistencias as $asistencia) {
        print_r($asistencia);
    }*/

    $asistencias_previstas = array();
    foreach ($eventos as $evento) {
        //Obtener de la tabla asistencias los registros que tengan el id del evento
        $asistenciasPrevistas = DB::table('asistencias_previstas')->where('id_evento', $evento->id)->get();
        //push a un arreglo de asistencias
        foreach ($asistenciasPrevistas as $asistenciasPrevista) {
            array_push($asistencias_previstas, $asistenciasPrevista);
        }    
    }
    /*Imprimir las asistencias
    foreach ($asistencias_previstas as $asistencias_prevista) {
        print_r($asistencias_prevista);
    }*/

    //Obtener tags del club
    $tags = explode(",", $club->tags);
    //Eliminar el ultimo elemento del array
    array_pop($tags);
    //Quitar espacios en blanco
    $tags = array_map('trim', $tags);
    //Obtener el numero de tags
    $totalTags = count($tags);
    //Crear arreglo con tipos de eventos campamento, clase, Concurso, Conferencia, Curso, Entrenamiento, Evaluación, Exhibición, Exposición, Seminario, Torneo
    $tipos = "Campamento,Clase,Concurso,Conferencia,Curso,Entrenamiento,Evaluación,Exhibición,Exposición,Seminario,Torneo,";
    //Convertir el string en un arreglo
    $tipos = explode(",", $tipos);
    //Eliminar el ultimo elemento del array
    array_pop($tipos);

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
                    <img src="@if ($club->foto != '') {{ URL::asset('images/' . $club->foto) }}@else{{ URL::asset('assets/images/users/avatar-1.jpg') }} @endif"
                        alt="user-img" class="img-thumbnail rounded-circle" />
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">{{$club->nombre}}</h3>
                    <p class="text-white">{{$admin->name." ".$admin->apaterno." ".$admin->amaterno}}</p>
                    <div class="hstack text-white gap-1">
                        <div class="me-2"><i
                                class="ri-mail-line me-1 text-white fs-16 align-middle"></i>
                                {{$admin->email}}
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            
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
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Total de constancias expedidas</h5>
                                        <h5>
                                            {{$club->constanciasExpedidas}}
                                        </h5>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Total de eventos</h5>
                                        <h5>
                                            {{$totalEventos}}
                                        </h5>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Total de participantes inscritos</h5>
                                        <h5>
                                            {{$totalInscripciones}}
                                        </h5>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->
                            </div>
                            <!--end col-->
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Total de tags</h5>
                                        <h5>
                                            {{$totalTags}}
                                        </h5>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->
                            </div>
                            <!--end col-->

                            <div><p> </p></div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="genques-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseOne" aria-expanded="false" aria-controls="genques-collapseOne">
                                        Eventos
                                    </button>
                                </h2>
                                <div id="genques-collapseOne" class="accordion-collapse collapse collapsed" aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body row">
                                        <div class="col-lg-8">
                                            <div class="table-responsive">
                                                <table class="table table-borderless align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Tipo de evento</th>
                                                            <th scope="col">Cantidad de eventos</th>
                                                            <th scope="col">Participaciones confirmadas promedio</th>
                                                            <th scope="col">Participaciones promedio</th>
                                                            <th scope="col">Participantes promedio por género <b style="color:red;">*</b></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($tipos as $tipo)
                                                            @php
                                                                $eventosTipo = array();
                                                                foreach ($eventos as $evento) {
                                                                    //Meter en un arreglo los eventos de cada tipo
                                                                    if ($evento->tipo == $tipo) {
                                                                        array_push($eventosTipo, $evento);
                                                                    }
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>{{$tipo}}</td>
                                                                <td>
                                                                    @php
                                                                        echo count($eventosTipo);
                                                                    @endphp
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        //Recorrer todo el array $eventosTipo y obtener aquellos donde el id del evento sea igual al id del evento en $asistencias_previstas
                                                                        $asistencias_previstasTipo = array();
                                                                        foreach ($eventosTipo as $eventoTipo) {
                                                                            foreach ($asistencias_previstas as $asistencia) {
                                                                                if ($asistencia->id_evento == $eventoTipo->id) {
                                                                                    array_push($asistencias_previstasTipo, $asistencia);
                                                                                }
                                                                            }
                                                                        }

                                                                        if(count($eventosTipo)==0){
                                                                            echo 0;
                                                                        }else{
                                                                            echo count($asistencias_previstasTipo)/count($eventosTipo);
                                                                        }
                                                                    @endphp
                                                                </td>
                                                                <td>
                                                                    @php
                                                                         //Recorrer todo el array $eventosTipo y obtener aquellos donde el id del evento sea igual al id del evento en $asistencias
                                                                        $asistenciasTipo = array();
                                                                        foreach ($eventosTipo as $eventoTipo) {
                                                                            foreach ($asistencias as $asistencia) {
                                                                                if ($asistencia->idEvento == $eventoTipo->id) {
                                                                                    array_push($asistenciasTipo, $asistencia);
                                                                                }
                                                                            }
                                                                        }

                                                                        if(count($eventosTipo)==0){
                                                                            echo 0;
                                                                        }else{
                                                                            echo count($asistenciasTipo)/count($eventosTipo);
                                                                        }
                                                                    @endphp
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        //Obtener el usuario de cada asistencia
                                                                        $usuariosTipo = array();
                                                                        foreach ($asistenciasTipo as $asistenciaTipo) {
                                                                            foreach ($usuarios as $usuario) {
                                                                                if ($usuario->id == $asistenciaTipo->idUsuario) {
                                                                                    array_push($usuariosTipo, $usuario);
                                                                                }
                                                                            }
                                                                        }

                                                                        //Obtener el género de cada usuario
                                                                        $generosTipo = array();
                                                                        foreach ($usuariosTipo as $usuarioTipo) {
                                                                            array_push($generosTipo, $usuarioTipo->genero);
                                                                        }

                                                                        $hombresTipo=0;
                                                                        $mujeresTipo=0;
                                                                        $neTipo=0;

                                                                        //Obtener cuantos "Masculino" hay en el arreglo
                                                                        for($i=0; $i<count($generosTipo); $i++){
                                                                            if($generosTipo[$i] == 'Masculino'){
                                                                                $hombresTipo++;
                                                                            }
                                                                            else if ($generosTipo[$i] == 'Femenino') {
                                                                                $mujeresTipo++;
                                                                            }
                                                                            else {
                                                                                $neTipo++;
                                                                            }
                                                                        }

                                                                        if(count($eventosTipo)==0){
                                                                            echo 0;
                                                                        }else{
                                                                            echo "H: ".$hombresTipo/count($eventosTipo). " | ";
                                                                            echo "M: ".$mujeresTipo/count($eventosTipo). " | ";
                                                                            echo "N.E.: ".$neTipo/count($eventosTipo);
                                                                        }
                                                                    @endphp
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <img src="https://i.postimg.cc/BbzZRP0n/Whats-App-Image-2022-12-26-at-20-01-28.jpg" style="width: 100%; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end eventos-->

                            <div><p> </p></div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="genques-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseTwo" aria-expanded="false" aria-controls="genques-collapseTwo">
                                        Participantes
                                    </button>
                                </h2>
                                <div id="genques-collapseTwo" class="accordion-collapse collapse" aria-labelledby="genques-headingTwo" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body row">
                                        <div class="col-lg-8">
                                            <div class="table-responsive">
                                                <table class="table table-borderless align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Etiquetas</th>
                                                            <th scope="col">Hombres</th>
                                                            <th scope="col">Mujeres</th>
                                                            <th scope="col">No especificado</th>
                                                            <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $masculinos = array();
                                                            $femeninos = array();
                                                            $nes = array();

                                                            foreach ($usuarios as $usuario) {
                                                                $inscripcionTMP = DB::table('inscripciones')->where('id_alumno', $usuario->id)->first();
                                                                if($usuario->genero == 'Masculino'){
                                                                    array_push($masculinos, $inscripcionTMP);
                                                                }
                                                                else if ($usuario->genero == 'Femenino') {
                                                                    array_push($femeninos, $inscripcionTMP);
                                                                }
                                                                else {
                                                                    array_push($nes, $inscripcionTMP);
                                                                }
                                                            }

                                                            //Escribir los tags
                                                            foreach($tags as $tag){
                                                                $thismtag = 0;
                                                                $thisftag = 0;
                                                                $thisnetag = 0;

                                                                foreach($masculinos as $masculino){
                                                                    if(strpos($masculino->tags, $tag) !== false){
                                                                        $thismtag++;
                                                                    }
                                                                }

                                                                foreach($femeninos as $femenino){
                                                                    if(strpos($femenino->tags, $tag) !== false){
                                                                        $thisftag++;
                                                                    }
                                                                }

                                                                foreach($nes as $ne){
                                                                    if(strpos($ne->tags, $tag) !== false){
                                                                        $thisnetag++;
                                                                    }
                                                                }

                                                                //Crear arreglo bidimensional donde 

                                                                echo "<tr>";
                                                                echo "<td>".$tag."</td>";
                                                                echo "<td>" . $thismtag . "</td>";
                                                                echo "<td>" . $thisftag . "</td>";
                                                                echo "<td>" . $thisnetag . "</td>";
                                                                echo "<td>" . $thismtag + $thisftag + $thisnetag . "</td>";
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>Total</td>
                                                            <td>{{ count($masculinos) }}</td>
                                                            <td>{{ count($femeninos) }}</td>
                                                            <td>{{ count($nes) }}</td>
                                                            <td>{{ $totalInscripciones }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <img src="https://i.postimg.cc/BbzZRP0n/Whats-App-Image-2022-12-26-at-20-01-28.jpg" style="width: 100%; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end Participantes-->

                            <div><br><p>
                            <b style="color:red;">*</b> H: Hombres, M: Mujeres, N.E.: No especificado.
                            </p></div>

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
@endsection
