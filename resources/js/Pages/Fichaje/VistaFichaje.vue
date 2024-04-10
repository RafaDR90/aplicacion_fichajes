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
    <div class="flex">
        <h1>VISTA DE FICHAR</h1>
        <Link :href="route('fichar')">Fichar</Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { ref, watchEffect } from 'vue';


const props = defineProps({
    error: String,
    exito: String,
});

/*---------------------------------
    MENSAJE DE ERROR O EXITO
---------------------------------*/
let errorMessage = ref(props.error??null);
let successMessage = ref(props.exito??null);

watchEffect(() => {
    errorMessage.value = props.error;
    successMessage.value = props.exito;

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

const muestrainfo = () => {
    //redirigo con inertia a /fichar

}

</script>