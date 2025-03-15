<template>
    <AppLayout title="Crear Plantilla">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Crear Plantilla
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="nombre_plantilla" class="block text-sm font-medium text-gray-700">Nombre de la Plantilla</label>
                                    <input type="text" v-model="form.nombre_plantilla" id="nombre_plantilla" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <span v-if="errors.nombre_plantilla" class="text-red-500 text-sm">{{ errors.nombre_plantilla }}</span>
                                </div>
                                <div class="mb-4">
                                    <label for="informacion_visita_id" class="block text-sm font-medium text-gray-700">Información de Visita</label>
                                    <v-select
                                        v-model="selectedInformacionVisita"
                                        :options="informacionVisitas"
                                        label="numero_avaluo"
                                        placeholder="Seleccionar información de visita..."
                                        @input="updateInformacionVisitaId"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                        :appendToBody="true"
                                        />
                                    <span v-if="errors.informacion_visita_id" class="text-red-500 text-sm">{{ errors.informacion_visita_id }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <a :href="route('plantillas.index')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import AppLayout from '@/Layouts/AppLayout.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const toast = useToast();

const form = useForm({
    nombre_plantilla: '',
    informacion_visita_id: '',
});

const errors = ref({});
const informacionVisitas = ref([]);
const selectedInformacionVisita = ref(null);

onMounted(() => {

    const token = localStorage.getItem('auth_token'); // Obtener el token guardado

    if (!token) {
        console.error('❌ No hay token de autenticación. El usuario debe iniciar sesión.');
        return;
    }

    // Configurar axios con el token
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    // Fetch clients from the server
    axios.get('/api/informacion-visitas').then(response => {
        //console.log('Clientes:', response.data);
        //clientes.value = response.data;
        console.log('Información de visitas recibida:', response.data);
        informacionVisitas.value = response.data
            .filter(informacion_visita => informacion_visita.avaluo) // Filtrar solo los que tienen avaluo
            .map(informacion_visita => ({
                id: informacion_visita.id,
                numero_avaluo: informacion_visita.avaluo.numero_avaluo
        }));
    }).catch(error => {
        console.error('Error fetching clients:', error);
    }); 
});
watch(selectedInformacionVisita, (newValue, oldValue) => {
    console.log('selectedInformacionVisita cambió de:', oldValue, 'a:', newValue);
    updateInformacionVisitaId(newValue);
});
const updateInformacionVisitaId = (informacionVisita) => {
    console.log('Valor recibido en updateInformacionVisitaId:', informacionVisita);
    
    if (Array.isArray(informacionVisita)) {
        informacionVisita = informacionVisita.length > 0 ? informacionVisita[0] : null;
    }

    form.informacion_visita_id = informacionVisita ? informacionVisita.id : '';
};

const submit = () => {
    console.log('Form data:', form);
    form.post(route('plantillas.store'), {
        onSuccess: () => {
            form.reset();
            toast.success('Plantilla creada correctamente.');
        },
        onError: (error) => {
            console.error('Error creating plantilla:', error);
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
.text-red-500 {
    color: #f56565;
}

</style>