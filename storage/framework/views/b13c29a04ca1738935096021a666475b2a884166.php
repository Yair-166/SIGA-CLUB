<?php
        //Obtener los clubes de la base de datos
        $clubs = DB::table('clubes')->get();
        //Obtener los usuarios de la base de datos 
        $users = DB::table('users')->get();
        //Obtener los datos de la tabla inscripciones
        $inscripciones = DB::table('inscripciones')->get();
?>
<?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/components/importaciones.blade.php ENDPATH**/ ?>