<template>
  <div class="pagination flex items-center justify-center space-x-4 p-6">
    <Link v-if="pagination.prev_page_url" :href="prevPageUrl"
      class="px-3 py-2 rounded-lg  bg-gray-200 font-extrabold border border-gray-300 hover:bg-gray-300"><svg width="24"
      height="24" viewBox="0 0 24 24">
      <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
    </svg></Link>
    <span class="text-gray-700 text-xl"> P&aacute;gina {{ pagination.current_page }} de {{ pagination.last_page }} </span>
    <Link v-if="pagination.next_page_url" :href="nextPageUrl"
      class="px-3 py-2 rounded-lg  bg-gray-200 font-extrabold border border-gray-300 hover:bg-gray-300"><svg width="24"
      height="24" viewBox="0 0 24 24">
      <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
    </svg></Link>
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
    },
    search: {
      type: String,
      required: false,
      default: ''
    },
    sortField: {
      type: String,
      required: false,
      default: ''
    }
  },
  computed: {
    prevPageUrl() {
      let url = this.pagination.prev_page_url;
      if (this.search) {
        url += '&search=' + this.search;
      }
      if (this.sortField) {
        url += '&sortField=' + this.sortField;
      }
      return url;
    },
    nextPageUrl() {
      let url = this.pagination.next_page_url;
      if (this.search) {
        url += '&search=' + this.search;
      }
      if (this.sortField) {
        url += '&sortField=' + this.sortField;
      }
      return url;
    }
  }
}
</script>