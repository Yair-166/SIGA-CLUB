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
            Constancia
        @endslot
    @endcomponent
@php
    //Recibir datos via get
    $uid = $_GET['uid'];
    //Obtener todas las asistencias del usuario con el id del usuario
    $asistencia = DB::table('asistencias')->where('id', $uid)->first();
    //Obtener el evento con el idEvento de la asistencia
    $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
    //Obtener el club 
    $club = DB::table('clubes')->where('id', $evento->id_club)->first();
    //Obtener confi_evento con el idEvento de la asistencia
    $confi_evento = DB::table('confi_eventos')->where('idEvento', $asistencia->idEvento)->first();
    //Obtener las filas de confi_evento donde el idEvento sea el mismo que la asistencia y coordinador sea diferente a -1
    $coordinadores = DB::table('confi_eventos')->where('idEvento', $asistencia->idEvento)->where('id_coordinador', '!=', '-1')->get();

    //Obtener las autoridades del club
    $autoridades = DB::table('autoridades')->where('idClub', $evento->id_club)->get();
    $contador = DB::table('autoridades')->where('idClub', $evento->id_club)->count();
    
    foreach($coordinadores as $coordinador){
        $coordinador = DB::table('users')->where('id', $coordinador->id_coordinador)->first();
        //Guardar datos del coordinador en un array nuevo
        $coordinadores_array[] = new stdClass();
        $coordinadores_array[count($coordinadores_array)-1]->id = $contador + 1;
        $coordinadores_array[count($coordinadores_array)-1]->nombre = $coordinador->name;
        $coordinadores_array[count($coordinadores_array)-1]->aPaterno = $coordinador->apaterno;
        $coordinadores_array[count($coordinadores_array)-1]->aMaterno = $coordinador->amaterno;
        $coordinadores_array[count($coordinadores_array)-1]->cargo = "Coordinador de la actividad";
        //Push coordinadores_array en autoridades
        $autoridades->push($coordinadores_array[count($coordinadores_array)-1]);
    }

    //Obtener el usuario de la base de datos por el id
    $user = DB::table('users')->where('id', $asistencia->idUsuario)->first();
@endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-n4 mx-n4 border-0">
                <div class="bg-soft-primary">
                    <div class="card-body pb-0 px-4">

                        <ul class="nav nav-tabs-custom border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#externa"
                                    role="tab">
                                    Constancia de evento
                                </a>
                            </li>

                            @if($user->boleta != NULL && Auth::user()->boleta != NULL)
                                <li class="nav-item">
                                    <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#ipn" role="tab">
                                        Constancia de evento IPN
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
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="externa" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-12 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <form action="{{route('pdf2', [$asistencia->id, 0])}}" method="POST">
                                        @csrf
                                            <h6 class="mb-3 fw-semibold text-uppercase">Descripción</h6>
                                            <label for="redaccion">Texto personalizado para la constancia</label>
                                            <textarea class="form-control" name="redaccion" id="redaccion" rows="3" placeholder="Escribe aquí tu texto personalizado para la constancia"></textarea>
                                            <br>
                                            <input hidden name="autoridades_externo" value="{{$autoridades}}">
                                            <label for="autoridades" class="form-label">Autoridades que firmarán la constancia</label>
                                            @foreach($autoridades as $autoridad)
                                                <div class="form-check form-switch form-switch-md" dir="ltr">
                                                    <input type="checkbox" class="form-check-input" name="autoridad{{$autoridad->id}}" 
                                                    value="{{$autoridad->id}}">
                                                    <label class="form-check-label" for="customSwitchsizemd">{{$autoridad->nombre}} {{$autoridad->aPaterno}} {{$autoridad->aMaterno}}</label>
                                                </div>
                                            @endforeach
                                            <br>
                                            <button type="submit" class="btn btn-primary">Generar PDF</button>
                                        </form>
                                        {{-- <a href="{{route('pdf', [$asistencia->id, 0])}}" class="btn btn-primary btn-sm">Generar Externo</a> --}}
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- ene col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end tab pane -->

                <div class="tab-pane fade" id="ipn" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-12 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <form action="{{route('pdf2', [$asistencia->id, 1])}}" method="POST">
                                        @csrf
                                            <h6 class="mb-3 fw-semibold text-uppercase">Descripción</h6>
                                            <label for="redaccion">Texto personalizado para la constancia</label>
                                            <textarea class="form-control" name="redaccion_ipn" id="redaccion" rows="3" placeholder="Escribe aquí tu texto personalizado para la constancia"></textarea>
                                            <br>
                                            <input hidden name="autoridades_externo" value="{{$autoridades}}">
                                            <label for="autoridades" class="form-label">Autoridades que firmarán la constancia</label>
                                            @foreach($autoridades as $autoridad)
                                                <div class="form-check form-switch form-switch-md" dir="ltr">
                                                    <input type="checkbox" class="form-check-input" name="autoridad_{{$autoridad->id}}" 
                                                    value="{{$autoridad->id}}">
                                                    <label class="form-check-label" for="customSwitchsizemd">{{$autoridad->nombre}} {{$autoridad->aPaterno}} {{$autoridad->aMaterno}}</label>
                                                </div>
                                            @endforeach
                                            <br>
                                            <label for="Escuela" class="form-label">Escuela</label>
                                            <select class="form-select" aria-label="Default select example" name="escuela">
                                                <option value="Centro Interdisciplinario de Ciencias de la Salud Unidad Santo Tomás" >CICS Unidad Santo Tomás</option>
                                                <option value="Centro Interdisciplinario de Ciencias de la Salud Unidad Milpa Alta" >CICS Unidad Milpa Alta</option>
                                                <option value="Escuela Nacional de Biblioteconomía y Archivonomía" >ENBA </option>
                                                <option value="Escuela Nacional de Ciencias Biológicas" >ENCB </option>
                                                <option value="Escuela Nacional de Medicina y Homeopatía" >ENMyH </option>
                                                <option value="Escuela Superior de Comercio y Administración Unidad Santo Tomás" >ESCA Unidad Santo Tomás</option>
                                                <option value="Escuela Superior de Comercio y Administración Unidad Tepepan" >ESCA Unidad Tepepan</option>
                                                <option value="Escuela Superior de Cómputo" >ESCOM </option>
                                                <option value="Escuela Superior de Economía" >ESE </option>
                                                <option value="Escuela Superior de Enfermería y Obstetricia" >ESEO </option>
                                                <option value="Escuela Superior de Física y Matemáticas" >ESFM </option>
                                                <option value="Escuela Superior de Ingeniería Mecánica y Eléctrica Unidad Zacatenco" >ESIME Unidad Zacatenco</option>
                                                <option value="Escuela Superior de Ingeniería Mecánica y Eléctrica Unidad Azcapotzalco" >ESIME Unidad Azcapotzalco</option>
                                                <option value="Escuela Superior de Ingeniería Mecánica y Eléctrica Unidad Culhuacán" >ESIME Unidad Culhuacán</option>
                                                <option value="Escuela Superior de Ingeniería Mecánica y Eléctrica Unidad Ticomán" >ESIME Unidad Ticomán</option>
                                                <option value="Escuela Superior de Ingeniería Química e Industrias Extractivas" >ESIQIE </option>
                                                <option value="Escuela Superior de Ingeniería Textil" >ESIT </option>
                                                <option value="Escuela Superior de Ingeniería y Arquitectura Unidad Tecamachalco" >ESIA Unidad Tecamachalco</option>
                                                <option value="Escuela Superior de Ingeniería y Arquitectura Unidad Ticomán" >ESIA Unidad Ticomán</option>
                                                <option value="Escuela Superior de Ingeniería y Arquitectura Unidad Zacatenco" >ESIA Unidad Zacatenco</option>
                                                <option value="Escuela Superior de Medicina" >ESM </option>
                                                <option value="Escuela Superior de Turismo" >EST </option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingeniería Campus Coahuila" >UPIIC Campus Coahuila</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Biotecnología" >UPIBI </option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingeniería Campus Guanajuato" >UPIIG Campus Guanajuato</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingeniería Campus Zacatecas" >UPIIZ Campus Zacatecas</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingeniería Campus Hidalgo" >UPIIH Campus Hidalgo</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingeniería Campus Palenque" >UPIIP Campus Palenque</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingeniería Campus Tlaxcala" >UPIIT Campus Tlaxcala</option>
                                                <option value="Unidad Profesional Interdisciplinaria de Ingeniería y Ciencias Sociales y Administrativas" >UPIICSA </option>
                                                <option value="Unidad Profesional Interdisciplinaria en Ingeniería y Tecnologías Avanzadas" >UPIITA </option>
                                                <option value="Unidad Profesional Interdisciplinaria de Energía y Movilidad" >UPIEM </option>
                                            </select>
                                            <br>
                                            <button type="submit" class="btn btn-primary">Generar PDF</button>
                                        </form>
                                        {{-- <a href="{{route('pdf', [$asistencia->id, 1])}}" class="btn btn-primary btn-sm">Generar IPN</a> --}}
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- ene col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end tab pane -->

            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/swiper/swiper.min.js') }}"></script>

    <script src="{{ URL::asset('assets/js/pages/profile.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
