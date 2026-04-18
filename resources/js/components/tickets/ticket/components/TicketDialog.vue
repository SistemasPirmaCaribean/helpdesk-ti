<template>
    <v-dialog v-model="model" max-width="1500">
        <v-card>
            <v-card-title>
                {{ esEdicion ? 'Editar Ticket' : 'Agregar Ticket' }}
            </v-card-title>

            <v-card-text>
                <v-form ref="formRef">
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-autocomplete
                                v-model="form.idcategory"
                                :items="categories ?? []"
                                item-title="name"
                                item-value="id"
                                label="Categoria"
                                variant="outlined"
                                :rules="[rules.required]"
                                clearable
                            ></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-select
                                v-model="form.idpriority"
                                :items="priorities ?? []"
                                item-title="name"
                                item-value="id"
                                label="Prioridad"
                                variant="outlined"
                                :rules="[rules.required]"
                                clearable
                            >
                                <!-- Cómo se muestran las opciones -->
                                <template #item="{ props, item }">
                                    <v-list-item v-bind="props">
                                        <template #prepend>
                                            <v-chip :color="item.raw.color || undefined" size="small" class="mr-2"></v-chip>
                                        </template>
                                        <!-- <v-list-item-title>
                                            {{ item.raw.name }}
                                        </v-list-item-title> -->
                                    </v-list-item>
                                </template>

                                <!-- Cómo se muestra el seleccionado -->
                                <template #selection="{ item }">
                                    <v-chip :color="item.raw.color || undefined" size="small" class="mr-2">
                                        {{ item.raw.name }}
                                    </v-chip>
                                </template>
                            </v-select>
                        </v-col>
                        <!-- <v-col cols="12" md="6">
                            <v-select
                                v-model="form.idtechnician"
                                :items="techniciansFiltrados"
                                item-title="nombre"
                                item-value="id"
                                label="Técnico"
                                variant="outlined"
                                clearable
                            ></v-select>
                        </v-col> -->
                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="form.date_opened"
                                label="Fecha de Apertura"
                                type="datetime-local"
                                variant="outlined"
                                readonly
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field v-model="form.title" label="Título" variant="outlined" :rules="[rules.required]" clearable></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-textarea
                                v-model="form.description"
                                label="Descripción"
                                variant="outlined"
                                :rules="[rules.required]"
                                clearable
                            ></v-textarea>
                        </v-col>
                    </v-row>
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
import service from '@/components/tickets/ticket/services/TicketService';
import { computed, ref, watch } from 'vue';

// Fechas en formato ISO (YYYY-MM-DDTHH:mm) para compatibilidad con v-text-field tipo datetime-local
const fechaActual = new Date()
    .toLocaleString('sv-SE', {
        timeZone: 'America/Mexico_City',
    })
    .replace(' ', 'T')
    .slice(0, 16);

interface Status {
    id: number;
    name: string;
    color: string;
    active: number;
}

interface Category {
    id: number;
    name: string;
    active: number;
}

interface Priority {
    id: number;
    name: string;
    color: string | null;
    active: number;
}

interface Technician {
    id: number;
    nombre: string;
    activo: number;
    idcategory: number;
    technician?: {
        id: number;
        nombre: string;
        activo: number;
    };
}

interface Ticket {
    id: number;
    idstatus: number;
    idcategory: number;
    idpriority: number;
    idtechnician: number;
    reference_number: string;
    title: string;
    description: string;
    date_opened: string;
    date_closed: string;
    active: number;
    status?: Status;
    category?: Category;
    priority?: Priority;
    technician?: Technician;
}

const props = defineProps({
    modelValue: Boolean,
    ticket: Object as () => Ticket | null,
    categories: Object as () => Category[] | null,
    priorities: Object as () => Priority[] | null,
    technicians: Object as () => Technician[] | null,
    esEdicion: Boolean,
});

const emit = defineEmits(['update:modelValue', 'saved', 'error']);

const model = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
});

const formRef = ref();

const form = ref<Ticket>({
    id: 0,
    idstatus: undefined as any,
    idcategory: undefined as any,
    idpriority: undefined as any,
    idtechnician: undefined as any,
    reference_number: '',
    title: '',
    description: '',
    date_opened: fechaActual,
    date_closed: '',
    active: 1,
});

watch(
    [() => props.ticket, () => props.categories, () => props.priorities, () => props.technicians],
    ([ticket]) => {
        if (ticket) {
            form.value = { ...ticket };
        } else {
            form.value = {
                id: 0,
                idstatus: undefined as any,
                idcategory: undefined as any,
                idpriority: undefined as any,
                idtechnician: undefined as any,
                reference_number: '',
                title: '',
                description: '',
                date_opened: fechaActual,
                date_closed: '',
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

    if (!valid) return;

    try {
        if (props.esEdicion) {
            await service.update(form.value.id, form.value);
        } else {
            await service.create(form.value);
        }

        model.value = false;

        form.value = {
            id: 0,
            idstatus: undefined as any,
            idcategory: undefined as any,
            idpriority: undefined as any,
            idtechnician: undefined as any,
            reference_number: '',
            title: '',
            description: '',
            date_opened: fechaActual,
            date_closed: '',
            active: 1,
        };

        emit('saved');
    } catch (error: any) {
        let mensaje = 'Error al guardar';

        if (error.response?.data?.message) {
            mensaje = error.response.data.message;
        }
        emit('error', mensaje);
    }
};

// const techniciansFiltrados = computed(() => {
//     if (!form.value.idcategory) return [];

//     const filtrados = (props.technicians ?? []).filter((item) => item.idcategory === form.value.idcategory);

//     // Extraer técnicos y eliminar duplicados
//     const unicos = new Map();

//     filtrados.forEach((item) => {
//         if (item.technician) {
//             unicos.set(item.technician.id, item.technician);
//         }
//     });

//     return Array.from(unicos.values());
// });
</script>
