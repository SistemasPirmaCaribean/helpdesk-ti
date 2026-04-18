<template>
    <v-main class="dashboard-bg">
        <v-container fluid>
            <!-- HEADER -->
            <div class="d-flex justify-space-between align-center mb-6">
                <h1 class="text-h5 font-weight-bold">Dashboard</h1>
            </div>

            <!-- ===================== -->
            <!-- RESUMEN GENERAL -->
            <!-- ===================== -->
            <v-card class="pa-4 elevation-2 mb-6 rounded-xl">
                <h2 class="section-title">Resumen General</h2>

                <v-row>
                    <v-col cols="12" md="3">
                        <totalTicketsIndicator :value="totalTickets" />
                    </v-col>

                    <v-col cols="12" md="3">
                        <closedTicketsPercentageIndicator :value="ClosedTicketsPercentage" />
                    </v-col>

                    <v-col cols="12" md="3">
                        <openClosedTicketsIndicator :data="OpenClosedTickets" />
                    </v-col>

                    <v-col cols="12" md="3">
                        <averageResolutionTimeIndicator :value="AverageResolutionTime" />
                    </v-col>
                </v-row>
            </v-card>

            <!-- ===================== -->
            <!-- CATEGORÍAS -->
            <!-- ===================== -->
            <v-card class="pa-4 elevation-2 mb-6 rounded-xl">
                <h2 class="section-title">Categorías</h2>

                <v-row>
                    <v-col cols="12" md="4">
                        <averageTimeByCategory :data="AverageTimeByCategory" />
                    </v-col>

                    <v-col cols="12" md="4">
                        <criticalTicketsPercentageByCategory :data="CriticalTicketsPercentageByCategory" />
                    </v-col>

                    <v-col cols="12" md="4">
                        <ticketsByCategoryIndicator :data="TicketsByCategoryIndicator" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <ticketsByCategoryTrend :data="TicketsByCategoryTrend" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <topCategoriesByTickets :data="TopCategoriesByTickets" />
                    </v-col>
                </v-row>
            </v-card>

            <!-- ===================== -->
            <!-- TÉCNICOS -->
            <!-- ===================== -->
            <v-card class="pa-4 elevation-2 mb-6 rounded-xl">
                <h2 class="section-title">Técnicos</h2>

                <v-row>
                    <v-col cols="12" md="3">
                        <averageResolutionTimeByTechnician :data="AverageResolutionTimeByTechnician" />
                    </v-col>

                    <v-col cols="12" md="3">
                        <technicianClosureRate :data="TechnicianClosureRate" />
                    </v-col>

                    <v-col cols="12" md="3">
                        <ticketsAssignedByTechnician :data="TicketsAssignedByTechnician" />
                    </v-col>

                    <v-col cols="12" md="3">
                        <ticketsResolvedByTechnician :data="TicketsResolvedByTechnician" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <openClosedTicketsByTechnician :data="OpenClosedTicketsByTechnician" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <technicianRanking :data="TechnicianRanking" />
                    </v-col>
                </v-row>
            </v-card>

            <!-- ===================== -->
            <!-- SLA / TIEMPOS -->
            <!-- ===================== -->
            <v-card class="pa-4 elevation-2 mb-6 rounded-xl">
                <h2 class="section-title">SLA y Tiempos</h2>

                <v-row>
                    <v-col cols="12" md="4">
                        <overdueTicketsIndicator :value="OverdueTickets" />
                    </v-col>

                    <v-col cols="12" md="4">
                        <overduePercentageIndicator :overdue="OverdueTickets" :total="totalTickets" />
                    </v-col>

                    <v-col cols="12" md="4">
                        <averageResolutionTimeByMonth :data="AverageResolutionTimeByMonth" />
                    </v-col>

                    <v-col cols="12">
                        <ticketsAging :data="TicketsAging" />
                    </v-col>
                </v-row>
            </v-card>

            <!-- ===================== -->
            <!-- TENDENCIAS -->
            <!-- ===================== -->
            <v-card class="pa-4 elevation-2 rounded-xl">
                <h2 class="section-title">Tendencias</h2>

                <v-row>
                    <v-col cols="12" md="4">
                        <ticketsByDepartament :data="TicketsByDepartment" />
                    </v-col>

                    <v-col cols="12" md="4">
                        <ticketsByMonth :data="TicketsByMonth" />
                    </v-col>

                    <v-col cols="12" md="4">
                        <ticketsByPriority :data="TicketsByPriority" />
                    </v-col>

                    <v-col cols="12">
                        <ticketsOpenedVsClosedByMonth :data="TicketsOpenedVsClosedByMonth" />
                    </v-col>
                </v-row>
            </v-card>
        </v-container>
    </v-main>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue';

import dashboardService from '@/components/tickets/dashboard/services/DashboardServices';

