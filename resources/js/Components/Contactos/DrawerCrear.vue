<!-- DrawerCrear.vue -->
<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Drawer, DrawerContent, DrawerHeader, DrawerTitle, DrawerFooter } from '@/Components/ui/drawer';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Textarea } from '@/Components/ui/textarea';

const props = defineProps({
    avaluoId: String,
    onSaved: Function,
});

const open = ref(false);

const form = useForm({
    avaluo_id: props.avaluoId,
    nombre: '',
    genero: '',
    celular: '',
    fecha_asignacion: '',
    observaciones: '',
});

const guardar = () => {
    form.post(route('contactos.store'), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
            form.reset();
            if (props.onSaved) props.onSaved();
        },
    });
};
</script>

<template>
    <div>
        <Button @click="open = true">Agregar Contacto</Button>

        <Drawer v-model:open="open">
            <DrawerContent>
                <DrawerHeader>
                    <DrawerTitle>Nuevo Contacto</DrawerTitle>
                </DrawerHeader>
                <div class="p-4 space-y-4">
                    <Input v-model="form.nombre" placeholder="Nombre" />
                    <Input v-model="form.genero" placeholder="Género" />
                    <Input v-model="form.celular" placeholder="Celular" />
                    <Input v-model="form.fecha_asignacion" type="date" placeholder="Fecha de asignación" />
                    <Textarea v-model="form.observaciones" placeholder="Observaciones" />
                </div>
                <DrawerFooter>
                    <Button @click="guardar" :disabled="form.processing">Guardar</Button>
                    <Button variant="outline" @click="open = false">Cancelar</Button>
                </DrawerFooter>
            </DrawerContent>
        </Drawer>
    </div>
</template>
