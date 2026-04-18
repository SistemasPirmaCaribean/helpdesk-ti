<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <div class="d-flex align-center mb-3">
            <v-icon color="deep-purple" class="mr-2">mdi-clock-outline</v-icon>
            <h3 class="card-title">Tiempo Promedio de Resolución</h3>
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
    data: { month: string; average: number }[];
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

const formatLabel = (m: string) => {
    const [year, month] = m.split('-');
    return `${month}/${year.slice(2)}`;
};

const renderChart = () => {
    if (!chartRef.value) return;
    if (chartInstance) chartInstance.destroy();

    const sorted = [...props.data].sort(
        (a, b) => new Date(a.month).getTime() - new Date(b.month).getTime()
    );

    chartInstance = new Chart(chartRef.value, {
        type: 'line',
        data: {
            labels: sorted.map(d => formatLabel(d.month)),
            datasets: [
                {
                    label: 'Horas promedio',
                    data: sorted.map(d => d.average),
                    borderColor: '#8B5CF6',
                    backgroundColor: '#8B5CF633',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
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
                        label: (ctx) => ` ${ctx.raw} hrs`,
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