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
                        <option value="name">Ordenar por (Nombre)</option>
                        <option value="email">Ordenar por (Correo)</option>
                        <option value="apellidos">Ordenar por (Apellidos)</option>
                    </select>
                    <InertiaLink :href="route('fichaje')"
                        class=" bg-azul-fondo-btn rounded-xl h-min w-max flex justify-center items-center hover:bg-azul-fondo-btn-hover active:bg-azul-fondo-btn-pulse">
                        <p class=" mx-2 text-lg text-white p-2"><span
                                class=" text-2xl font-bold mr-2 text-blue-100">+</span>A&ntilde;adir empleado</p>
                    </InertiaLink>
                </div>
            </div>

        </div>

        <table class="border border-x-gris-borde w-full rounded-b-xl">
            <thead class="border border-b-gris-borde h-20">
                <tr class=" ">
                    <th class=" text-left p-4">Empleado</th>
                    <th class=" text-left ">Acciones</th>
                </tr>
            </thead>
            <tbody class=" bg-white">
                <tr v-for="(user, index) in sortedUsers" :key="user.id" class=" border-b border-gris-ligth"
                    :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-100'">
                    <td class="flex gap-3 items-center p-4">
                        <div class=" w-10 h-10">
                            <img class="h-full w-full rounded-full object-cover" src="img/navbar/fotoperfil.png" alt="">
                        </div>
                        <div>
                            <p>{{ user.name }} {{ user.apellidos }}</p>
                            <p>{{ user.email }}</p>
                        </div>
                    </td>
                    <td>Algun botn</td>
                </tr>
            </tbody>


        </table>
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
            itemsPerPage: 10
        }
    },
    components: {
        InertiaLink,
        Link,
        Pagination
    },
    computed: {
        sortedUsers() {
            return this.users.data.sort((a, b) => {
                if (!this.sortField) return 0;
                return a[this.sortField] > b[this.sortField] ? 1 : -1;

            });
        },
    },
    methods: {
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
