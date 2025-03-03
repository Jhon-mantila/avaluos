// filepath: /mnt/c/xampp/htdocs/System/kyravaluos/resources/js/Pages/Clientes/Create.vue
<template>
    <AppLayout title="Clientes.create">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Crear Cliente
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" v-model="form.nombre" id="nombre" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="tipo_documento" class="block text-sm font-medium text-gray-700">Tipo Documento</label>
                                    <v-select
                                        v-model="form.tipo_documento"
                                        :options="tipo_documentos"
                                        placeholder="Seleccionar tipo documento.."
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
                                <input type="text" v-model="form.telefono" id="telefono" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="ciudad" class="block text-sm font-medium text-gray-700">Ciudad</label>
                                <input type="text" v-model="form.ciudad" id="ciudad" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                                <input type="file" @change="handleFileChange" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <a :href="route('clientes.index')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import AppLayout from '@/Layouts/AppLayout.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const toast = useToast();
const { props } = usePage();
console.log(props);
const tipo_documentos = ref(Object.keys(props.tipo_documento).map(key => ({ label: props.tipo_documento[key], value: key })));
const form = useForm({
    nombre: '',
    tipo_documento: '',
    documento: '',
    email: '',
    telefono: '',
    ciudad: '',
    logo: null,
});

const handleFileChange = (event) => {
    form.logo = event.target.files[0];
};

const submit = () => {
    // Extraer los valores de estado y tipo_avaluo antes de enviar el formulario
    form.tipo_documento = form.tipo_documento.value;
    form.post(route('clientes.store'), {
        onSuccess: () => {
            form.reset();
            toast.success('Cliente creado correctamente.');
        },
        onError: () => {
            toast.error('Ocurrió un error al crear el cliente.');
        },
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
</style>