import closedTicketsPercentageIndicator from '@/components/tickets/dashboard/indicadores_general/ClosedTicketsPercentage.vue';
import openClosedTicketsIndicator from '@/components/tickets/dashboard/indicadores_general/OpenClosedTickets.vue';
import totalTicketsIndicator from '@/components/tickets/dashboard/indicadores_general/TotalTickets.vue';

import averageTimeByCategory from '@/components/tickets/dashboard/indicadores_categoria/AverageTimeByCategory.vue';
import criticalTicketsPercentageByCategory from '@/components/tickets/dashboard/indicadores_categoria/CriticalTicketsPercentageByCategory.vue';
import ticketsByCategoryIndicator from '@/components/tickets/dashboard/indicadores_categoria/TicketsByCategoryIndicator.vue';

import averageResolutionTimeByTechnician from '@/components/tickets/dashboard/indicadores_tecnico/AverageResolutionTimeByTechnician.vue';
import technicianClosureRate from '@/components/tickets/dashboard/indicadores_tecnico/TechnicianClosureRate.vue';
import ticketsAssignedByTechnician from '@/components/tickets/dashboard/indicadores_tecnico/TicketsAssignedByTechnician.vue';
import ticketsResolvedByTechnician from '@/components/tickets/dashboard/indicadores_tecnico/TicketsResolvedByTechnician.vue';

import averageResolutionTimeIndicator from '@/components/tickets/dashboard/indicadores_tiempo/AverageResolutionTime.vue';
import overduePercentageIndicator from '@/components/tickets/dashboard/indicadores_tiempo/OverduePercentage.vue';
import overdueTicketsIndicator from '@/components/tickets/dashboard/indicadores_tiempo/OverdueTickets.vue';

//////////////////////////////////////
// Componentes Graficas
//////////////////////////////////////

import ticketsByDepartament from './graficas_general/TicketsByDepartament.vue';
import ticketsByMonth from './graficas_general/TicketsByMonth.vue';
import ticketsByPriority from './graficas_general/TicketsByPriority.vue';

import ticketsByCategoryTrend from './graficas_categoria/TicketsByCategoryTrend.vue';
import topCategoriesByTickets from './graficas_categoria/TopCategoriesByTickets.vue';

import openClosedTicketsByTechnician from './graficas_tecnico/OpenClosedTicketsByTechnician.vue';
import technicianRanking from './graficas_tecnico/TechnicianRanking.vue';

import averageResolutionTimeByMonth from './graficas_tiempo/AverageResolutionTimeByMonth.vue';
import ticketsAging from './graficas_tiempo/TicketsAging.vue';
import ticketsOpenedVsClosedByMonth from './graficas_tiempo/TicketsOpenedVsClosedByMonth.vue';

interface Status {
    id: number;
    name: string;
    color: string;
    active: number;
}

interface Category {
    id: number;
    name: string;
    active: number;
}

interface Priority {
    id: number;
    name: string;
    sla_hours: number;
    color: string | null;
    active: number;
}

interface User {
    id: number;
    nombre: string;
    activo: number;
    departamento_usuario?: DepartamentUser;
}

interface Departament {
    id: number;
    name: string;
    active: number;
}

interface DepartamentUser {
    id: number;
    iddepartament: number;
    iduser: number;
    active: number;
    departament?: Departament;
    user?: User;
}

interface Technician {
    id: number;
    nombre: string;
    activo: number;
}

interface Ticket {
    id: number;
    idstatus: number;
    idcategory: number;
    idpriority: number;
    iduser: number;
    idtechnician: number;
    reference_number: string;
    title: string;
    description: string;
    date_opened: string;
    date_closed: string | null;
    active: number;
    status?: Status;
    category?: Category;
    priority?: Priority;
    user?: User;
    technician?: Technician;
    departamento_usuario?: DepartamentUser;
}

const tickets = ref<Ticket[]>([]);

const fetchData = async () => {
    try {
        const data = await dashboardService.getAllTickets();
        tickets.value = data;
    } catch (e) {
        console.error(e);
    }
};

// TOTAL DE TICKETS
const totalTickets = computed(() => tickets.value.length);

// CLOSED TICKETS
const ClosedTicketsPercentage = computed(() => {
    if (!tickets.value.length) return 0;

    const closed = tickets.value.filter((t) => t.date_closed).length;
    return (closed / tickets.value.length) * 100;
});

// Open Closed Tickets
const OpenClosedTickets = computed(() => ({
    open: tickets.value.filter((t) => !t.date_closed).length,
    closed: tickets.value.filter((t) => t.date_closed).length,
}));

