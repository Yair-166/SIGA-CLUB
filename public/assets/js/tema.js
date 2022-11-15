//Funcion para cambiar el tema a oscuro
function getThemeMode() {
    //Obtener el valor del atributo data-layout-mode del body
    var themeMode = document.body.getAttribute("data-layout-mode");
    //Si el valor es dark, retornar 1, si es light, retornar 0
    if (themeMode == "dark") {
        cambiarTema(1);
    } else {
        cambiarTema(0);
    }
}

function cambiarTema(ftheme){

    let flagtheme = 0;

    if(ftheme == '1'){
        //cambiar el tema a <body data-layout-mode="light" data-topbar="light" data-sidebar="light">    
        document.body.setAttribute("data-layout-mode", "light");
        document.body.setAttribute("data-topbar", "light");
        document.body.setAttribute("data-sidebar", "light");
        flagtheme = 0;
    }else{
        //cambiar el tema a <body data-layout-mode="dark" data-topbar="dark" data-sidebar="dark">
        document.body.setAttribute("data-layout-mode", "dark");
        document.body.setAttribute("data-topbar", "dark");
        document.body.setAttribute("data-sidebar", "dark");
        flagtheme = 1;
    }

    //Guardar en una cookie el tema
    var d = new Date();
    d.setTime(d.getTime() + (1*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = "tema=" + flagtheme + ";" + expires + ";path=/";
    return 1;

}

function cargarTema(){
    //Obtener los datos de la cookie tema y cargar el tema
    var tema = getcookie("tema");
    if(tema == 1){
        cambiarTema(0);
    }
    else{
        cambiarTema(1);
    }
}

function getcookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}