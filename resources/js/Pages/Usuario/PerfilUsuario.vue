<template>
    <div v-if="error"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flex flex-col gap-1"
        role="alert">
        <strong class="font-bold">¡Ups!</strong>
        <span class="block sm:inline">- {{ error }}</span>
    </div>
    <div v-if="exito"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex flex-col gap-1"
        role="alert">
        <p class="block sm:inline">- {{ exito }}</p>
    </div>
    <div class=" w-full flex flex-col justify-center items-center ">
        <h2 class=" text-2xl font-bold self-start ml-20 mt-10">Panel Administraci&oacute;n</h2>
        <div class=" w-11/12 m-5 rounded-lg bg-white border border-gris-borde p-6">

            <!--Container principal de administracion-->
            <div class=" flex flex-col gap-4">
                <div class="flex flex-col gap-4">
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
                <div class="flex">
                    <label class="inline-flex items-center cursor-pointer gap-2">
                        <span class=" ">Solicita ubicacion: </span>
                        <input @change="handleUbicacionCheckbox" v-model="isChecked" type="checkbox"
                            class="sr-only peer">
                        <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>
            </div>
            <!--Fin del container principal de administracion-->

        </div>
        <h2 class=" text-2xl font-bold self-start ml-20">Panel de Usuario</h2>
        <div class=" w-11/12 m-5 rounded-lg bg-white border border-gris-borde p-6 flex flex-col gap-14">
            <div class="flex flex-col gap-4 items-center md:items-start md:flex-row md:gap-8">
                <div class=" w-60 h-60">
                    <img src="/img/navbar/fotoperfil.png" alt=""
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
                <div class="flex flex-col gap-2 w-5/12 min-w-72">
                    <h2 class="text-xl font-bold">Ubicaciones permitidas</h2>
                    <div class="border border-gris-borde rounded min-w-72 w-full p-5 h-60 overflow-auto">
                        <div v-if="selectedUser && selectedUser.ubicacion" v-for="ubicacion in selectedUser.ubicacion">
                            <div class="flex justify-between">
                                <p><span class=" font-bold">Pais: </span>{{ ubicacion.pais }}</p>
                                <p><span class=" font-bold">CP: </span>{{ ubicacion.cp }}</p>
                                <p><span class=" font-bold">Ciudad: </span>{{ ubicacion.ciudad }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p><span class=" font-bold">Latitud: </span>{{ ubicacion.latitud }}</p>
                                <p><span class=" font-bold">Longitud: </span>{{ ubicacion.longitud }}</p>
                            </div>
                            <div class="flex gap-1  justify-end mr-5 sm:mr-11 w-full py-2">
                                <button
                                    @click="desasociarUbicacionAlert(ubicacionId = ubicacion.id, accion = 'desasociar ubicacion', ejecutar = 'desasociarUbicacion')"
                                    class="h-6 w-6">
                                    <img class=" w-full h-full" src="/img/iconos/borrar.png" alt="Icono borrar"
                                        title="Borrar">
                                </button>
                            </div>
                            <div class=" border-b border-gris-borde"></div>
                        </div>
                    </div>

                </div>
                
                <div class="flex flex-col gap-2 w-5/12 min-w-72">
                    <h2 class="text-xl font-bold">Horario</h2>
                    <div class="border border-gris-borde rounded  min-w-72 w-full p-2 h-60 overflow-auto">
                        <div v-for=" horario in selectedUser.horarios">
                            <div class="flex justify-between">
                                <p class=" font-bold">{{ horario.nombre }}</p>
                                <div class=" w-6 h-6">
                                    <img class=" w-full h-full" src="/img/iconos/borrar.png" alt="Icono borrar"
                                        title="Borrar">
                                </div>
                            </div>
                            <div v-if="horario.hora_entrada">
                                <table class="table-auto border-collapse border">
                                    <thead class="text-sm">
                                        <tr class=" bg-gris-borde">
                                            <th class="border border-gris-borde px-1 ">Entrada</th>
                                            <th class="border px-1">Salida</th>
                                            <th v-if="horario.descanso_entrada" class="border  px-1">Descanso inicio
                                            </th>
                                            <th v-if="horario.descanso_salida" class="border  px-1">Descanso fin</th>
                                            <th class="border px-1">tiempo libre</th>
                                            <th class="border px-1">Total horas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class=" ">
                                            <td class="border text-center border-gris-borde">{{
        horario.hora_entrada.slice(0, 5) }}</td>
                                            <td class="border text-center border-gris-borde">{{
        horario.hora_salida.slice(0, 5) }}</td>
                                            <td v-if="horario.descanso_entrada"
                                                class="border text-center border-gris-borde">{{
        horario.descanso_entrada.slice(0, 5) }}</td>
                                            <td v-if="horario.descanso_salida"
                                                class="border text-center border-gris-borde">{{
        horario.descanso_salida.slice(0, 5) }}</td>
                                            <td class="border text-center border-gris-borde">{{ horario.libre }}"</td>
                                            <td class="border text-center border-gris-borde">{{ horario.total_horas }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                            </div>
                            <div class="border-b mt-2 mb-1 border-gris-borde"></div>

                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form class="flex items-center" @submit.prevent="asignarHorario">
                            <select class="bg-white text-black rounded-lg px-2 py-1 w-max ml-1"
                                v-model="formHorario.horario_id">
                                <option v-for="horario in allHorarios" :value="horario.id">{{ horario.nombre }}</option>
                            </select>
                            <button type="submit" class="bg-blue-500 text-white rounded-lg px-2 py-1 w-max ml-1">Asignar
                                horario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showAlert" class="absolute inset-0 flex justify-center  text-2xl">
        <div class=" sticky top-1/2 bg-white p-4 rounded shadow-gray-600 shadow-2xl h-max border">
            <p>Seguro que desea {{ accion }} de <span class=" font-bold">{{ selectedUser.name }}</span><span
                    v-if="ejecutar == 'cambiarRol'">a <span class=" font-bold">{{ selectedRole }}</span>?</span></p>
            <div class="flex justify-end gap-6 mt-10">
                <Link v-if="ejecutar == 'cambiarRol'"
                    :href="route(ejecutar, { 'id': selectedUser.id, 'rol': selectedRole })" method="post"
                    class="bg-green-500 text-white rounded-lg px-2" :preserve-state="false">
                Continuar
                </Link>
                <Link v-if="ejecutar == 'desasociarUbicacion'"
                    :href="route(ejecutar, { 'id': selectedUser.id, 'id_ubicacion': ubicacion_id })" method="post"
                    class="bg-green-500 text-white rounded-lg px-2" :preserve-state="false">
                Continuar
                </Link>
                <button @click="cancelar" class="bg-red-500 text-white rounded-lg px-2">Cancelar</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import { defineProps } from 'vue';
import { computed, reactive } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3'


const props = defineProps({
    selectedUser: Object,
    error: String,
    exito: String,
    allHorarios: Array,
});

const formHorario = reactive({
    horario_id: "",
})



let selectedRole = ref("");
let showAlert = ref(false);
let accion = ref("");
let ejecutar = ref("");

let ubicacion_id = ref("");

let isChecked = ref(false);


onMounted(() => {
    isChecked.value = props.selectedUser.requiere_ubicacion == 1;
})

let obtieneRol = computed(() => {
    if (props.selectedUser.roles.length === 0) {
        return "Normal";
    } else if (props.selectedUser.roles.some(role => role.role_name === "super-admin")) {
        return "Super Admin";
    } else {
        return "Admin";
    }
});



const superAdmin = () => {
    // Compruebo si soy admin o super-admin y devuelvo true si lo soy
    if (props.selectedUser.roles.some(role => role.role_name === "super-admin")) {
        return true;
    } else {
        return false;
    }
};

const cambiarRolAlert = () => {
    accion.value = "cambiar rol";
    showAlert.value = true;
    ejecutar.value = "cambiarRol";
};

const cancelar = () => {
    showAlert.value = false;
    accion.value = "";
    ejecutar.value = "";
};

const handleUbicacionCheckbox = () => {
    router.post('/toggle-requiere-ubicacion', {
        requiere_ubicacion: event.target.checked,
        idUser: props.selectedUser.id
    });
};

const desasociarUbicacionAlert = (ubicacionId, act, path) => {
    accion.value = act;
    ejecutar.value = path;
    ubicacion_id.value = ubicacionId;
    showAlert.value = true;
};

const asignarHorario = () => {
    router.post('/asignar-horario', {
        horario_id: formHorario.horario_id,
        idUser: props.selectedUser.id
    });
};

</script>