// AverageTimeByCategory
const AverageTimeByCategory = computed(() => {
    const map: Record<string, { total: number; count: number }> = {};

    tickets.value.forEach((t) => {
        if (!t.date_closed) return;

        const time = (new Date(t.date_closed).getTime() - new Date(t.date_opened).getTime()) / 3600000;

        if (!map[t.category?.name ? t.category?.name : 'unknown']) {
            map[t.category?.name || 'unknown'] = { total: 0, count: 0 };
        }

        map[t.category?.name || 'unknown'].total += time;
        map[t.category?.name || 'unknown'].count++;
    });

    return Object.entries(map).map(([category, data]) => ({
        category: category,
        average: data.total / data.count,
    }));
});

// CriticalTicketsPercentageByCategory
const CriticalTicketsPercentageByCategory = computed(() => {
    const map: Record<string, { total: number; critical: number }> = {};

    tickets.value.forEach((t) => {
        const categoryName = t.category?.name || 'unknown';
        if (!map[categoryName]) {
            map[categoryName] = { total: 0, critical: 0 };
        }

        map[categoryName].total++;

        if (t.idpriority === 4) {
            map[categoryName].critical++;
        }
    });

    return Object.entries(map).map(([category, data]) => ({
        category: String(category),
        average: (data.critical / data.total) * 100,
    }));
});

// TicketsByCategoryIndicator

const TicketsByCategoryIndicator = computed(() => {
    const map: Record<number, number> = {};

    tickets.value.forEach((t) => {
        if (t.category?.name) {
            map[t.idcategory] = (map[t.idcategory] || 0) + 1;
        }
    });

    return Object.entries(map).map(([category, total]) => ({
        category: tickets.value.find((t) => t.idcategory === Number(category))?.category?.name || String(category),
        total: Number(total),
    }));
});

//  AverageResolutionTimeByTechnician
const AverageResolutionTimeByTechnician = computed(() => {
    const map: Record<string, { total: number; count: number }> = {};

    tickets.value.forEach((t) => {
        if (!t.date_closed) return;

        const time = (new Date(t.date_closed).getTime() - new Date(t.date_opened).getTime()) / 3600000;
        const techName = t.technician?.nombre || 'unknown';

        if (!map[techName]) {
            map[techName] = { total: 0, count: 0 };
        }

        map[techName].total += time;
        map[techName].count++;
    });

    return Object.entries(map).map(([tech, data]: any) => ({
        technician: tech,
        average: data.total / data.count,
    }));
});

// TechnicianClosureRate
const TechnicianClosureRate = computed(() => {
    const map: Record<string, { total: number; closed: number }> = {};

    tickets.value.forEach((t) => {
        const techName = t.technician?.nombre || 'unknown';
        if (!map[techName]) {
            map[techName] = { total: 0, closed: 0 };
        }

        map[techName].total++;

        if (t.date_closed) {
            map[techName].closed++;
        }
    });

    return Object.entries(map).map(([tech, data]: any) => ({
        technician: tech,
        rate: (data.closed / data.total) * 100,
    }));
});

// TicketsAssignedByTechnician
const TicketsAssignedByTechnician = computed(() => {
    const map: Record<string, number> = {};

    tickets.value.forEach((t) => {
        const techName = t.technician?.nombre || 'unknown';
        map[techName] = (map[techName] || 0) + 1;
    });

    return Object.entries(map).map(([tech, total]) => ({
        technician: tech,
        total,
    }));
});

// TicketsResolvedByTechnician
const TicketsResolvedByTechnician = computed(() => {
    const map: Record<string, number> = {};

    tickets.value.forEach((t) => {
        if (!t.date_closed) return;

        const techName = t.technician?.nombre || 'unknown';
        map[techName] = (map[techName] || 0) + 1;
    });

    return Object.entries(map).map(([tech, total]) => ({
        technician: tech,
        total,
    }));
});

// GRAFICA

// TicketsByDepartment
const TicketsByDepartment = computed(() => {
    const map: Record<string, number> = {};
    console.log('Calculando tickets por departamento...');
    console.log(`Total tickets: ${tickets.value.length}`);
    console.log('Tickets:', tickets.value);
    tickets.value.forEach((t) => {
        const dept = t.user?.departamento_usuario?.departament?.name || 'Sin Departamento';
        map[dept] = (map[dept] || 0) + 1;
    });

    return map;
});

// TicketsByMonth
const TicketsByMonth = computed(() => {
    const map: Record<string, number> = {};

    tickets.value.forEach((t) => {
        const date = new Date(t.date_opened);
        const key = `${date.getFullYear()}-${date.getMonth() + 1}`;

        map[key] = (map[key] || 0) + 1;
    });

    return map;
});

// TicketsByPriority
const TicketsByPriority = computed(() => {
    const map: Record<number, number> = {};

    tickets.value.forEach((t) => {
        map[t.idpriority] = (map[t.idpriority] || 0) + 1;
    });

    return map;
});

