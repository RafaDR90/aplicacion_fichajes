<template>
  <nav @click="dropdownOpen = false" class="w-full h-20 flex items-center shadow-sm justify-between bg-gris-light">
    <button @click.stop="toggleSidebar"
      class="ml-2 menu-icon p-3 rounded-lg transition duration-100 hover:bg-gris-light hover:shadow-md focus:bg-gris-light focus:shadow-md active:bg-gris-light active:shadow-md">
      <div class="w-5 h-0.5 bg-gray-500 rounded-full"></div>
      <div class="w-5 h-0.5 bg-gray-500 my-1 rounded-full"></div>
      <div class="w-5 h-0.5 bg-gray-500 rounded-full"></div>
    </button>
    <div class="relative">
      <button @click="changueStatusDropdownPerfil" @click.stop
        class="relative flex justify-center items-center gap-2 px-2 mr-4 md:mr-14 rounded-lg transition duration-100 hover:bg-gris-light hover:shadow-md focus:bg-gris-light focus:shadow-md active:bg-gris-light active:shadow-md">
        <div class="h-9 w-9">
          <img class="h-full w-full rounded-full object-cover object-center" src="img/navbar/fotoperfil.png" alt="" />
        </div>
        <p v-if="$page && $page.props.auth.user">{{ $page.props.auth.user.name }} {{ $page.props.auth.user.apellidos }}</p>
        <div class="w-4 h-4">
          <img class="w-full Fh-full object-cover object-center mt-0.5" src="img/navbar/flecha-hacia-abajo.png"
            alt="Flecha" />
        </div>
      </button>
      <div v-show="dropdownPerfilOpen"
        class="absolute w-48 pb-2 ml-12 mt-0 bg-white rounded-lg shadow-xl transition duration-100 transform origin-top scale-95">
        <div @click.stop class="w-full flex flex-col justify-center items-center bg-primary-strong rounded-t-lg">
          <div class="w-20 h-20 my-2">
            <img class="w-full h-full rounded-full object-cover object-center" src="img/navbar/fotoperfil.png"
              alt="Foto de perfil" />
          </div>
          <p v-if="$page && $page.props.auth.user" class=" text-white py-2">{{ $page.props.auth.user.name }} {{ $page.props.auth.user.apellidos }}</p>
        </div>
        <Link @click="dropdownOpen = !dropdownOpen"
          class="block px-4 py-2 text-gray-800 hover:bg-gray-100 active:bg-gray-200" :href="route('profile.edit')">
        Perfil</Link>
        <DropdownLink @click="dropdownOpen = !dropdownOpen" :href="route('logout')" method="post" as="button">
          Cerrar sesion
        </DropdownLink>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { watch, ref, defineProps } from "vue";
import { Link } from "@inertiajs/vue3";
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
  dropdownPerfilOpen: Boolean,
  user: Object, 
});

const emit = defineEmits(["toggle-sidebar", "dropdown-perfil"]);

const changueStatusDropdownPerfil = () => {
  emit("dropdown-perfil");
};
const toggleSidebar = () => {
  emit("toggle-sidebar");
};

let dropdownOpen = ref(false);


</script>
