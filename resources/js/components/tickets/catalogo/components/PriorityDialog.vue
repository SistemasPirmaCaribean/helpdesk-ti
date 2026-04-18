<template>
    <v-dialog v-model="model" max-width="600">
        <v-card>
            <v-card-title>
                {{ esEdicion ? 'Editar Prioridad' : 'Agregar Prioridad' }}
            </v-card-title>

            <v-card-text>
                <v-form ref="formRef">
                    <v-text-field v-model="form.name" label="Nombre" variant="outlined" :rules="[rules.required]" clearable></v-text-field>
                    <v-text-field v-model="form.sla_hours" label="Horas de SLA" variant="outlined" :rules="[rules.required]" clearable type="number"></v-text-field>
                    <v-divider class="my-4"></v-divider>
                    <v-label class="mb-2">Color</v-label>
                    <v-color-picker v-model="form.color" label="Color" variant="outlined" :rules="[rules.color]" clearable></v-color-picker>
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
import prioritiesService from '@/components/tickets/catalogo/services/PriorityService';
import { computed, ref, watch } from 'vue';

interface Priority {
    id: number;
    name: string;
    sla_hours: number;
    color: string | null;
    active: number;
}

const props = defineProps({
    modelValue: Boolean,
    priority: Object as () => Priority | null,
    esEdicion: Boolean,
});

const emit = defineEmits(['update:modelValue', 'saved', 'error']);

const model = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
});

const formRef = ref();

const form = ref<Priority>({
    id: 0,
    name: '',
    sla_hours: 0,
    color: null,
    active: 1,
});

watch(
    () => props.priority,
    (val: any) => {
        if (val) {
            form.value = { ...val };
        } else {
            form.value = {
                id: 0,
                name: '',
                sla_hours: 0,
                color: null,
                active: 1,
            };
        }
    },
    { immediate: true },
);

const rules = {
    required: (v: any) => !!v || 'Campo requerido',
    color: (v: any) => !v || /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(v) || 'Color inválido',
};

const guardar = async () => {
    const { valid } = await formRef.value.validate();

    if (!valid) return;
    try {
        if (props.esEdicion) {
            await prioritiesService.update(form.value.id, form.value);
        } else {
            await prioritiesService.create(form.value);
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
