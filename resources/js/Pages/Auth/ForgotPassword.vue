<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';


defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>

    <GuestLayout>

        <Head title="Forgot Password" />



        <div class="mb-8 text-md text-gray-50">

            Olvidaste tu contraseña? No hay problema. Solo danos tu dirección de correo electrónico y te enviaremos un
            enlace para restablecer
            la contraseña por correo electrónico, el cual te permitirá elegir una nueva.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 rounded bg-gray-700 px-2">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" class=" text-white text-xl"/>

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-between mt-4 gap-4">
                <Link :href="route('login')" class="underline text-sm text-gray-50 hover:text-gray-300 rounded-md focus:outline-none">
                Volver al login
                </Link>
                <PrimaryButton :class="{ 'opacity-25': form.processing }" class=" w-max" :disabled="form.processing">
                    Enviar email
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
