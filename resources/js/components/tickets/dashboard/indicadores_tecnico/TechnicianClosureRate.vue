<template>
    <v-card class="pa-4 elevation-3 rounded-xl">
        <div class="d-flex align-center mb-3">
            <v-icon color="green" class="mr-2">mdi-check-circle</v-icon>
            <h3 class="card-title">Tasa de Cierre</h3>
        </div>

        <div v-for="item in data" :key="item.technician" class="mb-4">
            <div class="d-flex justify-space-between mb-1">
                <span class="tech">Técnico {{ item.technician }}</span>
                <span class="value">{{ item.rate.toFixed(1) }}%</span>
            </div>

            <v-progress-linear :model-value="item.rate" height="8" :color="getColor(item.rate)" rounded />
        </div>
    </v-card>
</template>

<script setup lang="ts">
defineProps<{
    data: { technician: string; rate: number }[];
}>();

const getColor = (rate: number) => {
    if (rate >= 80) return 'green';
    if (rate >= 50) return 'orange';
    return 'red';
};
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
