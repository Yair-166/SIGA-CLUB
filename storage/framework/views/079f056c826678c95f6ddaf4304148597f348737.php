<?php
    //Obtener dia actual
    $dia = date('d');
    //Obtener mes actual
    $mes = date('m');
    //Obtener el año actual
    $anio = date('Y');
    //Obtener los datos de la base de datos de la tabla asistencias con el id
    $asistencia = DB::table('asistencias')->where('id', $id)->first();
    //Obtener el evento de la tabla eventos con el id del evento de la tabla asistencias
    $evento = DB::table('eventos')->where('id', $asistencia->idEvento)->first();
    //Obtener el club con el id_club de la tabla eventos
    $club = DB::table('clubes')->where('id', $evento->id_club)->first();
    //Obtener los datos del usuario con el idUsuario de la tabla asistencias
    $usuario = DB::table('users')->where('id', $asistencia->idUsuario)->first();
    
?>

<html>

<head>

</head>

<body lang=ES-MX style='word-wrap:break-word'>

    <div class=WordSection1>

        <p class=MsoTitle align=left style='margin-left:0cm;text-align:left'>
            <span lang=ES style='font-size:36.0pt'>Club-&gt;foto</span>
        </p>

        <p class=MsoTitle align=left style='margin-left:0cm;text-align:left'>
            <span lang=ES style='font-size:36.0pt'>$club-&gt;nombre</span>
        </p>

        <span lang=ES style='font-size:17.0pt;line-height:146%;font-family:"Times New Roman",serif'>
            <br clear=all>
        </span>

    <p class=MsoBodyText align=right style='margin-top:12.0pt;margin-right:4.9pt;
    margin-bottom:0cm;margin-left:6.95pt;margin-bottom:.0001pt;text-align:right;
    text-indent:21.5pt;line-height:146%'><span lang=ES style='color:#6D6D6D'>N�mero
    de constancia</span><span lang=ES style='color:#6E6E6E'>:<span
    style='letter-spacing:.05pt'> </span></span><span lang=ES style='color:#6D6D6D'>$constancia-&gt;id</span></p>

    <p class=MsoNormal align=right style='text-align:right'><span lang=ES>&nbsp;</span></p>

    <p class=MsoNormal align=right style='text-align:right'><span lang=ES>&nbsp;</span></p>

    <p class=MsoNormal align=right style='text-align:right'><span lang=ES>&nbsp;</span></p>

    <p class=MsoNormal align=right style='text-align:right'><span lang=ES>&nbsp;</span></p>

    <p class=MsoNormal align=right style='text-align:right'><span lang=ES>&nbsp;</span></p>

    <p class=MsoNormal align=right style='text-align:right'><span lang=ES>&nbsp;</span></p>

    <p class=MsoNormal align=right style='text-align:right'><span lang=ES>C�digo qr</span></p>

    <p class=MsoNormal align=right style='text-align:right'><span lang=ES>&nbsp;</span></p>

    </div>

    <span lang=ES style='font-size:11.0pt;font-family:"Times New Roman",serif'><br
    clear=all style='page-break-before:auto'>
    </span>

    <div class=WordSection2>

    <p class=MsoBodyText><span lang=ES style='font-size:10.0pt'>&nbsp;</span></p>

    <p class=MsoBodyText><span lang=ES style='font-size:10.0pt'>&nbsp;</span></p>

    <p class=MsoBodyText style='margin-top:.4pt'><span lang=ES style='font-size:
    13.5pt'>&nbsp;</span></p>

    <p class=MsoNormal style='margin-top:4.2pt;margin-right:0cm;margin-bottom:0cm;
    margin-left:7.1pt;margin-bottom:.0001pt'><span lang=ES style='font-size:22.5pt;
    color:#6D6D6D'>CERTIFICADO DE PARTICIPACI�N</span></p>

    <p class=MsoNormal style='margin-top:21.0pt;margin-right:127.15pt;margin-bottom:
    0cm;margin-left:8.05pt;margin-bottom:.0001pt;text-indent:.1pt;line-height:91%'><span
    lang=ES style='font-size:100.0pt;line-height:91%'>$evento-&gt;nombre</span></p>

    <p class=MsoNormal style='margin-top:29.5pt;margin-right:0cm;margin-bottom:
    0cm;margin-left:8.35pt;margin-bottom:.0001pt'><span lang=ES style='font-size:
    23.0pt;color:#1C1C1C'>Impartido por: $user-&gt;name where id (evento-&gt;id_administrador)</span></p>

    <p class=MsoBodyText><span lang=ES style='font-size:26.0pt'>&nbsp;</span></p>

    <p class=MsoBodyText><span lang=ES style='font-size:26.0pt'>&nbsp;</span></p>

    <p class=MsoBodyText><span lang=ES style='font-size:26.0pt'>&nbsp;</span></p>

    <p class=MsoBodyText><span lang=ES style='font-size:26.0pt'>&nbsp;</span></p>

    <p class=MsoBodyText><span lang=ES style='font-size:26.0pt'>&nbsp;</span></p>

    <p class=MsoBodyText><span lang=ES style='font-size:26.0pt'>&nbsp;</span></p>

    <p class=MsoNormal style='margin-top:16.75pt;margin-right:0cm;margin-bottom:
    0cm;margin-left:5.35pt;margin-bottom:.0001pt'><span style='position:absolute;
    z-index:-1659928576;left:0px;margin-left:152px;margin-top:55px;width:15px;
    height:1px'><img width=15 height=1 src="constancia_archivos/image001.jpg"></span><span
    lang=ES style='font-size:74.0pt;font-family:"Calibri",sans-serif;color:#1A1A1A'>$user-&gt;nombre</span></p>

    <p class=MsoNormal style='margin-top:17.7pt;margin-right:1036.4pt;margin-bottom:
    0cm;margin-left:8.5pt;margin-bottom:.0001pt;line-height:121%'><span lang=ES
    style='font-size:26.0pt;line-height:121%;color:#1A1A1A'>Fecha: </span><b><span
    lang=ES style='font-size:26.0pt;line-height:121%;color:#1C1C1C'>$evento-&gt;fechaInicio</span></b><span
    lang=ES style='font-size:26.0pt;line-height:121%;color:#1A1A1A;letter-spacing:
    -6.7pt'> ���</span></p>

    <p class=MsoNormal style='margin-top:17.7pt;margin-right:1036.4pt;margin-bottom:
    0cm;margin-left:8.5pt;margin-bottom:.0001pt;line-height:121%'><span lang=ES
    style='font-size:26.0pt;line-height:121%;color:#1A1A1A'>D</span><span lang=ES
    style='font-size:26.0pt;line-height:121%;color:#1A1A1A'>uraci�n: <b>29.5<span
    style='letter-spacing:.35pt'> </span>horas<span style='letter-spacing:-.4pt'> </span></b></span><b><span
    lang=ES style='font-size:26.0pt;line-height:121%;color:#1C1C1C'>en<span
    style='letter-spacing:-.5pt'> </span></span></b><b><span lang=ES
    style='font-size:26.0pt;line-height:121%;color:#1A1A1A'>total</span></b></p>

    </div>

</body>

</html>
<?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/generar-constancia-externa.blade.php ENDPATH**/ ?>