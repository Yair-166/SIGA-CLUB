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
            Usuarios
        @endslot
    @endcomponent
    @php
        //$users = DB::table('users')->get();
        $eliminar = 0;
        $inscrito = 0;
        $type = 'todos';
        $string = '';
        //Checar si se envio algun parametro por la url
        if(isset($_GET['type'])){
            //Obtener el valor del parametro
            $type = $_GET['type'];
        }
        if($type == 'todos')
            $users = DB::table('users')->where('rol', '!=', 'super')->where('active', 1)->get();
        else if($type == 'admins')
            $users = DB::table('users')->where('rol', 'administrador')->where('active', 1)->get();
        else
            $users = DB::table('users')->where('rol', 'colaborador')->where('active', 1)->get();
        
        echo $string;
        echo $type;

    @endphp
    <div class="row">

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
                                        <th  scope="col">Nombre del usuario</th>
                                        <th  scope="col">Correo electronico</th>
                                        <th  scope="col">Tipo de usuario</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                
                                @foreach ($users as $user)
                                    <tbody class="list form-check-all">
                                    <tr>
                                        <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ URL::asset('images/' . $user->avatar) }}" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                </div>
                                                <div class="flex-grow-1 ms-2 name">
                                                    {{ $user->name }} {{ $user->apaterno }} {{ $user->amaterno }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="owner">
                                            {{ $user->email }}
                                        </td>
                                        <td class="rol">
                                            {{ $user->rol }}
                                        </td>
                                        <td>
                                            @php
                                                //juntar toda la información del user en un solo string
                                                $userData = $user->avatar .';'. $user->name .';'. $user->descripcion .";". $user->telefono;
                                                //Convertir a json el string
                                                $userData = json_encode($userData);
                                            @endphp
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                    data-bs-placement="top" title="Ver más">
                                                    <a href="javascript:mostrar({{$userData}});" class="view-item-btn">
                                                        <i class="ri-eye-fill align-bottom text-muted"></i>
                                                    </a>
                                                </li>

                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                    data-bs-placement="top" title="Eliminar">
                                                    <a id="adelete" class="delete-item-btn" href="#deleteRecordModal" data-bs-toggle="modal" data-bs-id="{{$user->id}}">
                                                        <button onClick="eliminarid({{$user->id}})" style="border: none; background: none;">
                                                            <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                        </button>
                                                    </a>
                                                </li>
                                                @if($user->rol != "administrador")
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                        data-bs-placement="top" title="Dar admin">
                                                        <a href="{{ route('darAdmin', $user->id)}}" class="view-item-btn">
                                                            <i class="ri-user-star-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                        data-bs-placement="top" title="Quitar admin">
                                                        <a href="{{ route('darAdmin', $user->id)}}" class="view-item-btn">
                                                            <i class="ri-user-unfollow-fill align-bottom text-muted"></i>
                                                        </a>                                                        </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                        data-bs-placement="top" title="Clubes permitidos">
                                                        <form action="{{ route('clubesPermitidos')}}" method="POST">
                                                            @csrf
                                                            <button type="button" class="ri-subtract-fill align-center text-muted" onclick="decrementar({{$user->id}})" style="background-color: transparent; border: 0px solid #3498db;"></button>
                                                            <input type="hidden" name="id" value="{{$user->id}}">
                                                            <label id="clubesAlMomento{{$user->id}}" value="{{$user->clubes}}" style="display:none;">{{$user->clubes}}</label>
                                                            <label id="nums{{$user->id}}">{{$user->clubes}}</label>
                                                            <button type="button" class="ri-add-fill align-center text-muted" onclick="incrementar({{$user->id}})" style="background-color: transparent; border: 0px solid #3498db;"></button>
                                                            <input type="hidden" id="finalnums{{$user->id}}" name="nums" value="{{$user->clubes}}" required>
                                                            <button type="submit" class="view-item-btn" style="background-color: transparent; border: 0px">
                                                                <i class="ri-checkbox-circle-fill text-muted"></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif

                                            </ul>
                                        </td>
                                    </tr>
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
                                        <h4 class="fs-semibold">¿Quiéres eliminar el usuario?</h4>
                                        <p class="text-muted fs-14 mb-4 pt-1">Eliminar el usuario implica borrar sus datos de contacto pero no sus participaciones generadas.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-primary fw-medium text-decoration-none" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                                            <form action="{{ route('deleteuser') }}" method="post">
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
                    <h5 id="info-name" class="mt-3 mb-1">user</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Información</h6>
                    <p id="info-description" class="text-muted mb-4">
                        Descripción del usuario
                    </p>
                    <a id="walink">
                        <img id="wa" src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/1200px-WhatsApp.svg.png" class="avatar-sm object-cover">
                    </a>
                    <a id="telelink">
                        <img id="tele" src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Telegram_logo.svg/1200px-Telegram_logo.svg.png" class="avatar-sm object-cover">
                    </a>
                </div>
            </div><!--end card-->
        </div><!--end col-->
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
            let userid = id;
            console.log(userid);
            document.getElementById("idelimnar").value = userid;
        }
        function mostrar(data){
            console.log(data);
            //Separar los datos
            let datos = data.split(";");
            //Asignar los datos a los campos
            document.getElementById("info-foto").src = "/images/" + datos[0];
            document.getElementById("info-name").innerHTML = datos[1];
            document.getElementById("info-description").innerHTML = datos[2];
            if(datos[3] == "")
            {
                document.getElementById("walink").removeAttribute('href');
                document.getElementById("telelink").removeAttribute('href');
            }
            else
            {
                document.getElementById("walink").setAttribute('href', 'https://wa.me/'+datos[3]);
                document.getElementById("telelink").setAttribute('href', 'https://t.me/+52'+datos[3]);
            }
            
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
        function incrementar(id){
            document.getElementById("nums".concat(id)).innerHTML = parseInt(document.getElementById("nums".concat(id)).innerHTML) + 1;
            document.getElementById("finalnums".concat(id)).value = parseInt(document.getElementById("nums".concat(id)).innerHTML);
        }
        function decrementar(id){
            document.getElementById("nums".concat(id)).innerHTML = parseInt(document.getElementById("nums".concat(id)).innerHTML) - 1;
            document.getElementById("finalnums".concat(id)).value = parseInt(document.getElementById("nums".concat(id)).innerHTML);
        }
    </script>
@endsection
