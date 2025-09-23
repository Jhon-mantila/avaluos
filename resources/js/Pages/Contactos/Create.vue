// filepath: /mnt/c/xampp/htdocs/System/kyravaluos/resources/js/Pages/Clientes/Create.vue
<template>
    <AppLayout title="Contatos.create">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Crear Contacto
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div class="flex items-center justify-between mb-4">
                             <!-- Título a la izquierda -->
                            <h3 class="text-lg font-semibold leading-tight text-gray-800"></h3>
                            
                            <!-- Botones a la derecha -->
                            <div class="flex gap-2">
                                <a :href="route('contactos.index')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Regresar
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Guardar
                                </button>
                            </div>
                        </div>

                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" v-model="form.nombre" id="nombre" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                                    <v-select
                                        v-model="form.genero"
                                        :options="generos"
                                        placeholder="Seleccionar tipo documento.."
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    />
                                
                            </div>
                            <div class="mb-4">
                                <label for="telefono" class="block text-sm font-medium text-gray-700">Celular</label>
                                <input type="text" v-model="form.celular" id="telefono" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
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
const generos = ref(Object.keys(props.genero).map(key => ({ label: props.genero[key], value: key })));
const form = useForm({
    nombre: '',
    genero: '',
    celular: '',
});


const submit = () => {
    // Extraer los valores de estado y tipo_avaluo antes de enviar el formulario
    form.genero = form.genero.value;
    form.post(route('contactos.store'), {
        onSuccess: () => {
            form.reset();
            toast.success('Contacto creado correctamente.');
        },
        onError: () => {
            toast.error('Ocurrió un error al crear el contacto.');
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