
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.project-list'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Clubes
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Eventos
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <?php
        //Obtener la variable club de la url
        $getClub = $_GET['club'];
        //Si $getClub es igual a "all" entonces mostrar todos los eventos de los clubes del usuario
        if($getClub == "all"){
            $clubes = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->get();
            //Guardar en un array los ids de los clubes
            $clubesIds = array();
            foreach ($clubes as $club) {
                array_push($clubesIds, $club->id);
            }
            //Obtener todos los eventos de los clubes del usuario
            $eventos = DB::table('eventos')->whereIn('id_club', $clubesIds)->get();
        }else{
            //Si no, mostrar los eventos donde el id_club sea igual a $getClub
            $eventos = DB::table('eventos')->where('id_club', $getClub)->get();
        }
    ?>

    

    <div class="row">
        <?php $__currentLoopData = $eventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                //Obtener el club del evento
                $club = DB::table('clubes')->where('id', $evento->id_club)->first();
            ?>
            <div class="col-xxl-3 col-sm-6 project-card">
                <div class="card">
                    <div class="card-body">
                        <div class="p-3 mt-n3 mx-n3 bg-soft-primary rounded-top">
                            <div class="text-center pb-3">
                                <img class="rounded-circle" src="<?php echo e(URL::asset('images/'.$club->foto)); ?>" alt="" height="55">
                            </div>
                        </div>

                        <div class="py-3">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1 fs-15"><a href="<?php echo e(URL::asset('/apps-eventos-overview?evento='.$evento->id)); ?>"
                                            class="text-dark"><?php echo e($evento->nombre); ?></a></h5>
                                    <p class="text-muted text-truncate-two-lines mb-3">
                                        <?php echo e($evento->descripcion); ?>

                                    </p>
                                </div>
                            </div>
                    </div>
                    <!-- end card body -->
                    <div class="card-footer bg-transparent border-top-dashed py-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="text-muted">
                                    <i class="ri-calendar-event-fill me-1 align-bottom"></i> 
                                    <?php
                                        //Verificar si la fecha de inicio es igual a la fecha de fin
                                        if($evento->fechaInicio == $evento->fechaFin){
                                            //Si es igual, mostrar solo la fecha de inicio
                                            echo date("d/m/Y", strtotime($evento->fechaInicio));
                                        }else{
                                            //Si no, mostrar la fecha de inicio y la fecha de fin
                                            echo date("d/m/Y", strtotime($evento->fechaInicio))." al ".date("d/m/Y", strtotime($evento->fechaFin));
                                        }
                                    ?>
                                </div>
                            </div>

                        </div>

                    </div>
                    <!-- end card footer -->
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <!-- end row -->

    

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('assets/js/pages/project-list.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/apps-eventos-list.blade.php ENDPATH**/ ?>