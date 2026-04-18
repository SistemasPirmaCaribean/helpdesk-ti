<template>
    <v-container>
        <PrioritiesTable :items="priorities" :loading="loading" @add="abrirAgregar" @edit="abrirEditar" />

        <PrioritiesDialog v-model="dialog" :priority="selected" :esEdicion="esEdicion" @saved="afterSave" @error="showError" />

        <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000">
            {{ snackbarMessage }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

import PrioritiesDialog from '@/components/tickets/catalogo/components/PriorityDialog.vue';
import PrioritiesTable from '@/components/tickets/catalogo/components/PriorityTable.vue';

import prioritiesService from '@/components/tickets/catalogo/services/PriorityService';

interface Priority {
    id: number;
    name: string;
    color: string;
    sla_hours: number;
    active: number;
}

const priorities = ref<Priority[]>([]);

const loading = ref(false);

const dialog = ref(false);

const esEdicion = ref(false);

const selected = ref<Priority | null>(null);

const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

const fetchData = async () => {
    loading.value = true;

    try {
        priorities.value = await prioritiesService.getAll();
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

const abrirEditar = (item: Priority) => {
    selected.value = item;
    esEdicion.value = true;
    dialog.value = true;
};

const afterSave = async () => {
    await fetchData();

    snackbarMessage.value = esEdicion.value ? 'Prioridad actualizada correctamente' : 'Prioridad creada correctamente';

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
