<template>
    <div v-if="exito"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex flex-col gap-1"
        role="alert">
        <p class="block sm:inline">- {{ exito }}</p>
    </div>
    <div class=" flex-col w-full min-h-[calc(100vh-5rem)] p-16">

        <div class="p-4 mb-6">
            <h1 class="text-4xl font-bold">Horarios</h1>

        </div>
        <div class="w-full  bg-white border border-gris-borde p-8 rounded-t-2xl">
            <Link :href="route('nuevoHorario')"
                class=" bg-azul-fondo-btn rounded-xl h-min w-max flex justify-center items-center hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse">
            <p class=" text-nowrap mx-2 text-lg text-white p-1"><span
                    class=" text-2xl font-bold mr-2 text-blue-100">+</span>A&ntilde;adir horario</p>
            </Link>
        </div>
        <div class="border border-gris-borde w-full rounded-b-xl">
            <div>
                <p class=" text-xl xl:text-2xl font-bold p-4">Lista de horarios</p>
                <div>
                    <div class=" bg-white rounded-b-xl p-3 flex flex-col gap-5  w-full">

                        <div v-for="(horario, index) in horarios" class="flex flex-col">
                            <div class=" flex flex-col sm:flex-row">
                                <p class=" text-xl ml-2 text-nowrap"><span class=" font-bold">#{{ horario.id }}</span>
                                    {{ horario.nombre }}</p>
                                <div class="flex gap-1  justify-end mr-5 sm:mr-11 w-full">
                                    <button
                                        @click="confirmacion(id = horario.id, nombre = horario.nombre, accion = 'editar', ruta = 'vistaEditaHorario')"
                                        class="h-6 w-6">
                                        <img class=" w-full h-full" src="/img/iconos/editar.png" alt="Icono editar"
                                            title="Editar">
                                    </button>
                                    <button
                                        @click="confirmacion(id = horario.id, nombre = horario.nombre, accion = 'borrar', ruta = 'borrarHorario')"
                                        class="h-6 w-6">
                                        <img class=" w-full h-full" src="/img/iconos/borrar.png" alt="Icono borrar"
                                            title="Borrar">
                                    </button>
                                </div>
                            </div>

                            <div class="pl-4 flex flex-col sm:flex-row sm:gap-10 flex-wrap ">
                                <div v-if="horario.tipo != 'flexible'" class="sm:w-56">
                                    <p>Entrada: {{ horario.hora_entrada }}</p>
                                    <p>Salida: {{ horario.hora_salida }}</p>
                                </div>
                                <div v-if="horario.tipo != 'flexible' && horario.tipo != 'continuo'" class="sm:w-56 ">
                                    <p>Descanso salida: {{ horario.descanso_salida }}</p>
                                    <p>Descanso entrada: {{ horario.descanso_entrada }}</p>
                                </div>
                                <div class="sm:w-56 ">
                                    <p v-if="horario.tipo != 'flexible'">Libre: {{ horario.libre }}</p>
                                    <p class=" font-bold">Total horas: {{ horario.total_horas }}</p>
                                </div>
                            </div>
                            <div v-if="index < horarios.length - 1"
                                class=" border-b border-gris-borde w-11/12 self-center mt-3"></div>
                            <div v-else class=" mt-2"></div>
                        </div>

                        <div v-if="showAlert" class="absolute inset-0 flex justify-center  text-2xl">
                            <div class=" sticky top-1/2 bg-white p-4 rounded shadow-gray-600 shadow-2xl h-max border">
                                <p>¿Seguro que desea <span class=" font-bold">{{ accion }}</span> el horario: <span
                                        class=" font-bold">{{ nombreHorario }}</span>?</p>
                                <div class="flex justify-end gap-6 mt-10">
                                    <Link @click="cancelar" :href="route(rute, { id: idHorario })"
                                        class="bg-green-500 text-white rounded-lg px-2">Continuar</Link>
                                    <button @click="cancelar"
                                        class="bg-red-500 text-white rounded-lg px-2">Cancelar</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';

const showAlert = ref(false);
const accion = ref('');
const nombreHorario = ref('');
const idHorario = ref('');
const rute = ref('');

const confirmacion = (id, nombre, act, ruta) => {
    idHorario.value = id;
    nombreHorario.value = nombre;
    accion.value = act;
    showAlert.value = true;
    rute.value = ruta;
}
const cancelar = () => {
    showAlert.value = false;
}

const props = defineProps({
    horarios: Array,
    flash: Object,
    exito: String,
    error: String,
});
</script>