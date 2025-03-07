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
                            <button @click="showModal = true" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Crear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para subir imágenes -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold mb-4">Subir Imágenes</h3>
                <input type="file" multiple @change="handleFileUpload" class="mb-4">
                <div class="flex justify-end">
                    <button @click="showModal = false" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancelar
                    </button>
                    <button @click="uploadImages" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Subir
                    </button>
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
import axios from 'axios';

const { props } = usePage();
const plantilla = ref(props.plantilla);
const showModal = ref(false);
const selectedFiles = ref([]);

// Imprimir la data de la plantilla en la consola
onMounted(() => {
    console.log('Plantilla:', props.plantilla);
});
const handleFileUpload = (event) => {
    selectedFiles.value = Array.from(event.target.files);
};

const uploadImages = () => {
    if (!plantilla.value?.id) {
        console.error("Error: El ID de la plantilla no está definido.");
        return;
    }
    // Aquí se manejará la subida de imágenes
    console.log('Archivos seleccionados:', selectedFiles.value);
    const formData = new FormData();
    selectedFiles.value.forEach((file, index) => {
        formData.append(`images[${index}]`, file); // Cambio aquí
        formData.append(`titles[${index}]`, file.name);
        formData.append(`types[${index}]`, file.type);
        formData.append(`orders[${index}]`, index + 1);
    });
    formData.append('plantilla_id', plantilla.value.id);
    console.log('Formulario de datos:', formData);
        // 🔍 Imprimir los valores dentro de FormData antes de enviarlo
        for (let pair of formData.entries()) {
        console.log(pair[0], pair[1]);
    }
    axios.post('/api/registros-fotograficos', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
    })
    .then(response => {
        console.log('Imágenes subidas:', response.data);
        // Aquí puedes actualizar el subpanel con las nuevas imágenes
    })
    .catch(error => {
        console.error('Error subiendo imágenes:', error);
    });

    showModal.value = false;
};
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