<template>
    <v-card class="pa-4 text-center rounded-xl elevation-3">
        <v-icon :color="color" size="32" class="mb-2">
            mdi-timer-sand
        </v-icon>

        <h3 class="label">Tickets Vencidos</h3>

        <h1 class="value" :style="{ color }">
            {{ value }}
        </h1>

        <small>fuera de SLA</small>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{ value: number }>();

const color = computed(() => {
    if (props.value === 0) return '#22C55E';
    if (props.value < 5) return '#F59E0B';
    return '#EF4444';
});
</script>

<style scoped>
.label { color: #6b7280; }
.value { font-size: 32px; font-weight: bold; }
</style>