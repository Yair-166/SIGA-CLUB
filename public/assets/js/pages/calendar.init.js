/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Calendar init js
*/


var start_date = document.getElementById("event-start-date");
var timepicker1 = document.getElementById("timepicker1");
var timepicker2 = document.getElementById("timepicker2");
var date_range = null;
var T_check = null;
document.addEventListener("DOMContentLoaded", function () {
    flatPickrInit();
    var addEvent = new bootstrap.Modal(document.getElementById('event-modal'), {
        keyboard: false
    });
    document.getElementById('event-modal');
    var modalTitle = document.getElementById('modal-title');
    var formEvent = document.getElementById('form-event');
    var selectedEvent = null;
    var forms = document.getElementsByClassName('needs-validation');
    /* initialize the calendar */

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var Draggable = FullCalendar.Draggable;
    var externalEventContainerEl = document.getElementById('external-events');
    var eventos_usr = document.getElementById('eventos_usr').value;

    console.log(eventos_usr.length)
    console.log(eventos_usr)
    

    if(eventos_usr.length == 4 || eventos_usr.length ==5 || eventos_usr.length ==6 || eventos_usr.length ==8  || eventos_usr.length == 12){
        var defaultEvents = [
            {
    
                id: 1,
                title: "ICPC",
                start: "2022-10-04",
                className: "bg-soft-info",
                allDay: true
            },
        ];
    }
    else{
        var existen_previstas = false;
        //Si existe el elemento asistencias_previstas en el html
        if(document.getElementById('asistencias_previstas')){
            var asistencias_previstas = document.getElementById('asistencias_previstas').value;
            var asistencias_previstas = JSON.parse(asistencias_previstas);
            console.log(asistencias_previstas);
            if(asistencias_previstas.length > 0){
                existen_previstas = true;
            }
            var ides = [];
            for(var i=0; i<asistencias_previstas.length; i++){
                ides.push(asistencias_previstas[i].id_evento);
            }
            console.log(ides);
        }

        //Partir el string en un array de objetos
        var eventos_usr = eventos_usr.split("],[");
        //Quitar las primeras y ultimas comillas
        eventos_usr[0] = eventos_usr[0].substring(1);
        eventos_usr[eventos_usr.length - 1] = eventos_usr[eventos_usr.length - 1].substring(0, eventos_usr[eventos_usr.length - 1].length - 1);
        console.log(eventos_usr);

        //console.log(eventos_usr);
        //var eventos_usr = JSON.parse(eventos_usr);

        //Imprimir el numero de eventos
        console.log(eventos_usr.length);
        //Obtener id, nombre, fecha y hora de los eventos
        var defaultEvents = [];

        
        for (var i = 0; i < eventos_usr.length; i++) {
            //Separar por ,\
            var evento = eventos_usr[i].split(",\\");
            var id = evento[0];
            //Eliminar el substring {\\\"id\\\": de la cadena
            id = id.substring(8); 
            
            var title = evento[1];
            //Eliminar el substring \\\"nombre\\\":\\\ de la cadena
            title = title.substring(12);
            //Eliminar el substring \\" de la cadena
            title = title.substring(0, title.length - 2);
            //Permitir los acentos 
            title = title.replace(/\\u00e1/g, "??");
            title = title.replace(/\\u00e9/g, "??");
            title = title.replace(/\\u00ed/g, "??");
            title = title.replace(/\\u00f3/g, "??");
            title = title.replace(/\\u00fa/g, "??");
            title = title.replace(/\\u00f1/g, "??");
            title = title.replace(/\\u00c1/g, "??");
            title = title.replace(/\\u00c9/g, "??");
            title = title.replace(/\\u00cd/g, "??");
            title = title.replace(/\\u00d3/g, "??");
            title = title.replace(/\\u00da/g, "??");
            title = title.replace(/\\u00d1/g, "??");
            //Eliminar todos los \ de la cadena
            title = title.replace(/\\/g, "");

            var start = evento[7];
            //Eliminar el substring \\\"fechaInicio\\\":\\\ de la cadena
            start = start.substring(17);
            //Eliminar el substring \\\" de la cadena
            start = start.substring(0, start.length - 2);

            var end = evento[8];
            //Eliminar el substring \\\"fechaFin\\\":\\\ de la cadena
            end = end.substring(14);
            //Eliminar el substring \\\" de la cadena
            end = end.substring(0, end.length - 2);
            //Sumar un dia a la fecha de fin
            end = new Date(end);
            end.setDate(end.getDate() + 1);
            end = end.toISOString().split('T')[0];

            var description = evento[4];
            //Eliminar el substring \\\"descripcion\\\":\\\ de la cadena
            description = description.substring(17);
            //Eliminar el substring \\\" de la cadena
            description = description.substring(0, description.length - 2);
            //Permitir los acentos
            description = description.replace(/\\u00e1/g, "??");
            description = description.replace(/\\u00e9/g, "??");
            description = description.replace(/\\u00ed/g, "??");
            description = description.replace(/\\u00f3/g, "??");
            description = description.replace(/\\u00fa/g, "??");
            description = description.replace(/\\u00f1/g, "??");
            description = description.replace(/\\u00c1/g, "??");
            description = description.replace(/\\u00c9/g, "??");
            description = description.replace(/\\u00cd/g, "??");
            description = description.replace(/\\u00d3/g, "??");
            description = description.replace(/\\u00da/g, "??");
            description = description.replace(/\\u00d1/g, "??");
            //Eliminar todos los \ de la cadena
            description = description.replace(/\\/g, "");

            var reglas = evento[11];
            //Eliminar el substring \\\"reglas\\\":\\\ de la cadena
            reglas = reglas.substring(12);
            //Eliminar el substring \\\" de la cadena
            reglas = reglas.substring(0, reglas.length - 2);
            //Si reglas es vacio
            if (reglas == "") {
                reglas = "No hay reglas establecidas.";
            }

            var tags = evento[12];
            //Si la cadena no contiene la palabra null
            if (tags.indexOf("null") == -1) {
                //Eliminar el substring \\\"tags\\\":\\\ de la cadena
                tags = tags.substring(10);
                //Permitir los acentos
                tags = tags.replace(/\\u00e1/g, "??");
                tags = tags.replace(/\\u00e9/g, "??");
                tags = tags.replace(/\\u00ed/g, "??");
                tags = tags.replace(/\\u00f3/g, "??");
                tags = tags.replace(/\\u00fa/g, "??");
                tags = tags.replace(/\\u00f1/g, "??");
                tags = tags.replace(/\\u00c1/g, "??");
                tags = tags.replace(/\\u00c9/g, "??");
                tags = tags.replace(/\\u00cd/g, "??");
                tags = tags.replace(/\\u00d3/g, "??");
                tags = tags.replace(/\\u00da/g, "??");
                tags = tags.replace(/\\u00d1/g, "??");
                //Eliminar todos los \ de la cadena
                tags = tags.replace(/\\/g, "");
            }
            else {
                tags = "Sin tags establecidos.";
            }


            var className = "bg-soft-info";
            
            //Verificar si el id del evento esta en el array de ides
            if(existen_previstas){
                if(ides.includes(id)){
                    className = "bg-soft-warning";
                }
            }

            var evento = {
                id: id,
                title: title,
                start: start,
                end: end,
                className: className,
                allDay: true,
                description: description,
                reglas: reglas,
                tags: tags
            };
            
            defaultEvents.push(evento);
        }
    }
   

    console.log(defaultEvents);

    // init draggable
    new Draggable(externalEventContainerEl, {
        itemSelector: '.external-event',
        eventData: function (eventEl) {
            return {
                title: eventEl.innerText,
                start: new Date(),
                className: eventEl.getAttribute('data-class')
            };
        }
    });

    var calendarEl = document.getElementById('calendar');

    function addNewEvent(info) {
        document.getElementById('form-event').reset();
        document.getElementById('btn-delete-event').setAttribute('hidden', true);
        addEvent.show();
        formEvent.classList.remove("was-validated");
        formEvent.reset();
        selectedEvent = null;
        modalTitle.innerText = 'Agregar evento';
        newEventData = info;
        document.getElementById("edit-event-btn").setAttribute("data-id", "new-event");
        document.getElementById('edit-event-btn').click();
        document.getElementById("edit-event-btn").setAttribute("hidden", true);
    }

    function getInitialView() {
        if (window.innerWidth >= 768 && window.innerWidth < 1200) {
            return 'timeGridWeek';
        } else if (window.innerWidth <= 768) {
            return 'listMonth';
        } else {
            return 'dayGridMonth';
        }
    }

    var eventCategoryChoice = new Choices("#event-category", {
        searchEnabled: false
    });

    var calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'local',
        editable: true,
        droppable: true,
        selectable: true,
        navLinks: true,
        initialView: getInitialView(),
        themeSystem: 'bootstrap',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        locale: 'es',
        windowResize: function (view) {
            var newView = getInitialView();
            calendar.changeView(newView);
        },
        eventClick: function (info) {
            document.getElementById("edit-event-btn").removeAttribute("hidden");
            document.getElementById('btn-save-event').setAttribute("hidden", true);
            document.getElementById("edit-event-btn").setAttribute("data-id", "edit-event");
            document.getElementById("edit-event-btn").innerHTML = "Edit";
            eventClicked();
            flatPickrInit();
            flatpicekrValueClear();
            addEvent.show();
            formEvent.reset();
            selectedEvent = info.event;

            // First Modal
            document.getElementById("modal-title").innerHTML = "";
            document.getElementById("event-location-tag").innerHTML = selectedEvent.extendedProps.location === undefined ? "No Location" : selectedEvent.extendedProps.location;
            document.getElementById("event-description-tag").innerHTML = selectedEvent.extendedProps.description === undefined ? "No Description" : selectedEvent.extendedProps.description;
            document.getElementById("event-rules-tag").innerHTML = selectedEvent.extendedProps.reglas === undefined ? "No Rules" : selectedEvent.extendedProps.reglas;
            document.getElementById("event-tags-tag").innerHTML = selectedEvent.extendedProps.reglas === undefined ? "No Tags" : selectedEvent.extendedProps.tags;
            document.getElementById("btn-asistire").setAttribute("href", "/asistire/" + selectedEvent.id);

            // Edit Modal
            document.getElementById("event-title").value = selectedEvent.title;
            document.getElementById("event-location").value = selectedEvent.extendedProps.location === undefined ? "No Location" : selectedEvent.extendedProps.location;
            document.getElementById("event-description").value = selectedEvent.extendedProps.description === undefined ? "No Description" : selectedEvent.extendedProps.description;
            document.getElementById("eventid").value = selectedEvent.id;

            if (selectedEvent.classNames[0]) {
                eventCategoryChoice.destroy();
                eventCategoryChoice = new Choices("#event-category", {
                    searchEnabled: false
                });
                eventCategoryChoice.setChoiceByValue(selectedEvent.classNames[0]);
            }
            var st_date = selectedEvent.start;
            var ed_date = selectedEvent.end;

            var date_r = function formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();
                if (month.length < 2)
                    month = '0' + month;
                if (day.length < 2)
                    day = '0' + day;
                return [year, month, day].join('-');
            };
            var r_date = ed_date == null ? (str_dt(st_date)) : (str_dt(st_date)) + ' to ' + (str_dt(ed_date));
            var er_date = ed_date == null ? (date_r(st_date)) : (date_r(st_date)) + ' to ' + (date_r(ed_date));

            flatpickr(start_date, {
                defaultDate: er_date,
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                mode: ed_date !== null ? "range" : "range",
                onChange: function (selectedDates, dateStr, instance) {
                    var date_range = dateStr;
                    var dates = date_range.split("to");
                    if (dates.length > 1) {
                        document.getElementById('event-time').setAttribute("hidden", true);
                    } else {
                        document.getElementById("timepicker1").parentNode.classList.remove("d-none");
                        document.getElementById("timepicker1").classList.replace("d-none", "d-block");
                        document.getElementById("timepicker2").parentNode.classList.remove("d-none");
                        document.getElementById("timepicker2").classList.replace("d-none", "d-block");
                        document.getElementById('event-time').removeAttribute("hidden");
                    }
                },
            });
            document.getElementById("event-start-date-tag").innerHTML = r_date;

            var gt_time = getTime(selectedEvent.start);
            var ed_time = getTime(selectedEvent.end);

            if (gt_time == ed_time) {
                document.getElementById('event-time').setAttribute("hidden", true);
                flatpickr(document.getElementById("timepicker1"), {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                });
                flatpickr(document.getElementById("timepicker2"), {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                });
            } else {
                document.getElementById('event-time').removeAttribute("hidden");
                flatpickr(document.getElementById("timepicker1"), {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    defaultDate: gt_time
                });

                flatpickr(document.getElementById("timepicker2"), {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    defaultDate: ed_time
                });
                document.getElementById("event-timepicker1-tag").innerHTML = tConvert(gt_time);
                document.getElementById("event-timepicker2-tag").innerHTML = tConvert(ed_time);
            }
            newEventData = null;
            modalTitle.innerText = selectedEvent.title;

            // formEvent.classList.add("view-event");
            document.getElementById('btn-delete-event').removeAttribute('hidden');
        },
        dateClick: function (info) {
            addNewEvent(info);
        },
        events: defaultEvents,
        eventReceive: function (info) {
            var newEvent = {
                id: Math.floor(Math.random() * 11000),
                title: info.event.title,
                start: info.event.start,
                allDay: info.event.allDay,
                className: info.event.classNames[0]
            };
            defaultEvents.push(newEvent);
            upcomingEvent(defaultEvents);
        },
        eventDrop: function (info) {
            var indexOfSelectedEvent = defaultEvents.findIndex(function (x) {
                return x.id == info.event.id
            });
            if (defaultEvents[indexOfSelectedEvent]) {
                defaultEvents[indexOfSelectedEvent].title = info.event.title;
                defaultEvents[indexOfSelectedEvent].start = info.event.start;
                defaultEvents[indexOfSelectedEvent].end = (info.event.end) ? info.event.end : null;
                defaultEvents[indexOfSelectedEvent].allDay = info.event.allDay;
                defaultEvents[indexOfSelectedEvent].className = info.event.classNames[0];
                defaultEvents[indexOfSelectedEvent].description = (info.event._def.extendedProps.description) ? info.event._def.extendedProps.description : '';
                defaultEvents[indexOfSelectedEvent].location = (info.event._def.extendedProps.location) ? info.event._def.extendedProps.location : '';
            }
            upcomingEvent(defaultEvents);
        }
    });

    calendar.render();

    upcomingEvent(defaultEvents);
    /*Add new event*/
    // Form to add new event
    formEvent.addEventListener('submit', function (ev) {
        //ev.preventDefault();
        var updatedTitle = document.getElementById("event-title").value;
        var updatedCategory = document.getElementById('event-category').value;
        var start_date = (document.getElementById("event-start-date").value).split("to");
        var updateStartDate = new Date(start_date[0].trim());
        var updateEndDate = (start_date[1]) ? new Date(start_date[1].trim()) : '';

        var end_date = null;
        var event_location = document.getElementById("event-location").value;
        var eventDescription = document.getElementById("event-description").value;
        var eventid = document.getElementById("eventid").value;
        var all_day = false;
        if (start_date.length > 1) {
            var end_date = new Date(start_date[1]);
            end_date.setDate(end_date.getDate() + 1);
            start_date = new Date(start_date[0]);
            all_day = true;
        } else {
            var e_date = start_date;
            var start_time = (document.getElementById("timepicker1").value).trim();
            var end_time = (document.getElementById("timepicker2").value).trim();
            start_date = new Date(start_date + "T" + start_time);
            end_date = new Date(e_date + "T" + end_time);
        }
        var e_id = defaultEvents.length + 1;

        // validation
        if (forms[0].checkValidity() === false) {
            forms[0].classList.add('was-validated');
        } else {
            if (selectedEvent) {
                selectedEvent.setProp("id", eventid);
                selectedEvent.setProp("title", updatedTitle);
                selectedEvent.setProp("classNames", [updatedCategory]);
                selectedEvent.setStart(updateStartDate);
                selectedEvent.setEnd(updateEndDate);
                selectedEvent.setAllDay(all_day);
                selectedEvent.setExtendedProp("description", eventDescription);
                selectedEvent.setExtendedProp("location", event_location);
                var indexOfSelectedEvent = defaultEvents.findIndex(function (x) {
                    return x.id == selectedEvent.id
                });
                if (defaultEvents[indexOfSelectedEvent]) {
                    defaultEvents[indexOfSelectedEvent].title = updatedTitle;
                    defaultEvents[indexOfSelectedEvent].start = updateStartDate;
                    defaultEvents[indexOfSelectedEvent].end = updateEndDate;
                    defaultEvents[indexOfSelectedEvent].allDay = all_day;
                    defaultEvents[indexOfSelectedEvent].className = updatedCategory;
                    defaultEvents[indexOfSelectedEvent].description = eventDescription;
                    defaultEvents[indexOfSelectedEvent].location = event_location;
                }
                calendar.render();
                // default
            } else {
                var newEvent = {
                    id: e_id,
                    title: updatedTitle,
                    start: start_date,
                    end: end_date,
                    allDay: all_day,
                    className: updatedCategory,
                    description: eventDescription,
                    location: event_location
                };
                calendar.addEvent(newEvent);
                defaultEvents.push(newEvent);
            }
            addEvent.hide();
            upcomingEvent(defaultEvents);
        }
    });

    document.getElementById("btn-delete-event").addEventListener("click", function (e) {
        if (selectedEvent) {
            for (var i = 0; i < defaultEvents.length; i++) {
                if (defaultEvents[i].id == selectedEvent.id) {
                    defaultEvents.splice(i, 1);
                    i--;
                }
            }
            upcomingEvent(defaultEvents);
            selectedEvent.remove();
            selectedEvent = null;
            addEvent.hide();
        }
    });
    document.getElementById("btn-new-event").addEventListener("click", function (e) {
        flatpicekrValueClear();
        flatPickrInit();
        addNewEvent();
        document.getElementById("edit-event-btn").setAttribute("data-id", "new-event");
        document.getElementById('edit-event-btn').click();
        document.getElementById("edit-event-btn").setAttribute("hidden", true);
    });
});


