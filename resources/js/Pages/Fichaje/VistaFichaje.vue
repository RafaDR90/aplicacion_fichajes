<template>
    <div class="flex">
        <h1>VISTA DE FICHAR</h1>
        <Link :href="route('fichar')">Fichar</Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const muestrainfo=()=>{
    //redirigo con inertia a /fichar

}
   
</script>