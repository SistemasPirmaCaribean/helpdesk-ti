<template>
    <v-dialog v-model="model" @update:model-value="$emit('update:modelValue', $event)" max-width="500px">
        <v-card>
            <v-card-title class="text-h5">Asignar ticket</v-card-title>
            <v-card-text>
                <v-select
                    v-model="form.idtechnician"
                    :items="techniciansFiltrados"
                    item-title="nombre"
                    item-value="id"
                    label="Técnico"
                    variant="outlined"
                    clearable
                ></v-select>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="asignarTicket">Asignar</v-btn>
                <v-btn text @click="$emit('update:modelValue', false)">Cancelar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { reactive, watch, computed } from 'vue';
import service from '@/components/tickets/ticket/services/TicketService';


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
    technician?: Technician;
}

const props = defineProps<{
    modelValue: boolean;
    ticket: Ticket | null;
    technicians: Technician[] | null;
}>();

const emit = defineEmits(['update:modelValue', 'ticketAsignado','saved', 'error']);

const model = computed({
    get() {
        return props.modelValue;
    },
    set(value: boolean) {
        emit('update:modelValue', value);
    },
});

const form = reactive({
    idtechnician: null as number | null,
});

watch(
    () => props.ticket,
    (newTicket: Ticket | null) => {
        if (newTicket) {
            form.idtechnician = newTicket.idtechnician;
        }
    },
    { immediate: true }
);

const asignarTicket = async () => {
    if (!props.ticket) return;

    try {
       await service.update(props.ticket.id, { idtechnician: form.idtechnician });

       model.value = false;

       emit('saved');
    } catch (error) {
        console.error(error);
        emit('error', error)
    }
};

const techniciansFiltrados = computed(() => {
    if (!props.technicians) {
        return [];
    }

    // Extraer técnicos y eliminar duplicados
    const unicos = new Map();

    props.technicians.forEach((item) => {
        if (item.technician) {
            unicos.set(item.technician.id, item.technician);
        }
    });

    return Array.from(unicos.values());
});
</script>
