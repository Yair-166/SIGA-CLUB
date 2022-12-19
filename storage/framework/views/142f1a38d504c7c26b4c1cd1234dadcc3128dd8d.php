

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Constancia válida'); ?>
<?php $__env->stopSection(); ?>

<?php
    //Obtener la variable de la ruta
    $id = request()->route('id');
    //Obtener la asistencia por el id de la base de datos
    $asistencia = DB::table('asistencias')->where('id', $id)->first();

    //Checar si la asistencia existe
    if($asistencia == null){

        echo "<center>";
        echo "<FONT FACE='impact' SIZE=6 COLOR='red'>
                Asistencia invalida
            </FONT>";
            //imprimir imagen que esta en /public/assets/images/tiste.png
        echo "</br>";
        echo "<img src='https://sigaclub.com/assets/images/tiste.png' height='600'>";
        echo "</br>";
        echo "</center>";
        exit();
    }

    //Obtener el evento de la tabla eventos con el id del evento de la tabla asistencias
    $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
    //Obtener el club con el id_club de la tabla eventos
    $club = DB::table('clubes')->where('id', $evento->id_club)->first();
    //Obtener el usuario con el idAdministrador de la tabla clubes
    $admin = DB::table('users')->where('id', $club->idAdministrador)->first();
    $ipn = false;
    //Verificar $admin->boleta es un campo vacio
    if($admin->boleta != null){
        //Si es vacio, se le asigna un valor por default
        $ipn = true;
    }
    //Obtener los datos del usuario con el idUsuario de la tabla asistencias
    $usuario = DB::table('users')->where('id', $asistencia->idUsuario)->first();
    
    
?>

<?php $__env->startSection('body'); ?>

    <body>
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('content'); ?>
        <div class="auth-page-wrapper pt-5">
            <!-- auth page bg -->
            <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
                <div class="bg-overlay"></div>

                <div class="shape">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 1440 120">
                        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                    </svg>
                </div>
            </div>

            <!-- auth page content -->
            <div class="auth-page-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center pt-4">
                                <div class="">
                                    <?php if($ipn): ?>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_IPN.png" alt="" width="40%">
                                        <img src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" alt="" class="move-animation" width="40%"" style="inline-block">
                                    <?php else: ?>
                                        <img src="<?php echo e(URL::asset('images/' . $club->foto)); ?>" alt="" class="error-basic-img move-animation">
                                    <?php endif; ?>
                                </div>
                                <div class="mt-n4">
                                    <h1 class="display-1 fw-medium">Constancia válida</h1>
                                    <h3 class="text-uppercase"><?php echo e($club->nombre); ?> || <?php echo e($evento->nombre); ?></h3>
                                    <h4 class="text-muted mb-4">
                                        <?php echo e($usuario->name); ?> <?php echo e($usuario->apaterno); ?> <?php echo e($usuario->amaterno); ?> 
                                        <i class="mdi mdi-clock-fast text-success"></i>
                                        <?php echo e($asistencia->asistenciaTotal); ?> horas
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                </div>
                <!-- end container -->
            </div>
            <!-- end auth page content -->

            <!-- footer -->
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <p class="mb-0 text-muted">&copy;
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script> SIGA-CLUB <i class="mdi mdi-heart text-danger"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>
        <!-- end auth-page-wrapper -->
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('script'); ?>
        <!-- particles js -->
        <script src="<?php echo e(URL::asset('assets/libs/particles.js/particles.js.min.js')); ?>"></script>
        <!-- particles app js -->
        <script src="<?php echo e(URL::asset('assets/js/pages/particles.app.js')); ?>"></script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/checkasistencia.blade.php ENDPATH**/ ?>