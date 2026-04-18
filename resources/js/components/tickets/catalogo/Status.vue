<template>
    <v-container>
        <StatusTable :items="statuses" :loading="loading" @add="abrirAgregar" @edit="abrirEditar" />

        <StatusDialog v-model="dialog" :status="selected" :esEdicion="esEdicion" @saved="afterSave" @error="showError" />

        <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000">
            {{ snackbarMessage }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

import StatusDialog from '@/components/tickets/catalogo/components/StatusDialog.vue';
import StatusTable from '@/components/tickets/catalogo/components/StatusTable.vue';

import statusesService from '@/components/tickets/catalogo/services/StatusService';

interface Status {
    id: number;
    name: string;
    color: string;
    active: number;
}

const statuses = ref<Status[]>([]);

const loading = ref(false);

const dialog = ref(false);

const esEdicion = ref(false);

const selected = ref<Status | null>(null);

const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

const fetchData = async () => {
    loading.value = true;

    try {
        statuses.value = await statusesService.getAll();
    } catch (e) {
        console.error(e);
    }

    loading.value = false;
};

const abrirAgregar = () => {
    selected.value = null;
    esEdicion.value = false;
    dialog.value = true;
};

const abrirEditar = (item: Status) => {
    selected.value = item;
    esEdicion.value = true;
    dialog.value = true;
};

const afterSave = async () => {
    await fetchData();

    snackbarMessage.value = esEdicion.value ? 'Estatus actualizado correctamente' : 'Estatus creado correctamente';

    snackbarColor.value = 'success';

    snackbar.value = true;
};

const showError = (msg: string) => {
    snackbarMessage.value = msg;
    snackbarColor.value = 'error';
    snackbar.value = true;
};

onMounted(fetchData);
</script>