// TicketsByCategoryTrend
const TicketsByCategoryTrend = computed(() => {
    const map: Record<string, Record<number, number>> = {};

    tickets.value.forEach((t) => {
        const month = new Date(t.date_opened).getMonth() + 1;
        const categoryName = t.category?.name || 'unknown';

        if (!map[categoryName]) map[categoryName] = {};
        map[categoryName][month] = (map[categoryName][month] || 0) + 1;
    });

    return map;
});

// TopCategoriesByTickets
const TopCategoriesByTickets = computed(() => {
    const map: Record<string, number> = {};

    tickets.value.forEach((t) => {
        const categoryName = t.category?.name || 'unknown';
        map[categoryName] = (map[categoryName] || 0) + 1;
    });

    return Object.entries(map)
        .sort((a: any, b: any) => b[1] - a[1])
        .slice(0, 5);
});

// OpenClosedTicketsByTechnician
const OpenClosedTicketsByTechnician = computed(() => {
    const map: Record<string, { open: number; closed: number }> = {};

    tickets.value.forEach((t) => {
        const techName = t.technician?.nombre || 'unknown';
        if (!map[techName]) {
            map[techName] = { open: 0, closed: 0 };
        }

        if (t.date_closed) {
            map[techName].closed++;
        } else {
            map[techName].open++;
        }
    });

    return map;
});

//TechnicianRanking
const TechnicianRanking = computed(() => {
    const map: Record<string, number> = {};

    tickets.value.forEach((t) => {
        if (!t.date_closed) return;

        const techName = t.technician?.nombre || 'unknown';
        map[techName] = (map[techName] || 0) + 1;
    });

    return Object.entries(map).sort((a: any, b: any) => b[1] - a[1]);
});

const AverageResolutionTime = computed(() => {
    const closed = tickets.value.filter((t) => t.date_closed);

    if (!closed.length) return 0;

    const total = closed.reduce((acc, t) => {
        return acc + (new Date(t.date_closed!).getTime() - new Date(t.date_opened).getTime());
    }, 0);

    return total / closed.length / 3600000; // horas
});

const OverdueTickets = computed(() => {
    const now = Date.now();
    const limitHours = 48; // ejemplo SLA

    return tickets.value.filter((t) => {
        if (t.date_closed) return false;

        const hours = (now - new Date(t.date_opened).getTime()) / 3600000;
        return hours > (t.priority?.sla_hours || limitHours);
    }).length;
});

const AverageResolutionTimeByMonth = computed(() => {
    const map: Record<string, { total: number; count: number }> = {};

    tickets.value.forEach((t) => {
        if (!t.date_closed) return;

        const date = new Date(t.date_closed);
        const key = `${date.getFullYear()}-${date.getMonth() + 1}`;

        const time = (new Date(t.date_closed).getTime() - new Date(t.date_opened).getTime()) / 3600000;

        if (!map[key]) {
            map[key] = { total: 0, count: 0 };
        }

        map[key].total += time;
        map[key].count++;
    });

    return Object.entries(map).map(([month, data]) => ({
        month,
        average: data.total / data.count,
    }));
});

const TicketsOpenedVsClosedByMonth = computed(() => {
    const map: Record<string, { opened: number; closed: number }> = {};

    tickets.value.forEach((t) => {
        // abiertos
        const openDate = new Date(t.date_opened);
        const openKey = `${openDate.getFullYear()}-${openDate.getMonth() + 1}`;

        if (!map[openKey]) map[openKey] = { opened: 0, closed: 0 };
        map[openKey].opened++;

        // cerrados
        if (t.date_closed) {
            const closeDate = new Date(t.date_closed);
            const closeKey = `${closeDate.getFullYear()}-${closeDate.getMonth() + 1}`;

            if (!map[closeKey]) map[closeKey] = { opened: 0, closed: 0 };
            map[closeKey].closed++;
        }
    });

    return Object.entries(map).map(([month, data]) => ({
        month,
        opened: data.opened,
        closed: data.closed,
    }));
});

const TicketsAging = computed(() => {
    const now = Date.now();

    const ranges = {
        '0-24h': 0,
        '1-3d': 0,
        '3-7d': 0,
        '+7d': 0,
    };

    tickets.value.forEach((t) => {
        if (!t.date_closed) return;

        const hours = (now - new Date(t.date_opened).getTime()) / 3600000;
        if (hours <= 24) ranges['0-24h']++;
        else if (hours <= 72) ranges['1-3d']++;
        else if (hours <= 168) ranges['3-7d']++;
        else ranges['+7d']++;
    });
    return ranges;
});

onMounted(() => {
    fetchData();
});
</script>

<style scoped>
.dashboard-bg {
    background: #f5f7fb;
    min-height: 100vh;
}

.section-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 16px;
    color: #374151;
}
</style>
