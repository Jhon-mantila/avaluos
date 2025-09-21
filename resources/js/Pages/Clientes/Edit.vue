<template>
    <AppLayout title="Clientes.edit">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Editar Cliente
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit"  enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" v-model="form.nombre" id="nombre" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="tipo_documento" class="block text-sm font-medium text-gray-700">Tipo Documento</label>
                                <v-select
                                        v-model="selectedTipoDodumento"
                                        :options="tipo_documentos"
                                        placeholder="Seleccionar Tipo de Documento..."
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    />
                            </div>
                            <div class="mb-4">
                                <label for="documento" class="block text-sm font-medium text-gray-700">Documento</label>
                                <input type="text" v-model="form.documento" id="documento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" v-model="form.email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" v-model="form.telefono" id="telefono" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="ciudad" class="block text-sm font-medium text-gray-700">Ciudad</label>
                                <input type="text" v-model="form.ciudad" id="ciudad" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                                <input type="file" @change="handleFileChange2" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <img v-if="form.logo" :src="form.logo" alt="Logo del Cliente" class="mt-2 max-w-xs">
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <a :href="referer" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Regresar
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from 'vue-toastification';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const props = defineProps({
    cliente: Object,
    tipo_documento: {
        type: Object,
        required: true,
        default: () => ({})
    }
});
const tipo_documentos = ref(Object.keys(props.tipo_documento).map(key => ({ label: props.tipo_documento[key], value: key })));
const toast = useToast();
console.log('Cliente:', props.cliente);
const form = ref({
    nombre: props.cliente.nombre || '',
    tipo_documento: props.cliente.tipo_documento || '',
    documento: props.cliente.documento || '',
    email: props.cliente.email || '',
    telefono: props.cliente.telefono || '',
    ciudad: props.cliente.ciudad || '',
    logo: props.cliente.logo ? `/storage/${props.cliente.logo}` : null,
    logo_file: null, // Añadir logo_file al formulario
});
const selectedTipoDodumento = ref(tipo_documentos.value.find(tipo_documento => tipo_documento.value === form.value.tipo_documento));
const handleFileChange2 = (e) => {
    console.log('Cambio de archivo:', e.target.files);
    const file = e.target.files[0];
    console.log('Archivo seleccionado:', file);
    if (file) {
        form.value.logo = URL.createObjectURL(file);
        form.value.logo_file = file; // Guardar el archivo real en form.logo_file
    }
};

watch(selectedTipoDodumento, (newValue, oldValue) => {
    console.log('selectedTipoDocuemtno cambió de:', oldValue, 'a:', newValue);
    form.tipo_documento = newValue ? newValue.value : '';
});
// Determinar la URL de referencia
const referer = ref(document.referrer.includes('clientes') ? document.referrer : route('clientes.index'));

const submit = async () => {
    console.log('Enviando formulario...', form.value);
    console.log('selectedTipoDodumento actual:', selectedTipoDodumento.value);
    form.value.tipo_documento = selectedTipoDodumento.value ? selectedTipoDodumento.value.value : '';
    const formData = new FormData();
    formData.append('_method', 'PUT'); // Simula una solicitud PUT
    formData.append('nombre', form.value.nombre);
    formData.append('tipo_documento', form.value.tipo_documento);
    formData.append('documento', form.value.documento);
    formData.append('email', form.value.email);
    formData.append('telefono', form.value.telefono);
    formData.append('ciudad', form.value.ciudad);
    if (form.value.logo_file) {
        formData.append('logo', form.value.logo_file); // Adjuntar el archivo de logo
    }

    try {
        const response = await axios.post(route('clientes.update', props.cliente.id), formData, {
        });
        console.log('Cliente actualizado correctamente.');
        toast.success('Cliente actualizado correctamente.');
        // 🔹 Esperar 1 segundo antes de redirigir
        /*setTimeout(() => {
            window.location.href = route('clientes.index');
        }, 1000);*/
        
    } catch (error) {
        console.log('Errores:', error.response.data.errors);
        toast.error('Hubo un error al actualizar el cliente.');
    }
};



</script>

<style scoped>
.form-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
}
</style>