// filepath: /mnt/c/xampp/htdocs/System/kyravaluos/resources/js/Pages/Clientes/Show.vue
<template>
    <AppLayout title="clientes.show">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detalles del Cliente
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold leading-tight text-gray-800">Información del Cliente</h3>
                            <a :href="route('clientes.edit', cliente.id)" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Editar
                            </a>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ cliente.nombre }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ cliente.email }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ cliente.telefono }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                            <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ cliente.ciudad }}</p>
                        </div>
                        <div class="mb-4" v-if="cliente.logo">
                            <label class="block text-sm font-medium text-gray-700">Logo</label>
                            <span>{{cliente.logo}}</span>
                            
                            <img :src="`/storage/${cliente.logo}`" alt="Logo del Cliente" class="mt-1 block w-full max-w-xs border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <a :href="referer" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Regresar
                            </a>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold leading-tight text-gray-800">Avaluos Relacionados ({{ avaluos.total }})</h3>
                            <table class="w-full mt-4 border-collapse">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left border-b">Número de Avalúo</th>
                                        <th class="px-4 py-2 text-left border-b">Tipo de Avalúo</th>
                                        <th class="px-4 py-2 text-left border-b">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="avaluo in avaluos.data" :key="avaluo.id">
                                        <td class="px-4 py-2 text-left border-b"><a :href="route('avaluos.show', avaluo.id)" class="text-blue-500 hover:text-blue-700">{{ avaluo.numero_avaluo }}</a></td>
                                        <td class="px-4 py-2 text-left border-b">{{ avaluo.tipo_avaluo }}</td>
                                        <td class="px-4 py-2 text-left border-b">{{ formatDate(avaluo.updated_at) }}</td>
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
import { defineProps, onMounted, ref } from 'vue';
import { formatDate } from '@/Utils/dateUtils'; // Importar la función desde el archivo de utilidades

import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    cliente: Object,
    avaluos: Object,
});

// Imprimir la data de cliente y avaluos en la consola
onMounted(() => {
    console.log('Cliente:', props.cliente);
    console.log('Avaluos:', props.avaluos);
});

// Determinar la URL de referencia
const referer = ref(document.referrer.includes('avaluos') ? document.referrer : route('clientes.index'));
</script>

<style scoped>
.form-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
}
</style>