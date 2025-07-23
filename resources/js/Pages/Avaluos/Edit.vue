<template>
    <AppLayout title="Editar Avalúo">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Editar Avalúo
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <!-- Tabs -->
                        <div class="flex border-b border-gray-200 mb-6">
                            <button
                                v-for="(tab, index) in tabs"
                                :key="index"
                                @click="activeTab = index"
                                :class="[
                                    'px-4 py-2 font-semibold text-sm focus:outline-none',
                                    activeTab === index ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'
                                ]"
                            >
                                {{ tab }}
                            </button>
                        </div>
                        <form @submit.prevent="submit">
                            <!-- Tab 1: Etapa 1 -->
                            <div v-show="activeTab === 0">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <label for="numero_avaluo" class="block text-sm font-medium text-gray-700">Número de Avalúo</label>
                                        <input type="text" v-model="form.numero_avaluo" id="numero_avaluo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <span v-if="errors.numero_avaluo" class="text-red-500 text-sm">{{ errors.numero_avaluo }}</span>
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
                                        <span v-if="errors.cliente_id" class="text-red-500 text-sm">{{ errors.cliente_id }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                                        <v-select
                                            v-model="selectedEstado"
                                            :options="estados"
                                            placeholder="Seleccionar estado..."
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        />
                                        <span v-if="errors.estado" class="text-red-500 text-sm">{{ errors.estado }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="tipo_avaluo" class="block text-sm font-medium text-gray-700">Tipo de Avalúo</label>
                                        <v-select
                                            v-model="selectedTipoAvaluo"
                                            :options="tiposAvaluo"
                                            placeholder="Seleccionar tipo de avalúo..."
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        />
                                        <span v-if="errors.tipo_avaluo" class="text-red-500 text-sm">{{ errors.tipo_avaluo }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                                        <input type="text" v-model="form.direccion" id="direccion" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <span v-if="errors.direccion" class="text-red-500 text-sm">{{ errors.direccion }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="ciudad" class="block text-sm font-medium text-gray-700">Ciudad</label>
                                        <input type="text" v-model="form.ciudad" id="ciudad" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <span v-if="errors.ciudad" class="text-red-500 text-sm">{{ errors.ciudad }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="departamento" class="block text-sm font-medium text-gray-700">Departamento</label>
                                        <input type="text" v-model="form.departamento" id="departamento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <span v-if="errors.departamento" class="text-red-500 text-sm">{{ errors.departamento }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                                        <textarea v-model="form.observaciones" id="observaciones" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                        <span v-if="errors.observaciones" class="text-red-500 text-sm">{{ errors.observaciones }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Etapa 2 -->
                            <div v-show="activeTab === 1">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="mb-4">
                                            <label for="uso" class="block text-sm font-medium text-gray-700">Uso</label>
                                            <v-select
                                                v-model="selectedUso"
                                                :options="tiposUso"
                                                placeholder="Seleccionar tipo de avalúo..."
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                            />
                                            <span v-if="errors.uso" class="text-red-500 text-sm">{{ errors.uso }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="auxiliar" class="block text-sm font-medium text-gray-700">Auxiliar</label>
                                        <input type="text" v-model="form.auxiliar" id="auxiliar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <span v-if="errors.auxiliar" class="text-red-500 text-sm">{{ errors.auxiliar }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="fecha_entrega_avaluo" class="block text-sm font-medium text-gray-700">Fecha Entrega Avalúo</label>
                                        <input type="datetime-local" v-model="form.fecha_entrega_avaluo" id="auxiliar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <span v-if="errors.fecha_entrega_avaluo" class="text-red-500 text-sm">{{ errors.fecha_entrega_avaluo }}</span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="valor_informe" class="block text-sm font-medium text-gray-700">Valor Informe</label>
                                        <input type="number" v-model="form.valor_informe" id="valor_informe" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <span v-if="errors.valor_informe" class="text-red-500 text-sm">{{ errors.valor_informe }}</span>
                                    </div>
                                </div>
                            </div>

                             <!-- Tab 3: Etapa 3 -->
                            <div v-show="activeTab === 2">
                                <div class="mb-4">
                                        <label for="valor_comercial_estimado" class="block text-sm font-medium text-gray-700">Valor Comercial</label>
                                        <input type="number" v-model="form.valor_comercial_estimado" id="valor_comercial_estimado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <span v-if="errors.valor_comercial_estimado" class="text-red-500 text-sm">{{ errors.valor_comercial_estimado }}</span>
                                </div>
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
import { ref, onMounted, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import AppLayout from '@/Layouts/AppLayout.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const toast = useToast();
const { props } = usePage();
const estados = ref(Object.keys(props.estados).map(key => ({ label: props.estados[key], value: key })));
const tiposAvaluo = ref(Object.keys(props.tiposAvaluo).map(key => ({ label: props.tiposAvaluo[key], value: key })));
const tiposUso = ref(Object.keys(props.tiposUso).map(key => ({ label: props.tiposUso[key], value: key })));
const form = useForm({
    numero_avaluo: props.avaluo.numero_avaluo || '',
    tipo_avaluo: props.avaluo.tipo_avaluo || '',
    direccion: props.avaluo.direccion || '',
    ciudad: props.avaluo.ciudad || '',
    departamento: props.avaluo.departamento || '',
    uso: props.avaluo.uso || '',
    valor_comercial_estimado: props.avaluo.valor_comercial_estimado || '',
    observaciones: props.avaluo.observaciones || '',
    cliente_id: props.avaluo.cliente_id || '',
    estado: props.avaluo.estado || '',
    auxiliar: props.avaluo.auxiliar || '',
    fecha_entrega_avaluo: props.avaluo.fecha_entrega_avaluo || '',
    valor_informe: props.avaluo.valor_informe || ''
});
const errors = ref({});
const clientes = ref([]);
const selectedCliente = ref(null);
const selectedEstado = ref(estados.value.find(estado => estado.value === form.estado));
const selectedTipoAvaluo = ref(tiposAvaluo.value.find(tipo => tipo.value === form.tipo_avaluo));
const selectedUso = ref(tiposUso.value.find(uso => uso.value === form.uso));

const tabs = ['Etapa 1', 'Etapa 2', 'Etapa 3'];
const activeTab = ref(0);
//LLevarlo para el tab donde esta el campo requerido
const fieldTabMap = {
  numero_avaluo: 0,
  cliente_id: 0,
  estado: 0,
  tipo_avaluo: 0,
  direccion: 0,
  ciudad: 0,
  departamento: 0,
  observaciones: 0,
  uso: 1,
  auxiliar: 1,
  fecha_entrega_avaluo: 1,
  valor_informe: 1,
  valor_comercial_estimado: 2
};

onMounted(() => {
    // Fetch clients from the server
    axios.get('/api/clientes').then(response => {
        console.log('Clientes recibidos:', response.data);
        clientes.value = response.data.map(cliente => ({
            id: cliente.id,
            nombre: cliente.nombre
        }));

        // Set the selected client
        selectedCliente.value = clientes.value.find(cliente => cliente.id === form.cliente_id);
    }).catch(error => {
        console.error('Error fetching clients:', error);
    });

    // Set the selected client
    //selectedCliente.value = clientes.value.find(cliente => cliente.id === form.cliente_id);
});

watch(selectedCliente, (newValue, oldValue) => {
    console.log('selectedCliente cambió de:', oldValue, 'a:', newValue);
    updateClienteId(newValue);
});

watch(selectedEstado, (newValue, oldValue) => {
    console.log('selectedEstado cambió de:', oldValue, 'a:', newValue);
    form.estado = newValue ? newValue.value : '';
});

watch(selectedTipoAvaluo, (newValue, oldValue) => {
    console.log('selectedTipoAvaluo cambió de:', oldValue, 'a:', newValue);
    form.tipo_avaluo = newValue ? newValue.value : '';
});

watch(selectedUso, (newValue, oldValue) => {
    console.log('selectedUso cambió de:', oldValue, 'a:', newValue);
    form.uso = newValue ? newValue.value : '';
});

const updateClienteId = (cliente) => {
    console.log('Valor recibido en updateClienteId:', cliente);
    
    if (Array.isArray(cliente)) {
        cliente = cliente.length > 0 ? cliente[0] : null;
    }

    form.cliente_id = cliente ? cliente.id : '';
};

// Determinar la URL de referencia
const referer = ref(document.referrer.includes('avaluos') ? document.referrer : route('avaluos.index'));

const submit = () => {

    console.log('Form data:', form.numero_avaluo);
    form.put(route('avaluos.update', props.avaluo.id), {
        onSuccess: () => {
            console.log('Avalúo actualizado correctamente.');
            toast.success(`Avalúo actualizado correctamente. Avalúo No.: ${form.numero_avaluo}`);
        },
        onError: (error) => {
            console.error('Error updating avaluo:', error);
            errors.value = error;

            // Buscar la primera clave con error y redirigir a su pestaña
            const firstErrorField = Object.keys(error)[0];
            if (firstErrorField && fieldTabMap[firstErrorField] !== undefined) {
                activeTab.value = fieldTabMap[firstErrorField];
            }

            toast.error('Revisa los campos con errores');
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
.text-red-500 {
    color: #f56565;
}
</style>