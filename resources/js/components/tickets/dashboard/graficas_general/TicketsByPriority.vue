<template>
    <v-card class="pa-4 rounded-xl elevation-3">
        <!-- HEADER -->
        <div class="d-flex align-center mb-3">
            <v-icon color="red" class="mr-2">mdi-alert</v-icon>
            <h3 class="card-title">Tickets por Prioridad</h3>
        </div>

        <!-- CHART -->
        <div class="chart-container">
            <canvas ref="chartRef"></canvas>

            <!-- TOTAL -->
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
    data: Record<number, number>;
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

// 🎨 Colores por prioridad (UX real)
const priorityColors: Record<number, string> = {
    1: '#EF4444', // crítica - rojo
    2: '#F59E0B', // alta - naranja
    3: '#3B82F6', // media - azul
    4: '#22C55E', // baja - verde
};

// 🧠 labels reales
const getPriorityLabel = (priority: number) => {
    switch (priority) {
        case 1: return 'Crítica';
        case 2: return 'Alta';
        case 3: return 'Media';
        case 4: return 'Baja';
        default: return `P${priority}`;
    }
};

const total = computed(() =>
    Object.values(props.data).reduce((a, b) => a + b, 0)
);

const renderChart = () => {
    if (!chartRef.value) return;

    if (chartInstance) {
        chartInstance.destroy();
    }

    const sorted = Object.entries(props.data)
        .sort((a, b) => Number(a[0]) - Number(b[0]));

    const labels = sorted.map(p => getPriorityLabel(Number(p[0])));
    const values = sorted.map(p => p[1]);
    const colors = sorted.map(p => priorityColors[Number(p[0])] || '#9CA3AF');

    chartInstance = new Chart(chartRef.value, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 8,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',

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
                    callbacks: {
                        label: (ctx) => {
                            const value = ctx.raw as number;
                            const percent = ((value / total.value) * 100).toFixed(1);
                            return ` ${value} tickets (${percent}%)`;
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