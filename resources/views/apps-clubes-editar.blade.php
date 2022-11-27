@extends('layouts.master')
@section('title')
    @lang('translation.overview')
@endsection
@section('content')
@php
    //Obtener el valor de la variable evento desde la url
    $getId = $_GET['club'];
    //Obtener el club de la base de datos donde el id sea igual al id del club
    $club = DB::table('clubes')->where('id', $getId)->first();
    //Obtener la tabla autoridades
    $autoridades = DB::table('autoridades')->where('idClub', $getId)->get();
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
                                            <h4 class="fw-bold">{{$club->nombre}}</h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                <div>Descripción : </div>
                                                <span class="fw-medium">{{$club->descripcion}}</span>
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
                                    Configuración
                                </a>
                            </li>                
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#project-activities" role="tab">
                                    Autoridades
                                </a>
                            </li>
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
        <div class="col-lg-10" style="float: none; margin: 0 auto;" >
            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-12 col-lg-10">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('editarClub') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="idAdministrador" value="{{Auth::user()->id}}">
                                        <input type="hidden" name="id" value="{{$club->id}}">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Foto</label>
                                            <input name="foto" class="form-control" type="file" id="formFile">
                                        </div>
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{$club->nombre}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="nomenclatura" class="form-label">Nomenclatura</label>
                                            <input type="text" class="form-control" id="nomenclatura" name="nomenclatura" value="{{$club->nomenclatura}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="localizacion" class="form-label">Localización</label>
                                            <input type="text" class="form-control" id="localizacion" name="localizacion" value="{{$club->localizacion}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="facebook" class="form-label">Link de facebook</label>
                                            <input type="text" class="form-control" id="facebook" name="localizacion" value="{{$club->facebook}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcion" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{$club->descripcion}}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="bienvenida" class="form-label">Mensaje de bienvenida</label>
                                            <textarea class="form-control" id="bienvenida" name="bienvenida" rows="3">{{$club->bienvenida}}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </form>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- ene col -->
                    </div>
                    <!-- end row -->
                    <!-- end team list -->
                </div>
                <!-- end tab pane -->
                <div class="tab-pane fade" id="project-activities" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Autoridades</h5>
                                    <form action="{{ route('agregarAutoridad') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="idClub" value="{{$club->id}}">
                                        <div class="mb-3">
                                            <label for="nombreautoridad" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombreautoridad" name="nombreautoridad">
                                        </div>
                                        <div class="mb-3">
                                            <label for="aPaterno" class="form-label">Apellido Paterno</label>
                                            <input type="text" class="form-control" id="aPaterno" name="aPaterno">
                                        </div>
                                        <div class="mb-3">
                                            <label for="aMaterno" class="form-label">Apellido Materno</label>
                                            <input type="text" class="form-control" id="aMaterno" name="aMaterno">
                                        </div>
                                        <div class="mb-3">
                                            <label for="cargo" class="form-label">Cargo</label>
                                            <input type="text" class="form-control" id="cargo" name="cargo">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                    </form>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- ene col -->
                        <div class="col-xl-3 col-lg-4">
                            
                            <div class="card">
                                <div class="card-header align-items-center d-flex border-bottom-dashed">
                                    <h4 class="card-title mb-0 flex-grow-1">Autoridades</h4>
                                </div>

                                <div class="card-body">
                                    <div data-simplebar style="height: 235px;" class="mx-n3 px-3">
                                        @foreach ($autoridades as $autoridad)
                                            <div class="vstack gap-3"
                                                style="border-bottom: 1px solid #e5e5e5; padding-bottom: 10px; margin-bottom: 10px;">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0">{{$autoridad->nombre}} {{$autoridad->aPaterno}} {{$autoridad->aMaterno}}</h5>
                                                        <p class="mb-0">{{$autoridad->cargo}}</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <a href="{{ route('eliminarAutoridad', $autoridad->id) }}" class="btn btn-sm btn-danger">Eliminar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <!-- end list -->
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
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
@endsection
