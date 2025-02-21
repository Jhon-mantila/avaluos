<template>
  <div>
    <nav>
      <ul class="pagination">
        <li v-for="link in links" :key="link.label" :class="{ active: link.active }">
          <a
            v-if="link.url"
            :href="link.url"
            v-html="link.label"
            @click.prevent="onClick(link.url)"
          ></a>
          <span v-else v-html="link.label"></span>
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