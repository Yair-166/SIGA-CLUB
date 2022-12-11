<script src="{{ URL::asset('assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/node-waves/node-waves.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/feather-icons/feather-icons.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/plugins/lord-icon-2.1.0.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/tema.js') }}"></script>
<script>
    function atras() {
        //Obtener la ruta actual
        var url = window.location.pathname;
        console.log(url);
        if(url != '/' && url != '/index'){
            //Enviar a la ruta anterior
            window.history.back();
        }
    }
</script>
@yield('script')
@yield('script-bottom')
