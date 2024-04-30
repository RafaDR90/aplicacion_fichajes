RECORDATORIO
- Mirar spatie para los roles
- añadir numero de notificaciones a sidebar
tengo que mirar el login de auth y el login de la api para ver como crea el token y añadirlo
- probar automatizacion desde servidor (no local)

Usuario super-admin
- admin@admin.com
- admin

- http://127.0.0.1:8000/api/cierra-fichajes para cerrar el dia (Requiere Admin)


DETALLES A TENER EN CUENTA

Se ha usado Laravel 11 con Breeze(Para el inicio de sesion), Inertia(Para los recargos de pagina) y vue.
Se ha usado Guzzle para obtener informacion sobre la ubicacion del cliente
Se ha usado FullCalendar para renderizar el calendario
Se ha usado LibPhoneNumber para validar los numeros de telefono
Se ha usado Breadcrumbs para las migas de pan
Se ha usado Firestore para las imagenes de perfil

- Si da error la creacion de token
    php artisan passport:client --personal

- Video padre
    https://www.youtube.com/watch?v=Uz56BOekpLA&ab_channel=OnlineWebTutor

    NIXPACKS_BUILD_CMD=composer install && npm install && npx vite build && php artisan optimize && php artisan config:cache && php artisan view:cache && php artisan passport:install --force --no-interaction && php artisan migrate --force --no-interaction