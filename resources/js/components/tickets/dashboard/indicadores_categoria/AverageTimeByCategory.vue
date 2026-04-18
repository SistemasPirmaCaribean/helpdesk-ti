<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <div class="d-flex align-center mb-3">
            <v-icon color="deep-purple" class="mr-2">mdi-timer-outline</v-icon>
            <h3 class="card-title">Tiempo promedio por categoría</h3>
        </div>

        <div v-for="item in sortedData" :key="item.category" class="mb-3">
            <div class="d-flex justify-space-between mb-1">
                <span class="category">Categoría {{ item.category }}</span>
                <span class="value">{{ item.average.toFixed(1) }} hrs</span>
            </div>

            <v-progress-linear
                :model-value="item.average"
                height="8"
                color="deep-purple"
                rounded
            />
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    data: { category: string; average: number }[];
}>();

const sortedData = computed(() =>
    [...props.data].sort((a, b) => b.average - a.average)
);
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