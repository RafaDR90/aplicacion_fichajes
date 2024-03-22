<template>
    <div @click="closeDropdownPerfil">
        <SidebarComponent :is-hidden="isHidden" />
        <div @click="closeSidebar" :class="{ 'xl:ml-80': !isHidden }" class="transition-all duration-300 transform">
            <NavbarComponent @dropdown-perfil="handleDropdownPerfil" @toggle-sidebar="toggleSidebar" :dropdownPerfilOpen :user="user" />
            <main class="w-full min-h-[calc(100vh-3.5rem)] bg-slate-300">
                <slot/>
            </main>
        </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import NavbarComponent from '@/Components/Navbar.vue';
  import SidebarComponent from '@/Components/Sidebar.vue';

  export default {
    components: {
      NavbarComponent,
      SidebarComponent
    },
    
    setup(props){
        const isHidden = ref(true);
        const dropdownPerfilOpen = ref(false);

        const toggleSidebar = () => {
            isHidden.value = !isHidden.value;
        };

        const closeSidebar = () => {
            if (!isHidden.value) {
                isHidden.value = true;
            }
        };

        const closeDropdownPerfil=()=>{
            if (dropdownPerfilOpen.value){
                dropdownPerfilOpen.value=false;
            }
        }

        const handleDropdownPerfil = (newDropdownPerfilOpen) => {
        dropdownPerfilOpen.value = !dropdownPerfilOpen.value;
        };

        return {
            isHidden, 
            toggleSidebar,
            closeSidebar,
            dropdownPerfilOpen,
            closeDropdownPerfil,
            handleDropdownPerfil
        }
    }
  }
  

  
  </script>