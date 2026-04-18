<template>
<v-container>
        <CategoryTable :items="categories" :loading="loading" @add="abrirAgregar" @edit="abrirEditar" />

        <CategoryDialog v-model="dialog" :category="selected" :esEdicion="esEdicion" @saved="afterSave" @error="showError" />

        <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000">
            {{ snackbarMessage }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

import CategoryDialog from '@/components/tickets/catalogo/components/CategoryDialog.vue';
import CategoryTable from '@/components/tickets/catalogo/components/CategoryTable.vue';

import categoriesService from '@/components/tickets/catalogo/services/CategoryService';

interface Category {
    id: number;
    name: string;
    active: number;
}

const categories = ref<Category[]>([]);

const loading = ref(false);

const dialog = ref(false);

const esEdicion = ref(false);

const selected = ref<Category | null>(null);

const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

const fetchData = async () => {
    loading.value = true;

    try {
        categories.value = await categoriesService.getAll();
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

const abrirEditar = (item: Category) => {
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