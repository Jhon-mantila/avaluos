<template>
    <AppLayout title="Contactos.edit">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Editar Contacto
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" v-model="form.nombre" id="nombre" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                                <v-select
                                        v-model="selectedGenero"
                                        :options="generos"
                                        placeholder="Seleccione el genero..."
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    />
                            </div>
                            <div class="mb-4">
                                <label for="celular" class="block text-sm font-medium text-gray-700">Celular</label>
                                <input type="text" v-model="form.celular" id="celular" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
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
    contacto: Object,
    genero: {
        type: Object,
        required: true,
        default: () => ({})
    }
});
const generos = ref(Object.keys(props.genero).map(key => ({ label: props.genero[key], value: key })));
const toast = useToast();
console.log('Contacto:', props.contacto);
const form = ref({
    nombre: props.contacto.nombre || '',
    genero: props.contacto.genero || '',
    celular: props.contacto.celular || '',
});

const selectedGenero = ref(generos.value.find(genero => genero.value === form.value.genero));


watch(selectedGenero, (newValue, oldValue) => {
    console.log('selectedGenero cambió de:', oldValue, 'a:', newValue);
    form.genero = newValue ? newValue.value : '';
    console.log('form.genero actualizado a:', form.genero);
});
// Determinar la URL de referencia
const referer = ref(document.referrer.includes('contactos') ? document.referrer : route('contactos.index'));

const submit = async () => {
    console.log('Enviando formulario...', form.value);
    console.log('selectedGenero actual:', selectedGenero.value);
    form.value.genero = selectedGenero.value ? selectedGenero.value.value : '';
    const formData = new FormData();
    formData.append('_method', 'PUT'); // Simula una solicitud PUT
    formData.append('nombre', form.value.nombre);
    formData.append('genero', form.value.genero);
    formData.append('celular', form.value.celular);

    try {
        const response = await axios.post(route('contactos.update', props.contacto.id), formData, {
        });
        console.log('Contacto actualizado correctamente.');
        toast.success('Contacto actualizado correctamente.');
        // 🔹 Esperar 1 segundo antes de redirigir
        /*setTimeout(() => {
            window.location.href = route('clientes.index');
        }, 1000);*/
        
    } catch (error) {
        console.log('Errores:', error.response.data.errors);
        toast.error('Hubo un error al actualizar el contacto.');
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