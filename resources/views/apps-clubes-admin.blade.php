@extends('layouts.master')
@section('title')
    @lang('translation.sellers')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Admin
        @endslot
        @slot('title')
            Clubes
        @endslot
    @endcomponent

    @php
        //Obtener todos los clubes de la base de datos donde el usuario sea el administrador
        $clubes = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->get();
        //si el usuario tiene rol super
        if(Auth::user()->rol == 'super'){
            //Obtener todos los clubes de la base de datos
            $clubes = DB::table('clubes')->get();
        }
    @endphp

    <div class="row mt-4">
        
        @foreach ($clubes as $club)

            @php
                //Contar el total de filas de la tabla inscripciones donde id_club sea igual al id del club y que active sea igual a 1
                $totalInscritos = DB::table('inscripciones')->where('id_club', $club->id)->where('active', 1)->count();
            @endphp

            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body text-center p-4">
                        <img class="rounded-circle header-profile-user" src="{{ URL::asset('images/' . $club->foto) }}" alt="" height="45">
                        <h5 class="mb-1 mt-4">
                            {{$club->nombre}}
                        </h5>
                        <p class="text-muted mb-4">{{$club->descripcion}}</p>
                        <div class="row mt-4">
                            <div class="col-lg-6 border-end-dashed border-end">
                                <h5>{{$totalInscritos}}</h5>
                                <span class="text-muted">Participantes</span>
                            </div>
                            <div class="col-lg-6">
                                <h5>{{$club->constanciasExpedidas}}</h5>
                                <span class="text-muted">Constancias expedidas</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{URL::asset('/pages-team?club='.$club->id)}}" class="btn btn-light w-100">Ver participantes</a>
                            <hr>
                            <a href="{{URL::asset('/apps-eventos-list?club='.$club->id)}}" class="btn btn-light w-100">Ver eventos</a>
                            <hr>
                            <a href="{{URL::asset('/apps-clubes-editar?club='.$club->id)}}" class="btn btn-light w-100">Ver detalles</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        @endforeach
        
    </div>
    <!--end row-->

@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/swiper/swiper.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/sellers.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
