<template>
    <v-container>
        <v-card>
            <v-card-title>Departamentos Tickets</v-card-title>
            <v-card-text>
                <v-row class="mt-4 mb-4" align="center" justify="space-between">
                    <!-- BOTON PARA ABRIR DIALOGO AGREGAR -->
                    <v-col cols="12" md="6">
                        <v-btn color="primary" @click="agregar">
                            <v-icon left>mdi-plus</v-icon>
                            Agregar Departamento
                        </v-btn>
                    </v-col>

                    <v-select
                        v-model="filtroEstatus"
                        :items="[
                            { title: 'Todos', value: 'todos' },
                            { title: 'Activo', value: 'activo' },
                            { title: 'Inactivo', value: 'inactivo' },
                        ]"
                        label="Filtrar por estatus"
                        variant="outlined"
                        density="compact"
                        style="max-width: 200px"
                    ></v-select>
                </v-row>

                <v-card-title class="d-flex align-center pe-2">
                    <v-icon icon="mdi-clipboard-check-multiple-outline" class="me-2"></v-icon>
                    &nbsp; Departamentos Tickets

                    <v-spacer></v-spacer>

                    <v-text-field
                        v-model="search"
                        density="compact"
                        label="Search"
                        prepend-inner-icon="mdi-magnify"
                        variant="outlined"
                        flat
                        hide-details
                    ></v-text-field>
                </v-card-title>
                <!-- 🔍 Barra de filtros -->

                <v-divider></v-divider>

                <v-data-table
                    :search="search"
                    class="elevation-1"
                    :items="itemFilter"
                    :headers="headers"
                    :loading="loading"
                    loading-text="Cargando datos..."
                    item-key="id"
                    multi-sort
                >
                    <template #[`item.active`]="{ item }">
                        <v-chip :color="item.active === 1 ? 'green' : 'red'" dark>
                            {{ item.active === 1 ? 'Activo' : 'Inactivo' }}
                        </v-chip>
                    </template>

                    <template #[`item.actions`]="{ item }">
                        <div class="d-flex" style="gap: 4px">
                            <v-btn size="x-small" color="secondary" icon @click="editarItem(item)" :disabled="item.active === 0">
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                        </div>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </v-container>

    <v-dialog v-model="dialog" min-width="400px" max-width="1000px" width="90%">
        <v-card>
            <v-card-title>
                <span class="text-h5">{{ esEdicion ? 'Editar Departamento' : 'Agregar Departamento' }}</span>
            </v-card-title>

            <v-card-text>
                <v-form ref="formRef" lazy-validation>
                    <v-row>
                        <v-col cols="12" md="12">
                            <v-text-field
                                v-model="form.name"
                                label="Nombre"
                                :rules="[rules.required, rules.minLength(2), rules.maxLength(100)]"
                                variant="outlined"
                                clearable
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text @click="dialog = false">Cancelar</v-btn>
                <v-btn color="primary" text @click="guardar">Guardar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-snackbar :color="snackbarColor" v-model="snackbar" :timeout="3000">
        {{ snackbarMessage }}
    </v-snackbar>
</template>

<script lang="ts" setup>
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';

interface Departament {
    id: number;
    name: string;
    active: number;
}

const departaments = ref<Departament[]>([]);

const search = ref('');
const filtroEstatus = ref('todos');
const loading = ref(false);
const esEdicion = ref(false);
const formRef = ref();
const dialog = ref(false);
const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

const form = ref<Departament>({
    id: 0,
    name: '',
    active: 1,
});

const headers = [
    { title: 'ID', key: 'id' },
    { title: 'Nombre', key: 'name' },
    { title: 'Estatus', key: 'active' },
    { title: 'Acciones', key: 'actions', sortable: false },
];

const rules = {
    required: (value: any) => !!value || 'Este campo es requerido.',
    maxLength: (max: number) => (value: string) => (value || '').length <= max || `Máximo ${max} caracteres.`,
    minLength: (min: number) => (value: string) => (value || '').length >= min || `Mínimo ${min} caracteres.`,
    maxDate: (maxDate: string) => (value: string) => {
        if (!value) return true; // Permitir campo vacío
        return value <= maxDate || `La fecha debe ser antes de ${maxDate}.`;
    },
    minDate: (minDate: string) => (value: string) => {
        if (!value) return true; // Permitir campo vacío
        return value >= minDate || `La fecha debe ser después de ${minDate}.`;
    },
};

const agregar = () => {
    esEdicion.value = false;
    dialog.value = true;

    form.value = { ...form.value };
    // Lógica para abrir el diálogo de agregar
};

const editarItem = (item: Departament) => {
    esEdicion.value = true;
    dialog.value = true;

    form.value = { ...item };
    // Lógica para abrir el diálogo de edición
};

const fetchData = async () => {
    loading.value = true;
    try {
        const config = {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('api-token')}`,
                Accept: 'application/json',
            },
        };
        const response = await axios.get('/api/tkt-priorities', config);
        departaments.value = response.data;
    } catch (error) {
        console.error('Error al cargar los departamentos:', error);
    } finally {
        loading.value = false;
    }
};

const itemFilter = computed(() => {
    if (filtroEstatus.value === 'todos') {
        return departaments.value;
    }
    const isActive = filtroEstatus.value === 'activo' ? 1 : 0;
    return departaments.value.filter((item) => item.active === isActive);
});

const guardar = async () => {
    try {
        const { valid } = await formRef.value.validate();

        if (valid) {
            if (esEdicion.value) {
                // Lógica para actualizar el departamento
                const config = {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('api-token')}`,
                        Accept: 'application/json',
                    },
                };
                await axios.put(`/api/tkt-priorities/${form.value.id}`, form.value, config);
            } else {
                // Lógica para agregar un nuevo departamento
                const config = {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('api-token')}`,
                        Accept: 'application/json',
                    },
                };
                await axios.post('/api/tkt-priorities', form.value, config);
            }
            dialog.value = false;
            await fetchData();
            snackbarMessage.value = esEdicion.value ? 'Departamento actualizado exitosamente.' : 'Departamento agregado exitosamente.';
            snackbarColor.value = 'success';
            snackbar.value = true;
        }
    } catch (error) {
        console.error('Error al guardar el departamento:', error);
    }
};

onMounted(() => {
    fetchData();
});
</script>
