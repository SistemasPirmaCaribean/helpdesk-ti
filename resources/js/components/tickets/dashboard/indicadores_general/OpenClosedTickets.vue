<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <h3 class="card-title mb-4">Abiertos vs Cerrados</h3>

        <div class="mb-2">
            <div class="d-flex justify-space-between">
                <span>Abiertos</span>
                <strong>{{ data.open }}</strong>
            </div>
            <v-progress-linear
                :model-value="openPercent"
                color="orange"
                height="8"
                rounded
            />
        </div>

        <div>
            <div class="d-flex justify-space-between">
                <span>Cerrados</span>
                <strong>{{ data.closed }}</strong>
            </div>
            <v-progress-linear
                :model-value="closedPercent"
                color="green"
                height="8"
                rounded
            />
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    data: { open: number; closed: number };
}>();

const total = computed(() => props.data.open + props.data.closed || 1);

const openPercent = computed(() => (props.data.open / total.value) * 100);
const closedPercent = computed(() => (props.data.closed / total.value) * 100);
</script>

<style scoped>
.card-title {
    font-weight: 600;
}
</style>