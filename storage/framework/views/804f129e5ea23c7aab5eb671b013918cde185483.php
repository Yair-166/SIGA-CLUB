
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
            Clubes
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php
        //Obtener los clubes de la base de datos
        $clubs = DB::table('clubes')->get();
        //Obtener los usuarios de la base de datos 
        $users = DB::table('users')->get();
        //Obtener los datos de la tabla inscripciones
        $inscripciones = DB::table('inscripciones')->get();
        $eliminar = 0;
        $inscrito = 0;
    ?>
    <div class="row">
    <?php if( (Auth::user()->rol == 'administrador' && Auth::user()->clubes > 0) || Auth::user()->rol == 'super'): ?>
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
    <?php endif; ?>  

        <div class="col-xxl-9">
            <div class="card" id="companyList">
                <div class="card-body">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                <strong>??Error!</strong> <?php echo e(session('error')); ?>

                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="sort" data-sort="name" scope="col">Nombre del club</th>
                                        <th class="sort" data-sort="owner" scope="col">Administrador</th>
                                        <th class="sort" data-sort="location" scope="col">Localizaci??n</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                
                                <?php $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($club->active == '1'): ?>
                                        <tbody class="list form-check-all">
                                        <tr>
                                            <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>
                                            </td>
                                            <td>
                                                <?php if($club->idAdministrador == Auth::user()->id || Auth::user()->rol == 'super'): ?>
                                                    <a href="<?php echo e(URL::asset('/apps-clubes-editar?club='.$club->id)); ?>">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                            </div>
                                                            <div class="flex-grow-1 ms-2 name">
                                                                <?php echo e($club->nombre); ?>

                                                            </div>
                                                        </div>
                                                    </a>
                                                <?php else: ?>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <img src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                        </div>
                                                        <div class="flex-grow-1 ms-2 name">
                                                            <?php echo e($club->nombre); ?>

                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="owner">
                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($user->id == $club->idAdministrador): ?>
                                                        <?php
                                                            $admin_name = $user->name . " " . $user->apaterno . " " . $user->amaterno;
                                                        ?>
                                                        <?php echo e($admin_name); ?>

                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                            <td class="location"><?php echo e($club->localizacion); ?></td>
                                            <td>
                                                <?php
                                                    //juntar toda la informaci??n del club en un solo string
                                                    $clubData = $club->foto . ';' . $club->nombre . ';' . $admin_name . ';' . $club->descripcion . ';' . $club->localizacion . ';' . $club->nomenclatura . ';' . $club->facebook;
                                                    //Convertir a json el string
                                                    $clubData = json_encode($clubData);
                                                ?>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                        data-bs-placement="top" title="Ver m??s">
                                                        <a href="javascript:mostrar(<?php echo e($clubData); ?>);" class="view-item-btn">
                                                            <i class="ri-eye-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                    <?php if($club->idAdministrador == Auth::user()->id || Auth::user()->rol == 'super'): ?>
                                                        
                                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top" title="Estadisticas">
                                                            <a class="edit-item-btn" href="<?php echo e(URL::asset('/dashboard?club='.$club->id)); ?>">
                                                                <i class="ri-bar-chart-fill align-bottom text-muted"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top" title="Eliminar">
                                                            <a id="adelete" class="delete-item-btn" href="#deleteRecordModal" data-bs-toggle="modal" data-bs-id="<?php echo e($club->id); ?>">
                                                                <button onClick="eliminarid(<?php echo e($club->id); ?>)" style="border: none; background: none;">
                                                                    <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                                </button>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <?php if(Auth::user()->rol == 'colaborador'): ?>
                                                        <?php $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inscripcion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($inscripcion->id_club == $club->id && $inscripcion->id_alumno == Auth::user()->id && $inscripcion->active == '1'): ?>
                                                                <?php
                                                                    $inscrito = 1;
                                                                ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($inscrito != 1): ?>
                                                            <form action="<?php echo e(route('inscribirse')); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="id_club" value="<?php echo e($club->id); ?>">
                                                                <input type="hidden" name="id_alumno" value="<?php echo e(Auth::user()->id); ?>">
                                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                                    data-bs-placement="top" title="Inscribirse">
                                                                    <a href="javascript:void(0);" class="inscribirse-item-btn">
                                                                        <button type="submit" style="border: none; background: none;">
                                                                            <i class="ri-user-add-fill align-bottom text-muted"></i>
                                                                        </button>
                                                                    </a>
                                                                </li>
                                                            </form>
                                                        <?php else: ?>
                                                            <form action="<?php echo e(route('desinscribirse')); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="id_club" value="<?php echo e($club->id); ?>">
                                                                <input type="hidden" name="id_alumno" value="<?php echo e(Auth::user()->id); ?>">
                                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                                    data-bs-placement="top" title="desinscribirse">
                                                                    <a href="javascript:void(0);" class="inscribirse-item-btn">
                                                                        <button type="submit" style="border: none; background: none;">
                                                                            <i class="ri-user-unfollow-fill align-bottom text-muted"></i>
                                                                        </button>
                                                                    </a>
                                                                </li>
                                                            </form>
                                                        <?php endif; ?>
                                                        <?php
                                                            $inscrito = 0;
                                                        ?>
                                                    <?php endif; ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
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

                                
                                <form action="<?php echo e(route('updateClub')); ?>" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
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
                                                                <img src="<?php echo e(URL::asset('assets/images/users/multi-user.jpg')); ?>"
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
                                                <?php if(Auth::user()->rol == "administrador"): ?>
                                                    <div>
                                                        <label for="owner-field" class="form-label">Nombre del administrador</label>
                                                        <input type="text" class="form-control"
                                                            value="<?php echo e(Auth::user()->name); ?> <?php echo e(Auth::user()->apaterno); ?> <?php echo e(Auth::user()->amaterno); ?>" 
                                                            disabled/>
                                                            <input type="hidden" name="idAdministrador" value="<?php echo e(Auth::user()->id); ?>">
                                                    </div>
                                                <?php else: ?>
                                                    <div>
                                                        <label for="owner-field" class="form-label">Nombre del administrador</label>
                                                        <select class="form-select" name="idAdministrador" required>
                                                            <option value="">Selecciona un administrador</option>
                                                            <?php
                                                                $administradores = App\Models\User::where('rol', 'administrador')->get();
                                                            ?>
                                                            <?php $__currentLoopData = $administradores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $administrador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($administrador->id); ?>"><?php echo e($administrador->name); ?> <?php echo e($administrador->apaterno); ?> <?php echo e($administrador->amaterno); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="nomenclatura-field" class="form-label">Prefijo para constancias</label>
                                                    <input type="text" id="nomenclatura-field" class="form-control" name="nomenclatura" placeholder="Prefijo para constancias" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="location-field" class="form-label">Por favor ingrese la ubicaci??n de la escuela y el sal??n, por ejemplo: Escuela XYZ, Sal??n ABC</label>
                                                    <input type="text" id="location-field" class="form-control" name="localizacion"  placeholder="Localizaci??n" required />
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
                                                    <label for="since-field" class="form-label">Descripci??n</label>
                                                    <textarea id="description-field" class="form-control" rows="3" name="descripcion" placeholder="Descripci??n del club"></textarea>
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

                                
                                <form action="<?php echo e(route('editarClub')); ?>" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
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
                                                                <img src="<?php echo e(URL::asset('assets/images/users/multi-user.jpg')); ?>"
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
                                                        value="<?php echo e(Auth::user()->name); ?> <?php echo e(Auth::user()->apaterno); ?> <?php echo e(Auth::user()->amaterno); ?>" 
                                                        disabled/>
                                                        <input type="hidden" name="idAdministrador" value="<?php echo e(Auth::user()->id); ?>">
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
                                                    <label for="editar-location" class="form-label">Localizaci??n</label>
                                                    <input type="text" id="editar-location" class="form-control" name="localizacion"  placeholder="Localizaci??n" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="editar-description" class="form-label">Descripci??n</label>
                                                    <textarea id="editar-description" class="form-control" rows="3" name="descripcion" ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary" id="add-btn">Guardar cambios</button>
                                            
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
                                        <h4 class="fs-semibold">??Qui??res eliminar el club?</h4>
                                        <p class="text-muted fs-14 mb-4 pt-1">Eliminar el club implica borrar todo su contenido.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-primary fw-medium text-decoration-none" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                                            <form action="<?php echo e(route('deleteClub')); ?>" method="post">
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
            <?php if(Auth::user()->rol == 'super'): ?>
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
                                            <th class="sort" data-sort="location" scope="col">Localizaci??n</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    
                                    <?php $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($club->active == '0'): ?>
                                            <tbody class="list form-check-all">
                                            <tr>
                                                <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(URL::asset('/apps-clubes-editar?club='.$club->id)); ?>">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                            </div>
                                                            <div class="flex-grow-1 ms-2 name">
                                                                <?php echo e($club->nombre); ?>

                                                              </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="owner">
                                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($user->id == $club->idAdministrador): ?>
                                                            <?php
                                                                $admin_name = $user->name . " " . $user->apaterno . " " . $user->amaterno;
                                                            ?>
                                                            <?php echo e($admin_name); ?>

                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                                <td class="location"><?php echo e($club->localizacion); ?></td>
                                                <td>
                                                    <?php
                                                        //juntar toda la informaci??n del club en un solo string
                                                        $clubData = $club->foto . ';' . $club->nombre . ';' . $admin_name . ';' . $club->descripcion . ';' . $club->localizacion . ';' . $club->nomenclatura . ';' . $club->facebook;
                                                        //Convertir a json el string
                                                        $clubData = json_encode($clubData);
                                                    ?>
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top" title="Ver m??s">
                                                            <a href="javascript:mostrar(<?php echo e($clubData); ?>);" class="view-item-btn">
                                                                <i class="ri-eye-fill align-bottom text-muted"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                            data-bs-placement="top" title="Activar">
                                                            <a href="<?php echo e(Route ('activarClub', $club->id)); ?>" class="view-item-btn">
                                                                <i class="ri-check-fill align-bottom text-muted"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
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

                                    
                                    <form action="<?php echo e(route('updateClub')); ?>" method="POST" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
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
                                                                    <img src="<?php echo e(URL::asset('assets/images/users/multi-user.jpg')); ?>"
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
                                                            value="<?php echo e(Auth::user()->name); ?> <?php echo e(Auth::user()->apaterno); ?> <?php echo e(Auth::user()->amaterno); ?>" 
                                                            disabled/>
                                                            <input type="hidden" name="idAdministrador" value="<?php echo e(Auth::user()->id); ?>">
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
                                                        <label for="location-field" class="form-label">Localizaci??n</label>
                                                        <input type="text" id="location-field" class="form-control" name="localizacion"  placeholder="Localizaci??n" required />
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
                                                        <label for="since-field" class="form-label">Descripci??n</label>
                                                        <textarea id="description-field" class="form-control" rows="3" name="descripcion" placeholder="Descripci??n del club"></textarea>
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

                                    
                                    <form action="<?php echo e(route('editarClub')); ?>" method="POST" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
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
                                                                    <img src="<?php echo e(URL::asset('assets/images/users/multi-user.jpg')); ?>"
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
                                                            value="<?php echo e(Auth::user()->name); ?> <?php echo e(Auth::user()->apaterno); ?> <?php echo e(Auth::user()->amaterno); ?>" 
                                                            disabled/>
                                                            <input type="hidden" name="idAdministrador" value="<?php echo e(Auth::user()->id); ?>">
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
                                                        <label for="editar-location" class="form-label">Localizaci??n</label>
                                                        <input type="text" id="editar-location" class="form-control" name="localizacion"  placeholder="Localizaci??n" required />
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div>
                                                        <label for="editar-description" class="form-label">Descripci??n</label>
                                                        <textarea id="editar-description" class="form-control" rows="3" name="descripcion" ></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary" id="add-btn">Guardar cambios</button>
                                                
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
                                            <h4 class="fs-semibold">??Qui??res eliminar el club?</h4>
                                            <p class="text-muted fs-14 mb-4 pt-1">Eliminar el club implica borrar todo su contenido.</p>
                                            <div class="hstack gap-2 justify-content-center remove">
                                                <button class="btn btn-link link-primary fw-medium text-decoration-none" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Cerrar</button>
                                                <form action="<?php echo e(route('deleteClub')); ?>" method="post">
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
                
            <?php endif; ?>
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
                    <h5 id="info-name" class="mt-3 mb-1">Club</h5>
                    <p id="info-admin" class="text-muted">Administrador</p>
                </div>
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Informaci??n</h6>
                    <p id="info-description" class="text-muted mb-4">
                        Descripci??n del club
                    </p>
                    <div class="table-responsive table-card">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-medium" scope="row">Localizaci??n</td>
                                    <td id="info-location">ESCOM, M??xico</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium" scope="row" colspan='2'> 
                                        <a href="#" id="info-facebook">
                                            <img src="<?php echo e(URL::asset('assets/images/fb.png')); ?>" alt="" class="avatar-sm rounded-circle object-cover">
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
            <?php if($message = Session::get('des')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Listo</strong> <?php echo e($message); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            <?php elseif($message = Session::get('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Club inscrito!</strong> 
                    </br>
                    <?php echo e($message); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            <?php endif; ?>  
        </div>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/clubes-catalogo.blade.php ENDPATH**/ ?>