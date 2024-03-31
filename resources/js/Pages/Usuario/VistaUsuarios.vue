<template>
    <div class="flex-col w-full min-h-[calc(100vh-5rem)] p-16">
        <div class="p-4 mb-6">
            <h1 class="text-4xl font-bold">Empleados</h1>
        </div>

        <div class="w-full  bg-white border border-gris-borde p-8 rounded-t-2xl">
            <div class="flex flex-col xl:flex-row justify-between gap-4">

                <div class=' h-16'>
                    <div
                        class="relative flex items-center w-full rounded-lg focus-within:shadow-lg bg-white overflow-hidden h-full border border-gris-borde">
                        <div class="grid place-items-center h-full w-12 text-gray-300 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input v-focus @input="updateSearch"
                            class="peer h-full w-full outline-none text-lg text-gray-700 pr-2 border-none placeholder:text-gray-300 focus:ring-0"
                            type="text" id="search" placeholder="Buscar por nombre..." :value="search" />
                    </div>
                </div>

                <div class="flex flex-col gap-4 xl:flex-row xl:items-center">
                    <select :value="sortField" @change="updateSortField" name="sortBy" id=""
                        class="border border-gris-borde rounded-lg p-4 h-16 text-lg pr-10">
                        <option class=" text-gray-500" value="">Ordenar</option>
                        <option value="name">Ordenar por (Nombre)</option>
                        <option value="email">Ordenar por (Correo)</option>
                        <option value="apellidos">Ordenar por (Apellidos)</option>
                    </select>
                    <Link :href="route('register')"
                        class=" bg-azul-fondo-btn rounded-xl h-min w-max flex justify-center items-center hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse">
                    <p class=" mx-2 text-lg text-white p-2"><span
                            class=" text-2xl font-bold mr-2 text-blue-100">+</span>A&ntilde;adir empleado</p>
                    </Link>
                </div>
            </div>

        </div>

        <div class="border border-gris-borde w-full rounded-b-xl">
            <!--<thead class="border border-b-gris-borde h-20">
                <tr class=" ">
                    <th class=" text-left p-4">Empleado</th>
                    <th class=" text-left ">Acciones</th>
                </tr>
            </thead>-->
            <div>
                <p class=" text-xl xl:text-2xl font-bold p-4">Lista de empleados</p>
                <div>
                    <div class="flex flex-col xl:flex-row relative xl:justify-between"
                        v-for="(user, index) in sortedUsers" :key="user.id"
                        :class="index % 2 == 0 ? 'bg-white' : 'bg-gray-100'">
                        <div class="p-2 xl:flex xl:gap-2">
                            <div :class="{ 'border-green-400 border-4 rounded-full': isAdmin(user) }"
                                class=" w-10 h-10 ">
                                <img class="h-full w-full rounded-full object-cover" src="img/navbar/fotoperfil.png"
                                    alt="">
                            </div>
                            <div class=" text-sm">
                                <p>{{ user.name }} {{ user.apellidos }}</p>
                                <p>{{ user.email }}</p>
                            </div>
                        </div>
                        <div class="absolute right-2 top-2 xl:relative">
                            <div class="hs-dropdown relative inline-flex">
                                <div v-show="isOpen[user.id]"
                                    class=" mr-9 z-20 hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-100 min-w-60 bg-white shadow-xl border border-gray-100 rounded-lg p-2 mt-2 dark:bg-gray-800 dark:border dark:border-gray-700 right-0 absolute"
                                    aria-labelledby="hs-dropdown-custom-icon-trigger">
                                    <Link :href="route('showUser',{id:user.id})" method="post" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700">
                                        <p>Ver perfil</p>
                                    </Link>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                        href="#">
                                        Newsletter
                                    </a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                        href="#">
                                        Purchases
                                    </a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                        href="#">
                                        Downloads
                                    </a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                        href="#">
                                        Team Account
                                    </a>
                                </div>
                                <button @click="toggleDropdownMenu($event, user.id)"
                                    id="hs-dropdown-custom-icon-trigger" type="button"
                                    class="hs-dropdown-toggle flex justify-center items-center size-9 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                    <svg class="flex-none size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!--<tbody class=" bg-white">
                <tr v-for="(user, index) in sortedUsers" :key="user.id" class=" border-b border-gris-ligth"
                    :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-100'">
                    <td class="flex gap-3 items-center p-4">
                        <div class=" w-10 h-10">
                            <img class="h-full w-full rounded-full object-cover" src="img/navbar/fotoperfil.png" alt="">
                        </div>
                        <div >
                            <p>{{ user.name }} {{ user.apellidos }}</p>
                            <p>{{ user.email }}</p>
                        </div>
                    </td>
                    <td>


                        <div class="hs-dropdown relative inline-flex">


                            <div v-show="isOpen[user.id]"
                                class=" mr-9 z-20 hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-100 min-w-60 bg-white shadow-xl border border-gray-100 rounded-lg p-2 mt-2 dark:bg-gray-800 dark:border dark:border-gray-700 right-0 absolute"
                                aria-labelledby="hs-dropdown-custom-icon-trigger">
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                    href="#">
                                    Newsletter
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                    href="#">
                                    Purchases
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                    href="#">
                                    Downloads
                                </a>
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                                    href="#">
                                    Team Account
                                </a>
                            </div>
                            <button @click="toggleDropdownMenu($event, user.id)" id="hs-dropdown-custom-icon-trigger"
                                type="button"
                                class="hs-dropdown-toggle flex justify-center items-center size-9 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                <svg class="flex-none size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="5" r="1" />
                                    <circle cx="12" cy="19" r="1" />
                                </svg>
                            </button>
                        </div>




                    </td>
                </tr>
            </tbody>-->


        </div>
        <nav>
            <nav>
                <Pagination :pagination="users" :search="search" :sortField="sortField" />
            </nav>
        </nav>

    </div>
</template>

<script>
import { InertiaLink } from '@inertiajs/inertia-vue3'
import { Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

export default {
    data() {
        return {
            search: '',
            sortField: '',
            currentPage: 1,
            itemsPerPage: 10,
            isOpen: [],
        }
    },
    components: {
        InertiaLink,
        Link,
        Pagination
    },
    computed: {
        /* Ordena los usuarios/empleados */
        sortedUsers() {
            return this.users.data.sort((a, b) => {
                if (!this.sortField) return 0;
                return a[this.sortField] > b[this.sortField] ? 1 : -1;

            });
        },
        /* Comprueba si es admin o super-admin */
        isAdmin() {
            return (user) => {
                const isAdmin = user.roles.some(role => role.role_name === 'admin' || role.role_name === 'super-admin');
                return isAdmin;

            }
        }
    },
    methods: {
        toggleDropdownMenu(event, index) {
            // si esta abierto lo cierra, si esta cerrado lo abre y cierra los demas
            this.isOpen[index] = !this.isOpen[index];
            for (let i in this.isOpen) {
                if (i != index) {
                    this.isOpen[i] = false;
                }
            }

        },
        updateSearch(event) {
            this.search = event.target.value;
            this.fetchUsers();
        },
        updateSortField(event) {
            this.sortField = event.target.value;
            this.fetchUsers();
        },
        setSort(field) {
            this.fetchUsers();
        },
        fetchUsers() {
            this.$inertia.get('/usuarios', {
                search: this.search,
                page: this.currentPage,
                sortField: this.sortField,
            }, {
                replace: true,
                preserveState: true,
            });
        },
        changePage(page) {
            this.currentPage = page;
            this.search = this.search;
            this.fetchUsers();
        }
    },

    props: {
        users: Object,
        search: String,
        sortField: String
    },

};
</script>
