<template>
    <div @click="closeDropdownPerfil" class=" bg-gris-light">
        <SidebarComponent v-if="$page && $page.props && $page.props.auth && $page.props.auth.user"
            :is-hidden="isHidden" />
        <div @click="closeSidebar" :class="{ 'xl:ml-80': !isHidden }" class="transition-all duration-300 transform">
            <NavbarComponent v-if="$page && $page.props && $page.props.auth && $page.props.auth.user"
                @dropdown-perfil="handleDropdownPerfil" @toggle-sidebar="toggleSidebar"
                :dropdownPerfilOpen="dropdownPerfilOpen" :user="user" />
            <main class="w-full min-h-[calc(100vh-5rem)]">
                <slot></slot>
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
    props: {


    },
    setup(props) {
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

        const closeDropdownPerfil = () => {
            if (dropdownPerfilOpen.value) {
                dropdownPerfilOpen.value = false;
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
    },
    methods: {
    }
}



</script>