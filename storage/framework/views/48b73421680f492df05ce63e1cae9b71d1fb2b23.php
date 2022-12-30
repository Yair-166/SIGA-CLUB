
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.companies'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('assets/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            SIGA
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Usuarios
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php
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

    ?>
    <div class="row">

        <div class="col-xxl-9">
            <div class="card" id="companyList">
                <div class="card-body">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                <strong>¡Error!</strong> <?php echo e(session('error')); ?>

                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
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
                                
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tbody class="list form-check-all">
                                    <tr>
                                        <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="<?php echo e(URL::asset('images/' . $user->avatar)); ?>" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                </div>
                                                <div class="flex-grow-1 ms-2 name">
                                                    <?php echo e($user->name); ?> <?php echo e($user->apaterno); ?> <?php echo e($user->amaterno); ?>

                                                </div>
                                            </div>
                                        </td>
                                        <td class="owner">
                                            <?php echo e($user->email); ?>

                                        </td>
                                        <td class="rol">
                                            <?php echo e($user->rol); ?>

                                        </td>
                                        <td>
                                            <?php
                                                //juntar toda la información del user en un solo string
                                                $userData = $user->avatar . ';' . $user->name . ';' . $user->descripcion;
                                                //Convertir a json el string
                                                $userData = json_encode($userData);
                                            ?>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                    data-bs-placement="top" title="Ver más">
                                                    <a href="javascript:mostrar(<?php echo e($userData); ?>);" class="view-item-btn">
                                                        <i class="ri-eye-fill align-bottom text-muted"></i>
                                                    </a>
                                                </li>

                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                    data-bs-placement="top" title="Eliminar">
                                                    <a id="adelete" class="delete-item-btn" href="#deleteRecordModal" data-bs-toggle="modal" data-bs-id="<?php echo e($user->id); ?>">
                                                        <button onClick="eliminarid(<?php echo e($user->id); ?>)" style="border: none; background: none;">
                                                            <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                        </button>
                                                    </a>
                                                </li>
                                                <?php if($user->rol != "administrador"): ?>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                        data-bs-placement="top" title="Dar admin">
                                                        <a href="<?php echo e(route('darAdmin', $user->id)); ?>" class="view-item-btn">
                                                            <i class="ri-user-star-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                        data-bs-placement="top" title="Quitar admin">
                                                        <a href="<?php echo e(route('darAdmin', $user->id)); ?>" class="view-item-btn">
                                                            <i class="ri-user-unfollow-fill align-bottom text-muted"></i>
                                                        </a>                                                        </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                        data-bs-placement="top" title="Clubes permitidos">
                                                        <form action="<?php echo e(route('clubesPermitidos')); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="id" value="<?php echo e($user->id); ?>">
                                                            <input type="text" name="nums" value="<?php echo e($user->clubes); ?>" required>
                                                            <button type="submit" class="view-item-btn">
                                                                <i class="ri-checkbox-circle-fill align-bottom text-muted"></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>

                                            </ul>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <form action="<?php echo e(route('deleteuser')); ?>" method="post">
                                                <?php echo csrf_field(); ?>
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
                                <img id="info-foto" src="<?php echo e(URL::asset('assets/images/users/multi-user.jpg')); ?>" alt="" class="avatar-sm rounded-circle object-cover">
                            </div>
                        </div>
                    </div>
                    <h5 id="info-name" class="mt-3 mb-1">user</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Información</h6>
                    <p id="info-description" class="text-muted mb-4">
                        Descripción del user
                    </p>
                </div>
            </div><!--end card-->
        </div><!--end col-->
    </div><!--end row-->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('/assets/libs/list.js/list.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/list.pagination.js/list.pagination.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/pages/crm-companies.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
    <script>
        function eliminarid(id){
            let userid = id;
            console.log(userid);
            document.getElementById("idelimnar").value = userid;
        }
        function mostrar(data){
            //Separar los datos
            let datos = data.split(";");
            //Asignar los datos a los campos
            document.getElementById("info-foto").src = "/images/" + datos[0];
            document.getElementById("info-name").innerHTML = datos[1];
            document.getElementById("info-description").innerHTML = datos[3];
            
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/apps-crm-users.blade.php ENDPATH**/ ?>