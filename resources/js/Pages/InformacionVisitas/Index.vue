<template>
    <AppLayout title="Información Visita">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Información Visita
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between mb-4">
                            <div>
                                <span class="text-lg font-semibold">Total de Visitas: {{ informacionVisitas.total }}</span>
                            </div>
                            <a :href="route('informacion-visita.create')" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Crear Visita
                            </a>
                        </div>
                        <div>
                            <!-- Barra de búsqueda -->
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Buscar visitas..."
                                @input="onSearch"
                                class="w-full p-2 border border-gray-300 rounded-md"
                            />

                            <!-- Tabla de visitas -->
                            <table class="w-full mt-4">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Número Avalúo</th>
                                        <th class="px-4 py-2">Nombre del Visitador</th>
                                        <th class="px-4 py-2">Celular</th>
                                        <th class="px-4 py-2">Ciudad</th>
                                        <th class="px-4 py-2">Fecha de Visita</th>
                                        <th class="px-4 py-2">Fecha de Creación</th>
                                        <th class="px-4 py-2">Fecha de Modificación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="visita in informacionVisitas.data" :key="visita.id">
                                        <td class="px-4 py-2 text-blue-500 hover:text-blue-700"><a :href="route('avaluos.show', visita.avaluo_id)">{{ visita.avaluo.numero_avaluo }}</a></td>
                                        <td class="px-4 py-2">{{ visita.visitador.user.name }}</td>
                                        <td class="px-4 py-2">{{ visita.celular }}</td>
                                        <td class="px-4 py-2">{{ visita.ciudad }}</td>
                                        <td class="px-4 py-2">{{ formatDate(visita.fecha_visita) }}</td>
                                        <td class="px-4 py-2">{{ formatDate(visita.created_at) }}</td>
                                        <td class="px-4 py-2">{{ formatDate(visita.updated_at) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Paginación -->
                            <Pagination :links="informacionVisitas.links" class="mt-4" />
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
    informacionVisitas: Object,
    filters: Object,
});


// Imprimir la data de informacionVisitas en la consola
onMounted(() => {
    console.log(props.informacionVisitas);
    console.log(props.informacionVisitas.data[0].visitador.user.name);
    /*// Imprimir el número del avalúo y el nombre del visitador
    props.informacionVisitas.data.forEach(visita => {
    console.log(`Número Avalúo: ${visita.avaluo.numero_avaluo}, Nombre del Visitador: ${visita.visitador.user.name}`);
    });*/
});

const search = ref(props.filters.search || '');

const onSearch = () => {
    router.get('/informacion-visita', { search: search.value }, {
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