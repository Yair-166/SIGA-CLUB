@extends('layouts.master-without-nav')

@section('title')
    @lang('Constancia válida')
@endsection

@php
    //Obtener la variable de la ruta
    $id = request()->route('id');
    //Obtener la asistencia por el id de la base de datos
    $asistencia = DB::table('asistencias')->where('id', $id)->first();
    //Obtener el evento de la tabla eventos con el id del evento de la tabla asistencias
    $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
    //Obtener el club con el id_club de la tabla eventos
    $club = DB::table('clubes')->where('id', $evento->id_club)->first();
    //Obtener los datos del usuario con el idUsuario de la tabla asistencias
    $usuario = DB::table('users')->where('id', $asistencia->idUsuario)->first();
    
@endphp

@section('body')

    <body>
    @endsection
    @section('content')
        <div class="auth-page-wrapper pt-5">
            <!-- auth page bg -->
            <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
                <div class="bg-overlay"></div>

                <div class="shape">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 1440 120">
                        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                    </svg>
                </div>
            </div>

            <!-- auth page content -->
            <div class="auth-page-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center pt-4">
                                <div class="">
                                    <img src="{{ URL::asset('assets/images/astrocheck.png') }}" alt="" class="error-basic-img move-animation">
                                </div>
                                <div class="mt-n4">
                                    <h1 class="display-1 fw-medium">Constancia válida</h1>
                                    <h3 class="text-uppercase">{{$club->nombre}} || {{$evento->nombre}}</h3>
                                    <h4 class="text-muted mb-4">
                                        {{$usuario->name}} {{$usuario->apaterno}} {{$usuario->amaterno}} 
                                        <i class="mdi mdi-clock-fast text-success"></i>
                                        {{$asistencia->asistenciaTotal}} horas
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                </div>
                <!-- end container -->
            </div>
            <!-- end auth page content -->

            <!-- footer -->
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <p class="mb-0 text-muted">&copy;
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script> SIGA-CLUB <i class="mdi mdi-heart text-danger"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>
        <!-- end auth-page-wrapper -->
    @endsection
    @section('script')
        <!-- particles js -->
        <script src="{{ URL::asset('assets/libs/particles.js/particles.js.min.js') }}"></script>
        <!-- particles app js -->
        <script src="{{ URL::asset('assets/js/pages/particles.app.js') }}"></script>
    @endsection
