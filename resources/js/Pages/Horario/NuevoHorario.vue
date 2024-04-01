<template>
    <div class=" flex-col w-full min-h-[calc(100vh-5rem)] p-16">
        <div class="p-4 mb-6">
            <h1 class="text-4xl font-bold">Nuevo horario</h1>

            <div class=" w-full rounded-b-xl">
                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="nombre" v-model="nombre"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </div>
                    <div class=" w-full">
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select id="tipo" v-model="tipo"
                                class=" h-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="partido">Partido</option>
                                <option value="continuo">Continuo</option>
                                <option value="flexible">Flexible</option>
                            </select>
                    </div>
                    <div class="flex w-full gap-5">
                        <div class=" w-full">
                            <label for="libre" class="block text-sm font-medium text-gray-700">Libre (minutos)</label>
                            <input type="number" id="libre" v-model="libre"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                        <div class=" w-full">
                            <label for="totalHoras" class="block text-sm font-medium text-gray-700">Total horas (H)</label>
                            <input type="number" id="totalHoras" v-model="totalHoras"  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    <div v-show="tipo !='flexible'" class="flex w-full gap-5">
                        <div class=" w-full">
                            <label for="entrada" class="block text-sm font-medium text-gray-700">Entrada</label>
                            <input type="time" id="entrada" v-model="entrada"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                        <div class=" w-full">
                            <label for="salida" class="block text-sm font-medium text-gray-700">Salida</label>
                            <input type="time" id="salida" v-model="salida"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                    </div>

                    <div v-show="tipo !='continuo' && tipo !='flexible'" class="flex w-full gap-5">
                        <div class=" w-full">
                            <label for="descanso_salida" class="block text-sm font-medium text-gray-700">Descanso
                                salida</label>
                            <input type="time" id="descanso_salida" v-model="descanso_salida"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                        <div class=" w-full">
                            <label for="descanso_entrada" class="block text-sm font-medium text-gray-700">Descanso
                                entrada</label>
                            <input type="time" id="descanso_entrada" v-model="descanso_entrada"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                    </div>

                    


                    <button type="submit"
                        class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-azul-fondo-btn hover:bg-azul-fondo-btn-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:bg-azul-fondo-btn-pulse active:bg-azul-fondo-btn-pulse">Crear
                        horario</button>
                </form>
            </div>
        </div>
    </div>





</template>

<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import { watch } from 'vue';

const nombre = ref('');
const entrada = ref('');
const salida = ref('');
const descanso_salida = ref('');
const descanso_entrada = ref('');
const libre = ref('');
const tipo = ref('');
const totalHoras = ref('');

watch(tipo, (newTipo) => {
    if (newTipo === 'continuo' || newTipo === 'flexible') {
        descanso_salida.value = null;
        descanso_entrada.value = null;
    }
    if (newTipo === 'flexible') {
        entrada.value = null;
        salida.value = null;
    }
});

const submitForm = () => {
    Inertia.post('/nuevo-horario', {
        nombre: nombre.value,
        entrada: entrada.value,
        salida: salida.value,
        descanso_salida: descanso_salida.value,
        descanso_entrada: descanso_entrada.value,
        libre: libre.value,
        tipo: tipo.value,
        totalHoras: totalHoras.value
    });
};
</script>