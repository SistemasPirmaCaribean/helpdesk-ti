<template>
    <v-card class="pa-4 text-center rounded-xl elevation-3">
        <v-icon color="deep-purple" size="32" class="mb-2">
            mdi-clock-outline
        </v-icon>

        <h3 class="label">Tiempo Promedio de Resolución</h3>

        <h1 class="value">{{ formattedTime }}</h1>

        <small class="sub">Horas</small>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{ value: number }>();

const formattedTime = computed(() => {
    if (!props.value) return '0h';
    if (props.value < 1) return `${(props.value * 60).toFixed(0)} min`;
    return `${props.value.toFixed(1)} h`;
});
</script>

<style scoped>
.label { color: #6b7280; }
.value { font-size: 32px; font-weight: bold; color: #7c3aed; }
.sub { color: #9ca3af; }
</style>