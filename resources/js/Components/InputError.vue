<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message" class=" bg-gray-700 px-2 rounded py-1">
        <p class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
</template>
