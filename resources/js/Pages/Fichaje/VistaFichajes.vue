<template>
    <div class="flex-col w-full min-h-[calc(100vh-5rem)] p-10">
        <div class="p-4 mb-6">
            <h1 class="text-4xl font-bold">Fichajes</h1>
        </div>

        <div class="w-full  bg-white border border-gris-borde p-8 rounded-t-2xl">
            <div class="flex flex-col  justify-between gap-4">

                <div class=' h-16'>
                    <div
                        class="relative flex items-center w-full rounded-lg focus-within:shadow-lg bg-white overflow-hidden h-full border border-gris-borde">
                        <div class="grid place-items-center h-full w-12 text-gray-300 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input v-focus v-model="searchName" @input="updateSearchName"
                            class="peer h-full w-full outline-none text-lg text-gray-700 pr-2 border-none placeholder:text-gray-300 focus:ring-0"
                            type="text" id="search" placeholder="Buscar por nombre..." />
                    </div>
                </div>

                <div class="flex flex-col gap-0 ">
                    <label for="fecha" class="text-md text-gray-600 font-semibold">Filtrar por fecha:</label>
                    <input type="date" id="fecha" name="fecha" :value="searchDateServer ?? null"
                        @change="updateSearchDate" class="border border-gris-borde rounded-lg p-4 h-16 text-lg  w-max">
                </div>
            </div>

        </div>

        <div class="border border-gris-borde w-full ">
            <div>

                <div class="flex flex-col  relative xl:justify-between" v-for="(user, index) in users.data"
                    :key="user.id" :class="index % 2 == 0 ? 'bg-white' : 'bg-gray-100'">
                    <div class="p-2 xl:flex xl:gap-2">
                        <div class=" text-sm">
                            <p class=" font-bold">{{ user.name }} {{ user.apellidos }}</p>
                            <p class=" text-nowrap"><span class=" font-semibold text-gray-700">Email: </span>{{
                                user.email }}</p>
                        </div>
                    </div>
                    <div v-if="user.checkins && user.checkins.length > 0"
                        class=" w-11/12 max-h-48   border bg-white border-gris-borde self-center mb-3 flex rounded-md">
                        <ul class=" w-4/6 border-r border-gris-borde overflow-auto">
                            <li v-for="checkin in user.checkins" class=" ">
                                <p class=" ml-3 text-sm">{{ checkin.tipo.charAt(0).toUpperCase() +
                                    checkin.tipo.slice(1) }}:
                                    {{ formatTime(checkin.created_at) }}</p>
                                <p class=" border-b"></p>
                            </li>
                        </ul>
                        <p class=" text-center  self-center w-[44%] font-bold"> Jornada: <span
                                :class="getWorkedHours(user.checkins) === 'En proceso' ? 'text-green-500' : 'text-red-500'">{{
                                    getWorkedHours(user.checkins) }}</span></p>
                    </div>

                </div>

            </div>
        </div>
        <nav>
            <nav class=" bg-white rounded-b-xl border border-gris-borde">
                <Pagination :pagination="users" :dateFilter="searchDateServer" :search="searchNameServer" />
            </nav>
        </nav>
    </div>
</template>
<script setup>
//defino las props
import { defineProps, onMounted, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';


const props = defineProps({
    error: String,
    exito: String,
    users: Array,
    serverDate: String,
    searchDateServer: String,
    searchNameServer: String
});

let searchName = ref('');
let searchDate = ref('');




const updateSearchDate = (event) => {
    searchDate.value = event.target.value;
    router.get('/fichajes', { dateFilter: searchDate.value, search: props.searchNameServer });
}

const formatTime = (dateString) => {
    let date = new Date(dateString);
    let hours = date.getUTCHours();
    let minutes = date.getUTCMinutes();
    let seconds = date.getUTCSeconds();

    // Asegurarse de que las horas, los minutos y los segundos siempre tengan dos dígitos
    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;

    return hours + ':' + minutes + ':' + seconds;
}

const getWorkedHours = (checkins) => {
    //compruebo si el ultimo checkin es de salida
    if (checkins[0].tipo != 'salida') {
        return 'En proceso';
    }
    //calculo las horas trabajadas
    let totalSeconds = 0;
    for (let i = checkins.length - 1; i > 0; i -= 2) {
        let checkin = checkins[i];
        let checkout = checkins[i - 1];
        let checkinDate = new Date(checkin.created_at);
        let checkoutDate = new Date(checkout.created_at);
        let diff = (checkoutDate - checkinDate) / 1000;
        totalSeconds += diff;
    }
    let hours = Math.floor(totalSeconds / 3600);
    let minutes = Math.floor((totalSeconds % 3600) / 60);
    return `${hours}:${minutes < 10 ? '0' + minutes : minutes} h.`;
}

let intervaloSearch = null;
const updateSearchName = (event) => {
    if (searchName.value == props.searchNameServer || (event.data === ' ' && searchName.value.slice(-1) === ' ')) {
        return;
    }
    if (intervaloSearch) {
        clearInterval(intervaloSearch);
    }
    intervaloSearch = setInterval(() => {
        router.get('/fichajes', { dateFilter: props.searchDateServer, search: searchName.value }, { preserveState: true});
        clearInterval(intervaloSearch);
    }, 500);
}

</script>