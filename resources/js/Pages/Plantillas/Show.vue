<template>
    <AppLayout title="Detalles de la Plantilla">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detalles de la Plantilla
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nombre de la Plantilla</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ plantilla.nombre_plantilla }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Número de Avalúo</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ plantilla.informacion_visita.avaluo.numero_avaluo }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Dirección de Visita</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ plantilla.informacion_visita.direccion }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ formatDate(plantilla.created_at) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <a :href="route('plantillas.index')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Regresar
                            </a>
                            <a :href="route('plantillas.edit', plantilla.id)" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Editar
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
const plantilla = ref(props.plantilla);

// Imprimir la data de la plantilla en la consola
onMounted(() => {
    console.log('Plantilla:', props.plantilla);
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