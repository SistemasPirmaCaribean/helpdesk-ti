<template>
    <v-card>

        <!-- HEADER RESPONSIVO -->
        <v-card-title>
            <v-row align="center" class="w-100">

                <v-col cols="12" md="4" class="d-flex align-center">
                    <v-icon class="me-2" color="warning">mdi-domain</v-icon>
                    <span class="text-h6">Departamento</span>
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
                    <v-btn
                        color="primary"
                        @click="$emit('add')"
                        elevation="1"
                        :block="smAndDown"
                        class="d-md-inline-flex"
                    >
                        <v-icon start>mdi-plus</v-icon>
                        Agregar
                    </v-btn>
                </v-col>

            </v-row>
        </v-card-title>

        <v-divider></v-divider>

        <!-- TABLA -->
        <v-data-table
            :headers="headers"
            :items="items"
            :loading="loading"
            :search="search"
            density="compact"
            hover
            class="elevation-0"
        >

            <!-- COLOR -->
            <template #[`item.color`]="{ item }">
                <div
                    :style="{
                        width: '28px',
                        height: '28px',
                        borderRadius: '50%',
                        backgroundColor: item.color,
                        border: '2px solid #e0e0e0',
                        margin: 'auto'
                    }"
                ></div>
            </template>

            <!-- ESTATUS -->
            <template #[`item.active`]="{ item }">
                <v-chip
                    size="small"
                    :color="item.active ? 'success' : 'error'"
                    variant="flat"
                >
                    {{ item.active ? 'Activo' : 'Inactivo' }}
                </v-chip>
            </template>

            <!-- ACCIONES -->
            <template #[`item.actions`]="{ item }">
                <div class="d-flex justify-center">
                    <v-btn
                        size="small"
                        icon
                        variant="text"
                        color="secondary"
                        @click="$emit('edit', item)"
                        :disabled="item.active === 0"
                    >
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                </div>
            </template>

        </v-data-table>

    </v-card>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useDisplay } from 'vuetify'

const { smAndDown } = useDisplay()

interface Department {
    id: number;
    name: string;
    color: string;
    active: number;
}

defineProps({
    items: {
        type: Array as () => Department[],
        required: true,
    },
    loading: {
        type: Boolean,
        required: true,
    },
});

const search = ref('');

const headers = [
    { title: 'ID', key: 'id', width: '70px' },
    { title: 'Nombre', key: 'name' },
    { title: 'Estatus', key: 'active', width: '120px' },
    { title: 'Acciones', key: 'actions', sortable: false, width: '100px', align: 'center' as const },
];
</script>