@php
	//Recibir los datos del controlador
	$nombre = $data['nombre'];
	$mensaje = $data['mensaje'];
@endphp

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
        .button {
			background-color: #4CAF50; /* Green */
			border: none;
			color: white;
			padding: 15px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
		}
    </style>
</head>

<body>
    <h1> Bienvenido {{ $nombre }} </h1>
    <p>
		Para continuar verifica tu cuenta haciendo click en el siguiente enlace:
	</p>
	<a type="button" class="button" id="button" href="{{ $mensaje }}">Verificar</a>
</body>

</html>