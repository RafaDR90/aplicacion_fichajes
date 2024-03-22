<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block px-4 py-2 text-gray-800 hover:bg-gray-100 active:bg-gray-200"
    >
        <slot />
    </Link>
</template>
