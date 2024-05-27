<template>
    <div v-if="error"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flex flex-col gap-1"
        role="alert">
        <strong class="font-bold">¡Ups!</strong>
        <span class="block sm:inline">- {{ error }}</span>
    </div>
    <div v-if="exito"
        class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex flex-col gap-1"
        role="alert">
        <p class="block sm:inline">- {{ exito }}</p>
    </div>
    <div class=" w-full flex flex-col justify-center items-center ">
        <div class="flex self-start ml-20 mt-6">
            <div v-for="(breadcrumb, index) in breadcrumbs" :key="index" class="flex">
                <Link v-if="index !== breadcrumbs.length - 1" :href="breadcrumb.url"><span
                    class=" text-gray-700 font-semibold hover:text-primary-strong">{{ breadcrumb.title }}</span>
                <span>></span> </Link>
                <Link v-else class=" text-gray-700 font-semibold"> &nbsp;{{ breadcrumb.title }}</Link>
            </div>
        </div>

        <h2 class=" text-2xl font-bold self-start ml-20 mt-10" v-if="role == 'super-admin' || role == 'admin'">Panel
            Administraci&oacute;n</h2>

        <div class=" w-11/12 m-5 rounded-lg bg-white border border-gris-borde p-6"
            v-if="role == 'super-admin' || role == 'admin'">

            <!--Container principal de administracion-->
            <div class=" flex flex-col gap-4 relative">
                <div class="flex flex-col gap-4">
                    <div class="flex gap-1">
                        <p>Usuario: </p>
                        <p :class="{ 'text-green-500': obtieneRol != 'Normal' }" class=" ">{{ obtieneRol }}</p>
                    </div>
                    <div v-if="obtieneRol != 'Super Admin'" class="flex gap-2">
                        <label for="roles">Modificar Rol</label>
                        <select v-model="selectedRole" class="border border-gray-300 rounded-lg pr-7 text-center">
                            <option v-if="obtieneRol != 'Normal'" value="normal">Normal</option>
                            <option v-if="obtieneRol != 'Admin'" value="admin">Admin</option>
                            <option v-if="obtieneRol != 'Super Admin' && superAdmin" value="super-admin">Super Admin
                            </option>
                        </select>
                        <button @click="cambiarRolAlert" class="bg-blue-500 text-white rounded-lg px-2">Cambiar</button>
                    </div>
                </div>
                <div class="flex">
                    <label class="inline-flex items-center cursor-pointer gap-2">
                        <span class=" ">Solicita ubicacion: </span>
                        <input @change="handleUbicacionCheckbox" v-model="isChecked" type="checkbox"
                            class="sr-only peer">
                        <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>
                <div v-if="selectedUser && !selectedUser.deleted_at" class="md:absolute right-0">
                    <button @click="deleteUserAlert"
                        class=" px-4 py-2 w-max bg-red-600  shadow-lg rounded-lg font-bold text-white hover:bg-red-500 cursor-pointer active:bg-red-700 active:shadow-none">Eliminar
                        usuario</button>
                </div>
                <div v-else class="md:absolute right-0">
                    <button @click="deleteUserAlert"
                        class=" px-4 py-2 w-max bg-green-600  shadow-lg rounded-lg font-bold text-white hover:bg-green-500 cursor-pointer active:bg-green-700 active:shadow-none">Restaurar
                        usuario</button>
                </div>
            </div>
            <!--Fin del container principal de administracion-->

        </div>
        <h2 class=" text-2xl font-bold self-start ml-20 mt-10">Panel de Usuario</h2>
        <div class=" w-11/12 m-5 rounded-lg bg-white border border-gris-borde p-6 flex flex-col gap-14">
            <div class="flex flex-col gap-4 items-center md:items-start md:flex-row md:gap-8">
                <div class=" w-60 h-60 relative">
                    <img v-if="perfilImage" :src="perfilImage"
                        class="bg-gris-light border border-gris-borde rounded-lg w-full h-full object-cover">
                    <img v-else src="/img/navbar/fotoperfil.png" alt="foto de perfil"
                        class="bg-gris-light border border-gris-borde rounded-lg w-full h-full">
                    <div v-if="selectedUser.id == $page.props.auth.user.id"
                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200">
                        <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none" />
                        <button class=" w-10 h-10">
                            <img @click="fileUpload" src="/img/iconos/editarFotoPerfil.png" alt="Icono editar"
                                class="w-full h-full">
                        </button>
                    </div>
                </div>
                <div class="flex flex-col gap-2 ">
                    <p class=" text-2xl font-bold mt-2">{{ selectedUser.name }} {{ selectedUser.apellidos }}</p>
                    <p class=" text-lg">{{ selectedUser.email }}</p>
                    <div v-if="!editPhone" class=" text-lg flex gap-3 items-center">
                        <p>{{ selectedUser.telefono }} </p>
                        <img v-if="selectedUser.id == $page.props.auth.user.id" @click="openEdit('editPhone')"
                            class=" w-5 h-5 cursor-pointer" src="/img/iconos/editar.png" alt="Icono editar">
                    </div>
                    <form form @submit.prevent="submitPhone" v-else>
                        <input type="text" class=" border border-gris-borde rounded-lg p-1" id="editedPhone"
                            name="editedPhone" @keyup.enter="submitPhone" @keyup.esc="editPhone = !editPhone"
                            @blur="editPhone = false" v-model="formPhone.editedPhone" ref="phoneInput">
                        <button class="hidden" type="submit">Submit</button>
                    </form>

                    <div v-if="!editAddress" class="flex gap-3 items-center">
                        <p class=" text-lg">{{ selectedUser.direccion }}</p>
                        <img v-if="selectedUser.id == $page.props.auth.user.id" @click="openEdit('editAddress')"
                            class=" w-5 h-5 cursor-pointer" src="/img/iconos/editar.png" alt="Icono editar">
                    </div>
                    <form form @submit.prevent="submitAddress" v-else>
                        <input type="text" class=" border border-gris-borde rounded-lg p-1 w-72 md:w-96"
                            id="editedAddress" name="editedAddress" @keyup.enter="submitAddress"
                            @keyup.esc="editAddress = !editAddress" @blur="editAddress = false"
                            v-model="formAddress.editedAddress">
                        <button class="hidden" type="submit">Submit</button>
                    </form>

                </div>
            </div>

            <div class="flex flex-wrap gap-10">
                <div class="flex flex-col gap-2 w-5/12 min-w-72">
                    <h2 class="text-xl font-bold">Ubicaciones permitidas</h2>
                    <div class="border border-gris-borde rounded min-w-72 w-full p-5 h-60 overflow-auto">
                        <div v-if="selectedUser && selectedUser.ubicacion" v-for="ubicacion in selectedUser.ubicacion">
                            <div class="flex justify-between">
                                <p><span class=" font-bold">Pais: </span>{{ ubicacion.pais }}</p>
                                <p><span class=" font-bold">CP: </span>{{ ubicacion.cp }}</p>
                                <p><span class=" font-bold">Ciudad: </span>{{ ubicacion.ciudad }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p><span class=" font-bold">Latitud: </span>{{ ubicacion.latitud }}</p>
                                <p><span class=" font-bold">Longitud: </span>{{ ubicacion.longitud }}</p>
                            </div>
                            <div class="flex gap-1  justify-end mr-5 sm:mr-11 w-full py-2">
                                <button v-if="role == 'super-admin' || role == 'admin'"
                                    @click="desasociarUbicacionAlert(ubicacionId = ubicacion.id, accion = 'desasociar ubicacion', ejecutar = 'desasociarUbicacion')"
                                    class="h-6 w-6">
                                    <img class=" w-full h-full" src="/img/iconos/borrar.png" alt="Icono borrar"
                                        title="Borrar">
                                </button>
                            </div>
                            <div class=" border-b border-gris-borde"></div>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col gap-2 w-5/12 min-w-72">
                    <h2 class="text-xl font-bold">Horario</h2>
                    <div class="border border-gris-borde rounded  min-w-72 w-full p-2 h-60 overflow-auto">
                        <div v-for=" horario in selectedUser.horarios">
                            <div class="flex justify-between">
                                <p class=" font-bold">{{ horario.nombre }}</p>
                                <p class=" text-sm">{{ horario.pivot.dias }}</p>
                                <button v-if="role == 'super-admin' || role == 'admin'" class=" w-6 h-6"
                                    @click="desasociarUbicacionAlert(ubicacionId = horario.id, accion = 'desasociar horario', ejecutar = 'desasociarHorario')">
                                    <img class=" w-full h-full" src="/img/iconos/borrar.png" alt="Icono borrar"
                                        title="Borrar">
                                </button>
                            </div>
                            <div v-if="horario.hora_entrada">
                                <table class="table-auto border-collapse border">
                                    <thead class="text-sm">
                                        <tr class=" bg-gris-borde">
                                            <th class="border border-gris-borde px-1 ">Entrada</th>
                                            <th class="border px-1">Salida</th>
                                            <th v-if="horario.descanso_entrada" class="border  px-1">Descanso inicio
                                            </th>
                                            <th v-if="horario.descanso_salida" class="border  px-1">Descanso fin</th>
                                            <th class="border px-1">tiempo libre</th>
                                            <th class="border px-1">Total horas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class=" ">
                                            <td class="border text-center border-gris-borde">{{
                                                horario.hora_entrada.slice(0, 5) }}</td>
                                            <td class="border text-center border-gris-borde">{{
                                                horario.hora_salida.slice(0, 5) }}</td>
                                            <td v-if="horario.descanso_entrada"
                                                class="border text-center border-gris-borde">{{
                                                    horario.descanso_entrada.slice(0, 5) }}</td>
                                            <td v-if="horario.descanso_salida"
                                                class="border text-center border-gris-borde">{{
                                                    horario.descanso_salida.slice(0, 5) }}</td>
                                            <td class="border text-center border-gris-borde">{{ horario.libre }}"</td>
                                            <td class="border text-center border-gris-borde">{{ horario.total_horas }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                            </div>
                            <div class="border-b mt-2 mb-1 border-gris-borde"></div>

                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form v-if="role == 'super-admin' || role == 'admin'" class="md:flex items-center"
                            @submit.prevent="asignarHorario">
                            <div class="flex gap-4">
                                <select class="bg-white text-black rounded-lg px-2 py-1 w-max ml-1 pr-7"
                                    v-model="formHorario.horario_id">
                                    <option v-for="horario in allHorarios" :value="horario.id">{{ horario.nombre }}
                                    </option>
                                </select>
                                <div v-if="selectedUser.horarios.length > 0" class="flex">
                                    <div v-for="day in days" class="">
                                        <div class="relative ">
                                            <input type="checkbox" :id="day" v-model="formHorario.selectedDays"
                                                :value="day" class="hidden" />
                                            <label :for="day" class="cursor-pointer">
                                                <span
                                                    class="w-4 h-4 mr-2 rounded border border-gray-300 flex items-center justify-center">
                                                    <span v-if="formHorario.selectedDays.includes(day)"
                                                        class="block w-2 h-2 bg-blue-600 rounded"></span>
                                                </span>
                                                {{ day }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button
                                v-if="(!selectedUser.horarios.length > 0 || formHorario.selectedDays.length > 0) && formHorario.horario_id"
                                type="submit"
                                class="bg-blue-500 text-white rounded-lg px-2 py-1 w-max ml-1 mt-1 md:mt-0">Asignar
                                horario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showAlert" class="absolute inset-0 flex justify-center  text-2xl">
        <div class=" sticky top-1/2 bg-white p-4 rounded shadow-gray-600 shadow-2xl h-max border">
            <p>Seguro que desea {{ accion }} de <span class=" font-bold">{{ selectedUser.name }}</span><span
                    v-if="ejecutar == 'cambiarRol'"> a <span class=" font-bold">{{ selectedRole }}</span>?</span></p>
            <div class="flex justify-end gap-6 mt-10">
                <Link v-if="ejecutar == 'cambiarRol'"
                    :href="route(ejecutar, { 'id': selectedUser.id, 'rol': selectedRole })" method="post"
                    class="bg-green-500 text-white rounded-lg px-2" :preserve-state="false">
                Continuar
                </Link>
                <Link v-else :href="route(ejecutar, { 'id': selectedUser.id, 'id_ubicacion': ubicacion_id })"
                    method="post" class="bg-green-500 text-white rounded-lg px-2" :preserve-state="false">
                Continuar
                </Link>
                <button @click="cancelar" class="bg-red-500 text-white rounded-lg px-2">Cancelar</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted, reactive, defineProps, computed, watch, watchEffect, defineEmits } from 'vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'
import { getStorage, getDownloadURL, ref as firebaseRef } from "firebase/storage";
import { Inertia } from '@inertiajs/inertia';
import { useProfileImage } from '@/Store/ProfileImage';

const profileImageStore = useProfileImage();


const props = defineProps({
    selectedUser: Object,
    error: String,
    exito: String,
    allHorarios: Array,
    role: String,
    breadcrumbs: Array,
    imgChange: {
        type: Boolean,
        default: false,
        required: false
    },
    navbarImgReload: {
        type: Boolean,
        default: false,
        required: false
    }
});




/*-----------------------------------
     IMAGEN PERFIL DE FIREBASE
-----------------------------------*/
const perfilImage = ref(null);
const storage = getStorage();

const downloadImage = () => {
    const userImageUrl = props.selectedUser.image_url;
    const storageRef = firebaseRef(storage, '/profile_images/' + userImageUrl);
    getDownloadURL(storageRef)
        .then((url) => {
            console.log('url', url)
            perfilImage.value = url; 
            
        })
        .catch((error) => {
            console.error("Error al obtener la URL de la imagen: ", error);
        });
}

if (props.selectedUser && props.selectedUser.image_url) {
    console.log('hace el downloadImage')
    downloadImage();
}

//compruebo si cambia el estado de props.imgChange y ejecuto la funcion downloadImage
onMounted(() => {
    if (props.imgChange) {
        setTimeout(() => {
            console.log('have el on mounted')
            profileImageStore.setImageUrl(perfilImage.value);
            props.imgChange = false;
            console.log('profileImageStore.imageUrl', profileImageStore.imageUrl)
            //   Inertia.reload({ preserveState: false, preserveScroll: true, refresh: true });
        }, 250);
    }
});



//const selectedDays = ref([]);
const days = ['L', 'M', 'X', 'J', 'V'];

const formHorario = reactive({
    horario_id: "",
    selectedDays: [],
})

watchEffect(() => {
    formHorario.selectedDays.sort((a, b) => {
        const order = ['L', 'M', 'X', 'J', 'V'];
        return order.indexOf(a) - order.indexOf(b);
    });
});



const formAddress = reactive({
    editedAddress: "",
})

const formPhone = reactive({
    editedPhone: "",
})

/*--------------------------------
BLOQUE EDITAR TELEFONO Y DIRECCION
--------------------------------*/
function submitPhone() {
    editPhone.value = false;
    router.post('/change-phone', formPhone)
}

function submitAddress() {
    router.post('/change-address', formAddress)
    editAddress.value = false;
}

const openEdit = (field) => {
    if (field === "editPhone") {
        formPhone.editedPhone = props.selectedUser.telefono;
        editPhone.value = true;
        //pongo el focus en el input, espero 100ms para que se renderice el input
        setTimeout(() => {
            document.getElementById('editedPhone').focus();
        }, 100);
    }
    if (field === "editAddress") {
        formAddress.editedAddress = props.selectedUser.direccion;
        editAddress.value = true;
        setTimeout(() => {
            document.getElementById('editedAddress').focus();
        }, 100);
    }
};
const editPhone = ref(false);
const editAddress = ref(false);
const phoneInput = ref(null);


/*---------------------------
BLOQUE SUBIR IMAGEN DE PERFIL
---------------------------*/
const fileInput = ref(null);

const fileUpload = () => {
    fileInput.value.click();
};

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    const formData = new FormData();
    formData.append('file', file);
    router.post('/change-profile-image', formData, {preserveState: false,});
};
/*---------------------
FIN BLOQUE SUBIR IMAGEN
---------------------*/

let selectedRole = ref("");
let showAlert = ref(false);
let accion = ref("");
let ejecutar = ref("");

let ubicacion_id = ref("");

let isChecked = ref(false);


onMounted(() => {
    isChecked.value = props.selectedUser.requiere_ubicacion == 1;
})

let obtieneRol = computed(() => {
    if (props.selectedUser.roles.length === 0) {
        return "Normal";
    } else if (props.selectedUser.roles.some(role => role.role_name === "super-admin")) {
        return "Super Admin";
    } else {
        return "Admin";
    }
});



const superAdmin = () => {
    // Compruebo si soy admin o super-admin y devuelvo true si lo soy
    if (props.selectedUser.roles.some(role => role.role_name === "super-admin")) {
        return true;
    } else {
        return false;
    }
};

const deleteUserAlert = () => {
    if (props.selectedUser.deleted_at) {
        accion.value = "restaurar la cuenta";
    } else {
        accion.value = "eliminar la cuenta";
    }
    showAlert.value = true;
    ejecutar.value = "deleteUser";
};

const cambiarRolAlert = () => {
    accion.value = "cambiar rol";
    showAlert.value = true;
    ejecutar.value = "cambiarRol";
};

const cancelar = () => {
    showAlert.value = false;
    accion.value = "";
    ejecutar.value = "";
};

const handleUbicacionCheckbox = () => {
    router.post('/toggle-requiere-ubicacion', {
        requiere_ubicacion: event.target.checked,
        idUser: props.selectedUser.id
    });
};

const desasociarUbicacionAlert = (ubicacionId, act, path) => {
    accion.value = act;
    ejecutar.value = path;
    ubicacion_id.value = ubicacionId;
    showAlert.value = true;
};

const asignarHorario = () => {
    router.post('/asignar-horario', {
        horario_id: formHorario.horario_id,
        idUser: props.selectedUser.id,
        selectedDays: formHorario.selectedDays
    });
};

</script>