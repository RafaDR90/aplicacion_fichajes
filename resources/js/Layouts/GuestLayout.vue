<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex justify-center items-center bg-white">
        <div class=" w-full min-h-screen flex flex-col lg:flex-row ">
            <div class="  w-full flex flex-col items-center mb-20 ">
                <img src="/img/dashboard/dashboard_image.png" alt="" class=" rounded-3xl w-min">
                <div class=" w-full flex flex-col items-center mt-20">
                    <h2 class=" text-5xl font-bold w-[350px] text-[#008afc]">Control horario de empleados</h2>
                    <div class="w-[350px] h-5 bg-[#2196f3] rounded-full mt-4 mb-8" />
                    <p class=" text-lg font-semibold text-center w-[350px] sm:w-[500px] text-wrap">Combina fichajes en el centro de trabajo, a
                        través de internet y desde el móvil en una sola herramienta y gestiona a toda tu plantilla desde
                        un mismo panel de control.</p>
                </div>
            </div>
            <div class="w-full lg:w-[700px] 2xl:w-[900px] min-h-screen bg-[#2196f3] flex justify-center items-center">
                <div class="w-11/12 sm:w-8/12 lg:w-10/12 flex flex-col items-center justify-center  ">
                    <slot />
                </div>
            </div>
        </div>




    </div>
</template>
