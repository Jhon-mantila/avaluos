<template>
    <AppLayout title="Visitadores">
    <template #header>  
        <h2
            class="text-xl font-semibold leading-tight text-gray-800"
        >
            Visitadores
        </h2>
    </template>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                        <div>
                            <div class="flex justify-between mb-4">
                            <div>
                                <span class="text-lg font-semibold">Total de Visitadores: {{ visitadores.total }}</span>
                            </div>
                            <a :href="route('visitadores.create')" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Crear Visitador
                            </a>
                        </div>
                            <!-- Barra de búsqueda -->
                            <div class="mb-4 w-full">
                                <input
                                    type="text"
                                    v-model="search"
                                    placeholder="Buscar visitadores..."
                                    @input="onSearch"
                                    class="w-full p-2 border border-gray-300 rounded-md"
                                />
                            </div>
                            <!-- Tabla de visitadores -->
                            <table>
                                <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Ciudad</th>
                                    <th>Activo</th>
                                    <th class="px-4 py-2">Última modificación</th>
                                    <th class="px-4 py-2">Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="visitador in visitadores.data" :key="visitador.id">
                                    <td class="px-4 py-2 whitespace-nowrap"><a :href="route('visitadores.show', visitador.id)" class="text-blue-500 hover:text-blue-700">{{ visitador.user.name }}</a></td> <!-- Nombre del usuario -->
                                    <td class="px-4 py-2 whitespace-nowrap">{{ visitador.user.email }}</td> <!-- Email del usuario -->
                                    <td class="px-4 py-2 whitespace-nowrap">{{ visitador.ciudad }}</td> <!-- Ciudad del visitador -->
                                    <td class="px-4 py-2 whitespace-nowrap">{{ visitador.active ? 'Sí' : 'No' }}</td> <!-- Estado activo -->
                                    <td class="px-4 py-2">{{ formatDate(visitador.updated_at) }}</td>
                                    <td class="px-4 py-2">{{ formatDate(visitador.created_at) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                    <a :href="route('visitadores.edit', visitador.id)" class="text-blue-500 hover:text-blue-700">Editar</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        
                            <!-- Paginación -->
                            <div>
                                <pagination :links="visitadores.links" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </AppLayout>
</template>
  
<script setup>
  import { ref, watch } from 'vue';
  import { router } from '@inertiajs/vue3'; // Usa `router` en lugar de `Inertia`
  import { formatDate } from '@/Utils/dateUtils'; // Importar la función desde el archivo de utilidades
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  
  const props = defineProps({
      visitadores: Object,
      filters: Object,
  });
  
  //const search = ref(props.filters.search || '');
  // Obtener el valor de búsqueda de la URL (si existe)
  const search = ref(new URLSearchParams(window.location.search).get('search') || '');
  
  const onSearch = () => {
      router.get('/visitadores', { search: search.value }, {
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