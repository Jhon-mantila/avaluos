<template>
    <AppLayout title="Crear Usuario">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Crear Usuario
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" v-model="form.name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" v-model="form.email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <span v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</span>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                                <input type="password" v-model="form.password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <span v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</span>
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                <input type="password" v-model="form.password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <span v-if="errors.password_confirmation" class="text-red-500 text-sm">{{ errors.password_confirmation }}</span>
                            </div>
                            <div class="mb-4">
                                <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                                <v-select 
                                    v-model="form.role" 
                                    :options="roles" 
                                    label="name" 
                                    :reduce="role => role.name"
                                    placeholder="Seleccione un rol"
                                ></v-select>
                                <span v-if="errors.role" class="text-red-500 text-sm">{{ errors.role }}</span>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <a :href="route('users.index')" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Cancelar
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Crear
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
import { ref, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import { useToast } from 'vue-toastification';

const { props } = usePage();
const errors = ref({});

const toast = useToast();
const roles = ref(props.roles);
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});
onMounted(() => {
    console.log("Roles desde backend:", roles.value);
});
const submit = () => {
    form.post(route('users.store'), {
        onSuccess: () => {
            form.reset();
            toast.success('Usuario creado correctamente.');
        },
        onError: (error) => {
            console.error('Error creating users:', error);
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