<template>
    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flex gap-1"
        role="alert">
        <strong class="font-bold">¡Ups!</strong>
        <span class="block sm:inline">{{ error }}</span>

    </div>

    <div class=" w-full flex flex-col justify-center items-center">
        <h2 class=" text-2xl font-bold self-start ml-20 mt-10">Panel Administraci&oacute;n</h2>
        <div class=" w-11/12 m-5 rounded-lg bg-white border border-gris-borde p-6">

            <!--Container principal de administracion-->
            <div>
                <div class="flex flex-col gap-1">
                    <div class="flex gap-1">
                        <p>Usuario: </p>
                        <p :class="{ 'text-green-500': obtieneRol != 'Normal' }" class=" ">{{ obtieneRol }}</p>
                    </div>
                    <div v-if="obtieneRol != 'Super Admin'" class="flex gap-2">
                        <label for="roles">Modificar Rol</label>
                        <select v-model="selectedRole" class="border border-gray-300 rounded-lg">
                            <option v-if="obtieneRol != 'Normal'" value="normal">Normal</option>
                            <option v-if="obtieneRol != 'Admin'" value="admin">Admin</option>
                            <option v-if="obtieneRol != 'Super Admin' && superAdmin" value="super-admin">Super Admin
                            </option>
                        </select>
                        <button @click="cambiarRolAlert" class="bg-blue-500 text-white rounded-lg px-2">Cambiar</button>
                    </div>
                </div>
            </div>
        </div>
        <h2 class=" text-2xl font-bold self-start ml-20">Panel de Usuario</h2>
        <div class=" w-11/12 m-5 rounded-lg bg-white border border-gris-borde p-6 flex flex-col gap-14">
            <div class="flex flex-col gap-4 items-center md:items-start md:flex-row md:gap-8">
                <div class=" w-60 h-60">
                    <img src="img/navbar/fotoperfil.png" alt=""
                        class=" bg-gris-light border border-gris-borde rounded-lg w-full h-full">
                </div>
                <div class="flex flex-col gap-2">
                    <p class=" text-2xl font-bold mt-2">{{ selectedUser.name }} {{ selectedUser.apellidos }}</p>
                    <p class=" text-lg">{{ selectedUser.email }}</p>
                    <p class=" text-lg">{{ selectedUser.telefono }}</p>
                    <p class=" text-lg">{{ selectedUser.direccion }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-10">
                <div class="flex flex-col gap-2 w-max">
                    <h2 class="text-xl font-bold">Ubicaciones permitidas</h2>
                    <div class="border border-gris-borde rounded w-72 md:w-96 p-5 h-40">
                        aqui van las hubicaciones
                    </div>
                    <div class="flex gap-2">
                        <button class="bg-blue-500 text-white rounded-lg px-2 py-1 w-max ml-1">Agregar ubicacion</button>
                        <button class="bg-red-500 text-white rounded-lg px-2 py-1 w-max ml-1">Eliminar</button>
                    </div>                </div>
                <div class="flex flex-col gap-2 w-max">
                    <h2 class="text-xl font-bold">Dispositivos permitidos</h2>
                    <div class="border border-gris-borde rounded w-72 md:w-96 p-5 h-40">
                        aqui van los dispositivos
                    </div>
                    <div class="flex gap-2">
                        <button class="bg-blue-500 text-white rounded-lg px-2 py-1 w-max ml-1">Agregar dispositivo</button>
                        <button class="bg-red-500 text-white rounded-lg px-2 py-1 w-max ml-1">Eliminar</button>
                    </div>                </div>
                <div class="flex flex-col gap-2 w-max">
                    <h2 class="text-xl font-bold">Horario</h2>
                    <div class="border border-gris-borde rounded w-72 md:w-96 p-5 h-40">
                        aqui van los horarios
                    </div>
                    <div class="flex gap-2">
                        <button class="bg-blue-500 text-white rounded-lg px-2 py-1 w-max ml-1">Agregar horario</button>
                        <button class="bg-red-500 text-white rounded-lg px-2 py-1 w-max ml-1">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showAlert" class="absolute inset-0 flex justify-center items-center text-2xl">
        <div class="bg-white p-4 rounded shadow-2xl">
            <p>Seguro que desea {{ accion }} de <span class=" font-bold">{{ selectedUser.name }}</span> a <span
                    class=" font-bold">{{ selectedRole }}</span>?</p>
            <div class="flex justify-end gap-6 mt-3">
                <button @click="continuar" class="bg-green-500 text-white rounded-lg px-2">Continuar</button>
                <button @click="cancelar" class="bg-red-500 text-white rounded-lg px-2">Cancelar</button>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: {
        selectedUser: Object,
        error: String,
    },
    data() {
        return {
            selectedRole: "",
            showAlert: false,
            accion: "",
            ejecutar: "",
        };
    },
    computed: {
        obtieneRol() {
            console.log(this.selectedUser.roles);
            if (this.selectedUser.roles.length === 0) {
                return "Normal";
            } else if (this.selectedUser.roles.some(role => role.role_name === "super-admin")) {
                return "Super Admin";
            } else {
                return "Admin";
            }
        },
    },
    methods: {
        superAdmin() {
            //compruebo si soy admin o super-admin y devuelvo true si lo soy
            if (this.selectedUser.roles.some(role => role.role_name === "super-admin")) {
                return true;
            } else {
                return false;
            }
        },
        cambiarRolAlert() {
            this.accion = "cambiar rol";
            this.showAlert = true;
            this.ejecutar = "cambiarRol";
        },
        continuar() {
            this.showAlert = false;
            this.accion = "";
            this[this.ejecutar](this.selectedUser.id, this.selectedRole);
            this.ejecutar = "";

        },
        cancelar() {
            this.showAlert = false;
            this.accion = "";
            this.ejecutar = "";
        },
        cambiarRol(id, rol) {
            this.$inertia.post(route("cambiarRol", { 'id': id, 'rol': rol }));
        },
    },
}
</script>