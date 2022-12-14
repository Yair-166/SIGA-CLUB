@extends('layouts.master')
@section('title')
    @lang('translation.list-view')
@endsection
@section('css')
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Super
        @endslot
        @slot('title')
            Dashboard
        @endslot
    @endcomponent
    @php
        //Obtener todos los clubes de la base de datos
        $clubes = DB::table('clubes')->get();
        $totalClubes = count($clubes);
        //Obtener todos los administradores de la base de datos
        $administradores = DB::table('users')->where('rol', 'administrador')->get();
        $totalAdministradores = count($administradores);
        //Obtener todos los colaboradores de la base de datos
        $colaboradores = DB::table('users')->where('rol', 'colaborador')->get();
        $totalColaboradores = count($colaboradores);
        //Obtener todos los usuarios de la base de datos cuyo rol sea diferente a super
        $usuarios = DB::table('users')->where('rol', '!=', 'super')->get();
        $totalUsuarios = count($usuarios);
        //Obtener todos los eventos de la base de datos
        $eventos = DB::table('eventos')->get();
        $totalEventos = count($eventos);
        //Crear arreglo con tipos de eventos campamento, clase, Concurso, Conferencia, Curso, Entrenamiento, Evaluación, Exhibición, Exposición, Seminario, Torneo
        $tipos = "Campamento,Clase,Concurso,Conferencia,Curso,Entrenamiento,Evaluación,Exhibición,Exposición,Seminario,Torneo,Otro,";
        //Convertir el string en un arreglo
        $tipos = explode(",", $tipos);
        //Eliminar el ultimo elemento del array
        array_pop($tipos);
        //Crear un arreglo vacio
        $cantdidadEventos = array();
        //Recorrer el arreglo de tipos de eventos
        foreach($tipos as $tipo){
            //Obtener la cantidad de eventos por tipo
            $cantidad = DB::table('eventos')->where('tipo', $tipo)->count();
            //Agregar la cantidad de eventos al arreglo
            array_push($cantdidadEventos, $cantidad);
        }
        //Convertir $tipos a string
        $tipos = implode(",", $tipos);
        //Convertir $cantdidadEventos a string
        $cantdidadEventos = implode(",", $cantdidadEventos);

    @endphp
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <a href='admin-dashboard'>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total de clubes</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{$totalClubes}}</h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded fs-3">
                                    <i data-feather="file-text" class="text-primary icon-dual-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </a>
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <a href='apps-crm-users?type=admins'>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total de administradores</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{$totalAdministradores}}</h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded fs-3">
                                    <i data-feather="user-check" class="text-primary icon-dual-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </a>
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <a href='apps-crm-users?type=colabs'>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total de participantes</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{$totalColaboradores}}</h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded fs-3">
                                    <i data-feather="users" class="text-primary icon-dual-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </a>
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <a href='apps-eventos-list?club=all'>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total de eventos</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{$totalEventos}}</h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-light rounded fs-3">
                                    <i data-feather="calendar" class="text-primary icon-dual-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </a>
            </div><!-- end card -->
        </div><!-- end col -->
    </div> <!-- end row-->

    <input type="hidden" id="totalAdministradores" value="{{$totalAdministradores}}">
    <input type="hidden" id="totalColaboradores" value="{{$totalColaboradores}}">
    <input type="hidden" id="tipos" value="{{$tipos}}">
    <input type="hidden" id="cantdidadEventos" value="{{$cantdidadEventos}}">



    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Cuentas activas</h4>
                </div>
                <div class="card-body">
                    <canvas id="grafica" class="chartjs-chart" data-colors='["#344D67", "#6ECCAF"]'></canvas>
                </div>
            </div> 
        </div> <!-- end col -->

        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Total de tipos de eventos</h4>
                </div>
                <div class="card-body">
                    <!--<canvas id="graficaTiposEventos" class="chartjs-chart" data-colors='["#344D67", "#6ECCAF", "#ADE792", "#F3ECB0"]'></canvas>-->
                    <canvas id="graficaTiposEventos" class="chartjs-chart" data-colors='["#344D67", "#6ECCAF"]'></canvas>
                </div>
            </div> 
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/list.js/list.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/list.pagination.js/list.pagination.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/invoiceslist.init.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/chart.js/chart.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/chartjs.init.js') }}"></script>
@endsection
