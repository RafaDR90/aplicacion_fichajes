<script setup>
import { onMounted } from "vue";
import "leaflet/dist/leaflet.css"; // Importa los estilos de Leaflet
import L from "leaflet"; // Importa la biblioteca Leaflet

const props = defineProps({
    lat: Number,
    lon: Number,
});

onMounted(() => {
    console.log('lat', props.lat);
    console.log('lon', props.lon);  // Asegúrate de que 'lon' está bien escrito

    if (props.lat && props.lon) {
        const map = L.map("map").setView([props.lat, props.lon], 13); // Ajuste del nivel de zoom

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        L.marker([props.lat, props.lon]).addTo(map);
    } else {
        console.error("Invalid coordinates: lat and lon must be provided.");
    }
});


</script>

<template>
    <div>
        <div id="map" class=" w-60 h-40"></div>
    </div>
</template>