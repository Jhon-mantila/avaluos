<template>
    <AppLayout title="avaluos.create">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Crear Avalúo
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="numero_avaluo" class="block text-sm font-medium text-gray-700">Número de Avalúo</label>
                                <input type="text" v-model="form.numero_avaluo" id="numero_avaluo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                                <v-select
                                    v-model="selectedCliente"
                                    :options="clientes"
                                    label="nombre"
                                    placeholder="Buscar cliente..."
                                    @input="updateClienteId"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                />
                            </div>
                            <div class="mb-4">
                                <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                                <input type="text" v-model="form.estado" id="estado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Guardar
                                </button>
                                <a :href="route('avaluos.index')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Regresar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import AppLayout from '@/Layouts/AppLayout.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const toast = useToast();

const form = useForm({
    numero_avaluo: '',
    cliente_id: '',
    estado: '',
});

const clientes = ref([]);
const selectedCliente = ref(null);

onMounted(() => {
    // Fetch clients from the server
    axios.get('/api/clientes').then(response => {
        //console.log('Clientes:', response.data);
        //clientes.value = response.data;
        console.log('Clientes recibidos:', response.data);
        clientes.value = response.data.map(cliente => ({
            id: cliente.id,
            nombre: cliente.nombre
        }));
    }).catch(error => {
        console.error('Error fetching clients:', error);
    });
});


watch(selectedCliente, (newValue, oldValue) => {
    console.log('selectedCliente cambió de:', oldValue, 'a:', newValue);
    updateClienteId(newValue);
});

const updateClienteId = (cliente) => {
    console.log('Valor recibido en updateClienteId:', cliente);
    
    if (Array.isArray(cliente)) {
        cliente = cliente.length > 0 ? cliente[0] : null;
    }

    form.cliente_id = cliente ? cliente.id : '';
};

const submit = () => {
    form.post(route('avaluos.store'), {
        onSuccess: () => {
            form.reset();
            toast.success('Avalúo creado correctamente.');
        }
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