RECORDATORIO
- Para modificar la hora en los cambios de hora a la del servidor VistaFichaje.vue / linea 94
- Mirar spatie para los roles
- comprobar por que no me sale en fichajes Aaron Briseño

composition api y composition option de vue

Usuario super-admin
- admin@admin.com
- admin

Todos los demas usuarios tienen de password "password" y el email se puede ver en la pestaña empleados.

- http://127.0.0.1:8000/api/cierra-fichajes para cerrar el dia (Requiere Admin)


DETALLES A TENER EN CUENTA

Se ha usado Laravel 11 con Breeze(Para el inicio de sesion), Inertia(Para los recargos de pagina) y vue.
Se ha usado Guzzle para obtener informacion sobre la ubicacion del cliente
Se ha usado FullCalendar para renderizar el calendario
Se ha usado LibPhoneNumber para validar los numeros de telefono
Se ha usado Breadcrumbs para las migas de pan

- Si da error la creacion de token
    php artisan passport:client --personal

- Video padre
    https://www.youtube.com/watch?v=Uz56BOekpLA&ab_channel=OnlineWebTutor




FUNCIONALIDADES A MENCIONAR:

(todas las rutas estan protegidas con auth o admin a travez de middleware aunque no esta aun muy pulido)
(Se ha usado Guzzle para obtener informacion sobre la ubicacion del cliente)
(a las 11 de la noche se automatizan varias funciones
    - EmpleadosAusentes (Comprueba los usuarios que no han fichado en el dia y crea una notificacion de asusencia)
    - cierraFichajes (Comprueba si alguien no ha fichado y cierra el fichaje a la hora fin de su horario y envia la notificacion)
    - compruebaHorasTrabajadas (Si alguien no ha trabajado las horas totales de su horario se envia notificacion)
    )

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
        - borrar horario (aún no funciona)

- Auth
    - Si esta marcada la casilla de ubicacion manda una notificacion (alerta) cuando hace login

- Notificaciones
    - muestra Notificaciones
    - puedes aceptar o rechazar notificaciones, estas se marcaran como leido y desapareceran 
    - puedes filtrar entre tipo de notificaciones
    - puedes filtrar por leidas y no leidas

- Horarios
    - añadir Horarios
    - borrar Horarios
    - editar horario
        - tanto al editar como al añadir, hay diferentes restricciones dependiendo del tipo (flexible, continuo, partido)

- Solicitud
    - Vacaciones
        - seleccionar dias
        - solicitar dias seleccionados
        - desmarcar dias seleccionados
        - los dias libres suben y bajan dependiendo dias marcados y dias restantes en la bd, se actualizaran al solicitar dias y si se deniegan se devolveran

    