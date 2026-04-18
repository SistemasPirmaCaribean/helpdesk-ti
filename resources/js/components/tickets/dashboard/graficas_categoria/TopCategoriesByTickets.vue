<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <!-- HEADER -->
        <div class="d-flex align-center mb-3">
            <v-icon color="deep-purple" class="mr-2">mdi-trophy</v-icon>
            <h3 class="card-title">Top Categorías por Tickets</h3>
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
    data: [string, number][]; // [categoryName, total]
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

// 🎨 Paleta moderna
const colors = [
    '#6366F1',
    '#22C55E',
    '#F59E0B',
    '#EF4444',
    '#3B82F6',
];

const renderChart = () => {
    if (!chartRef.value) return;

    if (chartInstance) {
        chartInstance.destroy();
    }

    const labels = props.data.map(item => item[0]); // 👈 ya nombre real
    const values = props.data.map(item => item[1]);

    const backgroundColors = labels.map((_, i) => colors[i % colors.length]);

    chartInstance = new Chart(chartRef.value, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Tickets',
                    data: values,
                    backgroundColor: backgroundColors,
                    borderRadius: 8, // ✨ barras redondeadas
                    barThickness: 18,
                },
            ],
        },
        options: {
            indexAxis: 'y', // horizontal

            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false, // 👈 más limpio
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                    padding: 10,
                    callbacks: {
                        label: (ctx) => ` ${ctx.raw} tickets`,
                    },
                },
            },

            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: '#e5e7eb',
                    },
                },
                y: {
                    grid: {
                        display: false,
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