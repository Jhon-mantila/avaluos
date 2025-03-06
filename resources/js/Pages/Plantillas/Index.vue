<template>
    <AppLayout title="Plantillas">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Plantillas
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between mb-4">
                            <div>
                                <span class="text-lg font-semibold">Total de Plantillas: {{ plantillas.total }}</span>
                            </div>
                            <a :href="route('plantillas.create')" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Crear Plantilla
                            </a>
                        </div>
                        <!-- Barra de búsqueda -->
                        <div class="mb-4 w-full">
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Buscar plantillas..."
                                @input="onSearch"
                                class="w-full p-2 border border-gray-300 rounded-md"
                            />
                        </div>
                        <!-- Tabla de plantillas -->
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Plantilla</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Creación</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Información Visita</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avalúo</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="plantilla in plantillas.data" :key="plantilla.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ plantilla.nombre_plantilla }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(plantilla.created_at) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ plantilla.informacion_visita.direccion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ plantilla.informacion_visita.avaluo.numero_avaluo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a :href="route('plantillas.show', plantilla.id)" class="text-blue-500 hover:text-blue-700">Ver</a>
                                        <a :href="route('plantillas.edit', plantilla.id)" class="ml-4 text-blue-500 hover:text-blue-700">Editar</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <Pagination :links="plantillas.links" class="mt-4" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatDate } from '@/Utils/dateUtils'; // Importar la función desde el archivo de utilidades
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    plantillas: Object,
    filters: Object,
});
const search = ref(new URLSearchParams(window.location.search).get('search') || '');
// Imprimir la data de avaluos en la consola
onMounted(() => {
    console.log(props.plantillas);
});


const onSearch = () => {
    // Implementar la lógica de búsqueda
    router.get('/plantillas', { search: search.value }, {
        preserveState: true,
        replace: true,
    });
};

watch(search, (value) => {
    onSearch();
});
</script>

<style scoped>
.form-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
}
.text-red-500 {
    color: #f56565;
}
</style>