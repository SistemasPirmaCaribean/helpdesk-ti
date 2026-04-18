<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <div class="d-flex align-center mb-3">
            <v-icon color="red" class="mr-2">mdi-timer-sand</v-icon>
            <h3 class="card-title">Antigüedad de Tickets</h3>
        </div>

        <div class="chart-container">
            <canvas ref="chartRef"></canvas>
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps<{
    data: Record<string, number>;
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

// 🎨 Colores por antigüedad (muy importante UX)
const agingColors: Record<string, string> = {
    '0-24h': '#22C55E', // 🟢 bien
    '1-3d': '#F59E0B',  // 🟠 warning
    '3-7d': '#EF4444',  // 🔴 riesgo
    '+7d': '#7F1D1D',   // 🔥 crítico
};

const renderChart = () => {
    if (!chartRef.value) return;
    if (chartInstance) chartInstance.destroy();

    const labels = Object.keys(props.data);
    const values = Object.values(props.data);

    const colors = labels.map(l => agingColors[l] || '#9CA3AF');

    chartInstance = new Chart(chartRef.value, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: colors,
                    borderRadius: 8,
                    barThickness: 30,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    callbacks: {
                        label: (ctx) => ` ${ctx.raw} tickets`,
                    },
                },
            },

            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    grid: { color: '#e5e7eb' },
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
    font-weight: 600;
    color: #374151;
}
.chart-container {
    height: 300px;
}
</style>