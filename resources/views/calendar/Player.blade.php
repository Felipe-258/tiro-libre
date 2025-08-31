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
                $('#calendar').fullCalendar({
                    locale: 'es',
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
        
                    defaultView: 'agendaDay',
                    slotDuration: '01:00:00', // Intervalo de 1 hora
                    slotLabelInterval: '01:00:00', // Etiquetas de hora en intervalos de 1 hora
                    slotEventOverlap: false,
                    allDaySlot: false, // Ocultar el apartado "All-day"
                    contentHeight: 'auto',
                    height: 'auto',

                    events: @json($events),

                    minTime: minTime,
                    maxTime: maxTime,
        
                    eventAfterAllRender: function() {
                        var today = moment().format('YYYY-MM-DD');
                        $('.fc-day[data-date="' + today + '"]').css('background-color', '#F9F9F9');
                    },
        
                    eventRender: function(event, element, view) {
                        // Acceso correcto a las propiedades personalizadas
                        var estado = event.extendedProps.estado;
                        var player = event.extendedProps.player;
                        var price = event.extendedProps.price;
        
                        // Estilos según el estado
                        if (estado === 'confirmado') {
                            element.css('background-color', '#8cb4ef');
                            element.css('border-color', '#8cb4ef');
                        } else if (estado === 'finalizado') {
                            element.css('background-color', '#3980ca');
                            element.css('border-color', '#3980ca');
                        } else if (estado === 'en curso') {
                            element.css('background-color', '#fff5c7');
                            element.css('border-color', '#fff5c7');
                        }
        
                        // Personalización según la vista activa
                        if (view.name === 'agendaDay') {
                            // Vista de día: muestra detalles completos
                            element.find('.fc-title').html(event.title + '<br/>Reservado por: ' + player + ' (' + estado + ')');
                        } else if (view.name === 'agendaWeek') {
                            // Vista de semana: muestra solo el jugador y el estado
                            element.find('.fc-title').html(event.title + '<br/>(' + player + ')');
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
