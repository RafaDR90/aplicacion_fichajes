RECORDATORIO
- Mirar spatie para los roles

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


- errar fichajes
    - login
    https://aplicacionfichajes-production.up.railway.app/api/login
        Accept: application/json
        email: cierrafichaje@cierrafichaje
        password: cierrafichaje
    - funcion
    https://aplicacionfichajes-production.up.railway.app/api/cierra-fichajes
        Accept: application/json
        Authorization: Bearer 
