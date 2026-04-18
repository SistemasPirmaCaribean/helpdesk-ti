<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <div class="d-flex align-center mb-3">
            <v-icon color="green" class="mr-2">mdi-check</v-icon>
            <h3 class="card-title">Tickets Resueltos</h3>
        </div>

        <div v-for="item in sortedData" :key="item.technician" class="mb-3">
            <div class="d-flex justify-space-between">
                <span class="tech">Técnico {{ item.technician }}</span>
                <span class="value">{{ item.total }}</span>
            </div>

            <v-progress-linear
                :model-value="getPercent(item.total)"
                height="8"
                color="green"
                rounded
            />
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    data: { technician: string; total: number }[];
}>();

const sortedData = computed(() =>
    [...props.data].sort((a, b) => b.total - a.total)
);

const max = computed(() =>
    Math.max(...props.data.map(i => i.total), 1)
);

const getPercent = (val: number) => (val / max.value) * 100;
</script>

<style scoped>
.card-title {
    font-weight: 600;
}
.tech {
    color: #6b7280;
    font-size: 13px;
}
.value {
    font-weight: 600;
}
</style>