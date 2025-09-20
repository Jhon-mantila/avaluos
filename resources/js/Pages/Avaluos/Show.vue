<template>
    <AppLayout title="Detalles del Avalúo">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detalles del Avalúo
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div class="flex items-center justify-between mb-4">
                             <!-- Título a la izquierda -->
                            <h3 class="text-lg font-semibold leading-tight text-gray-800">Información del Avalúo</h3>
                            
                            <!-- Botones a la derecha -->
                            <div class="flex gap-2">
                                <a :href="route('avaluos.edit', avaluo.id)" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Editar
                                </a>
                                <a :href="referer" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Regresar
                                </a>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="flex border-b border-gray-200 mb-6 px-4 py-2 bg-gray-100 rounded-t-lg">
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
                        <!-- Tab 1: Etapa 1 -->
                        <div v-show="activeTab === 0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Número de Avalúo</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.numero_avaluo }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Cliente</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-blue-500 hover:text-blue-700"><a :href="route('clientes.show', avaluo.cliente_id)">{{ avaluo.cliente.nombre }}</a></p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.estado }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Tipo de Avalúo</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.tipo_avaluo }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Departamento</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.departamento?.nombre || '' }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.municipio?.nombre || '' }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.direccion }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.observaciones }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Etapa 2 -->
                        <div v-show="activeTab === 1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Uso</label>
                                        <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.uso }}</p>
                                </div>

                                <!-- Auxiliar -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Auxiliar</label>
                                    <div @dblclick="editingField = 'auxiliar'">
                                        <template v-if="editingField === 'auxiliar'">
                                            <div class="flex gap-2 items-center">
                                                <input v-model="editable.auxiliar" type="text" class="form-input" />
                                                <button
                                                    @click="guardarCampo('auxiliar')"
                                                    class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                                                >
                                                    Guardar
                                                </button>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm cursor-pointer text-gray-500 italic">{{ avaluo.auxiliar || '\u00A0' }}</p>
                                        </template>
                                    </div>
                                </div>

                                <!-- Fecha entrega -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Fecha Entrega Avalúo</label>
                                    <div @dblclick="editingField = 'fecha_entrega_avaluo'">
                                        <template v-if="editingField === 'fecha_entrega_avaluo'">
                                            <div class="flex gap-2 items-center">
                                                <input v-model="editable.fecha_entrega_avaluo" type="datetime-local" class="form-input" />
                                                <button
                                                    @click="guardarCampo('fecha_entrega_avaluo')"
                                                    class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                                                >
                                                    Guardar
                                                </button>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm cursor-pointer">{{ avaluo.fecha_entrega_avaluo || '\u00A0' }}</p>
                                        </template>
                                    </div>
                                </div>

                                <!-- Valor Informe -->
                                <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Valor Informe</label>
                                        <div @dblclick="editingField = 'valor_informe'">
                                            <template v-if="editingField === 'valor_informe'">
                                                <div class="flex gap-2 items-center">
                                                    <input v-model="editable.valor_informe" type="number" class="form-input" />
                                                    <button
                                                        @click="guardarCampo('valor_informe')"
                                                        class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                                                    >
                                                        Guardar
                                                    </button>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm cursor-pointer">{{ avaluo.valor_informe || '\u00A0' }}</p>
                                            </template>
                                        </div>
                                    </div>


                            </div>
                        </div>

                        <!-- Tab 3: Etapa 2 -->
                        <div v-show="activeTab === 2">
                            <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Valor Comercial</label>
                                    <p class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ avaluo.valor_comercial_estimado }}</p>
                             </div>
                        </div>

                        <!-- Contactos -->
                        <div class="mt-8">
                            <div class="flex items-center justify-between px-4 py-2 bg-gray-100 rounded-t-lg"> 
                                <h3 class="text-lg font-semibold leading-tight text-gray-800">Contactos</h3>
                                <ContactosDrawer 
                                :avaluo-id="avaluo.id"  
                                @contacto-agregado="agregarContacto" 
                               :generos="generos" 
                                />
                            </div>
                            <div v-if="contactos.data.length">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NOMBRE</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CELUAR</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GÉNERO</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FECHA ASIGNACIÓN</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OBSERVACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="contacto in contactos.data" :key="contacto.id">
                                            <td class="px-6 py-4 whitespace-nowrap"><a :href="route('contactos.show', contacto.id)" class="text-blue-500 hover:text-blue-700">{{ contacto.nombre }}</a></td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ contacto.celular }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ contacto.genero }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ contacto.pivot?.fecha_asignacion ?? '' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ contacto.pivot?.observaciones ?? '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <Pagination :links="contactos.links" class="mt-4" />
                            </div>
                            <div v-else>
                                <p>No hay contactos registrados para este avalúo.</p>
                            </div>
                        </div>
                        <!-- Visitas -->
                        <div class="mt-8">
                            <div class="px-4 py-2 bg-gray-100 rounded-t-lg">
                                <h3 class="text-lg font-semibold leading-tight text-gray-800">Visitas</h3>
                            </div>
                            <div v-if="informacionVisitas.data.length">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Vista</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del Visitador</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celular</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ciudad</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Visita</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="visita in informacionVisitas.data" :key="visita.id">
                                            <td class="px-6 py-4 whitespace-nowrap"><a :href="route('informacion-visita.show', visita.id)" class="text-blue-500 hover:text-blue-700">{{ visita.incremental_id }}</a></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><a :href="route('visitadores.show', visita.visitador_id)" class="text-blue-500 hover:text-blue-700">{{ visita.visitador?.user?.name || 'N/A' }}</a></td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ visita.celular }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ visita.ciudad }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ visita.fecha_visita }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ visita.observaciones }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <Pagination :links="informacionVisitas.links" class="mt-4" />
                            </div>
                            <div v-else>
                                <p>No hay visitas registradas para este avalúo.</p>
                            </div>
                        </div>
                        <!-- Plantillas -->
                        <div class="mt-8">
                            <div class="px-4 py-2 bg-gray-100 rounded-t-lg">
                                <h3 class="text-lg font-semibold leading-tight text-gray-800">Plantillas</h3>
                            </div>
                            <div v-if="informacionPlantillas.data.length">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NOMBRE PLANTILLA</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FECHA CREACIÓN</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FECHA MODIFICACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="plantilla in informacionPlantillas.data" :key="plantilla.id">
                                            <td class="px-6 py-4 whitespace-nowrap"><a :href="route('plantillas.show', plantilla.id)" class="text-blue-500 hover:text-blue-700">{{ plantilla.nombre_plantilla }}</a></td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ plantilla.created_at }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ plantilla.updated_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <Pagination :links="informacionVisitas.links" class="mt-4" />
                            </div>
                            <div v-else>
                                <p>No hay plantillas registradas para este avalúo.</p>
                            </div>
                        </div>
   
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import ContactosDrawer from '@/Components/Avaluos/ContactosDrawer.vue'

