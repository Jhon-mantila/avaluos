<template>
    <AppLayout title="Detalles del Visitador">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detalles del Contacto
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">

                            <div class="flex items-center justify-between mb-4">
                                <!-- Título a la izquierda -->
                                <h3 class="text-lg font-semibold leading-tight text-gray-800">Información del Contacto</h3>
                                
                                <!-- Botones a la derecha -->
                                <div class="flex gap-2">
                                    <a :href="route('contactos.edit', contacto.id)" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Editar
                                    </a>
                                    <a :href="referer" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Regresar
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ contacto.nombre }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Género</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ contacto.genero }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Celular</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ contacto.celular }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Última modificación</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ formatDate(contacto.updated_at) }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ formatDate(contacto.created_at) }}</p>
                                </div>
                            </div>

                            <!-- Avaluos -->
                            <div class="mt-8">
                                <div class="flex items-center justify-between px-4 py-2 bg-gray-100 rounded-t-lg"> 
                                    <h3 class="text-lg font-semibold leading-tight text-gray-800">Avalúos</h3>
                                </div>
                                <div v-if="avaluos.data.length">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NÚMERO AVALUO</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ESTADO</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FECHA DE ENTREGA</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FECHA ASIGNACIÓN</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OBSERVACIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="avaluo in avaluos.data" :key="avaluo.id">
                                                <td class="px-6 py-1 whitespace-nowrap"><a :href="route('avaluos.show', avaluo.id)" class="text-blue-500 hover:text-blue-700">{{ avaluo.numero_avaluo }}</a></td>
                                                <td class="px-6 py-1 whitespace-nowrap">{{ avaluo.estado }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap">{{ avaluo.fecha_entrega_avaluo }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap">{{ avaluo.pivot?.fecha_asignacion ?? '' }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap">{{ avaluo.pivot?.observaciones ?? '' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <Pagination :links="avaluos.links" class="mt-4" />
                                </div>
                                <div v-else>
                                    <p>No hay avalúos registrados para este contacto.</p>
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
const contacto = ref(props.contacto);
const avaluos = ref(props.avaluos);
// Imprimir la data del visitador en la consola
onMounted(() => {
    console.log('Contactos:', props.contacto);
    console.log('Avaluos:', props.avaluos);

});

// Determinar la URL de referencia
const referer = ref(document.referrer.includes('contactos') ? document.referrer : route('avaluos.index'));
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