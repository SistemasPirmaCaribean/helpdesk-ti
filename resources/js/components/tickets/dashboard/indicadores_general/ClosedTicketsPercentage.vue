<template>
    <v-card class="pa-4 text-center rounded-xl elevation-3">
        <v-icon :color="color" size="32" class="mb-2">
            mdi-check-circle
        </v-icon>

        <h3 class="label">% Cerrados</h3>

        <h1 class="value" :style="{ color }">
            {{ value.toFixed(1) }}%
        </h1>
    </v-card>
</template>

<script setup lang="ts">
const props = defineProps<{ value: number }>();

const color =
    props.value >= 80 ? '#22C55E' :
    props.value >= 50 ? '#F59E0B' :
    '#EF4444';
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