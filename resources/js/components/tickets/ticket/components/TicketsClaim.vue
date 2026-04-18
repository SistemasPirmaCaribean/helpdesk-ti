<template>
    <v-dialog v-model="model" max-width="1200">
        <v-card rounded="xl">
            <!-- HEADER -->
            <v-card-title class="d-flex justify-space-between align-center">
                <div v-if="ticket">
                    <div class="text-h6 font-weight-bold">Ticket #{{ ticket.id }}</div>
                    <div class="text-subtitle-2 text-grey">
                        {{ ticket.title }}
                    </div>
                </div>

                <v-btn icon="mdi-close" variant="text" @click="model = false" />
            </v-card-title>

            <v-divider />

            <v-card-text>
                <!-- FORMULARIO -->
                <v-card class="mb-6" variant="tonal" rounded="lg">
                    <v-card-title class="text-subtitle-1 font-weight-bold"> Nueva Reclamación </v-card-title>

                    <v-card-text>
                        <v-form ref="formRef">
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="form.type"
                                        :items="types"
                                        item-title="title"
                                        item-value="key"
                                        label="Tipo de solicitud"
                                        variant="outlined"
                                        :rules="[rules.required]"
                                    />
                                </v-col>

                                <v-col cols="12" md="8">
                                    <v-textarea
                                        v-model="form.reason"
                                        label="Explica el motivo"
                                        variant="outlined"
                                        :rules="[rules.required]"
                                        rows="2"
                                        auto-grow
                                    />
                                </v-col>
                            </v-row>

                            <div class="d-flex mt-2 justify-end">
                                <v-btn color="primary" :loading="loading" @click="submitClaim"> Enviar </v-btn>
                            </div>
                        </v-form>
                    </v-card-text>
                </v-card>

                <!-- HISTORIAL -->
                <div>
                    <div class="text-subtitle-1 font-weight-bold mb-2">Historial de reclamaciones</div>

                    <v-alert v-if="claims.length === 0" type="info" variant="tonal"> No hay reclamaciones para este ticket. </v-alert>

                    <v-timeline v-else density="compact">
                        <v-timeline-item v-for="claim in claims" :key="claim.id" dot-color="primary">
                            <v-card rounded="lg">
                                <v-card-text>
                                    <div class="d-flex justify-space-between align-center">
                                        <div class="font-weight-bold">
                                            {{ getTypeLabel(claim.type) }}
                                        </div>

                                        <v-chip :color="getStatusColor(claim.status)" size="small" label>
                                            {{ claim.status }}
                                        </v-chip>
                                    </div>

                                    <div class="text-caption text-grey mt-1">Por: {{ claim.user.nombre }}</div>

                                    <div v-if="claim.reason" class="mt-2">
                                        {{ claim.reason }}
                                    </div>

                                    <!-- 🔥 ACCIONES -->
                                    <div v-if="(claim.status === 'pending' && isAdmin)" class="d-flex mt-3 justify-end" style="gap: 8px">
                                        <v-btn
                                            color="green"
                                            variant="tonal"
                                            size="small"
                                            :loading="loadingActions[claim.id]"
                                            @click="approveClaim(claim)"
                                        >
                                            Aceptar
                                        </v-btn>

                                        <v-btn
                                            color="red"
                                            variant="tonal"
                                            size="small"
                                            :loading="loadingActions[claim.id]"
                                            @click="rejectClaim(claim)"
                                        >
                                            Rechazar
                                        </v-btn>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-timeline-item>
                    </v-timeline>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import service from '@/components/tickets/ticket/services/TicketService';
import { computed, ref, watch, onMounted } from 'vue';

interface User {
    id: number;
    nombre: string;
}

interface Ticket {
    id: number;
    title: string;
}

interface Claim {
    id: number;
    idticket: number;
    type: string;
    status: string;
    reason: string | null;
    user: User;
}

const props = defineProps<{
    modelValue: boolean;
    ticket: Ticket | null;
    claims: Claim[];
}>();

const emit = defineEmits(['update:modelValue', 'saved', 'error', 'approveUnclaim', 'abrirAsignacion']);

const loading = ref(false);
const loadingActions = ref<Record<number, boolean>>({});

const idperfil = ref<number | null>(null);
const isAdmin = computed(() => idperfil.value === 1);

const rules = {
    required: (v: any) => !!v || 'Campo requerido',
};

const types = [
    { key: 'claim', title: 'Reclamar ticket' },
    { key: 'unclaim', title: 'Solicitar desasignación' },
];

const claims = ref<Claim[]>(props.claims || []);

const form = ref({
    idticket: props.ticket?.id || 0,
    type: 'claim',
    status: 'pending',
    reason: '',
});

const submitClaim = async () => {
    loading.value = true;
    try {
        await service.submitClaim(form.value);
        emit('saved');
        model.value = false;
    } catch (e) {
        emit('error', e);
    } finally {
        loading.value = false;
    }
};

const getTypeLabel = (type: string) => {
    return type === 'claim' ? 'Reclamación' : 'Desasignación';
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'approved':
            return 'green';
        case 'rejected':
            return 'red';
        default:
            return 'orange';
    }
};

const approveClaim = async (claim: Claim) => {
    loadingActions.value[claim.id] = true;
    try {
        if (claim.type === 'claim') {
            await service.approveClaim(claim.id);
        } else {
            await service.approveUnclaim(claim.id);
            
            emit('abrirAsignacion', props.ticket);
        }
        emit('saved');
    } catch (e) {
        emit('error', e);
    } finally {
        loadingActions.value[claim.id] = false;
    }
};

const rejectClaim = async (claim: Claim) => {
    loadingActions.value[claim.id] = true;
    try {
        if (claim.type === 'claim') {
            await service.rejectClaim(claim.id);
        } else {
            await service.rejectUnclaim(claim.id);
        }
        emit('saved');
    } catch (e) {
        emit('error', e);
    } finally {
        loadingActions.value[claim.id] = false;
    }
};

const model = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
});

watch(
    () => props.ticket,
    (ticket) => {
        form.value.idticket = ticket?.id || 0;
    },
);

watch(
    () => props.claims,
    (newClaims) => {
        claims.value = newClaims;
    },
);

onMounted(() => {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    idperfil.value = user.idperfil || null;
});
</script>
