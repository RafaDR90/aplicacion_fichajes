<script setup>
import { onMounted, ref } from "vue";
import "leaflet/dist/leaflet.css"; // Importa los estilos de Leaflet
import L from "leaflet"; // Importa la biblioteca Leaflet
import markerIconUrl from "@/Assets/marker-icon.png";


const props = defineProps({
    lat: Number,
    lon: Number,
});

const mapContainer = ref(null);

onMounted(() => {
    if (props.lat && props.lon) {
        const map = L.map(mapContainer.value).setView([props.lat, props.lon], 13); // Ajuste del nivel de zoom

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        const icon = L.icon({
            iconUrl: markerIconUrl,
            iconSize: [25, 41], // tamaño del icono
            iconAnchor: [12, 41], // punto donde el icono está anclado
            popupAnchor: [1, -34], // punto donde se muestra el popup
        });

        L.marker([props.lat, props.lon],{icon}).addTo(map);
    } else {
        console.error("Invalid coordinates: lat and lon must be provided.");
    }
});


</script>

<template>
    <div>
        <div ref="mapContainer" class=" w-60 h-40"></div>
    </div>
</template>