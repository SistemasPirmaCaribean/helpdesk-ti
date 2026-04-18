<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <!-- HEADER -->
        <div class="d-flex align-center mb-3">
            <v-icon color="indigo" class="mr-2">mdi-account-group</v-icon>
            <h3 class="card-title">Tickets Abiertos vs Cerrados por Técnico</h3>
        </div>

        <!-- CHART -->
        <div class="chart-container">
            <canvas ref="chartRef"></canvas>
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps<{
    data: Record<string, { open: number; closed: number }>;
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

const renderChart = () => {
    if (!chartRef.value) return;

    if (chartInstance) chartInstance.destroy();

    // 🔥 ordenar por total (mejor lectura)
    const sorted = Object.entries(props.data)
        .sort((a, b) =>
            (b[1].closed + b[1].open) - (a[1].closed + a[1].open)
        );

    const labels = sorted.map(i => i[0]);
    const openData = sorted.map(i => i[1].open);
    const closedData = sorted.map(i => i[1].closed);

    chartInstance = new Chart(chartRef.value, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Abiertos',
                    data: openData,
                    backgroundColor: '#F59E0B', // 🟠 warning
                    borderRadius: 6,
                    barThickness: 16,
                },
                {
                    label: 'Cerrados',
                    data: closedData,
                    backgroundColor: '#22C55E', // 🟢 success
                    borderRadius: 6,
                    barThickness: 16,
                },
            ],
        },
        options: {
            indexAxis: 'y', // 🔥 horizontal

            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                },
            },

            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: '#e5e7eb' },
                },
                y: {
                    grid: { display: false },
                },
            },
        },
    });
};

onMounted(renderChart);
watch(() => props.data, renderChart, { deep: true });
</script>

<style scoped>
.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
}

.chart-container {
    height: 320px;
}
</style>