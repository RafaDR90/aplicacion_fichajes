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
    <div class="flex-col w-full min-h-[calc(100vh-5rem)] p-4">
        <div class="p-4 mb-6">
            <h1 class="text-4xl font-bold">Solicitar</h1>
        </div>

        <div class="w-full  bg-white border border-gris-borde p-8 rounded-2xl min-h-[55vh]">
            <div
                class="flex flex-col md:flex-row items-center gap-2 md:gap-5 lg:gap-14 justify-center border-b pb-4 border-gris-borde ">
                <p @click="obtenerVacaciones" :class="{ ' bg-azul-fondo-btn-pulse shadow-xl': vista === 'vacaciones' }"
                    class=" cursor-pointer  text-sm text-nowrap font-bold px-6 py-4 text-white bg-azul-fondo-btn w-min h-min border border-azul-fondo-btn rounded-xl hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse active:shadow-xl lg:text-xl lg:px-10 lg:py-5">
                    Vacaciones</p>
                <div class=" border-b border-gris-borde  w-20 md:hidden"></div>
                <p @click="vista = ''"
                    class="cursor-pointer text-sm text-nowrap font-bold px-6 py-4 text-white bg-azul-fondo-btn w-min h-min border border-azul-fondo-btn rounded-xl hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse active:shadow-xl lg:text-xl lg:px-10 lg:py-5">
                    Otra opcion</p>
                <div class=" border-b border-gris-borde  w-20 md:hidden"></div>
                <p @click="vista = ''"
                    class="cursor-pointer text-sm text-nowrap font-bold px-6 py-4 text-white bg-azul-fondo-btn w-min h-min border border-azul-fondo-btn rounded-xl hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse active:shadow-xl lg:text-xl lg:px-10 lg:py-5">
                    Otra opcion</p>
            </div>
            <div v-if="vista == 'vacaciones'" class=" w-full flex flex-col items-center ">
                <ul class=" list-disc mt-5 lg:flex lg:gap-20">
                    <li class=" text-[#B21414] font-bold">Fines de semana y festivos</li>
                    <li class="font-bold text-yellow-400 text-nowrap">Vacaciones pendientes de confirmacion</li>
                    <li class="font-bold text-green-400">Vacaciones</li>
                </ul>
                <div class="flex flex-col w-full items-center">
                    <button @click="solicitarDiasSeleccionados"
                        class=" p-4 w-max bg-green-300 rounded-md mt-10 font-bold hover:bg-green-200 shadow-md active:shadow-none active:bg-green-300">Solicitar
                        dias seleccionados</button>
                    <button @click="deseleccionarTodo"
                        class=" p-4 w-max bg-red-500 rounded-md mt-10 font-bold hover:bg-red-400 shadow-md active:shadow-none active:bg-red-500">Deseleccionar
                        todo</button>
                    <p class=" font-bold text-sm mt-2"><span class=" font-bold text-gray-600">Dias disponibles:</span> 2
                    </p>
                </div>

            </div>
            <div v-if="vista == 'vacaciones'" class=" w-full mt-12 min-h-96 flex justify-center ">
                <FullCalendar :options="calendarOptions" class="w-[80vh]" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import { router } from '@inertiajs/vue3'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import esLocale from '@fullcalendar/core/locales/es';



//obtengo props
const props = defineProps({
    vacaciones: Array,
    exito: String,
    error: String,
})


const hoy = new Date()
const fechaActual = new Date(hoy.getFullYear(), hoy.getMonth())
const seisMesesDespues = new Date(hoy.getFullYear(), hoy.getMonth() + 8, 0)

const vista = ref('')
const fechasSeleccionadas = ref([]);

const obtenerVacaciones = () => {
    router.get('/solicitud', { vista: 'vacaciones' }, { preserveState: true });
    vista.value = 'vacaciones'
}

const deseleccionarTodo = () => {
    fechasSeleccionadas.value.forEach(fecha => {
        const dia = document.querySelector(`[data-date="${fecha}"]`);
        if (dia) {
            dia.style.border = '1px solid #D8D8D8';
            dia.style.backgroundColor = 'white';
        }
    });
    fechasSeleccionadas.value = [];
}

const solicitarDiasSeleccionados = () => {
    if (fechasSeleccionadas.value.length === 0) {
        return
    }
    router.post('/solicitud-vacaciones', { dias: fechasSeleccionadas.value }, { preserveState: false, refresh: true});
}

const calendarOptions = {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    datesSet: (info) => {
        fechasSeleccionadas.value.forEach(element => {
            const dia = document.querySelector(`[data-date="${element}"]`)
            if (dia) {
                dia.style.border = '4px solid green';
                dia.style.backgroundColor = 'rgba(0, 128, 0, 0.1)';
            }
        });
    },
    dateClick: (info) => {
        const dia = new Date(info.dateStr).getDay()
        if (dia === 0 || dia === 6 || info.date < new Date()) {
            return
        }
        let encontrado = false;
        props.vacaciones.forEach(vacacion => {
            if (vacacion.fecha === info.dateStr) {
                encontrado = true;
                return
            }
        });
        if (encontrado) {
            return
        }

        if (fechasSeleccionadas.value.includes(info.dateStr)) {
            fechasSeleccionadas.value = fechasSeleccionadas.value.filter(f => f !== info.dateStr)
            info.dayEl.style.backgroundColor = 'white';
            info.dayEl.style.border = '1px solid #D8D8D8';
        } else {
            fechasSeleccionadas.value.push(info.dateStr)
            info.dayEl.style.border = '4px solid green';
            info.dayEl.style.backgroundColor = 'rgba(0, 128, 0, 0.1)';

        }
    },
    events: props.events || [],
    validRange: {
        start: fechaActual,
        end: seisMesesDespues
    },
    firstDay: 1,
    locale: esLocale,
    dayCellDidMount: (info) => {
        //si la fecha es menor a la actual la pongo en gris
        if (new Date(info.date) < new Date()) {
            info.el.style.backgroundColor = 'grey';
        }
        //si es fin de semana lo pongo en color granate
        if (new Date(info.date).getDay() === 0 || new Date(info.date).getDay() === 6) {
            info.el.style.backgroundColor = '#B21414';
        }

        // Si la fecha existe en props.vacaciones, cambiar el color de fondo a amarillo
        props.vacaciones.forEach(vacacion => {
            // Crear la fecha en UTC
            let date = new Date(info.date);
            let utcDate = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));

            if (vacacion.fecha === utcDate.toISOString().slice(0, 10)) {
                if (vacacion.aprobada === 0) {
                    info.el.style.backgroundColor = '#FBBF24';
                } else {
                    info.el.style.backgroundColor = '#34D399';
                }
            }
        });
    },

}



</script>
