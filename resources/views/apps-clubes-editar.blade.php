@extends('layouts.master')
@section('title')
    @lang('translation.overview')
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
    //Obtener el valor de la variable evento desde la url
    $getId = $_GET['club'];
    //Obtener el club de la base de datos donde el id sea igual al id del club
    $club = DB::table('clubes')->where('id', $getId)->first();
    //Obtener la tabla autoridades
    $autoridades = DB::table('autoridades')->where('idClub', $getId)->get();
    //Obtener de la tabla users todos los que tengan el rol de administrador
    $administradores = DB::table('users')->where('rol', 'administrador')->get();
    $admin = DB::table('users')->where('id', $club->idAdministrador)->first();
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
                                            @if($club->tags != "")
                                                <div class="hstack gap-3 flex-wrap">
                                                    <div>Tags : </div>
                                                    @php
                                                        $tags = explode(",", $club->tags);
                                                        //Eliminar el ultimo elemento del array
                                                        array_pop($tags);
                                                    @endphp
                                                    @foreach ($tags as $tag)
                                                        <span class="badge bg-success">{{$tag}}
                                                            <a type="button" class="badge btn-danger" href={{ route('eliminarTagClub', ['idClub' => $club->id, 'tag' => $tag])}}>
                                                                <i class="mdi mdi-close"></i>
                                                            </a>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
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
                                        <input type="hidden" name="id" value="{{$club->id}}">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Foto</label>
                                            <input name="foto" class="form-control" type="file" id="formFile">
                                        </div>
                                        @if(Auth::user()->rol == "administrador")
                                            <input type="hidden" name="idAdministrador" value="{{Auth::user()->id}}">
                                        @else
                                            <div class="form-check form-switch form-switch-md" dir="ltr">
                                                <input type="checkbox" class="form-check-input" id="customSwitchsizemd" onclick="habilitar()">
                                                <label class="form-check-label" for="customSwitchsizemd">Cambiar administrador</label>
                                            </div>
                                            <br>
                                            <div class="mb-3" id="admins" style="display: none;">
                                                <label for="idAdministrador" class="form-label">Administrador</label>
                                                <select class="form-select" name="idAdministrador" id="idAdministrador" required>
                                                    <option value={{$club->idAdministrador}} selected>
                                                        {{$admin->name." ".$admin->apaterno." ".$admin->amaterno}}
                                                    </option>
                                                    @foreach ($administradores as $administrador)
                                                        @if($administrador->active == 1 && ($administrador->id != $club->idAdministrador))
                                                            <option value="{{$administrador->id}}" id="valoradmin">
                                                                {{$administrador->name." ".$administrador->apaterno." ".$administrador->amaterno}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{$club->nombre}}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nomenclatura" class="form-label">Prefijo para constancias (Texto que llevarán las constancias expedidas por el club)</label>
                                            <input type="text" class="form-control" id="nomenclatura" name="nomenclatura" value="{{$club->nomenclatura}}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="localizacion" class="form-label">Por favor ingrese la ubicación de la escuela y el salón, por ejemplo: Escuela XYZ, Salón ABC</label>
                                            <input type="text" class="form-control" id="localizacion" name="localizacion" value="{{$club->localizacion}}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="facebook" class="form-label">Link de facebook</label>
                                            <input type="text" class="form-control" id="facebook" name="facebook" value="{{$club->facebook}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcion" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{$club->descripcion}}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Tags" class="form-label">Tags <b>*Permiten separar los tipos de participantes del club</b></label>
                                            <input type="text" class="form-control" id="tags" name="tags" placeholder="Básicos, Intermedios, Avanzados...">
                                            <button type="button" class="btn btn-primary" onclick="agregarTag()">Agregar</button>
                                            <div id="labels"></div>
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
    <script type="text/javascript">
        function habilitar() {
            element = document.getElementById("admins");
            check = document.getElementById("customSwitchsizemd");
            if (check.checked) {
                element.style.display='block'
            }
            else {
                element.style.display='none';
            }
        }
    </script>
    <script>

        function agregarTag(){
            //Cancel submit
            event.preventDefault();

            //Obtener lo escrito en el input tags
            var tag = document.getElementById("tags").value;

            //Checar si existe un label con id = namecords
            if(document.getElementById(tag)){
                //Si existe, no hacer nada
                return;
            }
            else{
                //Agregar un input hidden con name=idcords y value=idcords
                var newInput = document.createElement('input');
                //Poner type hidden
                newInput.setAttribute("type", "hidden");
                //agrego la clase deseada
                newInput.className += "form-control";
                //Poner name al input igual al id del select
                newInput.setAttribute("name", tag);
                //Poner id al input igual al id del select
                newInput.setAttribute("id", tag);
                //Poner value al input igual al id del select
                newInput.setAttribute("value", tag);
                //agregando el input
                var contenedor = document.getElementById('labels');
                contenedor.appendChild(newInput);

                //Agregar un label con el nombre del coordinador
                var newLabel = document.createElement('label');
                //agrego la clase deseada
                newLabel.className += "badge badge-soft-success text-uppercase";
                //Poner id al label igual al id del select
                newLabel.setAttribute("id", tag);
                //Obtener de la base de datos el nombre del user con el idcords
                newLabel.innerHTML = tag;
                //agregando el label
                var contenedor = document.getElementById('labels');
                contenedor.appendChild(newLabel);

                //Agregar boton para eliminar el coordinador
                var newButton = document.createElement('button');
                //agrego la clase deseada
                newButton.className += "badge btn-soft-danger text-uppercase";
                //Poner id al label igual al id del select
                newButton.setAttribute("id", tag);
                //Poner value al label igual al id del select
                newButton.setAttribute("value", tag);
                //Poner onclick al boton
                newButton.setAttribute("onclick", "eliminarcoord(this.id, this.value)");
                //Poner el texto del boton
                newButton.innerHTML = "🗑️";
                //agregando el label
                var contenedor = document.getElementById('labels');
                contenedor.appendChild(newButton);

                //Poner un br
                var newBr = document.createElement('br');
                newBr.setAttribute("id", tag + "br");
                contenedor.appendChild(newBr);
            }
        }

        function eliminarcoord(namecords, idcords){
                //Eliminar el input hidden con name=namecords y value=idcords
                var input = document.getElementById(namecords);
                input.parentNode.removeChild(input);

                //Eliminar el label con id=namecords
                var label = document.getElementById(namecords);
                label.parentNode.removeChild(label);

                //Eliminar el boton con id=namecords
                var button = document.getElementById(namecords);
                button.parentNode.removeChild(button);

                //Eliminar el br
                var br = document.getElementById(namecords + "br");
                br.parentNode.removeChild(br);
            }
    </script>
@endsection
