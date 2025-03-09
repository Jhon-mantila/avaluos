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
                            <div class="flex justify-start space-x-4">
                                <a :href="route('plantillas.index')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Regresar
                                </a>
                                <a :href="route('plantillas.edit', plantilla.id)" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Editar
                                </a>
                            </div>
                            <div class="flex justify-end space-x-4">
                                <button @click="showModal = true" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Addición Registro Fotográfico
                                </button>
                                <a v-if="imagenes.length > 0" :href="`/plantillas/${plantilla.id}/pdf`" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    PDF
                                </a>
                            </div>
                        </div>

                        <!-- Subpanel para mostrar imágenes en Grid -->
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold leading-tight text-gray-800 mb-4">Imágenes</h3>

                            <draggable :list="imagenes" item-key="id" @end="updateOrder" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template #item="{ element }">
                                    <div class="bg-white shadow-md rounded-lg p-4 border flex flex-col items-center">
                                        <!-- Imagen -->
                                        <img :src="`/storage/${element.imagen}`" :alt="element.title" class="w-full h-40 object-cover rounded-lg">

                                        <!-- Información -->
                                        <div class="mt-3 text-center">
                                            <p class="font-semibold text-gray-800">{{ element.title }}</p>
                                        </div>
                                        <div class="flex justify-start space-x-4">
                                        <button @click="openEditModal(element)" class="btn btn-blue">Editar</button>
                                        <!-- Botón de eliminación -->
                                        <button @click="deleteImage(element.id)" class="btn btn-red">Eliminar</button>
                                    </div>
                                    </div>
                                </template>
                            </draggable>
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

        <!-- 🔹 Modal de Edición -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-lg font-semibold mb-4">Editar Imagen</h3>

                <!-- Título -->
                <label class="block text-sm font-medium text-gray-700">Título</label>
                <input v-model="editImageData.title" type="text" class="form-input w-full mb-3" placeholder="Nuevo título">

                <!-- Imagen -->
                <label class="block text-sm font-medium text-gray-700">Nueva Imagen (Opcional)</label>
                <input type="file" @change="handleFileUpdate" class="form-input w-full mb-3">

                <!-- Botones -->
                <div class="flex justify-end">
                    <button @click="showEditModal = false" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Cancelar</button>
                    <button @click="updateImage" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">Guardar</button>
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
import draggable from 'vuedraggable';

const { props } = usePage();
const plantilla = ref(props.plantilla);
const showModal = ref(false);
const selectedFiles = ref([]);
const imagenes = ref([]);
// **Variables para edición**
const showEditModal = ref(false);
const editImageData = ref({ id: null, title: "", newFile: null });
// Imprimir la data de la plantilla en la consola
onMounted(() => {
    console.log('Plantilla:', props.plantilla);
    fetchImages();
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

const fetchImages = () => {
    axios.get(`/api/plantillas/${plantilla.value.id}/imagenes`)
        .then(response => {
            console.log('Imágenes:', response.data);
            if (Array.isArray(response.data)) {
                imagenes.value = response.data.map((img, index) => ({
                    ...img,
                    orden: img.orden ?? index + 1 // Asegurar que `order` exista
                }));
            } else {
                console.error('Error: La respuesta no es un array:', response.data);
                imagenes.value = [];
            }
        })
        .catch(error => {
            console.error('Error fetching images:', error);
            imagenes.value = []; // Asegura que siempre sea un array
        });
};

const updateOrder = () => {
    if (!imagenes.value || imagenes.value.length === 0) {
        console.warn("No hay imágenes para actualizar el orden.");
        return;
    }

    const orders = imagenes.value.map((imagen, index) => ({
        id: imagen.id,
        order: index + 1 // Asegura que siempre haya un número de orden
    }));

    console.log('Orden de imágenes:', orders);
    axios.post('/api/imagenes/update-order', { orders })
        .then(response => {
            console.log('Orden actualizado:', response.data);
        })
        .catch(error => {
            console.error('Error updating order:', error);
        });
};

// **Abrir modal de edición**
const openEditModal = (imagen) => {
    editImageData.value = {
        id: imagen.id,
        title: imagen.title,
        newFile: null
    };
    showEditModal.value = true;
};

// **Capturar nueva imagen**
const handleFileUpdate = (event) => {
    editImageData.value.newFile = event.target.files[0];
};

// **Actualizar imagen y título**
const updateImage = () => {
    const formData = new FormData();
    formData.append("title", editImageData.value.title);

    if (editImageData.value.newFile) {
        formData.append("image", editImageData.value.newFile);
    }

    const url = `/api/imagenes/update/${editImageData.value.id}`;
    console.log("🔹 URL de actualización:", url); // Verifica si la URL es correcta

    axios.post(`/api/imagenes/update/${editImageData.value.id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
    })
    .then(() => {
        showEditModal.value = false;
        fetchImages(); // Recargar imágenes después de actualizar
    })
    .catch(error => console.error('Error actualizando imagen:', error));
};

// 🔹 Eliminar una imagen
const deleteImage = (id) => {
    if (!confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
        return;
    }

    axios.delete(`/api/imagenes/${id}`)
        .then(response => {
            console.log('Imagen eliminada:', response.data);
            fetchImages();
        })
        .catch(error => {
            console.error('Error eliminando imagen:', error);
        });
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