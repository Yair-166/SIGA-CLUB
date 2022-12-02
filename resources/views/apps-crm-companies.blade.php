@extends('layouts.master')
@section('title')
    @lang('translation.companies')
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('css')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            SIGA
        @endslot
        @slot('title')
            Clubes
        @endslot
    @endcomponent
    @php
        //Obtener los clubes de la base de datos
        $clubs = DB::table('clubes')->get();
        //Obtener los usuarios de la base de datos 
        $users = DB::table('users')->get();
        //Obtener los datos de la tabla inscripciones
        $inscripciones = DB::table('inscripciones')->get();
        $eliminar = 0;
        $inscrito = 0;
    @endphp
    <div class="row">
    @if (Auth::user()->rol == 'administrador')
       <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i class="ri-add-fill me-1 align-bottom"></i> Agregar club</button>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
    @endif  

        <div class="col-xxl-9">
            <div class="card" id="companyList">
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                <strong>¡Error!</strong> {{ session('error') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="sort" data-sort="name" scope="col">Nombre del club</th>
                                        <th class="sort" data-sort="owner" scope="col">Administrador</th>
                                        <th class="sort" data-sort="location" scope="col">Localización</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                
                                @foreach ($clubs as $club)
                                    @if($club->active == '1')
                                        <tbody class="list form-check-all">
                                        <tr>
                                            <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>
                                            </td>
                                            <td>
                                                @if($club->idAdministrador == Auth::user()->id || Auth::user()->rol == 'super')
                                                    <a href="{{URL::asset('/apps-clubes-editar?club='.$club->id)}}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ URL::asset('images/' . $club->foto) }}" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                            </div>
                                                            <div class="flex-grow-1 ms-2 name">
                                                                {{ $club->nombre }}
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ URL::asset('images/' . $club->foto) }}" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                        </div>
                                                        <div class="flex-grow-1 ms-2 name">
                                                            {{ $club->nombre }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="owner">
                                                @foreach ($users as $user)
                                                    @if ($user->id == $club->idAdministrador)
                                                        @php
                                                            $admin_name = $user->name . " " . $user->apaterno . " " . $user->amaterno;
                                                        @endphp
                                                        {{ $admin_name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="location">{{$club->localizacion}}</td>
                                            <td>
                                                @php
                                                    //juntar toda la información del club en un solo string
                                                    $clubData = $club->foto . ';' . $club->nombre . ';' . $admin_name . ';' . $club->descripcion . ';' . $club->localizacion . ';' . $club->nomenclatura . ';' . $club->facebook;
                                                    //Convertir a json el string
                                                    $clubData = json_encode($clubData);
                                                @endphp
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                        data-bs-placement="top" title="Ver más">
                                                        <a href="javascript:mostrar({{$clubData}});" class="view-item-btn">
                                                            <i class="ri-eye-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                    @if ($club->idAdministrador == Auth::user()->id || Auth::user()->rol == 'super')
                                                        {{-- <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top" title="Editar">
                                                            <a class="edit-item-btn" href="#editModal"  data-bs-toggle="modal">
                                                                <button onClick="eliminarid({{$clubData}})" style="border: none; background: none;">
                                                                    <i class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                                </button>
                                                        </li>
                                                        --}}
                                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top" title="Eliminar">
                                                            <a id="adelete" class="delete-item-btn" href="#deleteRecordModal" data-bs-toggle="modal" data-bs-id="{{$club->id}}">
                                                                <button onClick="eliminarid({{$club->id}})" style="border: none; background: none;">
                                                                    <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                                </button>
                                                            </a>
                                                        </li>
                                                    @endif

                                                    @if (Auth::user()->rol == 'colaborador')
                                                        @foreach ($inscripciones as $inscripcion)
                                                            @if ($inscripcion->id_club == $club->id && $inscripcion->id_alumno == Auth::user()->id && $inscripcion->active == '1')
                                                                @php
                                                                    $inscrito = 1;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        @if ($inscrito != 1)
                                                            <form action="{{ route('inscribirse') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id_club" value="{{$club->id}}">
                                                                <input type="hidden" name="id_alumno" value="{{Auth::user()->id}}">
                                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                                    data-bs-placement="top" title="Inscribirse">
                                                                    <a href="javascript:void(0);" class="inscribirse-item-btn">
                                                                        <button type="submit" style="border: none; background: none;">
                                                                            <i class="ri-user-add-fill align-bottom text-muted"></i>
                                                                        </button>
                                                                    </a>
                                                                </li>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('desinscribirse') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id_club" value="{{$club->id}}">
                                                                <input type="hidden" name="id_alumno" value="{{Auth::user()->id}}">
                                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                                    data-bs-placement="top" title="desinscribirse">
                                                                    <a href="javascript:void(0);" class="inscribirse-item-btn">
                                                                        <button type="submit" style="border: none; background: none;">
                                                                            <i class="ri-user-unfollow-fill align-bottom text-muted"></i>
                                                                        </button>
                                                                    </a>
                                                                </li>
                                                            </form>
                                                        @endif
                                                        @php
                                                            $inscrito = 0;
                                                        @endphp
                                                    @endif
                                                </ul>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#25a0e2,secondary:#00bd9d" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">No hay resultados</h5>
                                    <p class="text-muted mb-0">No se han encontrado resultados de su busqueda</p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="d-flex justify-content-end mt-3">
                            <div class="pagination-wrap hstack gap-2">
                                <a class="page-item pagination-prev disabled" href="#">
                                    Anterior
                                </a>
                                <ul class="pagination listjs-pagination mb-0"></ul>
                                <a class="page-item pagination-next" href="#">
                                    Siguiente
                                </a>
                            </div>
                        </div> --}}
                    </div>

                    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-soft-primary p-3">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                </div>

                                {{-- <form action="{{ route('index') }}" method="POST" enctype="multipart/form-data"> --}}
                                <form action="{{ route('updateClub') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" id="id-field" />
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <div class="text-center">
                                                    <div class="position-relative d-inline-block">
                                                        <div class="position-absolute bottom-0 end-0">
                                                            <label for="company-logo-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                                <div class="avatar-xs cursor-pointer">
                                                                    <div class="avatar-title bg-light border rounded-circle text-muted">
                                                                        <i class="ri-image-fill"></i>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            <input class="form-control d-none" value="" id="company-logo-input" type="file"
                                                                accept="image/png, image/gif, image/jpeg" name="foto">
                                                        </div>
                                                        <div class="avatar-lg p-1">
                                                            <div class="avatar-title bg-light rounded-circle">
                                                                <img src="{{ URL::asset('assets/images/users/multi-user.jpg') }}"
                                                        alt="" id="companylogo-img" class="avatar-md rounded-circle object-cover">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h5 class="fs-13 mt-3">Logo</h5>
                                                </div>
                                                <div>
                                                    <label for="companyname-field"
                                                        class="form-label">Nombre</label>
                                                    <input type="text" id="companyname-field"
                                                        class="form-control"
                                                        name="nombre"
                                                        placeholder="Nombre de tu club" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="owner-field" class="form-label">Nombre del administrador</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ Auth::user()->name }} {{ Auth::user()->apaterno }} {{ Auth::user()->amaterno }}" 
                                                        disabled/>
                                                        <input type="hidden" name="idAdministrador" value="{{ Auth::user()->id }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="nomenclatura-field" class="form-label">Prefijo para constancias</label>
                                                    <input type="text" id="nomenclatura-field" class="form-control" name="nomenclatura" placeholder="Prefijo para constancias" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="location-field" class="form-label">Localización</label>
                                                    <input type="text" id="location-field" class="form-control" name="localizacion"  placeholder="Localización" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="facebook-field" class="form-label">Link de facebook</label>
                                                    <input type="text" id="facebook-field" class="form-control" name="facebook"  placeholder="link de facebook" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="since-field" class="form-label">Descripción</label>
                                                    <textarea id="description-field" class="form-control" rows="3" name="descripcion" placeholder="Descripción del club"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="since-field" class="form-label">Mensaje de bienvenida</label>
                                                    <textarea id="bienvenida-field" class="form-control" rows="3" name="bienvenida" placeholder="Mensaje de bienvenida al club"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary" id="add-btn">Agregar club</button>
                                            {{-- <button type="button" class="btn btn-primary" id="update-btn">Actualizar</button> --}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!--end add modal-->

                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-soft-primary p-3">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                </div>

                                {{-- <form action="{{ route('index') }}" method="POST" enctype="multipart/form-data"> --}}
                                <form action="{{ route('editarClub') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" id="id-field" />
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <div class="text-center">
                                                    <div class="position-relative d-inline-block">
                                                        <div class="position-absolute bottom-0 end-0">
                                                            <label for="company-logo-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                                <div class="avatar-xs cursor-pointer">
                                                                    <div class="avatar-title bg-light border rounded-circle text-muted">
                                                                        <i class="ri-image-fill"></i>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            <input class="form-control d-none" value="" id="company-logo-input" type="file"
                                                                accept="image/png, image/gif, image/jpeg" name="foto">
                                                        </div>
                                                        <div class="avatar-lg p-1">
                                                            <div class="avatar-title bg-light rounded-circle">
                                                                <img src="{{ URL::asset('assets/images/users/multi-user.jpg') }}"
                                                        alt="" id="editar-foto" class="avatar-md rounded-circle object-cover">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h5 class="fs-13 mt-3">Logo</h5>
                                                </div>
                                                <div>
                                                    <label for="editar-name" id="editar-name1"
                                                        class="form-label">Nombre</label>
                                                    <input type="text" id="editar-name"
                                                        class="form-control"
                                                        name="nombre"
                                                        placeholder="Nombre de tu club" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <input type="hidden" name="id" value="1">
                                                    <label for="owner-field" class="form-label">Nombre del administrador</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ Auth::user()->name }} {{ Auth::user()->apaterno }} {{ Auth::user()->amaterno }}" 
                                                        disabled/>
                                                        <input type="hidden" name="idAdministrador" value="{{ Auth::user()->id }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="editar-nomenclatura" class="form-label">Nomenclatura</label>
                                                    <input type="text" id="editar-nomenclatura" class="form-control" name="nomenclatura" placeholder="Nomenclatura" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="editar-location" class="form-label">Localización</label>
                                                    <input type="text" id="editar-location" class="form-control" name="localizacion"  placeholder="Localización" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="editar-description" class="form-label">Descripción</label>
                                                    <textarea id="editar-description" class="form-control" rows="3" name="descripcion" ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary" id="add-btn">Guardar cambios</button>
                                            {{-- <button type="button" class="btn btn-primary" id="update-btn">Actualizar</button> --}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!--end edit modal-->

                    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel"
                        aria-hidden="true"
                        data-bs-id="">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
                                </div>
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#00bd9d,secondary:#25a0e2" style="width:90px;height:90px"></lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4 class="fs-semibold">¿Quiéres eliminar el club?</h4>
                                        <p class="text-muted fs-14 mb-4 pt-1">Eliminar el club implica borrar todo su contenido.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-primary fw-medium text-decoration-none" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                                            <form action="{{ route('deleteClub') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" id="idelimnar" value="">
                                                <button type="submit" class="btn btn-primary" id="delete-record">Si</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!--end delete modal -->
                   

                </div>
            </div><!--end card-->
            @if(Auth::user()->rol == 'super')
                <h5> Clubes desactivados </h5>
            
                <div class="card" id="companyList">
                    <div class="card-body">
                        <div>
                            <div class="table-responsive table-card mb-3">
                                <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="sort" data-sort="name" scope="col">Nombre del club</th>
                                            <th class="sort" data-sort="owner" scope="col">Administrador</th>
                                            <th class="sort" data-sort="location" scope="col">Localización</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    
                                    @foreach ($clubs as $club)
                                        @if($club->active == '0')
                                            <tbody class="list form-check-all">
                                            <tr>
                                                <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>
                                                </td>
                                                <td>
                                                    <a href="{{URL::asset('/apps-clubes-editar?club='.$club->id)}}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ URL::asset('images/' . $club->foto) }}" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                            </div>
                                                            <div class="flex-grow-1 ms-2 name">
                                                                {{ $club->nombre }}
                                                              </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="owner">
                                                    @foreach ($users as $user)
                                                        @if ($user->id == $club->idAdministrador)
                                                            @php
                                                                $admin_name = $user->name . " " . $user->apaterno . " " . $user->amaterno;
                                                            @endphp
                                                            {{ $admin_name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td class="location">{{$club->localizacion}}</td>
                                                <td>
                                                    @php
                                                        //juntar toda la información del club en un solo string
                                                        $clubData = $club->foto . ';' . $club->nombre . ';' . $admin_name . ';' . $club->descripcion . ';' . $club->localizacion . ';' . $club->nomenclatura . ';' . $club->facebook;
                                                        //Convertir a json el string
                                                        $clubData = json_encode($clubData);
                                                    @endphp
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top" title="Ver más">
                                                            <a href="javascript:mostrar({{$clubData}});" class="view-item-btn">
                                                                <i class="ri-eye-fill align-bottom text-muted"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top" title="Activar">
                                                            <a href="{{Route ('activarClub', $club->id)}}" class="view-item-btn">
                                                                <i class="ri-check-fill align-bottom text-muted"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="noresult" style="display: none">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#25a0e2,secondary:#00bd9d" style="width:75px;height:75px">
                                        </lord-icon>
                                        <h5 class="mt-2">No hay resultados</h5>
                                        <p class="text-muted mb-0">No se han encontrado resultados de su busqueda</p>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="d-flex justify-content-end mt-3">
                                <div class="pagination-wrap hstack gap-2">
                                    <a class="page-item pagination-prev disabled" href="#">
                                        Anterior
                                    </a>
                                    <ul class="pagination listjs-pagination mb-0"></ul>
                                    <a class="page-item pagination-next" href="#">
                                        Siguiente
                                    </a>
                                </div>
                            </div> --}}
                        </div>

                        <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0">
                                    <div class="modal-header bg-soft-primary p-3">
                                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                    </div>

                                    {{-- <form action="{{ route('index') }}" method="POST" enctype="multipart/form-data"> --}}
                                    <form action="{{ route('updateClub') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" id="id-field" />
                                            <div class="row g-3">
                                                <div class="col-lg-12">
                                                    <div class="text-center">
                                                        <div class="position-relative d-inline-block">
                                                            <div class="position-absolute bottom-0 end-0">
                                                                <label for="company-logo-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                                    <div class="avatar-xs cursor-pointer">
                                                                        <div class="avatar-title bg-light border rounded-circle text-muted">
                                                                            <i class="ri-image-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                                <input class="form-control d-none" value="" id="company-logo-input" type="file"
                                                                    accept="image/png, image/gif, image/jpeg" name="foto">
                                                            </div>
                                                            <div class="avatar-lg p-1">
                                                                <div class="avatar-title bg-light rounded-circle">
                                                                    <img src="{{ URL::asset('assets/images/users/multi-user.jpg') }}"
                                                            alt="" id="companylogo-img" class="avatar-md rounded-circle object-cover">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h5 class="fs-13 mt-3">Logo</h5>
                                                    </div>
                                                    <div>
                                                        <label for="companyname-field"
                                                            class="form-label">Nombre</label>
                                                        <input type="text" id="companyname-field"
                                                            class="form-control"
                                                            name="nombre"
                                                            placeholder="Nombre de tu club" required />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <label for="owner-field" class="form-label">Nombre del administrador</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ Auth::user()->name }} {{ Auth::user()->apaterno }} {{ Auth::user()->amaterno }}" 
                                                            disabled/>
                                                            <input type="hidden" name="idAdministrador" value="{{ Auth::user()->id }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <label for="nomenclatura-field" class="form-label">Prefijo para constancias</label>
                                                        <input type="text" id="nomenclatura-field" class="form-control" name="nomenclatura" placeholder="Prefijo para constancias" required />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <label for="location-field" class="form-label">Localización</label>
                                                        <input type="text" id="location-field" class="form-control" name="localizacion"  placeholder="Localización" required />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <label for="facebook-field" class="form-label">Link de facebook</label>
                                                        <input type="text" id="facebook-field" class="form-control" name="facebook"  placeholder="link de facebook" required />
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div>
                                                        <label for="since-field" class="form-label">Descripción</label>
                                                        <textarea id="description-field" class="form-control" rows="3" name="descripcion" placeholder="Descripción del club"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div>
                                                        <label for="since-field" class="form-label">Mensaje de bienvenida</label>
                                                        <textarea id="bienvenida-field" class="form-control" rows="3" name="bienvenida" placeholder="Mensaje de bienvenida al club"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary" id="add-btn">Agregar club</button>
                                                {{-- <button type="button" class="btn btn-primary" id="update-btn">Actualizar</button> --}}
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!--end add modal-->

                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0">
                                    <div class="modal-header bg-soft-primary p-3">
                                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                    </div>

                                    {{-- <form action="{{ route('index') }}" method="POST" enctype="multipart/form-data"> --}}
                                    <form action="{{ route('editarClub') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" id="id-field" />
                                            <div class="row g-3">
                                                <div class="col-lg-12">
                                                    <div class="text-center">
                                                        <div class="position-relative d-inline-block">
                                                            <div class="position-absolute bottom-0 end-0">
                                                                <label for="company-logo-input" class="mb-0"  data-bs-toggle="tooltip" data-bs-placement="right" title="Select Image">
                                                                    <div class="avatar-xs cursor-pointer">
                                                                        <div class="avatar-title bg-light border rounded-circle text-muted">
                                                                            <i class="ri-image-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                                <input class="form-control d-none" value="" id="company-logo-input" type="file"
                                                                    accept="image/png, image/gif, image/jpeg" name="foto">
                                                            </div>
                                                            <div class="avatar-lg p-1">
                                                                <div class="avatar-title bg-light rounded-circle">
                                                                    <img src="{{ URL::asset('assets/images/users/multi-user.jpg') }}"
                                                            alt="" id="editar-foto" class="avatar-md rounded-circle object-cover">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h5 class="fs-13 mt-3">Logo</h5>
                                                    </div>
                                                    <div>
                                                        <label for="editar-name" id="editar-name1"
                                                            class="form-label">Nombre</label>
                                                        <input type="text" id="editar-name"
                                                            class="form-control"
                                                            name="nombre"
                                                            placeholder="Nombre de tu club" required />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <input type="hidden" name="id" value="1">
                                                        <label for="owner-field" class="form-label">Nombre del administrador</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ Auth::user()->name }} {{ Auth::user()->apaterno }} {{ Auth::user()->amaterno }}" 
                                                            disabled/>
                                                            <input type="hidden" name="idAdministrador" value="{{ Auth::user()->id }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <label for="editar-nomenclatura" class="form-label">Nomenclatura</label>
                                                        <input type="text" id="editar-nomenclatura" class="form-control" name="nomenclatura" placeholder="Nomenclatura" required />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div>
                                                        <label for="editar-location" class="form-label">Localización</label>
                                                        <input type="text" id="editar-location" class="form-control" name="localizacion"  placeholder="Localización" required />
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div>
                                                        <label for="editar-description" class="form-label">Descripción</label>
                                                        <textarea id="editar-description" class="form-control" rows="3" name="descripcion" ></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary" id="add-btn">Guardar cambios</button>
                                                {{-- <button type="button" class="btn btn-primary" id="update-btn">Actualizar</button> --}}
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!--end edit modal-->

                        <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel"
                            aria-hidden="true"
                            data-bs-id="">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
                                    </div>
                                    <div class="modal-body p-5 text-center">
                                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#00bd9d,secondary:#25a0e2" style="width:90px;height:90px"></lord-icon>
                                        <div class="mt-4 text-center">
                                            <h4 class="fs-semibold">¿Quiéres eliminar el club?</h4>
                                            <p class="text-muted fs-14 mb-4 pt-1">Eliminar el club implica borrar todo su contenido.</p>
                                            <div class="hstack gap-2 justify-content-center remove">
                                                <button class="btn btn-link link-primary fw-medium text-decoration-none" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                                                <form action="{{ route('deleteClub') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" id="idelimnar" value="">
                                                    <button type="submit" class="btn btn-primary" id="delete-record">Si</button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!--end delete modal -->
                    

                    </div>
                </div><!--end card-->
                
            @endif
        </div><!--end col-->

        

        <div class="col-xxl-3">
            <div class="card" id="company-view-detail">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block">
                        <div class="avatar-md">
                            <div class="avatar-title bg-light rounded-circle">
                                <img id="info-foto" src="{{ URL::asset('assets/images/users/multi-user.jpg') }}" alt="" class="avatar-sm rounded-circle object-cover">
                            </div>
                        </div>
                    </div>
                    <h5 id="info-name" class="mt-3 mb-1">Club</h5>
                    <p id="info-admin" class="text-muted">Administrador</p>
                </div>
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Información</h6>
                    <p id="info-description" class="text-muted mb-4">
                        Descripción del club
                    </p>
                    <div class="table-responsive table-card">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-medium" scope="row">Localización</td>
                                    <td id="info-location">ESCOM, México</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" scope="row" colspan='2'> 
                                        <a href="#" id="info-facebook">
                                            <img src="{{ URL::asset('assets/images/fb.png') }}" alt="" class="avatar-sm rounded-circle object-cover">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!--end card-->

            
        </div><!--end col-->

        <div class="mb-4">
            @if($message = Session::get('des'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Listo</strong> {{$message}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            @elseif($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Club inscrito!</strong> 
                    </br>
                    {{$message}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            @endif  
        </div>
    </div><!--end row-->

@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/list.js/list.js.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/list.pagination.js/list.pagination.js.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/crm-companies.init.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script>
        function eliminarid(id){
            let clubid = id;
            console.log(clubid);
            document.getElementById("idelimnar").value = clubid;
        }
        function mostrar(data){
            //Separar los datos
            let datos = data.split(";");
            //Asignar los datos a los campos
            document.getElementById("info-foto").src = "/images/" + datos[0];
            document.getElementById("info-name").innerHTML = datos[1];
            document.getElementById("info-admin").innerHTML = datos[2];
            document.getElementById("info-description").innerHTML = datos[3];
            document.getElementById("info-location").innerHTML = datos[4];
            document.getElementById("info-facebook").href = datos[6];
            
        }
        function editarModal(data){
            //Separar los datos
            let dats = data.split(";");
            console.log(dats);

            //Asignar los datos a los campos al placeholder 
            document.getElementById("editar-foto").src = "/images/" + dats[0];
            document.getElementById("editar-name").placeholder = dats[1];
            document.getElementById("editar-description").placeholder = dats[3];
            document.getElementById("editar-location").placeholder = datos[4];
            document.getElementById("editar-nomenclatura").placeholder = datos[5];
        }
    </script>
@endsection
