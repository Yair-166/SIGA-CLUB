@extends('layouts.master')
@section('title') @lang('translation.faqs') @endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') SIGA @endslot
        @slot('title') Preguntas y respuestas @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card rounded-0 bg-soft-primary mx-n4 mt-n4 border-0">
                <div class="px-4">
                    <div class="row">
                        <div class="col-xxl-5 align-self-center">
                            <div class="py-4">
                                <h4 class="display-6 coming-soon-text">Preguntas frecuentes</h4>
                                <p class="text-muted fs-15 mt-3">Si no puede encontrar la respuesta a su pregunta en nuestras preguntas frecuentes, 
                                    siempre puede contactarnos o enviarnos un correo electrónico. ¡Te responderemos en breve!</p>
                                <div class="hstack flex-wrap gap-2">
                                    <a href="mailto: sigaclubtt@gmail.com?subject = Pregunta sobre SIGA-Club"  type="button" class="btn btn-primary btn-label rounded-pill">
                                        <i class="ri-mail-line label-icon align-middle rounded-pill fs-16 me-2">
                                        </i>Enviar correo</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 ms-auto">
                            <div class="mb-n5 pb-1 faq-img d-none d-xxl-block">
                                <img src="{{ URL::asset('assets/images/faq-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="row justify-content-evenly">
                <div class="col-lg-4">
                    <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0 me-1">
                                <i class="ri-question-line fs-24 align-middle text-success me-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0 fw-semibold">Preguntas generales</h5>
                            </div>
                        </div>

                        <div class="accordion accordion-border-box" id="genques-accordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="genques-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseOne" aria-expanded="false" aria-controls="genques-collapseOne">
                                        ¿Quiénes somos?
                                    </button>
                                </h2>
                                <div id="genques-collapseOne" class="accordion-collapse collapse collapsed" aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body">
                                        Somos el TT 2021-B004 de la Escuela Superior de Cómputo del Instituto Politécnico Nacional.
                                        El equipo esta conformado por 2 integrantes y 1 director.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="genques-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseTwo" aria-expanded="false" aria-controls="genques-collapseTwo">
                                        ¿Por qué nace SIGA-Club?
                                    </button>
                                </h2>
                                <div id="genques-collapseTwo" class="accordion-collapse collapse" aria-labelledby="genques-headingTwo" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body">
                                        Por la necesidad de tener un sistema de gestión de asistencias y actividades y sea altamente dirigido a clubes escolares.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="genques-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseThree" aria-expanded="false" aria-controls="genques-collapseThree">
                                        ¿Que los hace diferentes a otros sistemas?
                                    </button>
                                </h2>
                                <div id="genques-collapseThree" class="accordion-collapse collapse" aria-labelledby="genques-headingThree" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body">
                                        Nuesto sistema es altamente dirigido a clubes escolares y es algo que no se había implementado anteriormente, pero, además, es un sistema que se adapta a las necesidades 
                                        de cualquier evento donde se tiene que llevar una gestion tanto de actividades como de asistencias.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="genques-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseFour" aria-expanded="false" aria-controls="genques-collapseFour">
                                        ¿Hotel?
                                    </button>
                                </h2>
                                <div id="genques-collapseFour" class="accordion-collapse collapse" aria-labelledby="genques-headingFour" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body">
                                        Trivago.
                                    </div>
                                </div>
                            </div>
                        </div><!--end accordion-->
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0 me-1">
                                <i class="ri-user-settings-line fs-24 align-middle text-success me-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0 fw-semibold">Manejo de cuenta</h5>
                            </div>
                        </div>

                        <div class="accordion accordion-border-box" id="manageaccount-accordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="manageaccount-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseOne" aria-expanded="false" aria-controls="manageaccount-collapseOne">
                                        ¿Dónde puedo crear una cuenta?
                                    </button>
                                </h2>
                                <div id="manageaccount-collapseOne" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingOne" data-bs-parent="#manageaccount-accordion">
                                    <div class="accordion-body">
                                        En el siguiente enlace. <a href="https://panel.sigaclub.com/register"> Crear cuenta.</a>    
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="manageaccount-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseTwo" aria-expanded="false" aria-controls="manageaccount-collapseTwo">
                                        ¿Qué es una cuenta de administrador?
                                    </button>
                                </h2>
                                <div id="manageaccount-collapseTwo" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingTwo" data-bs-parent="#manageaccount-accordion">
                                    <div class="accordion-body">
                                        Es un tipo de cuenta donde el usuario puede crear clubes y gestionar tanto sus asistencias como sus actividades, además puede generar constancias por actividades a las que los participantes hayan asistido.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="manageaccount-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseThree" aria-expanded="false" aria-controls="manageaccount-collapseThree">
                                        ¿Qué es una cuenta de colaborador?
                                    </button>
                                </h2>
                                <div id="manageaccount-collapseThree" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingThree" data-bs-parent="#manageaccount-accordion">
                                    <div class="accordion-body">
                                        Es un tipo de cuenta donde el usuario se puede inscribir a clubes, ver si existen actividades de dichos clubes y asistir a las mismas.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="manageaccount-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseFour" aria-expanded="false" aria-controls="manageaccount-collapseFour">
                                        ¿Qué es el rol de usuario?
                                    </button>
                                </h2>
                                <div id="manageaccount-collapseFour" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingFour" data-bs-parent="#manageaccount-accordion">
                                    <div class="accordion-body">
                                        Es el rol que toma un participante al asistir a una actividad, este rol lo asigna el administrador del club, y puede ser participante o coordinador de una actividad.    
                                    </div>
                                </div>
                            </div>
                        </div><!--end accordion-->
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0 me-1">
                                <i class="ri-shield-keyhole-line fs-24 align-middle text-success me-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0 fw-semibold">Privacidad y seguridad</h5>
                            </div>
                        </div>

                        <div class="accordion accordion-border-box" id="privacy-accordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="privacy-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseOne" aria-expanded="false" aria-controls="privacy-collapseOne">
                                        ¿Dónde se encuentran las politicas?
                                    </button>
                                </h2>
                                <div id="privacy-collapseOne" class="accordion-collapse collapse collapsed" aria-labelledby="privacy-headingOne" data-bs-parent="#privacy-accordion">
                                    <div class="accordion-body">
                                        Al estar dentro de una cuenta, dar click sobre el nombre y foto, luego en "Configuraciones" y por ultimo en "Politicas de privacidad".
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="privacy-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseTwo" aria-expanded="false" aria-controls="privacy-collapseTwo">
                                        ¿Mi información esta segura?
                                    </button>
                                </h2>
                                <div id="privacy-collapseTwo" class="accordion-collapse collapse" aria-labelledby="privacy-headingTwo" data-bs-parent="#privacy-accordion">
                                    <div class="accordion-body">
                                        Sí, tu información es segura, ya que esta se encuentra encriptada en la base de datos, además se cuenta con un certificado SSL, que es un certificado digital que autentica 
                                        la identidad de un sitio web y habilita una conexión cifrada.    
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="privacy-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseThree" aria-expanded="false" aria-controls="privacy-collapseThree">
                                        ¿Usan cookies?
                                    </button>
                                </h2>
                                <div id="privacy-collapseThree" class="accordion-collapse collapse" aria-labelledby="privacy-headingThree" data-bs-parent="#privacy-accordion">
                                    <div class="accordion-body">
                                        Sí, usamos cookies para mejorar la experiencia de usuario.	
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="privacy-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseFour" aria-expanded="false" aria-controls="privacy-collapseFour">
                                        ¿Puedo eliminar mi información de contacto de la base de datos?
                                    </button>
                                </h2>
                                <div id="privacy-collapseFour" class="accordion-collapse collapse" aria-labelledby="privacy-headingFour" data-bs-parent="#privacy-accordion">
                                    <div class="accordion-body">
                                        Por supuesto que se puede eliminar la información de contacto de una base de datos, para ello se debe dar click en el nombre y foto, luego en "Configuraciones" y por ultimo se debe 
                                        escribir la contraseña y dar click en "Eliminar cuenta".
                                    </div>
                                </div>
                            </div>
                        </div><!--end accordion-->
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
