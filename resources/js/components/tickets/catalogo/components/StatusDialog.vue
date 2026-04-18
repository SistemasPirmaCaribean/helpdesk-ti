<template>
    <v-dialog v-model="model" max-width="600">
        <v-card>
            <v-card-title>
                {{ esEdicion ? 'Editar Departamento' : 'Agregar Departamento' }}
            </v-card-title>

            <v-card-text>
                <v-form ref="formRef">
                    <v-text-field v-model="form.name" label="Nombre" variant="outlined" :rules="[rules.required]" clearable></v-text-field>
                    <v-color-picker v-model="form.color" label="Color" variant="outlined" :rules="[rules.required]" clearable></v-color-picker>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn text @click="model = false"> Cancelar </v-btn>

                <v-btn color="primary" @click="guardar"> Guardar </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import statusesService from '@/components/tickets/catalogo/services/StatusService';
import { computed, ref, watch } from 'vue';

interface Status {
    id: number;
    name: string;
    color: string;
    active: number;
}

const props = defineProps({
    modelValue: Boolean,
    status: Object as () => Status | null,
    esEdicion: Boolean,
});

const emit = defineEmits(['update:modelValue', 'saved', 'error']);

const model = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
});

const formRef = ref();

const form = ref<Status>({
    id: 0,
    name: '',
    color: '',
    active: 1,
});

watch(
    () => props.status,
    (val: any) => {
        if (val) {
            form.value = { ...val };
        } else {
            form.value = {
                id: 0,
                name: '',
                color: '',
                active: 1,
            };
        }
    },
    { immediate: true },
);

const rules = {
    required: (v: any) => !!v || 'Campo requerido',
};

const guardar = async () => {
    const { valid } = await formRef.value.validate();

    if (!valid || !form.value.color) {
        if (!form.value.color) {
            emit('error', 'El campo color es requerido');
        }
        return;
    }

    try {
        if (props.esEdicion) {
            await statusesService.update(form.value.id, form.value);
        } else {
            await statusesService.create(form.value);
        }

        model.value = false;

        emit('saved');
    } catch (error: any) {
        let mensaje = 'Error al guardar';

        if (error.response?.data?.message) {
            mensaje = error.response.data.message;
        }

        emit('error', mensaje);
    }
};
</script>
