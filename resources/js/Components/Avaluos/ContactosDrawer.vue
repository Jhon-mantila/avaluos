<script setup>
import { ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import { computed } from 'vue';

const props = defineProps({
    avaluoId: String,
    generos: Array // Recibimos desde el padre
})
const emit = defineEmits(['contacto-agregado'])

const open = ref(false)

const form = useForm({
    id: '',
    nombre: '',
    genero: null, // Para v-select es mejor null en vez de ''
    celular: '',
    fecha_asignacion: '',
    observaciones: ''
})
console.log('Generos recibidos:', props.generos)
const generosArray = computed(() =>
    Object.entries(props.generos).map(([value, label]) => ({ value, label }))
);
function submit() {
    form.post(route('avaluos.contactos.store', props.avaluoId), {
        preserveScroll: true,
        onSuccess: (page) => {
            console.log('Respuesta completa del controlador:', page); // Ver toda la respuesta
            //const nuevoContacto = page.props.contactos
            const nuevoContacto = page.props.contactos.data.at(0)
            console.log('Contacto emitido al padre:', nuevoContacto)
            emit('contacto-agregado', page.props.contactos.data.at(0))
            form.reset()
            open.value = false
            //router.reload({ only: ['contactos'] }) // Recarga solo lista de contactos
        }
    })

    

}
</script>

<template>
    <div>
        <button 
            @click="open = true" 
            class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full text-lg font-bold hover:bg-blue-600"
            title="Agregar contacto"
        >
            +
        </button>

        <transition name="fade">
            <div v-if="open" class="fixed inset-0 bg-black bg-opacity-30 z-40" @click="open = false"></div>
        </transition>

        <transition name="slide">
            <div v-if="open" class="fixed right-0 top-0 h-full w-96 bg-white shadow-lg z-50 p-6">
                <h2 class="text-xl font-bold mb-4">Agregar Contacto</h2>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nombre</label>
                        <input v-model="form.nombre" class="w-full border rounded px-3 py-2" />
                        <div v-if="form.errors.nombre" class="text-red-500 text-sm">{{ form.errors.nombre }}</div>
                    </div>                  
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Género</label>
                        <v-select
                        v-model="form.genero"
                        :options="generosArray"
                        label="label"       
                        :reduce="op => op.value"  
                        placeholder="Seleccione género..."
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        />
                       
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Celular</label>
                        <input v-model="form.celular" class="w-full border rounded px-3 py-2" />
                        <div v-if="form.errors.celular" class="text-red-500 text-sm">{{ form.errors.celular }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Fecha Asignación</label>
                        <input type="date" v-model="form.fecha_asignacion" class="w-full border rounded px-3 py-2" />
                        <div v-if="form.errors.fecha_asignacion" class="text-red-500 text-sm">{{ form.errors.fecha_asignacion }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Observaciones</label>
                        <textarea v-model="form.observaciones" class="w-full border rounded px-3 py-2"></textarea>
                        <div v-if="form.errors.observaciones" class="text-red-500 text-sm">{{ form.errors.observaciones }}</div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded" :disabled="form.processing">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.slide-enter-from { transform: translateX(100%); }
.slide-enter-to { transform: translateX(0); }
.slide-leave-from { transform: translateX(0); }
.slide-leave-to { transform: translateX(100%); }
.slide-enter-active,
.slide-leave-active { transition: transform 0.3s ease; }

.fade-enter-active,
.fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from,
.fade-leave-to { opacity: 0; }
</style>