const toast = useToast();
const { props } = usePage();
const avaluo = ref(props.avaluo);
const generos = ref(props.generos); // Lista de géneros para el dropdown
const editable = ref({
    auxiliar: avaluo.value.auxiliar ?? '',
    fecha_entrega_avaluo: avaluo.value.fecha_entrega_avaluo ?? '',
    valor_informe: avaluo.value.valor_informe ?? '',
});

const editing = ref(false); // para activar todos a la vez
const editingField = ref(null); // Nombre del campo que está siendo editado


const informacionVisitas = ref(props.informacionVisitas);
const informacionPlantillas = ref(props.informacionPlantillas);
const contactos = ref(props.contactos);

const tabs = ['Etapa 1', 'Etapa 2', 'Etapa 3'];
const activeTab = ref(0);

function agregarContacto(nuevoContacto) {
    console.log('Nuevo contacto agregado:', nuevoContacto);
    contactos.value.data.push(nuevoContacto) // ⚠️ es contactos.value.data, no contactos.value
}
// Imprimir la data de cliente y avaluos en la consola
onMounted(() => {
    console.log('generos:', props.generos);
    console.log('Avaluos:', props.avaluo);
    console.log('Contactos:', props.contactos);
    console.log('informacionVisitas:', props.informacionVisitas);
    console.log('informacionPlantillas:', props.informacionPlantillas);
});
// Determinar la URL de referencia
//const referer = ref(document.referrer.includes('clientes') ? document.referrer : route('avaluos.index'));
const referer = ref(document.referrer.includes('clientes') || document.referrer.includes('informacion-visita') || document.referrer.includes('plantillas') || document.referrer.includes('visitadores') ? document.referrer : route('avaluos.index'));
console.log('Referer:', referer.value);

function guardarCampo(campo) {
    router.put(route('avaluos.updateCampo', avaluo.value.id), {
        [campo]: editable.value[campo],
    }, {
        preserveScroll: true,
        onSuccess: () => {
            avaluo.value[campo] = editable.value[campo];
            editingField.value = null;
            toast.success(`Campo ${campo} guardado exitosamente.`);
        },
        onError: (err) => {
            console.error('Error al guardar campo', campo, ':', err);
        }
    });
}

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