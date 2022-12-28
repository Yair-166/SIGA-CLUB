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
        $totalClubes = count($clubes);
    @endphp

    @if(Auth::user()->rol == 'administrador')
        <div class="row mt-4">
            <div class="card">
                <div class="card-body text-center p-1">
                    <h5 class="mb-1 mt-1">
                        Clubes administrados:
                    </h5>
                    <p class="text-muted mb-1">
                        {{$totalClubes}}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="row mt-4">
        
        @foreach ($clubes as $club)

            @php
                //Contar el total de filas de la tabla inscripciones donde id_club sea igual al id del club y que active sea igual a 1
                $totalInscritos = DB::table('inscripciones')->where('id_club', $club->id)->where('active', 1)->count();
            @endphp

            <div class="col-xl-3 col-lg-6">
                <a href="{{URL::asset('/dashboard?club='.$club->id)}}">
                    <div class="card">
                        <div class="card-body text-center p-4">
                            <img class="rounded-circle header-profile-user" src="{{ URL::asset('images/' . $club->foto) }}" alt="" height="45">
                            <h5 class="mb-1 mt-4">
                                {{$club->nombre}}
                            </h5>
                            <p class="text-muted mb-4">{{$club->descripcion}}</p>
                            <div class="mt-4">
                                <a href="{{URL::asset('/dashboard?club='.$club->id)}}" class="btn btn-light w-100">Ver estadísticas</a>
                            </div>
                        </div>
                    </div>
                </a>
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
