@extends('screens.app')

@section('content')

<head>
    <title>Agenda</title>
    <link rel="stylesheet" href="{{ asset('/calendar/fullcalendar.css') }}">
    <link rel="stylesheet" href="{{ asset('/calendar/bootstrap.css') }}">
    <script src="{{ asset('/calendar/es.js') }}"></script>
    <script src="{{ asset('/calendar/jquery.min.js') }}"></script>
    <script src="{{ asset('/calendar/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/calendar/moment.min.js') }}"></script>
    <script src="{{ asset('/calendar/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('script.js') }}"></script>

    <script>
        var minTime = '{{ $minTime }}';
        var maxTime = '{{ $maxTime }}';

        $(document).ready(function() {
            var calendar = $('#calendar').fullCalendar({
                locale: 'es',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 
                            'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 
                                'Nov', 'Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],

                defaultView: 'agendaWeek',
                slotDuration: '01:00:00', // Intervalo de 1 hora
                slotLabelInterval: '01:00:00', // Etiquetas de hora en intervalos de 1 hora
                slotEventOverlap: false,
                allDaySlot: false, // Ocultar el apartado "All-day"
                height: 'auto',

                events: @json($events),

                minTime: minTime,
                maxTime: maxTime,

                /* eventClick: function(event) {
                    var now = moment();
                    var eventTime = moment(event.start);

                    if (eventTime.isBefore(now)) {
                        window.location.href = '/ruta/pasado/' + event.id;
                    } else {
                        window.location.href = '/ruta/futuro/' + event.id;
                    }
                }, */

                dayClick: function(date, jsEvent, view) {
                    var now = moment();
                    var selectedDate = moment(date);

                    if (view.name === 'month') {
                    if (selectedDate.isBefore(now, 'day')) {
                        window.location.href = '/ruta/pasado-dia/' + selectedDate.format('YYYY-MM-DD');
                    } else {
                        window.location.href = '/ruta/futuro-dia/' + selectedDate.format('YYYY-MM-DD');
                    }
                } else if (view.name === 'agendaDay' || view.name === 'agendaWeek') {
                    if (selectedDate.isBefore(now)) {
                        window.location.href = '/ruta/pasado-hora/' + selectedDate.format('YYYY-MM-DDTHH:mm:ss');
                    } else {
                        window.location.href = '/ruta/futuro-hora/' + selectedDate.format('YYYY-MM-DDTHH:mm:ss');
                        element.append('<div class="fc-title">' + event.title + '');
                        element.append('Estado: ' + estado + '</div>');
                    }
                }
                    // Personalización según la vista activa
                if (view.name === 'agendaDay') {
                    // Vista de día: muestra detalles completos
                    if (element.find('.fc-title').length) {
                        element.find('.fc-title').append('<br/>Reservado por: ' + player + ' (' + estado + ')');
                        //element.find('.fc-title').append('<br/>Precio: $' + price + '');
                        //element.find('.fc-title').append('<br/>Estado: ' + estado + '');  Mostrar el estado
                    } else {
                        element.append('<div class="fc-title">' + event.title + '</div>');
                        element.append('(' + player + ')');
                        //element.append('Precio: $' + price + '');
                        //element.append('Estado: ' + estado + '');  Mostrar el estado
                    }
                } else if (view.name === 'agendaWeek') {
                    // Vista de semana: muestra solo el jugador y el estado
                    if (element.find('.fc-title').length) {
                        element.find('.fc-title').append('<br/>(' + player + ')');
                        //element.find('.fc-title').append('<br/>Estado: ' + estado + '');  Mostrar el estado
                    } else {
                        element.append('<div class="fc-title">' + event.title + '</div>');
                        element.append('(' + player + ')');
                        //element.append('Estado: ' + estado + '');  Mostrar el estado
                    }
                } else if (view.name === 'month') {
                    // Vista de mes: muestra solo el título
                    /* element.append('Reservado por: ' + event.extendedProps.player + ''); */

                }
                },
                
                 eventRender: function(event, element, view) {
                    var estado = event.extendedProps.estado;

                    if (estado === 'confirmado') {
                    element.css('background-color', '#8cb4ef'); // Cambiar el color de fondo si el estado es "confirmado"
                    element.css('border-color', '#8cb4ef'); // Borde verde para estado confirmado
                    element.css('margin', '2px 5px');
                } else if (estado === 'finalizado') {
                    element.css('background-color', '#3980ca'); // Cambiar el color de fondo si el estado es "finalizado"
                    element.css('border-color', '#3980ca'); // Borde rojo para estado finalizado
                    //element.css('color', 'var(--light)');
                    element.css('margin', '2px 5px');
                } else if (estado === 'en curso') {
                    element.css('background-color', '#fff5c7'); // Naranja pastel para "corriendo"
                    element.css('border-color', '#fff5c7'); // Borde naranja para en curso
                    element.css('margin', '2px 5px');
                }

                    if (view.name === 'agendaDay') {
                        if (element.find('.fc-title').length) {
                            element.find('.fc-title').append(' (' + estado + ')');
                        } else {
                            element.append('<div class="fc-title">' + event.title + '');
                            element.append('Estado: ' + estado + '</div>');
                        }
                    }
                }
            });
            
        });
    </script>   
</head>

<div class="table-data">
    <body class="calendar-body">
        <br />
        <h2 align="center">Mi Agenda</h2>
        <br />
        <div class="container">
            <div id="calendar"></div>
        </div>
    </body>
</div>

<style>
        /* .fc-day-grid-event .fc-content {
            background-color: var(--blue);
        } */
        .fc-content .fc-time {
            display: none; /* Oculta solo el tiempo dentro del evento */
        }
        
        .fc-content {
            padding: 3px;
            
        }
 
        #content main {
            background-color: var(--grey);
            color: var(--dark);
        }
    
        .fc-unthemed td.fc-today {
            color: var(--dark);
            background-color: var(--light-blue);
        }
    
        body.dark .fc-unthemed td.fc-today {
            color: var(--light);
        }
    
        .today-highlight {
            color: var(--light);
            background-color: var(--light-blue); /* Fondo claro para el día actual */
        }

        .fc-unthemed .fc-today {
            background-color: #dae5fa !important; /* Fondo azul apagado */
            color: rgb(0, 0, 0) !important; /* Texto en blanco */
        }

        body.dark .fc-unthemed .fc-today {
            background-color: #041831be !important; /* Fondo negro en modo oscuro */
            color: white !important;
        }
    
        body.dark .today-highlight {
            color: var(--light);
            background-color: black; /* Fondo negro en modo oscuro */
        }
    
        /* Otros estilos para el modo oscuro */
        body.dark .fc .fc-button-group> :first-child {
            color: white;
            border-color: whitesmoke;
            background: var(--light);
        }
    
        body.dark .fc .fc-button-group>* {
            color: white;
            border-color: whitesmoke;
            background: var(--light);
        }

        .fc-event {
            color: var(--white); /* Cambia el color del texto a negro */
            /*font-weight: bold;  Hace el texto más grueso */
            /* font-weight: 550; */
        }

</style>

@endsection
