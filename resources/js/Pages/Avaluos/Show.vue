<template>
    <AppLayout title="Detalles del Avalúo">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detalles del Avalúo
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold leading-tight text-gray-800">Información del Avalúo</h3>
                            <a :href="route('avaluos.edit', avaluo.id)" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Editar
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Número de Avalúo</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.numero_avaluo }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Cliente</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-blue-500 hover:text-blue-700"><a :href="route('clientes.show', avaluo.cliente_id)">{{ avaluo.cliente.nombre }}</a></p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.estado }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Tipo de Avalúo</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.tipo_avaluo }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.direccion }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.ciudad }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Departamento</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.departamento }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Uso</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.uso }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Valor Comercial</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.valor_comercial_estimado }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.observaciones }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <a :href="referer" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Regresar
                            </a>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold leading-tight text-gray-800">Visitas</h3>
                            <div v-if="informacionVisitas.data.length">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Vista</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Visitador</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celular</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ciudad</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Visita</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="visita in informacionVisitas.data" :key="visita.id">
                                            <td class="px-6 py-4 whitespace-nowrap"><a :href="route('informacion-visita.show', visita.id)" class="text-blue-500 hover:text-blue-700">{{ visita.incremental_id }}</a></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><a :href="route('visitadores.show', visita.visitador_id)" class="text-blue-500 hover:text-blue-700">{{ visita.visitador?.user?.name || 'N/A' }}</a></td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ visita.celular }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ visita.ciudad }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ visita.fecha_visita }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ visita.observaciones }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <Pagination :links="informacionVisitas.links" class="mt-4" />
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
import Pagination from '@/Components/Pagination.vue';

const { props } = usePage();
const avaluo = ref(props.avaluo);
const informacionVisitas = ref(props.informacionVisitas);

// Imprimir la data de cliente y avaluos en la consola
onMounted(() => {
    console.log('Avaluos:', props.avaluo);
    console.log('informacionVisitas:', props.informacionVisitas);
});
// Determinar la URL de referencia
//const referer = ref(document.referrer.includes('clientes') ? document.referrer : route('avaluos.index'));
const referer = ref(document.referrer.includes('clientes') || document.referrer.includes('informacion-visita') || document.referrer.includes('plantillas') || document.referrer.includes('visitadores') ? document.referrer : route('avaluos.index'));
console.log('Referer:', referer.value);

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