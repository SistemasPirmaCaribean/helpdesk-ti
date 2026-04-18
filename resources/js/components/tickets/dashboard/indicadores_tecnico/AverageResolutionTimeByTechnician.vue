<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <div class="d-flex align-center mb-3">
            <v-icon color="deep-purple" class="mr-2">mdi-account-clock</v-icon>
            <h3 class="card-title">Tiempo promedio de resolución por técnico</h3>
        </div>

        <div v-for="item in sortedData" :key="item.technician" class="mb-3">
            <div class="d-flex justify-space-between mb-1">
                <span class="tech">Técnico {{ item.technician }}</span>
                <span class="value">{{ item.average.toFixed(1) }} hrs</span>
            </div>

            <v-progress-linear
                :model-value="getPercent(item.average)"
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
    data: { technician: number; average: number }[];
}>();

const sortedData = computed(() =>
    [...props.data].sort((a, b) => a.average - b.average) // 🔥 menor = mejor
);

const max = computed(() =>
    Math.max(...props.data.map(i => i.average), 1)
);

const getPercent = (val: number) => (val / max.value) * 100;
</script>

<style scoped>
.card-title { font-weight: 600; }
.tech { color: #6b7280; font-size: 13px; }
.value { font-weight: 600; }
</style>