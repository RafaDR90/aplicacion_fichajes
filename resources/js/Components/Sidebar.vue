<template>
    <div id="sidebar" :class="{ '-translate-x-full': isHidden, 'translate-x-0': !isHidden }"
        class=" bg-white fixed h-full top-0 left-0 transition-all duration-300 transform z-50  w-80 border-r border-gray-300">
        <div class="h-16">
            <img class="h-full ml-5 mt-2" src="img/sidebar/logo.png" alt="">
        </div>
        <div class="w-full h-[calc(100%-4.5rem)] flex flex-col gap-1 items-center justify-around">

            <Link :href="route('fichaje')" class=" button_nav ">
            <div class="h-6 w-6">
                <img class="h-full w-full" src="img/sidebar/fichajes.png" alt="">
            </div>
            <p class="mr-8">Fichar</p>
            </Link>

            <Link :href="route('showUsers')" class=" button_nav ">
            <div class="h-6 w-6">
                <img class="h-full w-full" src="img/sidebar/empleado.png" alt="">
            </div>
            <p class="mr-8">Empleados</p>
            </Link>

            <button class=" button_nav ">
                <div class="h-6 w-6">
                    <img class="h-full w-full" src="img/sidebar/incidente.png" alt="">
                </div>
                <p class="mr-8">Incidencias</p>
            </button>

            <button class=" button_nav ">
                <div class="h-6 w-6">
                    <img class="h-full w-full" src="img/sidebar/ausencia.png" alt="">
                </div>
                <p class="mr-8">Ausencias</p>
            </button>

            <button class=" button_nav ">
                <div class="h-6 w-6">
                    <img class="h-full w-full" src="img/sidebar/fichajes.png" alt="">
                </div>
                <p class="mr-8">Fichajes</p>
            </button>

            <Link :href="route('horarios')" class=" button_nav ">
                <div class="h-6 w-6">
                    <img class="h-full w-full" src="img/sidebar/calendario.png" alt="">
                </div>
                <p class="mr-8">Horarios</p>
            </Link>


        </div>
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';
import { Link } from "@inertiajs/vue3";


const props = defineProps({
    isHidden: Boolean,
});



</script>