<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { flash } from '@inertiajs/inertia';

const form = useForm({
    name: '',
    apellidos: '',
    email: '',
    telefono: '',
    direccion: '',
    
});

const submit = () => {
    form.post(route('register'),);
};

const props = defineProps(['error']);

</script>

<template>
    
    <div class=" m-10 xl:m-20">
        <Head title="Register" />
        <h2 class=" mb-10 text-2xl font-bold">Registrar empleado</h2>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="apellidos" value="Apellidos" />

                <TextInput id="apellidos" type="text" class="mt-1 block w-full" v-model="form.apellidos" required
                    autocomplete="apellidos" />

                <InputError class="mt-2" :message="form.errors.apellidos" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="telefono" value="Telefono" />

                <TextInput id="telefono" type="text" class="mt-1 block w-full" v-model="form.telefono" required
                    autocomplete="telefono" />

                <InputError class="mt-2" :message="form.errors.telefono" />
            </div>

            <div class="mt-4">
                <InputLabel for="direccion" value="Direccion" />

                <TextInput id="direccion" type="text" class="mt-1 block w-full" v-model="form.direccion" required
                    autocomplete="direccion" />

                <InputError class="mt-2" :message="form.errors.direccion" />
            </div>

            

            <div class="flex items-center justify-end mt-4">
                <!--<Link :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Already registered?
                </Link>--> 

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
