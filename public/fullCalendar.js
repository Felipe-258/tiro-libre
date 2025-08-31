import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    let calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        events: '/turns/events', // Ruta que proporcionará los datos de los turnos
        dateClick: function (info) {
            // Redirige a la vista del calendario para el día seleccionado
            window.location.href = `/calendar/${info.dateStr}`;
        }
    });

    calendar.render();
});
