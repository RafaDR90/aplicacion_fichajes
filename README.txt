DETALLES A TENER EN CUENTA

Se ha usado Laravel 11 con Breeze(Para el inicio de sesion), Inertia(Para los recargos de pagina) y vue.
Se ha usado Guzzle para obtener informacion sobre la ubicacion del cliente

- Si da error la creacion de token
    php artisan passport:client --personal

- Video padre
    https://www.youtube.com/watch?v=Uz56BOekpLA&ab_channel=OnlineWebTutor


estoy con el panel de usuario, falta preparar boton para borrar y quitar botones sobrantes

podria ponerme a restringir el acceso como quiera si no estoy en una ubicacion permitida 

FUNCIONALIDADES A MENCIONAR:

(todas las rutas estan protegidas con auth o admin a travez de middleware aunque no esta aun muy pulido)
(Se ha usado Guzzle para obtener informacion sobre la ubicacion del cliente)

- En el nav hay un dropdown que permite editar perfil (no esta pulido) y cerrar sesion

- Vista empleados
    - filtra por nombre
    - ordena
    - añadir empleado
    - pagination
    - vista panel usuario
    - los admins salen remarcados

- Vista penel usuario (perfil)
    - modificar rol
        - restricciones: a un super-admin no se le puede editar, un admin no puede crear un super-admin
    - solicitud ubicacion en el siguiente login
    - lista ubicaciones
        - elimina ubicacion
    - lista horarios
        - asignacion horario

- Auth
    - Si esta marcada la casilla de ubicacion manda una notificacion (alerta) cuando hace login

- Notificaciones
    - muestra Notificaciones
    - puedes aceptar o rechazar notificaciones, estas se marcaran como leido y desapareceran (hasta que pueda filtrar por leidas)

- Horarios
    - añadir Horarios
    - borrar Horarios
    - editar horario
        - tanto al editar como al añadir, hay diferentes restricciones dependiendo del tipo (flexible, continuo, partido)

    (Aun no se pueden asignar horarios)