<template>
    <div id="sidebar" :class="{ '-translate-x-full': isHidden, 'translate-x-0': !isHidden }"
        class=" bg-white fixed h-full top-0 left-0 transition-all duration-300 transform z-50  w-80 border-r border-gray-300">
        <div class="h-16">
            <img class="h-full ml-5 mt-2" src="/img/sidebar/logo.png" alt="">
        </div>
        <div class="w-full h-[calc(100%-4.5rem)] flex flex-col gap-4 items-center overflow-auto">

            <Link :href="route('fichaje')" class=" button_nav mt-10">
            <div class="h-6 w-6">
                <img class="h-full w-full" src="/img/sidebar/fichajes.png" alt="">
            </div>
            <p class="mr-8">Fichar</p>
            </Link>

            <Link :href="route('solicitud')" class=" button_nav ">
                <div class="h-6 w-6">
                    <img class="h-full w-full" src="/img/sidebar/solicitud.png" alt="">
                </div>
                <p class="mr-8">Solicitud</p>
            </Link>

            <p v-if="$page.props.auth.user.roles && $page.props.auth.user.roles.length > 0 && ($page.props.auth.user.roles[0].role_name=='super-admin' || $page.props.auth.user.roles[0].role_name=='admin')" class=" text-center w-10/12 border-b border-gris-borde pb-2 mt-3 text-xl">Panel Administracion</p>
            <Link v-if="$page.props.auth.user.roles && $page.props.auth.user.roles.length > 0 && ($page.props.auth.user.roles[0].role_name=='super-admin' || $page.props.auth.user.roles[0].role_name=='admin')" :href="route('showUsers')" class=" button_nav ">
            <div class="h-6 w-6">
                <img class="h-full w-full" src="/img/sidebar/empleado.png" alt="">
            </div>
            <p class="mr-8">Empleados</p>
            </Link>

            <Link v-if="$page.props.auth.user.roles && $page.props.auth.user.roles.length > 0 && ($page.props.auth.user.roles[0].role_name=='super-admin' || $page.props.auth.user.roles[0].role_name=='admin')" :href="route('alertas')" class="button_nav">
    <div class="h-6 w-6">
        <img class="h-full w-full" src="/img/sidebar/incidente.png" alt="">
    </div>
    <p class="mr-8">Notificaciones</p>
    <div v-if="notificacionesCount > 0" class="bg-red-500 rounded-full h-max w-max px-1 flex items-center justify-center text-white">
        {{ notificacionesCount }}
    </div>
</Link>

            <Link v-if="$page.props.auth.user.roles && $page.props.auth.user.roles.length > 0 && ($page.props.auth.user.roles[0].role_name=='super-admin' || $page.props.auth.user.roles[0].role_name=='admin')" :href="route('showFichajes')" class=" button_nav ">
                <div class="h-6 w-6">
                    <img class="h-full w-full" src="/img/sidebar/fichajes.png" alt="">
                </div>
                <p class="mr-8">Fichajes</p>
            </Link>

            <Link v-if="$page.props.auth.user.roles && $page.props.auth.user.roles.length > 0 && ($page.props.auth.user.roles[0].role_name=='super-admin' || $page.props.auth.user.roles[0].role_name=='admin')" :href="route('horarios')" class=" button_nav ">
                <div class="h-6 w-6">
                    <img class="h-full w-full" src="/img/sidebar/calendario.png" alt="">
                </div>
                <p class="mr-8">Horarios</p>
            </Link>
            


        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from "@inertiajs/vue3";


const props = defineProps({
    isHidden: Boolean,
});
let { props: pageProps } = usePage();


/*--------------------
    NOTIFICACIONES
--------------------*/
const notificacionesCount = ref(0);
const fetchNotificaciones = async () => {
  const response = await fetch(`${window.location.origin}/api/obtiene-notificaciones`, {
    headers: {
      'Authorization': `Bearer ${pageProps.auth.token}`,
      'Accept': 'application/json',
    },
  });
  const data = await response.json();
  notificacionesCount.value = data.notificacionesCount;
  console.log(data.notificacionesCount);
};


let interval;

onMounted(() => {
  if (pageProps.auth.token) {
    interval = setInterval(() => {
      fetchNotificaciones();
    }, 5000);
  }
});

onUnmounted(() => {
  if (pageProps.auth.token)
    clearInterval(interval);
});



</script>