function flatPickrInit() {
    var config = {
        enableTime: true,
        noCalendar: true,
    };
    var date_range = flatpickr(
        start_date, {
            enableTime: false,
            mode: "range",
            minDate: "today",
            onChange: function (selectedDates, dateStr, instance) {
                var date_range = dateStr;
                var dates = date_range.split("to");
                if (dates.length > 1) {
                    document.getElementById('event-time').setAttribute("hidden", true);
                } else {
                    document.getElementById("timepicker1").parentNode.classList.remove("d-none");
                    document.getElementById("timepicker1").classList.replace("d-none", "d-block");
                    document.getElementById("timepicker2").parentNode.classList.remove("d-none");
                    document.getElementById("timepicker2").classList.replace("d-none", "d-block");
                    document.getElementById('event-time').removeAttribute("hidden");
                }
            },
        });
    flatpickr(timepicker1, config);
    flatpickr(timepicker2, config);

}

function flatpicekrValueClear() {
    start_date.flatpickr().clear();
    timepicker1.flatpickr().clear();
    timepicker2.flatpickr().clear();
}


function eventClicked() {
    document.getElementById('form-event').classList.add("view-event");
    document.getElementById("event-title").classList.replace("d-block", "d-none");
    document.getElementById("event-category").classList.replace("d-block", "d-none");
    document.getElementById("event-start-date").parentNode.classList.add("d-none");
    document.getElementById("event-start-date").classList.replace("d-block", "d-none");
    document.getElementById('event-time').setAttribute("hidden", true);
    document.getElementById("timepicker1").parentNode.classList.add("d-none");
    document.getElementById("timepicker1").classList.replace("d-block", "d-none");
    document.getElementById("timepicker2").parentNode.classList.add("d-none");
    document.getElementById("timepicker2").classList.replace("d-block", "d-none");
    document.getElementById("event-location").classList.replace("d-block", "d-none");
    document.getElementById("event-description").classList.replace("d-block", "d-none");
    document.getElementById("event-start-date-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-timepicker1-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-timepicker2-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-location-tag").classList.replace("d-none", "d-block");
    document.getElementById("event-description-tag").classList.replace("d-none", "d-block");
    document.getElementById('btn-save-event').setAttribute("hidden", true);
}

