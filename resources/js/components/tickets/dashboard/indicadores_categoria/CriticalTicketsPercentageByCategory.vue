<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <!-- HEADER -->
        <div class="d-flex align-center mb-4">
            <v-icon color="red" class="mr-2">mdi-alert</v-icon>
            <h3 class="card-title">Tickets críticos por categoría</h3>
        </div>

        <!-- LISTA -->
        <div
            v-for="item in sortedData"
            :key="item.category"
            class="mb-4"
        >
            <div class="d-flex justify-space-between align-center mb-1">
                <span class="category">
                    {{ item.category }}
                </span>

                <span class="value">
                    {{ item.average.toFixed(1) }}%
                </span>
            </div>

            <v-progress-linear
                :model-value="item.average"
                height="8"
                rounded
                :color="getColor(item.average)"
            />
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    data: { category: string; average: number }[];
}>();

// Ordenar de mayor a menor
const sortedData = computed(() =>
    [...props.data].sort((a, b) => b.average - a.average)
);

// Color dinámico según severidad
const getColor = (value: number) => {
    if (value >= 70) return 'red';
    if (value >= 40) return 'orange';
    return 'green';
};
</script>

<style scoped>
.card-title {
    font-weight: 600;
    color: #374151;
}

.category {
    font-size: 13px;
    color: #6b7280;
}

.value {
    font-weight: 600;
}
</style>