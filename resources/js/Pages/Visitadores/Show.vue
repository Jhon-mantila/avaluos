<template>
    <AppLayout title="Detalles del Visitador">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detalles del Visitador
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ visitador.user.name }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ visitador.user.email }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ visitador.ciudad }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Activo</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ visitador.active ? 'Sí' : 'No' }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Última modificación</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ formatDate(visitador.updated_at) }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ formatDate(visitador.created_at) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <a :href="referer" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Regresar
                            </a>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold leading-tight text-gray-800">
                                Visitas ({{ visitas.total }})
                            </h3>
                            <div v-if="visitas.data.length">
                                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-4">
                                    <div class="p-6 text-gray-900">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead>
                                                <tr>
                                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Visita</th>
                                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Avalúo</th>
                                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado Avalúo</th>
                                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Visita</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="visita in visitas.data" :key="visita.id">
                                                    <td class="px-6 py-4 whitespace-nowrap"><a :href="route('informacion-visita.show', visita.id)" class="text-blue-500 hover:text-blue-700">{{ visita.incremental_id }}</a></td>
                                                    <td class="px-6 py-4 whitespace-nowrap"><a :href="route('avaluos.show', visita.avaluo_id)" class="text-blue-500 hover:text-blue-700">{{ visita.avaluo.numero_avaluo }}</a></td>
                                                    <td class="px-6 py-4 whitespace-nowrap">{{ visita.avaluo.estado }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(visita.fecha_visita) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <Pagination :links="visitas.links" class="mt-4" />
                                        </div>                
                                    </div>  
                                </div>
                            <div v-else>
                                <p>No hay visitas registradas para este avalúo.</p>
                            </div>
                        </div>
                    </div>
                    
                    </div>
                </div>
            </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { formatDate } from '@/Utils/dateUtils'; // Importar la función desde el archivo de utilidades
import Pagination from '@/Components/Pagination.vue';
const { props } = usePage();
const visitador = ref(props.visitador);
const visitas = ref(props.visitas);
// Imprimir la data del visitador en la consola
onMounted(() => {
    console.log('Visitador:', props.visitador);
    console.log('Visitas:', props.visitas);
    console.log('Avaluo:', props.visitas.data[0].avaluo);
});

// Determinar la URL de referencia
const referer = ref(document.referrer.includes('informacion-visita') ? document.referrer : route('visitadores.index'));
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