function editEvent(data) {
    var data_id = data.getAttribute("data-id");
    if (data_id == 'new-event') {
        document.getElementById('modal-title').innerHTML = "";
        //Obtener el valor del input rol_usr
        var rol_usr = document.getElementById('rol_usr').value;
        if (rol_usr == 'colaborador') {
            document.getElementById('modal-title').innerHTML = "Sin evento programado";
        } else {
            document.getElementById('modal-title').innerHTML = "Agregar Evento";
        }
        document.getElementById("btn-save-event").innerHTML = "Agregar Evento"; 
        eventTyped();
    } else if (data_id == 'edit-event') {
        data.innerHTML = "Cancel";
        data.setAttribute("data-id", 'cancel-event');
        document.getElementById("btn-save-event").innerHTML = "Update Event";
        data.removeAttribute("hidden");
        eventTyped();
    } else {
        data.innerHTML = "Edit";
        data.setAttribute("data-id", 'edit-event');
        eventClicked();
    }
}

function eventTyped() {
    document.getElementById('form-event').classList.remove("view-event");
    document.getElementById("event-title").classList.replace("d-none", "d-block");
    document.getElementById("event-category").classList.replace("d-none", "d-block");
    document.getElementById("event-start-date").parentNode.classList.remove("d-none");
    document.getElementById("event-start-date").classList.replace("d-none", "d-block");
    document.getElementById("timepicker1").parentNode.classList.remove("d-none");
    document.getElementById("timepicker1").classList.replace("d-none", "d-block");
    document.getElementById("timepicker2").parentNode.classList.remove("d-none");
    document.getElementById("timepicker2").classList.replace("d-none", "d-block");
    document.getElementById("event-location").classList.replace("d-none", "d-block");
    document.getElementById("event-description").classList.replace("d-none", "d-block");
    document.getElementById("event-start-date-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-timepicker1-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-timepicker2-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-location-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-description-tag").classList.replace("d-block", "d-none");
    document.getElementById("event-rules-tag").classList.replace("d-block", "d-none");
    document.getElementById('btn-save-event').removeAttribute("hidden");
}

