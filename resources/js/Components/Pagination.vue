<template>
  <div>
    <nav class="flex justify-center mt-6">
      <ul class="flex space-x-1">
        <li
          v-for="link in links"
          :key="link.label"
        >
          <a
            v-if="link.url"
            :href="link.url"
            v-html="link.label"
            @click.prevent="onClick(link.url)"
            class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200"
            :class="{
              'bg-blue-500 text-white border-blue-500 hover:bg-blue-600 hover:text-white': link.active
            }"
          ></a>
          <span
            v-else
            v-html="link.label"
            class="px-3 py-1 border border-gray-200 rounded-md text-sm text-gray-400 cursor-not-allowed"
          ></span>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'; // Usa `router` en lugar de `Inertia`

const props = defineProps({
  links: Array,
});

const onClick = (url) => {
  router.visit(url); // Usa `router.visit` para navegar
};
</script>

<style scoped>
.pagination {
  display: flex;
  list-style: none;
  padding: 0;
}

.pagination li {
  margin: 0 5px;
}

.pagination li.active {
  font-weight: bold;
}
</style>