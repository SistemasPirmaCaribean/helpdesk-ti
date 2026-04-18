<template>
    <v-card class="pa-4 text-center rounded-xl elevation-3">
        <v-icon color="blue" size="32" class="mb-2">
            mdi-ticket
        </v-icon>

        <h3 class="label">Total Tickets</h3>

        <h1 class="value">
            {{ value }}
        </h1>
    </v-card>
</template>

<script setup lang="ts">
defineProps<{ value: number }>();
</script>

<style scoped>
.label {
    color: #6b7280;
}
.value {
    font-size: 34px;
    font-weight: bold;
    color: #1d4ed8;
}
</style>