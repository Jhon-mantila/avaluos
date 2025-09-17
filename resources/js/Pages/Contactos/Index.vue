<template>
    <AppLayout title="Contactos">
    <template #header>  
        <h2
            class="text-xl font-semibold leading-tight text-gray-800"
        >
            Contactos
        </h2>
    </template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">  
                        <div class="flex justify-between mb-4">
                            <div>
                                <span class="text-lg font-semibold">Total de contactos: {{ contactos.total }}</span>
                            </div>
                            <a :href="route('contactos.create')" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Crear Contacto
                            </a>
                        </div>
                        <div>
                            <!-- Barra de búsqueda -->
                            <input
                            type="text"
                            v-model="search"
                            placeholder="Buscar contactos..."
                            @input="onSearch"
                            class="w-full p-2 border border-gray-300 rounded-md"
                            />

                            <!-- Tabla de contactos -->
                            <table class="w-full mt-4">
                            <thead>
                                <tr>
                                <th class="px-4 py-2">Nombre</th>
                                <th class="px-4 py-2">Genero</th>
                                <th class="px-4 py-2">Celular</th>
                                <th class="px-4 py-2">Última modificación</th>
                                <th class="px-4 py-2">Fecha Creación</th>
                                <th class="px-4 py-2">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="contacto in contactos.data" :key="contacto.id">
                                <td class="px-4 py-2 whitespace-nowrap"><a :href="route('contactos.show', contacto.id)" class="text-blue-500 hover:text-blue-700">{{ contacto.nombre }}</a></td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ contacto.genero }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ contacto.celular }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ formatDate(contacto.updated_at) }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ formatDate(contacto.created_at) }}</td>

                                <td class="px-4 py-2">
                                    <a :href="route('contactos.edit', contacto.id)" class="text-blue-500 hover:text-blue-700">Editar</a>
                                </td>
                                </tr>
                            </tbody>
                            </table>

                            <!-- Paginación -->
                            <Pagination :links="contactos.links" class="mt-4" />
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </AppLayout>
</template>
<script setup>
  import { ref, watch, onMounted } from 'vue';
  import { router } from '@inertiajs/vue3'; // Usa `router` en lugar de `Inertia`
  import { formatDate } from '@/Utils/dateUtils'; // Importar la función desde el archivo de utilidades
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  
  const props = defineProps({
      contactos: Object,
      filters: Object,
  });

    // Imprimir la data de contactos en la consola
    onMounted(() => {
        console.log(props.contactos);
    });

  
  //const search = ref(props.filters.search || '');
  // Obtener el valor de búsqueda de la URL (si existe)
  const search = ref(new URLSearchParams(window.location.search).get('search') || '');
  
  const onSearch = () => {
      router.get('/contactos', { search: search.value }, {
          preserveState: true,
          replace: true,
      });
  };
  
  watch(search, (value) => {
      onSearch();
  });
</script>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: #f2f2f2;
}
</style>