
<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.calendar'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('/assets/libs/fullcalendar/fullcalendar.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Apps <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Calendario <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <?php
        //Obtener los clubes donde idAdmin = id del usuario logueado
        $clubes = DB::table('clubes')->where('idAdministrador', Auth::user()->id)->get();
        //Obtener los eventos de cada club y guardarlos en un solo string
        $eventos = "";
        foreach ($clubes as $club) {
            $eventos .= DB::table('eventos')->where('id_club', $club->id)->get();
        }
        //Dar formato de json a los eventos
        $eventosj = json_encode($eventos);
        //Eliminar la primera y ultima llave
        $eventosj = str_replace("[{", "{", $eventosj);
        $eventosj = str_replace("}]", "}", $eventosj);

        $eventosj = str_replace("}{", "}],[{", $eventosj);

        //Eliminar el ][ entre cada evento
        //$eventosj = str_replace("][", ",", $eventosj);
        echo $eventosj;

        //Hasta aquiiiiii

        //echo $eventos;
        //Quitar los corchetes de apertura y cierre de cada evento
        $eventos = str_replace("[", "", $eventos);
        $eventos = str_replace("]", "", $eventos);
        //Agregar comas entre cada evento
        $eventos = str_replace("}", "},", $eventos);
        //Eliminar los espacios entre cada evento
        $eventos = str_replace("}, ", "},", $eventos);
        
        //echo $eventos;
        
    ?>
    <input type="hidden" id="eventos_usr" value="<?php echo e($eventosj); ?>"/>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <button class="btn btn-primary w-100" id="btn-new-event"><i
                                    class="mdi mdi-plus"></i> Crear evento</button>

                            <div id="external-events">
                                <br>
                                <p class="text-muted">Arrastra y suelta tu evento o haz clic en el calendario</p>
                                <div class="external-event fc-event bg-soft-success text-success" data-class="bg-soft-success">
                                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>planificación de nuevo evento 
                                </div>
                                <div class="external-event fc-event bg-soft-info text-info" data-class="bg-soft-info">
                                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>Reunion
                                </div>
                                <div class="external-event fc-event bg-soft-warning text-warning" data-class="bg-soft-warning">
                                    <i
                                        class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>Generación de reportes
                                </div>
                                <div class="external-event fc-event bg-soft-danger text-danger" data-class="bg-soft-danger">
                                    <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>Crear nuevo tema
                                </div>
                            </div>

                        </div>
                    </div>
                    <div>
                        <h5 class="mb-1">Proximos eventos</h5>
                        <p class="text-muted">No te pierdas los eventos programados</p>
                        <div class="pe-2 me-n1 mb-3" data-simplebar style="height: 400px">
                            <div id="upcoming-event-list"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body bg-soft-info">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i data-feather="calendar" class="text-info icon-dual-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-15">Bienvenido al calendario</h6>
                                    <p class="text-muted mb-0">Los eventos que agende se mostrarán aqui. Haga clic en un evento para ver los detalles y administrar el evento de los solicitantes.</p>
                                </div>
                            </div>
                        </div>
                    </div><!--end card-->
                </div> <!-- end col-->

                <div class="col-xl-9">
                    <div class="card card-h-100">
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div><!-- end col -->
            </div><!--end row-->

            <div style='clear:both'></div>

            <!-- Add New Event MODAL -->
            <div class="modal fade" id="event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class="modal-header p-3 bg-soft-info">
                            <h5 class="modal-title" id="modal-title">Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body p-4">

                            <form action="<?php echo e(route('creaEvento')); ?>" method="POST" class="needs-validation" name="event-form" id="form-event" >
                            <?php echo csrf_field(); ?>
                                <div class="text-end">
                                    <a href="#" class="btn btn-sm btn-soft-primary" id="edit-event-btn" data-id="edit-event" onclick="editEvent(this)" role="button">Edit</a>
                                </div>
                                <div class="event-details">
                                    <div class="d-flex mb-2">
                                        <div class="flex-grow-1 d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="ri-calendar-event-line text-muted fs-16"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="d-block fw-semibold mb-0" id="event-start-date-tag"></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="ri-time-line text-muted fs-16"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="d-block fw-semibold mb-0"><span id="event-timepicker1-tag"></span> - <span id="event-timepicker2-tag"></span></h6>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="ri-map-pin-line text-muted fs-16"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="d-block fw-semibold mb-0"> <span id="event-location-tag"></span></h6>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="ri-discuss-line text-muted fs-16"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="d-block text-muted mb-0" id="event-description-tag"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row event-form">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Club</label>
                                            <select class="form-select" name="id_club" id="event-club" required>
                                                <?php $__currentLoopData = $clubes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($club->id); ?>"><?php echo e($club->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        <div class="invalid-feedback">Por favor, seleccione un club</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Tipo</label>
                                            <select class="form-select" name="category" id="event-category" required>
                                                <option value="Clase">Clase</option>
                                                <option value="Campamento">Campamento</option>
                                                <option value="Curso">Curso</option>
                                                <option value="Seminario">Seminario</option>
                                                <option value="Entrenamiento">Entrenamiento</option>
                                                <option value="Evaluación">Evaluación</option>
                                                <option value="Concurso">Concurso</option>
                                                <option value="Torneo">Torneo</option>
                                                <option value="Conferencia">Conferencia</option>
                                                <option value="Exposición">Exposición</option>
                                                <option value="Exhibición">Exhibición</option>
                                            </select>
                                            <div class="invalid-feedback">Por favor, seleccione una categoria valida</div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Modalidad</label>
                                            <select class="form-select" name="modalidad" id="event-modalidad" required>
                                                <option value="Presencial">Presencial</option>
                                                <option value="A distancia">A distancia</option>
                                                <option value="Híbrida">Híbrida</option>
                                            </select>
                                            <div class="invalid-feedback">Por favor, seleccione una modalidad valida</div>
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre del evento</label>
                                            <input class="form-control d-none" placeholder="Nombre del evento" type="text" name="title" id="event-title" required/>
                                            <div class="invalid-feedback">Proporcione un nombre de evento válido</div>
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Tipo de asitencia</label>
                                            <select class="form-select" name="tipoAsistencia" id="event-astype" required>
                                                <option value="Total">Total</option>
                                                <option value="Parcial">Parcial</option>
                                            </select>
                                            <div class="invalid-feedback">Por favor, seleccione un tipo valido</div>
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label>Fecha del evento</label>
                                            <div class="input-group d-none">
                                                <input name="fecha" type="text" id="event-start-date" class="form-control flatpickr flatpickr-input" placeholder="Seleccione una fecha" readonly required>
                                                <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                                            </div>
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-12" id="event-time">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Hora de inicio</label>
                                                    <div class="input-group d-none">
                                                        <input name="horaInicio" id="timepicker1" type="text"
                                                            class="form-control flatpickr flatpickr-input" placeholder="Seleccione la hora de inicio" value="00:00" readonly>
                                                        <span class="input-group-text"><i class="ri-time-line"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Hora de fin</label>
                                                    <div class="input-group d-none">
                                                        <input name="horaFin" id="timepicker2" type="text" class="form-control flatpickr flatpickr-input" placeholder="Seleccione la hora de fin" value="23:59" readonly>
                                                        <span class="input-group-text"><i class="ri-time-line"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end col-->
                                    <div class="col-12" hidden>
                                        <div class="mb-3">
                                            <label for="event-location">Lugar del evento</label>
                                            <div>
                                                <input type="text" class="form-control d-none" name="event-location" id="event-location" placeholder="Lugar del evento">
                                            </div>
                                        </div>
                                    </div><!--end col-->
                                    <input type="hidden" id="eventid" name="eventid" value="" />
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Descripción</label>
                                            <textarea name="descripcion" class="form-control d-none" id="event-description" placeholder="Descripción del evento" rows="3" spellcheck="false"></textarea>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-soft-danger" id="btn-delete-event"><i class="ri-close-line align-bottom"></i> Borrar</button>
                                    <button type="submit" class="btn btn-success" id="btn-save-event">Agregar evento</button>
                                </div>
                            </form>
                        </div>
                    </div> <!-- end modal-content-->
                </div> <!-- end modal dialog-->
            </div> <!-- end modal-->
            <!-- end modal-->
        </div>
    </div> <!-- end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('assets/libs/fullcalendar/fullcalendar.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/js/pages/calendar.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\SIGA-CLUB\resources\views/apps-calendar.blade.php ENDPATH**/ ?>