<template>
    <AppLayout title="Editar Información de Visita">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Editar Información de Visita
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="avaluo_id" class="block text-sm font-medium text-gray-700">Avalúo</label>
                                    <v-select
                                        v-model="selectedAvaluo"
                                        :options="avaluos"
                                        label="nombre"
                                        placeholder="Seleccionar avalúo..."
                                        @input="updateAvaluoId"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    />
                                    <span v-if="errors.avaluo_id" class="text-red-500 text-sm">{{ errors.avaluo_id }}</span>
                                </div>
                                <div class="mb-4">
                                    <label for="visitador_id" class="block text-sm font-medium text-gray-700">Visitador</label>
                                    <v-select
                                        v-model="selectedVisitador"
                                        :options="visitadores"
                                        label="nombre"
                                        placeholder="Seleccionar visitador..."
                                        @input="updateVisitadorId"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    />
                                    <span v-if="errors.visitador_id" class="text-red-500 text-sm">{{ errors.visitador_id }}</span>
                                </div>
                                <div class="mb-4">
                                    <label for="celular" class="block text-sm font-medium text-gray-700">Celular</label>
                                    <input type="text" v-model="form.celular" id="celular" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <span v-if="errors.celular" class="text-red-500 text-sm">{{ errors.celular }}</span>
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
                                    <label for="fecha_visita" class="block text-sm font-medium text-gray-700">Fecha de Visita</label>
                                    <input type="datetime-local" v-model="form.fecha_visita" id="fecha_visita" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <span v-if="errors.fecha_visita" class="text-red-500 text-sm">{{ errors.fecha_visita }}</span>
                                </div>
                                <div class="mb-4">
                                    <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                                    <textarea v-model="form.observaciones" id="observaciones" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                    <span v-if="errors.observaciones" class="text-red-500 text-sm">{{ errors.observaciones }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <a :href="route('informacion-visita.index')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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

const form = useForm({
    avaluo_id: props.informacionVisita.avaluo_id || '',
    visitador_id: props.informacionVisita.visitador_id || '',
    celular: props.informacionVisita.celular || '',
    direccion: props.informacionVisita.direccion || '',
    ciudad: props.informacionVisita.ciudad || '',
    fecha_visita: props.informacionVisita.fecha_visita || '',
    observaciones: props.informacionVisita.observaciones || '',
});

const errors = ref({});
const avaluos = ref([]);
const visitadores = ref([]);
const selectedAvaluo = ref(null);
const selectedVisitador = ref(null);

onMounted(() => {
    // Fetch avaluos from the server
    axios.get('/api/avaluos').then(response => {
        console.log('Avaluos recibidos:', response.data);
        avaluos.value = response.data.map(avaluo => ({
            id: avaluo.id,
            nombre: avaluo.numero_avaluo
        }));
        selectedAvaluo.value = avaluos.value.find(avaluo => avaluo.id === form.avaluo_id);
    }).catch(error => {
        console.error('Error fetching avaluos:', error);
    });

    // Fetch visitadores from the server
    axios.get('/api/visitadores').then(response => {
        console.log('Visitadores recibidos:', response.data);
        visitadores.value = response.data.map(visitador => ({
            id: visitador.id,
            nombre: visitador.user.name
        }));
        selectedVisitador.value = visitadores.value.find(visitador => visitador.id === form.visitador_id);
    }).catch(error => {
        console.error('Error fetching visitadores:', error);
    });
});

watch(selectedAvaluo, (newValue, oldValue) => {
    console.log('selectedAvaluo cambió de:', oldValue, 'a:', newValue);
    updateAvaluoId(newValue);
});

watch(selectedVisitador, (newValue, oldValue) => {
    console.log('selectedVisitador cambió de:', oldValue, 'a:', newValue);
    updateVisitadorId(newValue);
});

const updateAvaluoId = (avaluo) => {
    console.log('Valor recibido en updateAvaluoId:', avaluo);
    
    if (Array.isArray(avaluo)) {
        avaluo = avaluo.length > 0 ? avaluo[0] : null;
    }

    form.avaluo_id = avaluo ? avaluo.id : '';
};

const updateVisitadorId = (visitador) => {
    console.log('Valor recibido en updateVisitadorId:', visitador);
    
    if (Array.isArray(visitador)) {
        visitador = visitador.length > 0 ? visitador[0] : null;
    }

    form.visitador_id = visitador ? visitador.id : '';
};

const submit = () => {
    console.log('Form data:', form);
    form.put(route('informacion-visita.update', props.informacionVisita.id), {
        onSuccess: () => {
            toast.success('Información de visita actualizada correctamente.');
        },
        onError: (error) => {
            console.error('Error updating informacion visita:', error);
            errors.value = error;
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