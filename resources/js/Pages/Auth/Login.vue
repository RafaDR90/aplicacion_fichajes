<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 rounded bg-gray-700 px-2">
            {{ status }}
        </div>
        <h3 class=" text-white text-4xl mb-5 font-bold text-center">Inicie sesión para acceder al fichador</h3>
        <div class=" bg-white rounded-full w-11/12 h-1 mb-20"></div>

        <form @submit.prevent="submit" class=" w-full flex flex-col ">

            <div>
                <InputLabel for="email" value="Email" class=" text-white text-xl" />

                <TextInput id="email" type="email" name="email" class="mt-1 block w-full" v-model="form.email" required
                    autofocus autocomplete="email" />

                <InputError class="mt-2" :message="form.errors.email"  />
            </div>

            <div class="mt-8">
                <InputLabel for="password" value="Contraseña" class=" text-xl text-white" />

                <TextInput id="password" type="password" name="password" class="mt-1 block w-full"
                    v-model="form.password" required autocomplete="current-password" />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <Link v-if="canResetPassword" :href="route('password.request')"
                    class="underline text-sm text-gray-50 hover:text-gray-300 rounded-md focus:outline-none ">
                ¿Has olvidado tu contraseña?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Entrar
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
