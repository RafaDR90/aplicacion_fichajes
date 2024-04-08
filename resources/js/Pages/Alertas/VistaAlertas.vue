<template>
    <div class="flex-col w-full min-h-[calc(100vh-5rem)] p-4">
        <div class="p-4 mb-6">
            <h1 class="text-4xl font-bold">Notificaciones</h1>
        </div>

        <div class="w-full  bg-white border border-gris-borde p-8 rounded-t-2xl">
            <!-- Aqui va la barra de busqueda y filtros-->
            <select :value="filter" @change="updateFilter" name="filter" id=""
                class="border border-gris-borde rounded-lg p-4 h-16 text-lg pr-10">
                <option class=" text-gray-500" value="">Todo</option>
                <option value="ubicacion">Solicitud de ubicaciones</option>
                <option value="vacaciones">Solicitud de vacaciones</option>
            </select>
        </div>

        <div class="border border-gris-borde w-full ">
            <div>
                <p class=" text-xl xl:text-2xl font-bold p-4">Lista de notificaciones</p>
                <div>
                    <!--Aqui las notificaciones con un v-for-->
                    <div class=" bg-white rounded-b-xl p-3 flex flex-col gap-5  w-full border-t border-gris-borde">
                        <div v-for="alerta in alertas.data" :key="alerta.id">
                            <div v-if="alerta.tipo == 'ubicacion'">
                                <p class=" text-green-500 font-bold text-xl mb-2">{{ alerta.mensaje }}</p>
                                <div class="flex flex-col sm:flex-row sm:gap-40">
                                    <div>
                                        <p><span class=" font-bold">De: </span>#{{ alerta.user.id }} {{ alerta.user.name
                                            }}
                                            {{ alerta.user.apellidos }}</p>
                                        <p><span class=" font-bold">C&oacute;digo de pa&iacute;s: </span>{{
                                            alerta.datos.countryCode }}</p>
                                    </div>
                                    <div>
                                        <p><span class=" font-bold">Ciudad: </span> {{ alerta.datos.city }}</p>
                                        <p><span class=" font-bold">CP: </span>{{ alerta.datos.zip }}</p>
                                    </div>
                                    <div>
                                        <p><span class=" font-bold">Latitud: </span>{{ alerta.datos.lat }}</p>
                                        <p><span class=" font-bold">Longitud: </span>{{ alerta.datos.lon }}</p>
                                    </div>

                                </div>
                                <div class="flex gap-3 mt-3">
                                    <button
                                        @click="openConfirmation(alerta.id, alerta.user.id, 'añadir la ubicación', alerta.user.name + ' ' + alerta.user.apellidos, 'addUbicacion')"
                                        class="bg-green-500 text-white p-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-50 hover:bg-green-600 active:bg-green-700">Aceptar</button>

                                    <button
                                        @click="openConfirmation(alerta.id, alerta.user.id, 'denegar la ubicación', alerta.user.name + ' ' + alerta.user.apellidos, 'denyUbicacion')"
                                        class="bg-red-500 text-white p-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-50 hover:bg-red-600 active:bg-red-700">Denegar</button>

                                </div>
                                <p class=" text-sm text-gray-500 mt-2"><span class=" font-bold">Fecha: </span>{{ new
                                    Date(alerta.created_at).toLocaleDateString('es-ES', {
                                        year: 'numeric', month: 'long', day: 'numeric'
                                    }) }} a las {{ new
                                        Date(alerta.created_at).toLocaleTimeString('es-ES', {
                                            hour: '2-digit', minute:
                                                '2-digit'
                                        }) }}</p>
                                <!-- Linea separadora -->
                                <div v-if="alerta != alertas.data[alertas.data.length - 1]"
                                    class="border-t border-gris-borde w-full mt-4">
                                </div>
                            </div>

                            <div v-if="alerta.tipo == 'vacaciones'">
                                <p class=" text-blue-500 font-bold text-xl mb-2">{{ alerta.mensaje }}</p>
                                <div class="flex flex-col sm:flex-row sm:gap-40">
                                    <div>
                                        <p><span class=" font-bold">De: </span>#{{ alerta.user.id }} {{ alerta.user.name
                                            }}
                                            {{ alerta.user.apellidos }}</p>
                                        <p><span class=" font-bold">Dias solicitados: </span><span
                                                v-for="(dia, index) in alerta.datos">{{ new
                                                    Date(dia).toLocaleDateString('es-ES') }} <span
                                                    v-if="index !== alerta.datos.length - 1"> - </span> <span
                                                    v-else>.</span> </span></p>
                                    </div>

                                </div>
                                <div class="flex gap-3 mt-3">
                                    <button
                                        @click="openConfirmation(alerta.id, alerta.user.id, 'añadir vacaciones', alerta.user.name + ' ' + alerta.user.apellidos, 'aceptarVacaciones')"
                                        class="bg-green-500 text-white p-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-50 hover:bg-green-600 active:bg-green-700">Aceptar</button>

                                    <button
                                        @click="openConfirmation(alerta.id, alerta.user.id, 'denegar vacaciones', alerta.user.name + ' ' + alerta.user.apellidos, 'deniegaVacaciones')"
                                        class="bg-red-500 text-white p-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-50 hover:bg-red-600 active:bg-red-700">Denegar</button>

                                </div>
                                <p class=" text-sm text-gray-500 mt-2"><span class=" font-bold">Fecha: </span>{{ new
                                    Date(alerta.created_at).toLocaleDateString('es-ES', {
                                        year: 'numeric', month: 'long', day: 'numeric'
                                    }) }} a las {{ new
                                        Date(alerta.created_at).toLocaleTimeString('es-ES', {
                                            hour: '2-digit', minute:
                                                '2-digit'
                                        }) }}</p>
                                <!-- Linea separadora -->
                                <div v-if="alerta != alertas.data[alertas.data.length - 1]"
                                    class="border-t border-gris-borde w-full mt-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class=" bg-white rounded-b-xl border border-gris-borde">
            <Pagination :pagination="alertas" :filter="filter" />
        </nav>
    </div>




    <div v-if="showAlert" class="absolute inset-0 flex justify-center  text-2xl">
        <div class=" sticky top-1/2 bg-white p-4 rounded shadow-gray-600 shadow-2xl h-max border">
            <p>¿Seguro que desea <span class=" font-bold">{{ accion }}</span> a <span class=" font-bold">{{
                nombreEmp }}</span>?</p>
            <div class="flex justify-end gap-6 mt-10">
                <Link @click="closeAlert" :href="route(rute, { idAlert: idAlerta, idUser: idEmp })" method="post"
                    class="bg-green-500 text-white rounded-lg px-2">
                Continuar
                </Link>
                <button @click="closeAlert" class="bg-red-500 text-white rounded-lg px-2">Cancelar</button>
            </div>
        </div>
    </div>
</template>

<script setup>

import { ref, defineProps } from 'vue';
import { Link } from "@inertiajs/vue3";
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3';



const props = defineProps({
    alertas: Array,
    filter: String
});
// Variables para la confirmacion.
const showAlert = ref(false);
const accion = ref('');
const nombreEmp = ref('');
const idEmp = ref('');
const rute = ref('');
const idAlerta = ref('');
const sortField = ref('');
const search = ref('');

const updateFilter = (event) => {
    router.get(`/alertas?filter=${event.target.value}`);
};



function openConfirmation(id, idEmpleado, act, nombre, ruta) {
    idAlerta.value = id;
    idEmp.value = idEmpleado;
    nombreEmp.value = nombre;
    accion.value = act;
    rute.value = ruta;
    showAlert.value = true;

}

function closeAlert() {
    showAlert.value = false;
}





</script>