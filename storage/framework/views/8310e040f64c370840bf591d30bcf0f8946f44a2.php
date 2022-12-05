<?php
	//Recibir los datos del controlador
	$nombre = $data['nombre'];
	$mensaje = $data['mensaje'];
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<style type="text/css">
        
		.ft10{font-size:16px;font-family:Times;color:#bf504d;}
		.ft11{font-size:14px;font-family:Times;color:#000000;}
		.ft12{font-size:14px;font-family:Times;color:#365e91;}
		.ft13{font-size:15px;font-family:Times;color:#000000;}
		.ft14{font-size:18px;font-family:Times;font-weight:bold;color:#000000;}
		.ft15{font-size:14px;font-family:Times;font-weight:bold;color:#000000;}
        
    </style>
</head>

<body>
    <h1> Hola <?php echo e($nombre); ?> </h1>
    <p>
		<?php echo e($mensaje); ?>

    </p>
</body>

</html><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/emailBienvenida.blade.php ENDPATH**/ ?>