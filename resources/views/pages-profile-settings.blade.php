@extends('layouts.master')
@section('title')
    @lang('translation.settings')
@endsection
@section('content')
@php
    $user = Auth::user()
@endphp
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ URL::asset('assets/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="@if (Auth::user()->avatar != '') {{ URL::asset('images/' . Auth::user()->avatar) }}@else{{ URL::asset('assets/images/users/avatar-1.jpg') }} @endif"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{$user->name . " " . $user->apaterno . " " . $user->amaterno}}</h5>
                        <p class="text-muted mb-0">{{$user->rol}}</p>
                        <div class="mb-4">
                            @if($message = Session::get('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> {{$message}}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close">
                                    </button>
                                </div>
                            @elseif($message = Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Exito!</strong> {{$message}}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close">
                                    </button>
                                </div>
                            @endif  
                        </div>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>
                                Informaci??n personal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                Cambiar contrase??a
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#privacy" role="tab">
                                <i class="far fa-envelope"></i>
                                Politicas de privacidad
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{route('edituser')}}" method="POST">
                             @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <input type="hidden" name="id" value="{{$user->id}}">
                                            <label for="firstnameInput" class="form-label">
                                                Nombre(s)
                                            </label>
                                            <input name="name" type="text" class="form-control" id="firstnameInput"
                                                placeholder="{{$user->name}}" value={{$user->name}}>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="lastnameInput" class="form-label">
                                                Apellido paterno
                                            </label>
                                            <input name="apaterno" type="text" class="form-control" id="lastnameInput"
                                                placeholder="{{$user->apaterno}}" value={{$user->apaterno}}>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="amaternoa" class="form-label">
                                                Apellido materno
                                            </label>
                                            <input name="amaterno" type="text" class="form-control" id="amaternoa"
                                                placeholder="{{$user->amaterno}}" value={{$user->amaterno}}>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                Correo electr??nico
                                            </label>
                                            <input name="email" type="email" class="form-control" id="email"
                                                placeholder="{{$user->email}}" value="{{$user->email}}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="telefono" class="form-label">
                                                T??lefono celular
                                            </label>
                                            <input name="telefono" type="text" class="form-control" id="telefono"
                                                placeholder="{{$user->telefono}}" value="{{$user->telefono}}">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <input name="created_at" type="text" class="form-control" data-provider="flatpickr"
                                                id="JoiningdatInput" data-date-format="d M, Y"
                                                data-deafult-date="{{$user->created_at}}" placeholder="Select date" disabled hidden/>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3 pb-2">
                                            <label for="exampleFormControlTextarea"
                                                class="form-label">Descripci??n</label>
                                            <textarea name="descripcion" class="form-control" id="exampleFormControlTextarea" placeholder="Cuentanos sobre ti"
                                                rows="3" value="{{$user->descripcion}}">{{$user->descripcion}}</textarea>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="form-check form-switch form-switch-md" dir="ltr">
                                            @php
                                                $boleta = $user->boleta;
                                                if($boleta == null){
                                                    echo '<input type="checkbox" class="form-check-input" id="customSwitchsizemd" onclick="habilitar()">';
                                                }
                                                else{
                                                    echo '<input type="checkbox" class="form-check-input" id="flexSwitchCheckChecked" onclick="habilitar()" checked>';
                                                }
                                            @endphp
                                            <label class="form-check-label" for="customSwitchsizemd">Soy Polit??cnico</label>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    @php
                                        if($boleta == null || $boleta == ""){
                                            echo '<div class="col-lg-6" id="boletatinha" style="display: none;">';
                                        } 
                                        else{
                                            echo '<div class="col-lg-6" id="boletatinha" style="display: block;">';
                                        }
                                    @endphp
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea"
                                                class="form-label">Boleta o n??mero de empleado</label>
                                            <input name="boleta" type="text" class="form-control" id="boleta" placeholder="{{$user->boleta}}" value="{{$user->boleta}}" />
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form action="{{route('editarPassword')}}" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <input type="hidden" name="id" value="{{$user->id}}">
                                            <label for="oldpasswordInput" class="form-label">
                                                Contrase??a actual
                                            </label>
                                            <input name="current_password" type="password" class="form-control" id="oldpasswordInput"
                                                placeholder="Ingresa la contrase??a actual">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">
                                                Nueva contrase??a*
                                            </label>
                                            <input name="password" type="password" class="form-control" id="newpasswordInput"
                                                placeholder="Ingresa la nueva contrase??a">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">
                                                Confirmar contrase??a*
                                            </label>
                                            <input type="password" class="form-control" id="confirmpasswordInput"
                                                placeholder="Confirma la contrase??a">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <a href="javascript:void(0);"
                                                class="link-primary text-decoration-underline">
                                                ??Olvidaste tu contrase??a?
                                            </a>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">
                                                Guardar
                                            </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="privacy" role="tabpanel">
                            <div class="mb-3">
                                <h5 class="card-title text-decoration-underline mb-3">
                                    Politicas de privacidad:
                                </h5>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex">
                                        <div class="flex-grow-1">
                                            <label for="directMessage" class="form-check-label fs-14">
                                                Acuerdos
                                            </label>
                                            <p class="text-muted">Consultalos aqui.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h5 class="card-title text-decoration-underline mb-3">
                                    Borrar cuenta:
                                </h5>

                                {{-- <form action="{{route('eliminarUser')}}" method="POST"> --}}

                                <p class="text-muted" style="font-size: 14px;">
                                    Si deseas borrar tu cuenta, puedes hacerlo aqui.
                                </p>
                                <div>
                                    <input type="password" class="form-control" id="passwordInput"
                                        placeholder="Ingresa tu contrase??a" style="max-width: 265px;">
                                </div>
                                <div class="hstack gap-2 mt-3">
                                    <a id="adelete" class="delete-item-btn" href="#deleteRecordModal" data-bs-toggle="modal" data-bs-id="{{Auth::user()->id}}">
                                        <button type="submit" class="btn btn-soft-primary">
                                            Cerrar y borrar cuenta
                                        </button>
                                    </a>
                                </div>

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
                                                    <h4 class="fs-semibold">??Qui??res eliminar el usuario?</h4>
                                                    <p class="text-muted fs-14 mb-4 pt-1">Eliminar el usuario implica borrar tus datos de contacto pero no tus participaciones generadas.</p>
                                                    <div class="hstack gap-2 justify-content-center remove">
                                                        </br></br>
                                                        <form action="{{ route('eliminarUser') }}" method="post">
                                                            @csrf
                                                            <input name="password_delete" type="password" class="form-control mb-12" id="passwordInput"
                                                                placeholder="Ingresa tu contrase??a">
                                                            </br>
                                                            <a class="btn btn-link link-primary fw-medium text-decoration-none" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Cerrar</a>
                                                            <button type="submit" class="btn btn-primary" id="delete-record">Si</button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!--end delete modal -->

                            </div>
                        </div>
                        <!--end tab-pane-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script type="text/javascript">
        function habilitar() {
            element = document.getElementById("boletatinha");
            check = document.getElementById("customSwitchsizemd");
            if (check.checked) {
                element.style.display='block';
            }
            else {
                element.style.display='none';
            }
        }
    </script>
@endsection
