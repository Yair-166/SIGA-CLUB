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
    //Obtener el usuario de la base de datos por el id
    $user = DB::table('users')->where('id', $uid)->first();
    $texto = $numero = "";
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
                            @if(Auth::user()->rol == 'administrador')
                                @php
                                    $texto = "Clubes administrados";
                                    //obtener el numero de clubes administrados por el usuario
                                    $numero = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->count();
                                @endphp
                            @elseif(Auth::user()->rol == 'colaborador')
                                @php
                                    $texto = "Clubes inscritos";
                                    //obtener el numero de clubes en los que el usuario esta inscrito
                                    $numero = DB::table('inscripciones')->where('id_alumno', Auth::user()->id)->count();
                                @endphp
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
                            <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Sobre {{$user->name}}</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities" role="tab">
                                <i class="ri-list-unordered d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Activities</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Projects</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab">
                                <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span
                                    class="d-none d-md-inline-block">Documents</span>
                            </a>
                        </li>
                        --}}
                    </ul>
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-5">
                                            @php
                                                //Obtener el created_at del usuario y convertirlo a formato de fecha
                                                $fecha = date("d-m-Y", strtotime($user->created_at));
                                            @endphp
                                            Fecha de registro: {{$fecha}}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Descripción</h5>
                                        <p>
                                            {{$user->descripcion}}
                                        </p>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->

                                
                            {{-- <div class="card">
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
                                                            <th scope="col">Generar constancia</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-sm">
                                                                        <div
                                                                            class="avatar-title bg-light text-primary rounded fs-20">
                                                                            <i class="ri-file-zip-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ms-3 flex-grow-1">
                                                                        <h6 class="fs-15 mb-0"><a
                                                                                href="javascript:void(0)"
                                                                                class="link-secondary">Artboard-documents.zip</a>
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>Zip File</td>
                                                            <td>4.57 MB</td>
                                                            <td>12 Dec 2021</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-light btn-icon" id="dropdownMenuLink15"
                                                                        data-bs-toggle="dropdown" aria-expanded="true">
                                                                        <i class="ri-equalizer-fill"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                                        aria-labelledby="dropdownMenuLink15">
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-eye-fill me-2 align-middle text-muted"></i>View</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a>
                                                                        </li>
                                                                        <li class="dropdown-divider"></li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-sm">
                                                                        <div
                                                                            class="avatar-title bg-light text-primary rounded fs-20">
                                                                            <i class="ri-file-pdf-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ms-3 flex-grow-1">
                                                                        <h6 class="fs-15 mb-0"><a
                                                                                href="javascript:void(0);"
                                                                                class="link-secondary">Bank
                                                                                Management System</a></h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>PDF File</td>
                                                            <td>8.89 MB</td>
                                                            <td>24 Nov 2021</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-light btn-icon" id="dropdownMenuLink3"
                                                                        data-bs-toggle="dropdown" aria-expanded="true">
                                                                        <i class="ri-equalizer-fill"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                                        aria-labelledby="dropdownMenuLink3">
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-eye-fill me-2 align-middle text-muted"></i>View</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a>
                                                                        </li>
                                                                        <li class="dropdown-divider"></li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-sm">
                                                                        <div
                                                                            class="avatar-title bg-light text-primary rounded fs-20">
                                                                            <i class="ri-video-line"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ms-3 flex-grow-1">
                                                                        <h6 class="fs-15 mb-0"><a
                                                                                href="javascript:void(0);"
                                                                                class="link-secondary">Tour-video.mp4</a>
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>MP4 File</td>
                                                            <td>14.62 MB</td>
                                                            <td>19 Nov 2021</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-light btn-icon" id="dropdownMenuLink4"
                                                                        data-bs-toggle="dropdown" aria-expanded="true">
                                                                        <i class="ri-equalizer-fill"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                                        aria-labelledby="dropdownMenuLink4">
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-eye-fill me-2 align-middle text-muted"></i>View</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a>
                                                                        </li>
                                                                        <li class="dropdown-divider"></li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-sm">
                                                                        <div
                                                                            class="avatar-title bg-light text-primary rounded fs-20">
                                                                            <i class="ri-file-excel-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ms-3 flex-grow-1">
                                                                        <h6 class="fs-15 mb-0"><a
                                                                                href="javascript:void(0);"
                                                                                class="link-secondary">Account-statement.xsl</a>
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>XSL File</td>
                                                            <td>2.38 KB</td>
                                                            <td>14 Nov 2021</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-light btn-icon" id="dropdownMenuLink5"
                                                                        data-bs-toggle="dropdown" aria-expanded="true">
                                                                        <i class="ri-equalizer-fill"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                                        aria-labelledby="dropdownMenuLink5">
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-eye-fill me-2 align-middle text-muted"></i>View</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a>
                                                                        </li>
                                                                        <li class="dropdown-divider"></li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-sm">
                                                                        <div
                                                                            class="avatar-title bg-light text-primary rounded fs-20">
                                                                            <i class="ri-folder-line"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ms-3 flex-grow-1">
                                                                        <h6 class="fs-15 mb-0"><a
                                                                                href="javascript:void(0);"
                                                                                class="link-secondary">Project
                                                                                Screenshots Collection</a>
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>Floder File</td>
                                                            <td>87.24 MB</td>
                                                            <td>08 Nov 2021</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-light btn-icon" id="dropdownMenuLink6"
                                                                        data-bs-toggle="dropdown" aria-expanded="true">
                                                                        <i class="ri-equalizer-fill"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                                        aria-labelledby="dropdownMenuLink6">
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-eye-fill me-2 align-middle"></i>View</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-download-2-fill me-2 align-middle"></i>Download</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-sm">
                                                                        <div
                                                                            class="avatar-title bg-light text-primary rounded fs-20">
                                                                            <i class="ri-image-2-fill"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ms-3 flex-grow-1">
                                                                        <h6 class="fs-15 mb-0"><a
                                                                                href="javascript:void(0);"
                                                                                class="link-secondary">Velzon-logo.png</a>
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>PNG File</td>
                                                            <td>879 KB</td>
                                                            <td>02 Nov 2021</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-light btn-icon" id="dropdownMenuLink7"
                                                                        data-bs-toggle="dropdown" aria-expanded="true">
                                                                        <i class="ri-equalizer-fill"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                                        aria-labelledby="dropdownMenuLink7">
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-eye-fill me-2 align-middle"></i>View</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-download-2-fill me-2 align-middle"></i>Download</a>
                                                                        </li>
                                                                        <li><a class="dropdown-item"
                                                                                href="javascript:void(0);"><i
                                                                                    class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-center mt-3">
                                                <a href="javascript:void(0);" class="text-primary "><i
                                                        class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i>
                                                    Load more </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                                

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
@endsection
