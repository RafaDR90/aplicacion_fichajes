<template>
    <div class="flex-col w-full min-h-[calc(100vh-5rem)] p-16">
        <div class="p-4 mb-6">
            <h1 class="text-4xl font-bold">Solicitar</h1>
        </div>

        <div class="w-full  bg-white border border-gris-borde p-8 rounded-2xl min-h-[55vh]">
            <div class="flex flex-col md:flex-row items-center gap-2 md:gap-5 lg:gap-14 justify-center ">
                <p @click="obtenerVacaciones" :class="{ ' bg-azul-fondo-btn-pulse shadow-xl': vista === 'vacaciones' }" class="  text-sm text-nowrap font-bold px-6 py-4 text-white bg-azul-fondo-btn w-min h-min border border-azul-fondo-btn rounded-xl hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse active:shadow-xl lg:text-xl lg:px-10 lg:py-5">Vacaciones</p>
                <div class=" border-b border-gris-borde  w-20 md:hidden"></div>
                <p @click="vista = ''" class="  text-sm text-nowrap font-bold px-6 py-4 text-white bg-azul-fondo-btn w-min h-min border border-azul-fondo-btn rounded-xl hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse active:shadow-xl lg:text-xl lg:px-10 lg:py-5">Otra opcion</p>
                <div class=" border-b border-gris-borde  w-20 md:hidden"></div>
                <p @click="vista = ''" class="  text-sm text-nowrap font-bold px-6 py-4 text-white bg-azul-fondo-btn w-min h-min border border-azul-fondo-btn rounded-xl hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse active:shadow-xl lg:text-xl lg:px-10 lg:py-5">Otra opcion</p>
            </div>
            <div v-if="vista=='vacaciones'" class=" w-full mt-12 h-96 bg-red-500">
                <p v-for="vaca in vacaciones">{{ vaca }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import { router } from '@inertiajs/vue3'


//obtengo props
const props = defineProps({
    vacaciones: Array
})

const vista = ref('')

const obtenerVacaciones = () => {
    router.get('/solicitud', { vista: 'vacaciones' },{preserveState: true});
    vista.value = 'vacaciones'
}

</script>