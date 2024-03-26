<template>
    <div class="pagination">
      <Link v-if="pagination.prev_page_url" :href="pagination.prev_page_url">&lt;</Link>
      <span> Página {{ pagination.current_page }} de {{ pagination.last_page }} </span>
      <Link v-if="pagination.next_page_url" :href="pagination.next_page_url">&gt;</Link>
    </div>
  </template>
  
  <script>
  import { Link } from '@inertiajs/vue3'
  
  export default {
    components: {
      Link
    },
    props: {
      pagination: {
        type: Object,
        required: true
      }
    }
  }
  </script>