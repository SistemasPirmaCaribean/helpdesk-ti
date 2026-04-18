<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <!-- HEADER -->
        <div class="d-flex align-center mb-3">
            <v-icon color="indigo" class="mr-2">mdi-chart-timeline-variant</v-icon>
            <h3 class="card-title">Tickets por Mes</h3>
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
    data: Record<string, number>;
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

// 📅 Formato más limpio
const formatLabels = (labels: string[]) => {
    return labels.map(l => {
        const [year, month] = l.split('-');
        return `${month}/${year.slice(2)}`; // 01/26
    });
};

const renderChart = () => {
    if (!chartRef.value) return;

    if (chartInstance) {
        chartInstance.destroy();
    }

    // 🔥 ordenar por fecha (clave)
    const sorted = Object.entries(props.data)
        .sort((a, b) => new Date(a[0]).getTime() - new Date(b[0]).getTime());

    const labels = formatLabels(sorted.map(i => i[0]));
    const values = sorted.map(i => i[1]);

    chartInstance = new Chart(chartRef.value, {
        type: 'line', // 🔥 mejor que bar
        data: {
            labels,
            datasets: [
                {
                    label: 'Tickets',
                    data: values,
                    borderColor: '#6366F1',
                    backgroundColor: '#6366F133',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                },
            },

            scales: {
                x: {
                    grid: { display: false },
                },
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
    font-size: 16px;
    font-weight: 600;
    color: #374151;
}

.chart-container {
    height: 300px;
}
</style>