<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <!-- HEADER -->
        <div class="d-flex align-center mb-3">
            <v-icon color="blue" class="mr-2">mdi-domain</v-icon>
            <h3 class="card-title">Tickets por Departamento</h3>
        </div>

        <!-- CHART -->
        <div class="chart-container">
            <canvas ref="chartRef"></canvas>

            <!-- TOTAL CENTRO -->
            <div class="chart-center">
                <div class="total">{{ total }}</div>
                <div class="label">Tickets</div>
            </div>
        </div>
    </v-card>
</template>

<script setup lang="ts">
import { onMounted, ref, watch, computed } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps<{
    data: Record<string, number>;
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

// 🎨 Colores modernos
const colors = [
    '#3B82F6', // blue
    '#22C55E', // green
    '#F59E0B', // amber
    '#EF4444', // red
    '#8B5CF6', // purple
    '#14B8A6', // teal
];

// 🧠 total dinámico
const total = computed(() => {
    return Object.values(props.data).reduce((a, b) => a + b, 0);
});

const renderChart = () => {
    if (!chartRef.value) return;

    if (chartInstance) {
        chartInstance.destroy();
    }

    const labels = Object.keys(props.data);
    const values = Object.values(props.data);

    const backgroundColors = labels.map((_, i) => colors[i % colors.length]);

    chartInstance = new Chart(chartRef.value, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: backgroundColors,
                    borderWidth: 0,
                    hoverOffset: 8,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            cutout: '70%', // 🔥 donut más elegante

            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                    },
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                    padding: 10,
                    callbacks: {
                        label: (ctx) => {
                            const value = ctx.raw as number;
                            const percentage = ((value / total.value) * 100).toFixed(1);
                            return ` ${value} tickets (${percentage}%)`;
                        },
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

/* 🔥 centro del donut */
.chart-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.total {
    font-size: 22px;
    font-weight: bold;
    color: #111827;
}

.label {
    font-size: 12px;
    color: #6b7280;
}
</style>