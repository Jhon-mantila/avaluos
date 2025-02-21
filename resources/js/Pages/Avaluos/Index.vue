// filepath: /mnt/c/xampp/htdocs/System/kyravaluos/resources/js/Pages/Avaluos/Index.vue
<template>
    <AppLayout title="Avaulos.index">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Avalúos
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between mb-4">
                            <div>
                                <span class="text-lg font-semibold">Total de Avalúos: {{ avaluos.total }}</span>
                            </div>
                            <a :href="route('avaluos.create')" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Crear Avalúo
                            </a>
                        </div>
                        <div>
                            <!-- Barra de búsqueda -->
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Buscar avaluos..."
                                @input="onSearch"
                                class="w-full p-2 border border-gray-300 rounded-md"
                            />

                            <!-- Tabla de avaluos -->
                            <table class="w-full mt-4">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Numero Avalúo</th>
                                        <th class="px-4 py-2">Cliente</th>
                                        <th class="px-4 py-2">Estado</th>
                                        <th class="px-4 py-2">Última modificaión</th>
                                        <th class="px-4 py-2">Fecha Creación</th>
                                        <th class="px-4 py-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="avaluo in avaluos.data" :key="avaluo.id">
                                        <td class="px-4 py-2">{{ avaluo.numero_avaluo }}</td>
                                        <td class="px-4 py-2 text-blue-500 hover:text-blue-700"><a :href="route('clientes.show', avaluo.cliente_id)" target="_blank">{{ avaluo.cliente.nombre }}</a></td>
                                        <td class="px-4 py-2">{{ avaluo.estado }}</td>
                                        <td class="px-4 py-2">{{ formatDate(avaluo.updated_at) }}</td>
                                        <td class="px-4 py-2">{{ formatDate(avaluo.created_at) }}</td>
                                        <td class="px-4 py-2">
                                            <a :href="route('avaluos.edit', avaluo.id)" class="text-blue-500 hover:text-blue-700">Editar</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Paginación -->
                            <Pagination :links="avaluos.links" class="mt-4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { formatDate } from '@/Utils/dateUtils'; // Importar la función desde el archivo de utilidades
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    avaluos: Object,
    filters: Object,
});

// Imprimir la data de avaluos en la consola
onMounted(() => {
    console.log(props.avaluos);
});

const search = ref(new URLSearchParams(window.location.search).get('search') || '');

const onSearch = () => {
    router.get('/avaluos', { search: search.value }, {
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