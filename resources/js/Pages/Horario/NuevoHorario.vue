<template>

    <div v-if="erroresRef"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flex flex-col gap-1"
        role="alert">
        <strong class="font-bold">¡Ups!</strong>
        <span v-for="errorGroup in errores">
            <p class="block sm:inline" v-for="error in errorGroup">- {{ error }}</p>
        </span>
    </div>


    <div class=" flex-col w-full min-h-[calc(100vh-5rem)] p-16">
        <div class="flex self-start ml-5 mt-0">
            <div v-for="(breadcrumb, index) in breadcrumbs" :key="index" class="flex">
                <Link v-if="index !== breadcrumbs.length - 1" :href="breadcrumb.url"><span
                    class=" text-gray-700 font-semibold hover:text-primary-strong">{{ breadcrumb.title }}</span>
                <span>></span> </Link>
                <Link v-else class=" text-gray-700 font-semibold"> &nbsp;{{ breadcrumb.title }}</Link>
            </div>
        </div>
        <div class="p-4 mb-6">
            <h1 class="text-4xl font-bold">{{ horario ? 'Editar horario' : 'Nuevo horario' }}</h1>
            <div class=" w-full rounded-b-xl">
                <form @submit.prevent="horario ? submitFormEdit() : submitForm()" class="space-y-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" id="nombre" v-model="form.nombre"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </div>
                    <div class=" w-full">
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select id="tipo" v-model="form.tipo"
                            class=" h-10 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="partido">Partido</option>
                            <option value="continuo">Continuo</option>
                            <option value="flexible">Flexible</option>
                        </select>
                        
                    </div>
                    <div class="flex w-full gap-5">
                        <div v-show="form.tipo != 'flexible'" class=" w-full">
                            <label for="libre" class="block text-sm font-medium text-gray-700">Libre (minutos)</label>
                            <input type="number" id="libre" v-model="form.libre"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                        <div class=" w-full">
                            <label for="totalHoras" class="block text-sm font-medium text-gray-700">Total horas
                                (H)</label>
                            <input type="number" id="totalHoras" v-model="form.totalHoras"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    <div v-show="form.tipo != 'flexible'" class="flex w-full gap-5">
                        <div class=" w-full">
                            <label for="entrada" class="block text-sm font-medium text-gray-700">Entrada</label>
                            <input type="time" id="entrada" v-model="form.hora_entrada"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                        <div class=" w-full">
                            <label for="salida" class="block text-sm font-medium text-gray-700">Salida</label>
                            <input type="time" id="salida" v-model="form.hora_salida"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                    </div>

                    <div v-show="form.tipo != 'continuo' && form.tipo != 'flexible'" class="flex w-full gap-5">
                        <div class=" w-full">
                            <label for="descanso_salida" class="block text-sm font-medium text-gray-700">Descanso
                                salida</label>
                            <input type="time" id="descanso_salida" v-model="form.descanso_salida"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                        <div class=" w-full">
                            <label for="descanso_entrada" class="block text-sm font-medium text-gray-700">Descanso
                                entrada</label>
                            <input type="time" id="descanso_entrada" v-model="form.descanso_entrada"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                    </div>




                    <button type="submit"
                        class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-azul-fondo-btn hover:bg-azul-fondo-btn-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:bg-azul-fondo-btn-pulse active:bg-azul-fondo-btn-pulse">
                        {{ horario ? 'Edita horario' : 'Crear horario' }}</button>
                </form>
            </div>
        </div>
    </div>
    <p>{{ horario }}</p>





</template>

<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import { watch } from 'vue';
import { reactive } from 'vue';
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';
import { watchEffect } from 'vue';
import { Link } from '@inertiajs/vue3';




const form = reactive({
    nombre: '',
    hora_entrada: '',
    hora_salida: '',
    descanso_salida: '',
    descanso_entrada: '',
    libre: '',
    tipo: '',
    totalHoras: '',
});


watch(() => form.tipo, (newTipo) => {
    if (newTipo === 'continuo' || newTipo === 'flexible') {
        form.descanso_salida = null;
        form.descanso_entrada = null;
    }
    if (newTipo === 'flexible') {
        form.hora_entrada = null;
        form.hora_salida = null;
    }
});



function submitForm() {
    router.post('/nuevo-horario', form)
}

function submitFormEdit() {
    router.post(`/editar-horario/${props.horario.id}`, form)
}


const props = defineProps({
    errores: Array,
    horario: Array,
    exito: String,
    breadcrumbs: Array
})

console.log(props.breadcrumbs)
watch(() => props.horario, (newHorario) => {
    if (newHorario) {
        form.nombre = newHorario.nombre || '';
        form.hora_entrada = newHorario.hora_entrada ? newHorario.hora_entrada.substr(0, 5) : '';
        form.hora_salida = newHorario.hora_salida ? newHorario.hora_salida.substr(0, 5) : '';
        form.descanso_salida = newHorario.descanso_salida ? newHorario.descanso_salida.substr(0, 5) : '';
        form.descanso_entrada = newHorario.descanso_entrada ? newHorario.descanso_entrada.substr(0, 5) : '';
        form.libre = newHorario.libre || '';
        form.tipo = newHorario.tipo || '';
        form.totalHoras = newHorario.total_horas || '';
    }
}, { immediate: true });

let erroresRef = ref(props.errores)

watch(() => {
    erroresRef.value = props.errores
})

</script>