<template>
    <v-card>
        <!-- HEADER RESPONSIVO -->
        <v-card-title>
            <v-row align="center" class="w-100">
                <v-col cols="12" md="4" class="d-flex align-center">
                    <v-icon class="me-2" color="warning">mdi-tag</v-icon>
                    <span class="text-h6">Categoría</span>
                </v-col>

                <!-- BUSCADOR -->
                <v-col cols="12" md="4">
                    <v-text-field
                        v-model="search"
                        label="Buscar"
                        prepend-inner-icon="mdi-magnify"
                        variant="outlined"
                        density="compact"
                        hide-details
                        clearable
                    />
                </v-col>

                <!-- BOTON -->
                <v-col cols="12" md="4" class="d-flex justify-md-end">
                    <v-btn color="primary" @click="$emit('add')" elevation="1" :block="smAndDown" class="d-md-inline-flex">
                        <v-icon start>mdi-plus</v-icon>
                        Agregar
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-title>

        <v-divider></v-divider>

        <!-- TABLA -->
        <v-data-table :headers="headers" :items="items" :loading="loading" :search="search" density="compact" hover class="elevation-0">
            <template #[`item.technician.nombre`]="{ item }">
                {{ item.technician?.nombre ?? 'Sin asignar' }}
            </template>

            <!-- ESTATUS -->
            <template #[`item.status.name`]="{ item }">
                <v-chip size="small" :color="item.status?.color" variant="flat">
                    {{ item.status?.name }}
                </v-chip>
            </template>

            <!-- PRIORIDAD -->
            <template #[`item.priority.name`]="{ item }">
                <v-chip size="small" :color="item.priority?.color || undefined" variant="flat">
                    {{ item.priority?.name }}
                </v-chip>
            </template>

            <!-- FECHA APERTURA -->
            <template #[`item.date_opened`]="{ item }">
                {{ new Date(item.date_opened).toLocaleString() }}
            </template>

            <!-- ACCIONES -->
            <template #[`item.actions`]="{ item }">
                <div class="d-flex align-center justify-center">
                    <!-- EDITAR -->
                    <v-btn size="small" icon variant="text" color="warning" @click="$emit('edit', item)" :disabled="!(item.status?.id === 1)">
                        <v-icon>mdi-pencil</v-icon>
                        <v-tooltip activator="parent" text="Editar ticket" />
                    </v-btn>

                    <v-divider vertical class="mx-2"></v-divider>

                    <!-- MENSAJES -->
                    <v-tooltip text="Ver mensajes">
                        <template #activator="{ props }">
                            <v-badge :content="item.unread" color="error" v-if="(item.unread ?? 0) > 0">
                                <v-btn
                                    v-bind="props"
                                    size="small"
                                    icon
                                    variant="text"
                                    color="info"
                                    @click="$emit('message', item)"
                                    :disabled="item.status?.id === 7"
                                >
                                    <v-icon>mdi-message-text</v-icon>
                                </v-btn>
                            </v-badge>

                            <v-btn
                                v-else
                                v-bind="props"
                                size="small"
                                icon
                                variant="text"
                                color="info"
                                @click="$emit('message', item)"
                                :disabled="item.status?.id === 7"
                            >
                                <v-icon>mdi-message-text</v-icon>
                            </v-btn>
                        </template>
                    </v-tooltip>

                    <v-divider v-if="isAdmin || isTechnician" vertical class="mx-2"></v-divider>

                    <!-- RECLAMACIONES -->

                    <v-btn
                        v-if="isAdmin || isTechnician"
                        size="small"
                        icon
                        variant="text"
                        color="purple"
                        @click="$emit('claim', item)"
                        :disabled="!(item.status?.id === 1 || item.status?.id === 3 || item.status?.id === 4)"
                    >
                        <v-icon>mdi-file-document</v-icon>
                        <v-tooltip activator="parent" text="Ver Reclamaciones" />
                    </v-btn>

                    <v-divider vertical class="mx-2"></v-divider>

                    <!-- ASIGNAR TECNICO -->
                    <v-btn
                        v-if="isAdmin"
                        size="small"
                        icon
                        variant="text"
                        color="secondary"
                        @click="$emit('assign', item)"
                        :disabled="!(item.status?.id === 1 || item.status?.id === 3 || item.status?.id === 4)"
                    >
                        <v-icon>mdi-account-cog</v-icon>
                        <v-tooltip activator="parent" text="Asignar técnico" />
                    </v-btn>

                    <!-- EN PROCESO -->
                    <v-btn
                        v-if="(isAdmin || isTechnician) && (item.status?.id === 3 || item.status?.id === 5)"
                        size="small"
                        icon
                        variant="text"
                        color="primary"
                        @click="$emit('process', item, 4)"
                    >
                        <v-icon>mdi-progress-clock</v-icon>

                        <v-tooltip activator="parent" text="Marcar como en proceso" />
                    </v-btn>

                    <!-- RESUELTO -->
                    <v-btn
                        v-if="(isAdmin || isTechnician) && item.status?.id === 4"
                        size="small"
                        icon
                        variant="text"
                        color="success"
                        @click="$emit('process', item, 6)"
                    >
                        <v-icon>mdi-check</v-icon>

                        <v-tooltip activator="parent" text="Marcar como resuelto" />
                    </v-btn>

                    <!-- EN ESPERA -->
                    <v-btn
                        v-if="(isAdmin || isTechnician) && item.status?.id === 4"
                        size="small"
                        icon
                        variant="text"
                        color="warning"
                        @click="$emit('process', item, 5)"
                    >
                        <v-icon>mdi-pause</v-icon>

                        <v-tooltip activator="parent" text="Marcar como en espera" />
                    </v-btn>

                    <!-- CERRAR -->
                    <v-btn
                        v-if="(isAdmin || !isTechnician) && (item.status?.id === 6 || item.status?.id === 5)"
                        size="small"
                        icon
                        variant="text"
                        color="error"
                        @click="$emit('process', item, 7)"
                    >
                        <v-icon>mdi-close</v-icon>

                        <v-tooltip activator="parent" text="Cerrar ticket" />
                    </v-btn>
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useDisplay } from 'vuetify';

const { smAndDown } = useDisplay();

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
    unread?: number;
    status?: Status;
    category?: Category;
    priority?: Priority;
    technician?: Technician;
}

defineProps({
    items: {
        type: Array as () => Ticket[],
        required: true,
    },
    loading: {
        type: Boolean,
        required: true,
    },
});

const search = ref('');
const idperfil = ref<number | null>(null);
const isAdmin = computed(() => idperfil.value === 1);
const isTechnician = computed(() => idperfil.value === 2);

const headers = [
    { title: 'ID', key: 'id', width: '70px' },
    { title: 'Referencia', key: 'reference_number' },
    { title: 'Usuario', key: 'user.nombre' },
    { title: 'Título', key: 'title' },
    { title: 'Descripción', key: 'description', width: '350px' },
    { title: 'Categoría', key: 'category.name' },
    { title: 'Prioridad', key: 'priority.name' },
    { title: 'Técnico', key: 'technician.nombre' },
    { title: 'Fecha apertura', key: 'date_opened', width: '150px' },
    { title: 'Estatus', key: 'status.name', width: '120px' },
    { title: 'Acciones', key: 'actions', sortable: false, width: '100px', align: 'center' as const },
];

onMounted(() => {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    idperfil.value = user.idperfil || null;
});
</script>