// upcoming Event
function upcomingEvent(a) {
    a.sort(function (o1, o2) {
        return (new Date(o1.start)) - (new Date(o2.start));
    });
    document.getElementById("upcoming-event-list").innerHTML = null;
    a.forEach(function (element) {
        var title = element.title;
        var id = element.id;
        var e_dt = element.end ? element.end : null;
        if (e_dt == "Invalid Date" || e_dt == undefined) {
            e_dt = null;
        } else {

            ende = new Date(e_dt);
            //Restar 1 dia a ende
            //console.log(ende);
            //Pasar a formato yyyy-mm-dd
            var dd = String(ende.getDate()).padStart(2, '0');
            var mm = String(ende.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = ende.getFullYear();
            e_dt = yyyy + '-' + mm + '-' + dd;
            
            e_dt = e_dt.replace(/-/g, '/');
            e_dt = new Date(e_dt).toLocaleDateString('en', {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric'
            });
        }
        var st_date = str_dt(element.start);
        var ed_date = str_dt(element.end);
        if (st_date === ed_date) {
            e_dt = null;
        }
        var startDate = element.start;
        startDate = startDate.replace(/-/g, '/');
        if (startDate === "Invalid Date" || startDate === undefined) {
            startDate = null;
        } else {
            startDate = new Date(startDate).toLocaleDateString('en', {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric'
            });
        }
        var end_dt = (e_dt) ? " a " + e_dt : '';
        var category = (element.className).split("-");
        var description = (element.description) ? element.description : "";
        var e_time_s = tConvert(getTime(element.start));
        var e_time_e = tConvert(getTime(element.end));

        if (e_time_s == e_time_e) {
            var e_time_s = "Todo el d??a";
            var e_time_e = null;
        }
        var e_time_e = (e_time_e) ? " a " + e_time_e : "";

        //Checar si startDate esta contenido en end_dt
        if (end_dt.includes(startDate)) {
            var end_dt = "";
        }

        u_event = "<div class='card mb-3'>\
                        <a href='apps-eventos-overview?evento="+id+"'>\
                            <div class='card-body'>\
                                <div class='d-flex mb-3'>\
                                    <div class='flex-grow-1'><i class='mdi mdi-checkbox-blank-circle me-2 text-" + category[2] + "'></i><span class='fw-medium'>" + startDate + end_dt + " </span></div>\
                                    <div class='flex-shrink-0'><small class='badge badge-soft-primary ms-auto'>" + e_time_s + e_time_e + "</small></div>\
                                </div>\
                                <h6 class='card-title fs-16'> " + title + "</h6>\
                                <p class='text-muted text-truncate-two-lines mb-0'> " + description + "</p>\
                            </div>\
                        </a>\
                    </div>";
        document.getElementById("upcoming-event-list").innerHTML += u_event;
    });
};

function getTime(params) {
    params = new Date(params);
    if (params.getHours() != null) {
        var hour = params.getHours();
        var minute = (params.getMinutes()) ? params.getMinutes() : 00;
        return hour + ":" + minute;
    }
}

function tConvert(time) {
    var t = time.split(":");
    var hours = t[0];
    var minutes = t[1];
    var newformat = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    return (hours + ':' + minutes + ' ' + newformat);
}

var str_dt = function formatDate(date) {
    var monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayoo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    var d = new Date(date),
        month = '' + monthNames[(d.getMonth())],
        day = '' + d.getDate(),
        year = d.getFullYear();
    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;
    return [day + " " + month, year].join(',');
};