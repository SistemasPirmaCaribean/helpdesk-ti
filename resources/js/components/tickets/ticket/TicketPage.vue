<template>
    <v-container>
        <v-row class="mb-4">
            <!-- Rango de fechas -->
            <v-col cols="12" md="4">
                <v-text-field v-model="dateFrom" label="Fecha inicio" type="date" density="compact" clearable />
            </v-col>

            <v-col cols="12" md="4">
                <v-text-field v-model="dateTo" label="Fecha fin" type="date" density="compact" clearable />
            </v-col>
            <v-divider vertical class="mx-2"></v-divider>
            <!-- Estatus -->
            <v-col cols="12" md="3">
                <v-select v-model="selectedStatus" :items="statusOptions" label="Estatus" clearable density="compact" />
            </v-col>
        </v-row>
        <TicketTable
            :items="filteredTickets"
            :loading="loading"
            @add="abrirAgregar"
            @edit="abrirEditar"
            @message="abrirMensaje"
            @assign="abrirAsignacion"
            @claim="abrirClaim"
            @process="statusProcess"
        />

        <TicketDialog
            v-model="dialog"
            :ticket="selected"
            :categories="categories"
            :priorities="priorities"
            :technicians="technicians"
            :esEdicion="esEdicion"
            @saved="afterSave"
            @error="showError"
        />

        <TicketMessagesDialog v-model="dialogMessages" :ticket="selected" :messages="comments" @reload="selected?.id && fetchComments(selected.id)" />

        <TicketDialogAsignacion
            v-model="dialogAsignacion"
            :ticket="selected"
            :technicians="technicians"
            @saved="afterSave"
            @error="showError"
            @ticketAsignado="afterSave"
        ></TicketDialogAsignacion>

        <TicketsClaim
            v-model="dialogClaim"
            :ticket="selected"
            :claims="claims"
            @claimUpdated="afterSave"
            @error="showError"
            @saved="afterSave"
            @abrirAsignacion="abrirAsignacion"
        ></TicketsClaim>

        <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000">
            {{ snackbarMessage }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';

import TicketDialog from '@/components/tickets/ticket/components/TicketDialog.vue';
import TicketMessagesDialog from '@/components/tickets/ticket/components/TicketMessagesDialog.vue';
import TicketTable from '@/components/tickets/ticket/components/TicketTable.vue';

import service from '@/components/tickets/ticket/services/TicketService';
import TicketDialogAsignacion from './components/TicketDialogAsignacion.vue';
import TicketsClaim from './components/TicketsClaim.vue';

let interval: any = null;

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

interface Usuario {
    id: number;
    nombre: string;
}

interface Comment {
    id: number;
    idticket: number;
    iduser: number;
    comment: string;
    created_at?: string;
    user?: Usuario;
}

interface Claim {
    id: number;
    idticket: number;
    iduser: number;
    type: string;
    status: string;
    reason: string | null;
    ticket: Ticket | null;
    user: Usuario;
}

const categories = ref<Category[]>([]);
const priorities = ref<Priority[]>([]);
const tickets = ref<Ticket[]>([]);
const technicians = ref<Technician[]>([]);

const comments = ref<Comment[]>([]);
const claims = ref<Claim[]>([]);

const loading = ref(false);

const dialog = ref(true);
const dialogMessages = ref(false);
const dialogAsignacion = ref(false);
const dialogClaim = ref(false);

const esEdicion = ref(false);

const selected = ref<Ticket | null>(null);

const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

const dateFrom = ref<string | null>(null);
const dateTo = ref<string | null>(null);
const selectedStatus = ref<string | null>(null);

const fetchData = async () => {
    // loading.value = true;

    try {
        tickets.value = await service.getAll();
    } catch (e) {
        console.error(e);
    }

    loading.value = false;
};

const fetchCategories = async () => {
    try {
        categories.value = await service.getCategories();
    } catch (e) {
        console.error(e);
    }
};

const fetchPriorities = async () => {
    try {
        priorities.value = await service.getPriorities();
    } catch (e) {
        console.error(e);
    }
};

const fetchComments = async (idticket: number) => {
    try {
        comments.value = await service.getCommentsByTicket(idticket);
    } catch (e) {
        console.error(e);
    }
};

const fetchTechnicians = async () => {
    try {
        technicians.value = await service.getTechnicians();
    } catch (e) {
        console.error(e);
    }
};

const fetchClaims = async (idticket: number) => {
    try {
        claims.value = await service.getClaimsByTicket(idticket);
    } catch (e) {
        console.error(e);
    }
};

const abrirAgregar = () => {
    selected.value = null;
    esEdicion.value = false;
    dialog.value = true;
};

const abrirEditar = (item: Ticket) => {
    selected.value = item;
    console.log('selected', selected.value);
    esEdicion.value = true;
    dialog.value = true;
};

const abrirMensaje = async (item: Ticket) => {
    selected.value = item;
    dialogMessages.value = true;
    await fetchComments(item.id);
    await fetchData(); // para actualizar los mensajes no leidos
};

const abrirAsignacion = (item: Ticket) => {
    selected.value = item;
    dialogAsignacion.value = true;
};

const abrirClaim = async (item: Ticket) => {
    selected.value = item;
    await fetchClaims(item.id);
    dialogClaim.value = true;
};

const afterSave = async () => {
    await fetchData();

    snackbarMessage.value = esEdicion.value ? 'Ticket actualizado correctamente' : 'Ticket creado correctamente';

    snackbarColor.value = 'success';

    snackbar.value = true;
};

const showError = (msg: string) => {
    snackbarMessage.value = msg;
    snackbarColor.value = 'error';
    snackbar.value = true;
};

const statusProcess = async (item: Ticket, idstatus: number) => {
    try {
        await service.statusProcess(item.id, idstatus);

        await fetchData();

        snackbarMessage.value = 'Estatus del ticket actualizado correctamente';
        snackbarColor.value = 'success';
        snackbar.value = true;
    } catch (e) {
        console.error(e);
        snackbarMessage.value = 'Error al actualizar el estatus del ticket';
        snackbarColor.value = 'error';
        snackbar.value = true;
    }
};

onMounted(async () => {
    loading.value = true;
    await fetchData();
    await fetchCategories();
    await fetchPriorities();
    await fetchTechnicians();
    // revisar cada 10 segundos si hay cambios
    interval = setInterval(async () => {
        await fetchData();

        // si el dialogo de mensajes está abierto refresca comentarios
        if (dialogMessages.value && selected.value?.id) {
            await fetchComments(selected.value.id);
        }
    }, 10000);
});

onUnmounted(() => {
    clearInterval(interval);
});

const toDateOnly = (date: string | Date) => {
    return new Date(date).toISOString().split('T')[0];
};

const filteredTickets = computed(() => {
    return tickets.value.filter((item) => {
        const itemDate = toDateOnly(item.date_opened);

        const from = dateFrom.value;
        const to = dateTo.value;

        const matchDate = (!from || itemDate >= from) && (!to || itemDate <= to);

        const matchStatus = !selectedStatus.value || item.status?.name === selectedStatus.value;

        return matchDate && matchStatus;
    });
});

const statusOptions = computed(() => {
    const uniqueStatuses = new Set(tickets.value.map((ticket) => ticket.status?.name).filter((name): name is string => !!name));
    const sortedStatuses = Array.from(uniqueStatuses).sort((a, b) => a.localeCompare(b));
    return sortedStatuses;
});
</script>
