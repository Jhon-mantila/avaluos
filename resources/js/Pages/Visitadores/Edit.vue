<template>
    <AppLayout title="Editar Visitador">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Editar Visitador
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario</label>
                                    <v-select
                                        v-model="selectedUsers"
                                        :options="users"
                                        label="name"
                                        placeholder="Seleccionar usuario..."
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    />
                                    <span v-if="errors.user_id" class="text-red-500 text-sm">{{ errors.user_id }}</span>
                                </div>
                                <div class="mb-4">
                                    <label for="ciudad" class="block text-sm font-medium text-gray-700">Ciudad</label>
                                    <input type="text" v-model="form.ciudad" id="ciudad" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <span v-if="errors.ciudad" class="text-red-500 text-sm">{{ errors.ciudad }}</span>
                                </div>
                                <div class="mb-4">
                                    <label for="active" class="block text-sm font-medium text-gray-700">Activo</label>
                                    <input type="checkbox" v-model="form.active" id="active" class="mt-1 block">
                                    <span v-if="errors.active" class="text-red-500 text-sm">{{ errors.active }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <a :href="route('visitadores.index')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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
import AppLayout from '@/Layouts/AppLayout.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

const props = defineProps({
    visitador: Object,
    users: Array,
});

const form = useForm({
    user_id: props.visitador.user_id,
    ciudad: props.visitador.ciudad,
    active: Boolean(props.visitador.active),
});
const usuarios = ref([]);
const selectedUsers = ref(null);
const errors = ref({});
console.log('Visitador recibido:', props.visitador.active);
onMounted(() => {
    // Fetch avaluos from the server
    axios.get('/api/users').then(response => {
        console.log('Usuartios recibidos:', response.data);
        usuarios.value = response.data.map(user => ({
            id: user.id,
            name: user.name
        }));
        selectedUsers.value = usuarios.value.find(user => user.id === form.user_id);
    }).catch(error => {
        console.error('Error fetching usuarios:', error);
    });
});

watch(selectedUsers, (newValue, oldValue) => {
    console.log('selectedUsers cambió de:', oldValue, 'a:', newValue);
    updateUsersId(newValue);
});
const updateUsersId = (user) => {
    console.log('Valor recibido en updateAvaluoId:', user);
    
    if (Array.isArray(user)) {
        user = user.length > 0 ? user[0] : null;
    }

    form.user_id = user ? user.id : '';
};

const submit = () => {
    form.put(route('visitadores.update', props.visitador.id), {
        onSuccess: () => {
            form.reset();
        },
        onError: (error) => {
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