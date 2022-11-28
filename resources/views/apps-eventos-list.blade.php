@extends('layouts.master')
@section('title')
    @lang('translation.project-list')
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
        //Obtener la variable club de la url
        $getClub = $_GET['club'];
        //Si $getClub es igual a "all" entonces mostrar todos los eventos de los clubes del usuario
        if($getClub == "all"){
            $clubes = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->get();
            //Guardar en un array los ids de los clubes
            $clubesIds = array();
            foreach ($clubes as $club) {
                array_push($clubesIds, $club->id);
            }
            //Obtener todos los eventos de los clubes del usuario
            $eventos = DB::table('eventos')->whereIn('id_club', $clubesIds)->get();
        }else{
            //Si no, mostrar los eventos donde el id_club sea igual a $getClub
            $eventos = DB::table('eventos')->where('id_club', $getClub)->get();
        }
    @endphp

    {{-- Acomodar por semana, mes y asi
    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div>
                <a href="{{URL::asset('/apps-projects-create')}}" class="btn btn-soft-secondary"><i
                        class="ri-add-line align-bottom me-1"></i> Add New</a>
            </div>
        </div>
        <div class="col-sm">
            <div class="d-flex justify-content-sm-end gap-2">

                <select class="form-control w-md" data-choices data-choices-search-false>
                    <option value="All" selected>All</option>
                    <option value="Today">Today</option>
                    <option value="Yesterday" selected>Yesterday</option>
                    <option value="Last 7 Days">Last 7 Days</option>
                    <option value="Last 30 Days">Last 30 Days</option>
                    <option value="This Month">This Month</option>
                    <option value="Last Year">Last Year</option>
                </select>
            </div>
        </div>
    </div><!-- end row --> --}}

    <div class="row">
        @foreach($eventos as $evento)
            @php
                //Obtener el club del evento
                $club = DB::table('clubes')->where('id', $evento->id_club)->first();
            @endphp
            <div class="col-xxl-3 col-sm-6 project-card">
                <div class="card">
                    <div class="card-body">
                        <div class="p-3 mt-n3 mx-n3 bg-soft-primary rounded-top">
                            <div class="text-center pb-3">
                                <img class="rounded-circle" src="{{ URL::asset('images/'.$club->foto) }}" alt="" height="55">
                            </div>
                        </div>

                        <a href="{{URL::asset('/apps-eventos-overview?evento='.$evento->id)}}" class="text-dark">
                            <div class="py-3">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fs-15">{{$evento->nombre}}</h5>
                                    <p class="text-muted text-truncate-two-lines mb-3">
                                        {{$evento->descripcion}}
                                        </br>
                                        Interesados en asistir: 
                                        @php
                                            //Contar cuantos usuarios estan interesados en asistir al evento
                                            $interesados = DB::table('asistencias_previstas')->where('id_evento', $evento->id)->count();
                                        @endphp
                                        {{$interesados}}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- end card body -->
                    <div class="card-footer bg-transparent border-top-dashed py-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="text-muted">
                                    <i class="ri-calendar-event-fill me-1 align-bottom"></i> 
                                    @php
                                        //Verificar si la fecha de inicio es igual a la fecha de fin
                                        if($evento->fechaInicio == $evento->fechaFin){
                                            //Si es igual, mostrar solo la fecha de inicio
                                            echo date("d/m/Y", strtotime($evento->fechaInicio));
                                        }else{
                                            //Si no, mostrar la fecha de inicio y la fecha de fin
                                            echo date("d/m/Y", strtotime($evento->fechaInicio))." al ".date("d/m/Y", strtotime($evento->fechaFin));
                                        }
                                    @endphp
                                </div>
                            </div>

                        </div>

                    </div>
                    <!-- end card footer -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        @endforeach
    </div>
    <!-- end row -->

    {{-- Para cambiar de pagina se usa el siguiente codigo
    <div class="row g-0 text-center text-sm-start align-items-center mb-4">
        <div class="col-sm-6">
            <div>
                <p class="mb-sm-0 text-muted">Showing <span class="fw-semibold">1</span> to <span
                        class="fw-semibold">10</span> of <span class="fw-semibold text-decoration-underline">12</span>
                    entries</p>
            </div>
        </div>
        <!-- end col -->
        <div class="col-sm-6">
            <ul class="pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                <li class="page-item disabled">
                    <a href="#" class="page-link">Previous</a>
                </li>
                <li class="page-item active">
                    <a href="#" class="page-link">1</a>
                </li>
                <li class="page-item ">
                    <a href="#" class="page-link">2</a>
                </li>
                <li class="page-item">
                    <a href="#" class="page-link">3</a>
                </li>
                <li class="page-item">
                    <a href="#" class="page-link">4</a>
                </li>
                <li class="page-item">
                    <a href="#" class="page-link">5</a>
                </li>
                <li class="page-item">
                    <a href="#" class="page-link">Next</a>
                </li>
            </ul>
        </div><!-- end col -->
    </div><!-- end row --> --}}

@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/project-list.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
