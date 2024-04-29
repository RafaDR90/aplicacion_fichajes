<template>
    <div v-if="errorMessage"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flex flex-col gap-1"
        role="alert">
        <span class="block sm:inline">- {{ error }}</span>
    </div>
    <div v-if="successMessage"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex flex-col gap-1"
        role="alert">
        <p class="block sm:inline">- {{ exito }}</p>
    </div>
    <div class="flex flex-col items-center  h-[80vh] justify-between lg:justify-normal lg:gap-32  lg:w-1/2">
        <div class=" w-max mt-10 ">
            <p v-if="horario && horario.tipo != 'flexible'" class=" font-bold text-xl ml-1 lg:text-4xl">Horario</p>
            <p v-else-if="horario && horario.tipo == 'flexible'" class=" font-bold text-xl ml-1">Horario flexible</p>
            <p v-else class=" font-bold text-xl">No se le ha asignado ningun horario</p>
            <div v-if="horario" class=" bg-white flex gap-20 rounded-lg border border-gris-borde lg:text-2xl">
                <div class="">
                    <p v-if="horario.tipo != 'flexible'" class=" font-bold p-4"><span>{{
                        hourFormat(horario.hora_entrada) }}</span> - <span v-if="horario.descanso_salida">{{
                                hourFormat(horario.descanso_salida) }}</span><span v-else>{{ hourFormat(horario.hora_salida)
                            }}</span></p>
                    <p v-if="horario.tipo == 'partido'" class=" font-bold px-4 pb-4"><span>{{
                        hourFormat(horario.descanso_entrada) }}</span> -
                        <span>{{ hourFormat(horario.hora_salida) }}</span>
                    </p>
                </div>
                <div>
                    <p v-if="horario.libre" class=" pt-4 px-4"><span class=" font-bold">Tiempo libre:</span> {{
                        horario.libre }}</p>
                    <p class=" p-4"><span class=" font-bold">Total jornada:</span> {{ horario.total_horas }}</p>
                </div>

            </div>

        </div>
        <div class="  flex gap-10 lg:gap-32 max-w-[90%] items-center lg:absolute lg:right-[40%] lg:top-1/2">
            <Link :href="route('fichar')" :class="{
                'bg-red-500 hover:bg-red-400 active:bg-red-500': props.fichajes && props.fichajes[0] && props.fichajes[0].tipo === 'entrada',
                'bg-[#2196f3] hover:bg-[#64b5f6] active:bg-[#2196f3]': !props.fichajes || !props.fichajes[0] || props.fichajes[0].tipo === 'salida'
            }" class="inline-block text-white text-lg rounded-full px-10 py-4 lg:px-14 lg:py-6 transition-colors duration-200 ease-in-out shadow-xl hover:shadow-xl active:shadow-none lg:text-2xl">
            Fichar
            </Link>
            <div>
                <p class="font-bold lg:text-2xl">Tiempo transcurrido: <span
                        class=" text-gray-400 font-bold">{{ totalHours }}</span></p>
            </div>
        </div>

        <div class="w-11/12  max-w-96 mb-10 lg:absolute right-20 mt-10">
            <p class=" font-semibold text-lg ml-1 lg:text-3xl">Registro de hoy</p>
            <div class=" bg-white w-full  border border-gris-borde rounded-lg h-44 lg:h-[19.1vh] overflow-auto">
                <ul class="">
                    <li v-for="fichaje in fichajes" class=" ">
                        <p class=" ml-3 lg:text-lg">{{ fichaje.tipo.charAt(0).toUpperCase() + fichaje.tipo.slice(1) }}:
                            {{ formatTime(fichaje.created_at) }}</p>
                        <p class=" border-b"></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div
        class="fixed bottom-0 text-xl justify-center text-nowrap border border-gris-borde w-max left-1/2 transform -translate-x-1/2 bg-white rounded-tl-lg rounded-tr-lg p-4 hidden sm:flex">
        <p>Hora: <span>{{ clockFormat(serverTimeRef) }}</span></p>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps, onMounted, onUnmounted, computed } from 'vue';
import { ref, watchEffect } from 'vue';


const props = defineProps({
    error: String,
    exito: String,
    horario: Object,
    fichajes: Array,
    serverTime: String,

});

/*---------------------------------
    MENSAJE DE ERROR O EXITO
---------------------------------*/
let errorMessage = ref(props.error ?? null);
let successMessage = ref(props.exito ?? null);
/*---------------------------------*/

let serverTimeRef = ref();
let serverTimeToCompare = ref();
let totalHours = ref(['00:00:00']);

watchEffect(() => {
    errorMessage.value = props.error;
    successMessage.value = props.exito;
    serverTimeToCompare.value = new Date(props.serverTime);
    serverTimeRef.value = new Date(new Date(props.serverTime).getTime() - 2 * 60 * 60 * 1000);

    if (props.error) {
        setTimeout(() => {
            errorMessage.value = null;
        }, 10000);
    }
    if (props.exito) {
        setTimeout(() => {
            successMessage.value = null;
        }, 10000);
    }
});

let intervalId;
onMounted(() => {
    intervalId = setInterval(() => {
        if (serverTimeRef.value && serverTimeToCompare.value) {
            serverTimeRef.value = new Date(serverTimeRef.value.getTime() + 1000);
            serverTimeToCompare.value = new Date(serverTimeToCompare.value.getTime() + 1000);
        }
    }, 1000);
});
onUnmounted(() => {
    clearInterval(intervalId);
});

const muestrainfo = () => {
    //redirigo con inertia a /fichar

}

const hourFormat = (hour) => {
    if (!hour) return '';
    let hourArray = hour.split(':');
    return hourArray[0] + ':' + hourArray[1];
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

const clockFormat = (date) => {
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');
    return `${hours}:${minutes}:${seconds}`;
};

const calculateTime = () => {
    let totalSeconds = 0;
    let lastEntry;
    for (let i = props.fichajes.length - 1; i >= 0; i--) {
        const fichaje = props.fichajes[i];
        if (fichaje.tipo === 'entrada') {
            lastEntry = new Date(fichaje.created_at);
        } else {
            const salida = new Date(fichaje.created_at);
            totalSeconds += (salida - lastEntry) / 1000;
            lastEntry = null;

        }
    }


    // Si el ultimo fichaje fue una entrada, suma la diferencia con la hora actual
    if (lastEntry) {
        const serverTime = new Date(serverTimeToCompare.value);

        totalSeconds += (serverTime - lastEntry) / 1000;
    }

    const hours = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
    const minutes = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
    const seconds = Math.floor(totalSeconds % 60).toString().padStart(2, '0');

    totalHours.value = `${hours}:${minutes}:${seconds}`;
};

// Llamar a calculateTime cada vez que fichajes o serverTimeRef cambie
watchEffect(() => {
    if (props.fichajes)
        calculateTime();
});



</script>