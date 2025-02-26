<template>
    <AppLayout title="Detalles de la Visita">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detalles de la Visita
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Número Visita</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ visita.incremental_id }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Número de Avalúo</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><a :href="route('avaluos.show', visita.avaluo_id)" class="text-blue-500 hover:text-blue-700">{{ visita.avaluo.numero_avaluo }}</a></p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nombre del Visitador</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"><a :href="route('visitadores.show', visita.visitador_id)" class="text-blue-500 hover:text-blue-700">{{ visita.visitador.user.name }}</a></p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Celular</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ visita.celular }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ visita.direccion }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ visita.ciudad }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Fecha de Visita</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ formatDate(visita.fecha_visita) }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ visita.observaciones }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <a :href="referer" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Regresar
                            </a>
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

const { props } = usePage();
const visita = ref(props.visita);

// Imprimir la data de la visita en la consola
onMounted(() => {
    console.log('Visita:', props.visita);
});

// Determinar la URL de referencia
const referer = ref(document.referrer.includes('informacion-visita') ? document.referrer : route('informacion-visita.index'));
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