<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <!-- HEADER -->
        <div class="d-flex align-center mb-3">
            <v-icon color="primary" class="mr-2">mdi-chart-line</v-icon>
            <h3 class="card-title">Tendencia de Tickets por Categoría</h3>
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
    data: Record<string, Record<number, number>>;
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

// 🎨 Paleta de colores moderna
const colorPalette = [
    '#6366F1', // indigo
    '#22C55E', // green
    '#F59E0B', // amber
    '#EF4444', // red
    '#3B82F6', // blue
    '#8B5CF6', // violet
    '#14B8A6', // teal
];

// Obtener meses ordenados
const getAllMonths = () => {
    const months = new Set<number>();

    Object.values(props.data).forEach(category => {
        Object.keys(category).forEach(month => {
            months.add(Number(month));
        });
    });

    return Array.from(months).sort((a, b) => a - b);
};

// Render chart
const renderChart = () => {
    if (!chartRef.value) return;

    if (chartInstance) {
        chartInstance.destroy();
    }

    const months = getAllMonths();

    const datasets = Object.entries(props.data).map(([categoryName, monthData], index) => {
        const color = colorPalette[index % colorPalette.length];

        return {
            label: categoryName,
            data: months.map(m => monthData[m] || 0),
            borderColor: color,
            backgroundColor: color + '33',
            tension: 0.4, // ✨ líneas suaves
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
        };
    });

    chartInstance = new Chart(chartRef.value, {
        type: 'line',
        data: {
            labels: months.map(m => `Mes ${m}`),
            datasets,
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                    },
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                    padding: 10,
                },
            },

            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e5e7eb',
                    },
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
    position: relative;
    height: 300px;
}
</style>