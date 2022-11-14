
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.settings'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php
    $user = Auth::user()
?>
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="<?php echo e(URL::asset('assets/images/profile-bg.jpg')); ?>" class="profile-wid-img" alt="">
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="<?php if(Auth::user()->avatar != ''): ?> <?php echo e(URL::asset('images/' . Auth::user()->avatar)); ?><?php else: ?><?php echo e(URL::asset('assets/images/users/avatar-1.jpg')); ?> <?php endif; ?>"
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
                        <h5 class="fs-16 mb-1"><?php echo e($user->name . " " . $user->apaterno . " " . $user->amaterno); ?></h5>
                        <p class="text-muted mb-0"><?php echo e($user->rol); ?></p>
                        <div class="mb-4">
                            <?php if($message = Session::get('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> <?php echo e($message); ?>

                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close">
                                    </button>
                                </div>
                            <?php elseif($message = Session::get('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Exito!</strong> <?php echo e($message); ?>

                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close">
                                    </button>
                                </div>
                            <?php endif; ?>  
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
                                Información personal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                Cambiar contraseña
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
                            <form action="<?php echo e(route('edituser')); ?>" method="POST">
                             <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <input type="hidden" name="id" value="<?php echo e($user->id); ?>">
                                            <label for="firstnameInput" class="form-label">
                                                Nombre(s)
                                            </label>
                                            <input name="name" type="text" class="form-control" id="firstnameInput"
                                                placeholder="<?php echo e($user->name); ?>" value=<?php echo e($user->name); ?>>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="lastnameInput" class="form-label">
                                                Apellido paterno
                                            </label>
                                            <input name="apaterno" type="text" class="form-control" id="lastnameInput"
                                                placeholder="<?php echo e($user->apaterno); ?>" value=<?php echo e($user->apaterno); ?>>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="amaternoa" class="form-label">
                                                Apellido materno
                                            </label>
                                            <input name="amaterno" type="text" class="form-control" id="amaternoa"
                                                placeholder="<?php echo e($user->amaterno); ?>" value=<?php echo e($user->amaterno); ?>>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6" hidden>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                Correo electrónico
                                            </label>
                                            <input name="email" type="email" class="form-control" id="email"
                                                placeholder="<?php echo e($user->email); ?>" value="<?php echo e($user->email); ?>">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <input name="created_at" type="text" class="form-control" data-provider="flatpickr"
                                                id="JoiningdatInput" data-date-format="d M, Y"
                                                data-deafult-date="<?php echo e($user->created_at); ?>" placeholder="Select date" disabled hidden/>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3 pb-2">
                                            <label for="exampleFormControlTextarea"
                                                class="form-label">Descripción</label>
                                            <textarea name="descripcion" class="form-control" id="exampleFormControlTextarea" placeholder="Cuentanos sobre ti"
                                                rows="3" value="<?php echo e($user->descripcion); ?>"><?php echo e($user->descripcion); ?></textarea>
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
                            <form action="<?php echo e(route('editarPassword')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <input type="hidden" name="id" value="<?php echo e($user->id); ?>">
                                            <label for="oldpasswordInput" class="form-label">
                                                Contraseña actual
                                            </label>
                                            <input name="current_password" type="password" class="form-control" id="oldpasswordInput"
                                                placeholder="Ingresa la contraseña actual">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">
                                                Nueva contraseña*
                                            </label>
                                            <input name="password" type="password" class="form-control" id="newpasswordInput"
                                                placeholder="Ingresa la nueva contraseña">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">
                                                Confirmar contraseña*
                                            </label>
                                            <input type="password" class="form-control" id="confirmpasswordInput"
                                                placeholder="Confirma la contraseña">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <a href="javascript:void(0);"
                                                class="link-primary text-decoration-underline">
                                                ¿Olvidaste tu contraseña?
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
                                <p class="text-muted" style="font-size: 14px;">
                                    Si deseas borrar tu cuenta, puedes hacerlo aqui.
                                </p>
                                <div>
                                    <input type="password" class="form-control" id="passwordInput"
                                        placeholder="Ingresa tu contraseña" style="max-width: 265px;">
                                </div>
                                <div class="hstack gap-2 mt-3">
                                    <a href="javascript:void(0);" class="btn btn-soft-primary">
                                        Cerrar y borrar cuenta
                                    </a>
                                </div>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('assets/js/pages/profile-setting.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/pages-profile-settings.blade.php ENDPATH**/ ?>