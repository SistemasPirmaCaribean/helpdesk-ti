<template>
    <v-card class="pa-4 elevation-3 rounded-xl text-center">
        <v-icon :color="color" size="32" class="mb-2"> mdi-alert-circle </v-icon>

        <h3 class="label">% de Tickets Vencidos</h3>

        <h1 class="value" :style="{ color }">{{ percentage.toFixed(1) }}%</h1>

        <small>del total de tickets</small>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{ overdue: number; total: number }>();

const percentage = computed(() => (props.total ? (props.overdue / props.total) * 100 : 0));

const color = computed(() => {
    if (percentage.value < 10) return '#22C55E';
    if (percentage.value < 30) return '#F59E0B';
    return '#EF4444';
});
</script>

<style scoped>
.label {
    color: #6b7280;
}
.value {
    font-size: 32px;
    font-weight: bold;
}
</style>
