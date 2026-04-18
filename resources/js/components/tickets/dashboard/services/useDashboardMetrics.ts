import { computed, Ref } from 'vue';

export function useDashboardMetrics(tickets: Ref<any[]>) {

    const totalTickets = computed(() => tickets.value.length);

    const closedTickets = computed(() =>
        tickets.value.filter(t => t.date_closed !== null)
    );

    const openTickets = computed(() =>
        tickets.value.filter(t => t.date_closed === null)
    );

    const isClosed = (t: any) => t.date_closed !== null;

    const getResolutionTime = (t: any) => {
        if (!t.date_closed) return null;

        const open = new Date(t.date_opened).getTime();
        const close = new Date(t.date_closed).getTime();

        return (close - open) / (1000 * 60 * 60); // horas
    };

    return {
        totalTickets,
        closedTickets,
        openTickets,
        isClosed,
        getResolutionTime
    };
}