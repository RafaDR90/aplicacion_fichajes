<template>
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

            <input v-focus
                class="peer h-full w-full outline-none text-lg text-gray-700 pr-2 border-none placeholder:text-gray-300 focus:ring-0"
                type="text" id="search" :placeholder="placeHolder" v-model="search" />
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    placeHolder: {
        type: String,
        default: 'Buscar...'
    },
    search: {
        type: String,
        default: ''
    }
});

const emits = defineEmits(['updateSearch']);

const search = ref(props.search)

let searchTimeOut = null
watch(search, (value) => {
    if (value.slice(-1) === ' ') {
        return
    }
    if (searchTimeOut) {
        clearTimeout(searchTimeOut)
    }
    searchTimeOut = setTimeout(() => {
        let searchValue = value.trim();
        emits('updateSearch', searchValue)
    }, 500)



})

</script>