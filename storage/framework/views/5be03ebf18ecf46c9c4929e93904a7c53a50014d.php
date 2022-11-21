
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
        //Obtener variable club de la url
        $getClub = $_GET['club'];

        if($getClub == "all"){
            //Obtener todos los clubes cuyo idAdministrador sea igual al id del usuario logueado
            $clubes = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->get();
            //Obtener un array con los id de los clubes del usuario logueado
            $clubesId = array();
            foreach ($clubes as $club) {
                array_push($clubesId, $club->id);
            }
            //Obtener todas las inscripciones cuyo idClub sea igual a alguno de los id de los clubes del usuario logueado
            $inscripciones = DB::table('inscripciones')->whereIn('id_club', $clubesId)->get();
        }
        else{
            //Obtener el club cuyo id sea igual al id del club de la url
            $clubes = DB::table('clubes')->where('id', $getClub)->first();
            //Obtener todas las inscripciones cuyo idClub sea igual al id del club de la url
            $inscripciones = DB::table('inscripciones')->where('id_club', $getClub)->get();
        }

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
                <!--end col-->
                <div class="col-sm-auto ms-auto">
                    <div class="list-grid-nav hstack gap-1">
                        <button type="button" id="grid-view-button"
                            class="btn btn-soft-primary nav-link btn-icon fs-14 active filter-button"><i
                                class="ri-grid-fill"></i></button>
                        <button type="button" id="list-view-button"
                            class="btn btn-soft-primary nav-link  btn-icon fs-14 filter-button"><i
                                class="ri-list-unordered"></i></button>
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
                                                    <h3 class="fs-16 mb-1"><?php echo e($participante->name); ?></h3>
                                                    <h3 class="fs-16 mb-1"><?php echo e($participante->apaterno); ?> <?php echo e($participante->amaterno); ?></h3>
                                                    <p class="text-muted mb-0"><?php echo e($club->nombre); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col">
                                            <div class="text-end">
                                                <a href="pages-profile-view?uid=<?php echo e($inscripcion->id); ?>" class="btn btn-light view-btn">Ver participaciones</a>
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