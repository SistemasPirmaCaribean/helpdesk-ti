<template>
    <v-container>
        <DepartamentsTable :items="departaments" :loading="loading" @add="abrirAgregar" @edit="abrirEditar" />

        <DepartamentsDialog v-model="dialog" :departament="selected" :esEdicion="esEdicion" @saved="afterSave" @error="showError" />

        <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000">
            {{ snackbarMessage }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

import DepartamentsDialog from '@/components/tickets/catalogo/components/DepartamentDialog.vue';
import DepartamentsTable from '@/components/tickets/catalogo/components/DepartamentTable.vue';

import departamentsService from '@/components/tickets/catalogo/services/DepartamentService';

interface Departament {
    id: number;
    name: string;
    active: number;
}

const departaments = ref<Departament[]>([]);

const loading = ref(false);

const dialog = ref(false);

const esEdicion = ref(false);

const selected = ref<Departament | null>(null);

const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

const fetchData = async () => {
    loading.value = true;

    try {
        departaments.value = await departamentsService.getAll();
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

const abrirEditar = (item: Departament) => {
    selected.value = item;
    esEdicion.value = true;
    dialog.value = true;
};

const afterSave = async () => {
    await fetchData();

    snackbarMessage.value = esEdicion.value ? 'Departamento actualizado correctamente' : 'Departamento creado correctamente';

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
