<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <!-- HEADER -->
        <div class="d-flex align-center mb-3">
            <v-icon color="amber" class="mr-2">mdi-trophy</v-icon>
            <h3 class="card-title">Ranking de Técnicos</h3>
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
    data: [string, number][];
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

const colors = [
    '#FBBF24', // 🥇 oro
    '#9CA3AF', // 🥈 plata
    '#F97316', // 🥉 bronce
    '#3B82F6',
    '#22C55E',
];

const renderChart = () => {
    if (!chartRef.value) return;

    if (chartInstance) chartInstance.destroy();

    // 🔥 asegurar orden descendente
    const sorted = [...props.data].sort((a, b) => b[1] - a[1]);

    const labels = sorted.map(i => i[0]);
    const values = sorted.map(i => i[1]);

    const backgroundColors = labels.map((_, i) =>
        colors[i] || '#6366F1'
    );

    chartInstance = new Chart(chartRef.value, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: backgroundColors,
                    borderRadius: 8,
                    barThickness: 18,
                },
            ],
        },
        options: {
            indexAxis: 'y',

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
                    callbacks: {
                        label: (ctx) => ` ${ctx.raw} tickets cerrados`,
                    },
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