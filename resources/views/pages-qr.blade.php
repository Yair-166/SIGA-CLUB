@extends('layouts.master')
@section('title')
    @lang('translation.sitemap')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Evento
        @endslot
        @slot('title')
            QR
        @endslot
    @endcomponent
    @php
        //Obtener el id del evento de la url
        $idEvento = $_GET['uid'];
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-header ">
                    <h4 class="card-title mb-0">Escanea el QR para pasar tu asistencia</h4>
                </div>
                <div class="card-body">
                    <div class="sitemap-content">
                        <div class="visible-print text-center">
                            @php
                                //Obtener la primer fila de la tabla confi_eventos donde el idEvento sea ia igual a $idEvento
                                $evento = DB::table('confi_eventos')->where('idEvento', $idEvento)->first();

                                $anterior = $evento->qrActual;
                                $qrActual = rand(100000, 999999);

                                //Actualizar el campo qrActual de la tabla confi_eventos
                                DB::table('confi_eventos')->where('idEvento', $idEvento)->update(['qrActual' => $qrActual, 'ultimoQR' => $anterior]);

                                $qr = QrCode::size(400)->margin(0)->generate("http://panel.sigaclub.com/asistencias?evento=".$idEvento."&token=".$qrActual);
                                echo $qr;

                            @endphp
                                <meta http-equiv="refresh" content="20">
                        </div>
                    </div>
                    <!--end sitemap-content-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->

@endsection
@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
