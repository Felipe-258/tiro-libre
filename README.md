![Tiro Libre Logo](\public\logos\logo tiro-libre square.png)

# Tiro Libre

Tiro Libre es una plataforma para alquilar canchas. Aquí, los cancheros publican sus canchas y los jugadores pueden buscar las mejores opciones para disfrutar con amigos, cuidar el bolsillo, salvar las distancias e incluso crear salas para encontrar nuevos jugadores y formar un equipo ganador. El programa permite tener distintos roles:

- **Jugador**: Puede alquilar, buscar canchas y armar salas para buscar jugadores.
- **Canchero**: Puede publicar y promocionar su cancha, proporcionando información como ubicación, precio y número de teléfono.
- **Administrador**: Capaz de modificar o cambiar aspectos críticos del sistema.
- **Super Administrador**: Capaz de designar los roles de administrador.

## Versiones

- **Laragon Full**: 6.0 220916
- **PHP**: 8.1.10
- **MySQL**: 8.0.30
- **Apache**: 2.4.54
- **Heidi Portable**: 12.1.0.6537

# Instalación de Tiro Libre

Para probar el proyecto de manera local, se debe tener instalado Laragon 6.0 y que las demás versiones coincidan para no tener inconvenientes.

1. Descargar o clonar el proyecto.
2. Copiar la carpeta `Reserva-Futbolera`.
3. Renombrar el archivo `.env.example` a `.env` y modificarlo si es necesario.
    - En tu archivo .env linea 21, colocar `QUEUE_CONNECTION=database`.
4. Usando la terminal y teniendo `composer` instalado, navegar hasta el directorio.
5. Ejecutar `composer install` (tomará un momento).
6. Ejecutar `npm install`.
7. Generar una 'application key' con `php artisan key:generate --ansi`.
8. Ejecutar las migraciones con `php artisan migrate`.
    - Opcional: si quieres tener datos de prueba, ejecutar `php artisan migrate:refresh --seed`.
9. Ejecutar las migraciones con `php artisan storage:link`.  
10. Ejecutar `npm run dev`.
11. Si queres probar el envio de mensajes por whatsapp. Debes:
    - Ejecutar `node resources/js/whatsapp/whatsapp.js ` 
    - Ejecutar `php artisan queue:work`. Para ejecutar las notificaciones por whatsapp.
12. Por último, iniciar el servidor local:
    - Recomendamos hacerlo mediante el menú de Laragon:
        - Menú
        - WWW
        - Reserva-Futbolera [http://reserva-futbolera.test]
    - También se puede iniciar escribiendo `php artisan serve`:
        - Con esto deberías abrir [http://127.0.0.1:8000].

¡Y eso es todo! Ahora puedes disfrutar de Tiro Libre en tu entorno local.
