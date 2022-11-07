
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
            CRM
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Clubes
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i class="ri-add-fill me-1 align-bottom"></i> Agregar club</button>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="hstack text-nowrap gap-2">
                                <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
        <div class="col-xxl-9">
            <div class="card" id="companyList">
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 50px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                            </div>
                                        </th>
                                        <th class="sort" data-sort="name" scope="col">Nombre del club</th>
                                        <th class="sort" data-sort="owner" scope="col">Administrador</th>
                                        <th class="sort" data-sort="location" scope="col">Localización</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="chk_child" value="option1">
                                            </div>
                                        </th>
                                        <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ001</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="<?php echo e(URL::asset('assets/images/brands/dribbble.png')); ?>" alt="" class="avatar-xxs rounded-circle image_src object-cover">
                                                </div>
                                                <div class="flex-grow-1 ms-2 name">Nesta Technologies
                                                </div>
                                            </div>
                                        </td>
                                        <td class="owner">Tonya Noble</td>
                                        <td class="location">Los Angeles, USA</td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                    data-bs-placement="top" title="Call">
                                                    <a href="javascript:void(0);" class="text-muted d-inline-block">
                                                        <i class="ri-phone-line fs-16"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                    data-bs-placement="top" title="Message">
                                                    <a href="javascript:void(0);" class="text-muted d-inline-block">
                                                        <i class="ri-question-answer-line fs-16"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                    data-bs-placement="top" title="View">
                                                    <a href="javascript:void(0);" class="view-item-btn"><i
                                                            class="ri-eye-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                    data-bs-placement="top" title="Edit">
                                                    <a class="edit-item-btn" href="#showModal" data-bs-toggle="modal"><i
                                                            class="ri-pencil-fill align-bottom text-muted"></i></a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover"
                                                    data-bs-placement="top" title="Delete">
                                                    <a class="remove-item-btn" data-bs-toggle="modal" href="#deleteRecordModal">
                                                        <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

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
                        <div class="d-flex justify-content-end mt-3">
                            <div class="pagination-wrap hstack gap-2">
                                <a class="page-item pagination-prev disabled" href="#">
                                    Anterior
                                </a>
                                <ul class="pagination listjs-pagination mb-0"></ul>
                                <a class="page-item pagination-next" href="#">
                                    Siguiente
                                </a>
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

                                
                                <form action="<?php echo e(route('updatePassword')); ?>" method="POST" enctype="multipart/form-data">
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
                                                                accept="image/png, image/gif, image/jpeg">
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
                                                        placeholder="Nombre de tu club" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="owner-field" class="form-label">Nombre del administrador</label>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo e(Auth::user()->name); ?> <?php echo e(Auth::user()->apaterno); ?> <?php echo e(Auth::user()->amaterno); ?>" 
                                                        disabled/>
                                                    <input type="hidden" id="owner-field" class="form-control" value="<?php echo e(Auth::user()->id); ?>" disabled/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="nomenclatura-field" class="form-label">Nomenclatura</label>
                                                    <input type="text" id="nomenclatura-field" class="form-control"  placeholder="Nomenclatura" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
                                                    <label for="location-field" class="form-label">Localización</label>
                                                    <input type="text" id="location-field" class="form-control"  placeholder="Localización" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="since-field" class="form-label">Descripción</label>
                                                    <textarea id="description-field" class="form-control" rows="3" placeholder="Descripción del club"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary" id="add-btn">Agregar club</button>
                                            <button type="button" class="btn btn-primary" id="edit-btn">Actualizar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!--end add modal-->

                    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel"
                        aria-hidden="true">
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
                                            <button class="btn btn-primary" id="delete-record">Si</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end delete modal -->

                </div>
            </div><!--end card-->
        </div><!--end col-->
        <div class="col-xxl-3">
            <div class="card" id="company-view-detail">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block">
                        <div class="avatar-md">
                            <div class="avatar-title bg-light rounded-circle">
                                <img src="<?php echo e(URL::asset('assets/images/users/multi-user.jpg')); ?>" alt="" class="avatar-sm rounded-circle object-cover">
                            </div>
                        </div>
                    </div>
                    <h5 class="mt-3 mb-1">Club</h5>
                    <p class="text-muted">Administrador</p>
                </div>
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Información</h6>
                    <p class="text-muted mb-4">
                        Descripción del club
                    </p>
                    <div class="table-responsive table-card">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-medium" scope="row">Localización</td>
                                    <td>ESCOM, México</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/apps-crm-companies.blade.php ENDPATH**/ ?>