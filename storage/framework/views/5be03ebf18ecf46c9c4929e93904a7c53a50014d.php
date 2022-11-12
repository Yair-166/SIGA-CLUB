
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.team'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Clubes
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Participantes
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php
        //Obtener todos los clubes cuyo idAdministrador sea igual al id del usuario logueado
        $clubes = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->get();
        //Obtener un array con los id de los clubes del usuario logueado
        $clubesId = array();
        foreach ($clubes as $club) {
            array_push($clubesId, $club->id);
        }
        //Obtener todas las inscripciones cuyo idClub sea igual a alguno de los id de los clubes del usuario logueado
        $inscripciones = DB::table('inscripciones')->whereIn('id_club', $clubesId)->get();
        //Eliminar createdAt y updatedAt de $inscripciones
        foreach ($inscripciones as $inscripcion) {
            unset($inscripcion->created_at);
            unset($inscripcion->updated_at);
        }
        //print_r($inscripciones);
        //Obtener un array con los id de los participantes de las inscripciones del usuario logueado
        $participantesId = array();
        foreach ($inscripciones as $inscripcion) {
            array_push($participantesId, $inscripcion->id_alumno);
        }
    ?>

    <div class="card">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-sm-4">
                    <div class="search-box">
                        <input type="text" class="form-control"
                            placeholder="Search for name, tasks, projects or something...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
                <div class="col-sm-auto ms-auto">
                    <div class="list-grid-nav hstack gap-1">
                        <button type="button" id="grid-view-button"
                            class="btn btn-soft-primary nav-link btn-icon fs-14 active filter-button"><i
                                class="ri-grid-fill"></i></button>
                        <button type="button" id="list-view-button"
                            class="btn btn-soft-primary nav-link  btn-icon fs-14 filter-button"><i
                                class="ri-list-unordered"></i></button>
                        <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false"
                            class="btn btn-soft-secondary btn-icon fs-14"><i class="ri-more-2-fill"></i></button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                            <li><a class="dropdown-item" href="#">All</a></li>
                            <li><a class="dropdown-item" href="#">Last Week</a></li>
                            <li><a class="dropdown-item" href="#">Last Month</a></li>
                            <li><a class="dropdown-item" href="#">Last Year</a></li>
                        </ul>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmembers"><i
                                class="ri-add-fill me-1 align-bottom"></i> Add Members</button>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="team-list grid-view-filter row">                    
                    <?php $__currentLoopData = $inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inscripcion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <?php
                            //obtener el id del participante de la inscripcion
                            $idParticipante = $inscripcion->id_alumno;
                            //Obtener los datos del participante
                            $participante = DB::table('users')->where('id', $idParticipante)->first();
                            //Obtener el club al que pertenece el participante
                            $club = DB::table('clubes')->where('id', $inscripcion->id_club)->first();
                        ?>
                        <div class="col">
                            <div class="card team-box">
                                <div class="team-cover">
                                    <img src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" alt="" class="img-fluid" />
                                </div>
                                <div class="card-body p-4">
                                    <div class="row align-items-center team-row">
                                        <div class="col team-settings">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="bookmark-icon flex-shrink-0 me-2">
                                                        <input type="checkbox" id="favourite1"
                                                            class="bookmark-input bookmark-hide">
                                                        <label for="favourite1" class="btn-star">
                                                            <svg width="20" height="20">
                                                                <use xlink:href="#icon-star" />
                                                            </svg>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col text-end dropdown">
                                                    <a href="javascript:void(0);" id="dropdownMenuLink2"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill fs-17"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="dropdownMenuLink2">
                                                        <li><a class="dropdown-item" href="javascript:void(0);"><i
                                                                    class="ri-eye-line me-2 align-middle"></i>Ver</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0);"><i
                                                                    class="ri-delete-bin-5-line me-2 align-middle"></i>Dar de baja</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col">
                                            <div class="team-profile-img">
                                                <div class="avatar-lg img-thumbnail rounded-circle flex-shrink-0">
                                                    <img src="<?php echo e(URL::asset('images/' . $participante->avatar)); ?>" alt=""
                                                        class="img-fluid d-block rounded-circle" />
                                                </div>
                                                <div class="team-content">
                                                    <a data-bs-toggle="offcanvas" href="#offcanvasExample"
                                                        aria-controls="offcanvasExample">
                                                        <h5 class="fs-16 mb-1"><?php echo e($participante->name); ?></h5>
                                                    </a>
                                                    <p class="text-muted mb-0"><?php echo e($club->nombre); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col">
                                            <div class="text-end">
                                                <a href="<?php echo e(URL::asset('/pages-profile')); ?>" class="btn btn-light view-btn">Ver participaciones</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="col-lg-12">
                        <div class="text-center mb-3">
                            <a href="javascript:void(0);" class="text-primary"><i
                                    class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> Load More </a>
                        </div>
                    </div>
                </div>
                <!--end row-->

                <!-- Modal -->
                <div class="modal fade" id="addmembers" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <div class="modal-header bg-soft-primary p-3">
                                <h5 class="modal-title" id="myModalLabel">Add New Members</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="teammembersName" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="teammembersName"
                                                    placeholder="Enter name">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="designation" class="form-label">Designation</label>
                                                <input type="text" class="form-control" id="designation"
                                                    placeholder="Enter designation">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="totalProjects" class="form-label">Projects</label>
                                                <input type="number" class="form-control" id="totalProjects"
                                                    placeholder="Total projects">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="totalTasks" class="form-label">Tasks</label>
                                                <input type="number" class="form-control" id="totalTasks"
                                                    placeholder="Total tasks">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-4">
                                                <label for="formFile" class="form-label">Profile Images</label>
                                                <input class="form-control" type="file" id="formFile">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Add Member</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--end modal-content-->
                    </div>
                    <!--end modal-dialog-->
                </div>
                <!--end modal-->

                <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="offcanvasExample">
                    <!--end offcanvas-header-->
                    <div class="offcanvas-body profile-offcanvas p-0">
                        <div class="team-cover">
                            <img src="<?php echo e(URL::asset('assets/images/small/img-9.jpg')); ?>" alt="" class="img-fluid" />
                        </div>
                        <div class="p-3">
                            <div class="team-settings">
                                <div class="row">
                                    <div class="col">
                                        <div class="bookmark-icon flex-shrink-0 me-2">
                                            <input type="checkbox" id="favourite13" class="bookmark-input bookmark-hide">
                                            <label for="favourite13" class="btn-star">
                                                <svg width="20" height="20">
                                                    <use xlink:href="#icon-star" />
                                                </svg>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col text-end dropdown">
                                        <a href="javascript:void(0);" id="dropdownMenuLink14" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="ri-more-fill fs-17"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink14">
                                            <li><a class="dropdown-item" href="javascript:void(0);"><i
                                                        class="ri-eye-line me-2 align-middle"></i>View</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);"><i
                                                        class="ri-star-line me-2 align-middle"></i>Favorites</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);"><i
                                                        class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <div class="p-3 text-center">
                            <img src="<?php echo e(URL::asset('assets/images/users/avatar-2.jpg')); ?>" alt=""
                                class="avatar-lg img-thumbnail rounded-circle mx-auto">
                            <div class="mt-3">
                                <h5 class="fs-15"><a href="javascript:void(0);" class="link-primary">Nancy
                                        Martino</a></h5>
                                <p class="text-muted">Team Leader & HR</p>
                            </div>
                            <div class="hstack gap-2 justify-content-center mt-4">
                                <div class="avatar-xs">
                                    <a href="javascript:void(0);"
                                        class="avatar-title bg-soft-secondary text-secondary rounded fs-16">
                                        <i class="ri-facebook-fill"></i>
                                    </a>
                                </div>
                                <div class="avatar-xs">
                                    <a href="javascript:void(0);"
                                        class="avatar-title bg-soft-success text-success rounded fs-16">
                                        <i class="ri-slack-fill"></i>
                                    </a>
                                </div>
                                <div class="avatar-xs">
                                    <a href="javascript:void(0);"
                                        class="avatar-title bg-soft-info text-info rounded fs-16">
                                        <i class="ri-linkedin-fill"></i>
                                    </a>
                                </div>
                                <div class="avatar-xs">
                                    <a href="javascript:void(0);"
                                        class="avatar-title bg-soft-danger text-danger rounded fs-16">
                                        <i class="ri-dribbble-fill"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row g-0 text-center">
                            <div class="col-6">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1">124</h5>
                                    <p class="text-muted mb-0">Projects</p>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-6">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1">81</h5>
                                    <p class="text-muted mb-0">Tasks</p>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                        <div class="p-3">
                            <h5 class="fs-15 mb-3">Personal Details</h5>
                            <div class="mb-3">
                                <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Number</p>
                                <h6>+(256) 2451 8974</h6>
                            </div>
                            <div class="mb-3">
                                <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Email</p>
                                <h6>nancymartino@email.com</h6>
                            </div>
                            <div>
                                <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Location</p>
                                <h6 class="mb-0">Carson City - USA</h6>
                            </div>
                        </div>
                        <div class="p-3 border-top">
                            <h5 class="fs-15 mb-4">File Manager</h5>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 avatar-xs">
                                    <div class="avatar-title bg-soft-danger text-danger rounded fs-16">
                                        <i class="ri-image-2-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1"><a href="javascript:void(0);"
                                            class="link-secondary">Images</a></h6>
                                    <p class="text-muted mb-0">4469 Files</p>
                                </div>
                                <div class="text-muted">
                                    12 GB
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 avatar-xs">
                                    <div class="avatar-title bg-soft-secondary text-secondary rounded fs-16">
                                        <i class="ri-file-zip-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1"><a href="javascript:void(0);"
                                            class="link-secondary">Documents</a></h6>
                                    <p class="text-muted mb-0">46 Files</p>
                                </div>
                                <div class="text-muted">
                                    3.46 GB
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 avatar-xs">
                                    <div class="avatar-title bg-soft-success text-success rounded fs-16">
                                        <i class="ri-live-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1"><a href="javascript:void(0);"
                                            class="link-secondary">Media</a></h6>
                                    <p class="text-muted mb-0">124 Files</p>
                                </div>
                                <div class="text-muted">
                                    4.3 GB
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs">
                                    <div class="avatar-title bg-soft-primary text-primary rounded fs-16">
                                        <i class="ri-error-warning-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1"><a href="javascript:void(0);"
                                            class="link-secondary">Others</a></h6>
                                    <p class="text-muted mb-0">18 Files</p>
                                </div>
                                <div class="text-muted">
                                    846 MB
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end offcanvas-body-->
                    <div class="offcanvas-foorter border p-3 hstack gap-3 text-center position-relative">
                        <button class="btn btn-light w-100"><i class="ri-question-answer-fill align-bottom ms-1"></i> Send
                            Message</button>
                        <a href="<?php echo e(URL::asset('/pages-profile')); ?>" class="btn btn-primary w-100"><i
                                class="ri-user-3-fill align-bottom ms-1"></i> View Profile</a>
                    </div>
                </div>
                <!--end offcanvas-->
            </div>
        </div><!-- end col -->
    </div>
    <!--end row-->

    <svg class="bookmark-hide">
        <symbol viewBox="0 0 24 24" stroke="currentColor" fill="var(--color-svg)" id="icon-star">
            <path stroke-width=".4"
                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
            </path>
        </symbol>
    </svg>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('assets/js/pages/team.init.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/pages-team.blade.php ENDPATH**/ ?>