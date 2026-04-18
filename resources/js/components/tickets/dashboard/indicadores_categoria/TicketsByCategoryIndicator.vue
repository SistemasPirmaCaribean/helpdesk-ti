<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <div class="d-flex align-center mb-3">
            <v-icon color="blue" class="mr-2">mdi-shape</v-icon>
            <h3 class="card-title">Tickets por categoría</h3>
        </div>

        <div v-for="item in sortedData" :key="item.category" class="mb-3">
            <div class="d-flex justify-space-between">
                <span class="category">Categoría {{ item.category }}</span>
                <span class="value">{{ item.total }}</span>
            </div>

            <v-progress-linear
                :model-value="getPercentage(item.total)"
                height="8"
                color="blue"
                rounded
            />
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    data: { category: string; total: number }[];
}>();

const sortedData = computed(() =>
    [...props.data].sort((a, b) => b.total - a.total)
);

const max = computed(() =>
    Math.max(...props.data.map(i => i.total), 1)
);

const getPercentage = (value: number) => (value / max.value) * 100;
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