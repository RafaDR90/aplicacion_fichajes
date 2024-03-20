DETALLES A TENER EN CUENTA

- Si da error la creacion de token
    php artisan passport:client --personal

- Video padre
    https://www.youtube.com/watch?v=Uz56BOekpLA&ab_channel=OnlineWebTutor

Pasos a seguir para añadir roles, creamos tabla roles y tabla role_user con id_user y id_role, entonces creamos un
middleware que comprobara si el usuario es un determinado rol, y usamos el middleware en las rutas en api.php


Ultimo realizado añade tabla roles y roles_user para despues poder hacer el middleware y crear dispositivos permitidos y usuarios