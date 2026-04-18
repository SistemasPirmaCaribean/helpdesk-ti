<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: Number,
        required: true,
    },
});

const titles: Record<number, string> = {
    403: 'Acceso denegado',
    404: 'Página no encontrada',
    500: 'Error del servidor',
    503: 'Servicio no disponible',
};

const descriptions: Record<number, string> = {
    403: 'No tienes permisos para acceder a esta sección del sistema.',
    404: 'La página que buscas no existe dentro del sistema.',
    500: 'Ocurrió un error interno en el servidor.',
    503: 'El sistema no está disponible temporalmente.',
};

const title = computed(() => titles[props.status] ?? 'Error');
const description = computed(() => descriptions[props.status] ?? 'Ocurrió un error inesperado.');

function goBack() {
    window.history.back();
}

function goHome() {
    window.location.href = '/dashboard';
}
</script>

<template>
    <div class="error-wrapper">

        <div class="error-background">
            {{ status }}
        </div>

        <div class="error-content">

            <div class="company">
                Sistema Corporativo <strong>PIRMA</strong>
            </div>

            <h1 class="error-title">
                {{ title }}
            </h1>

            <p class="error-description">
                {{ description }}
            </p>

            <div class="buttons">
                <button class="btn-secondary" @click="goBack">
                    ← Volver
                </button>

                <button class="btn-primary" @click="goHome">
                    Ir al inicio
                </button>
            </div>

        </div>
    </div>
</template>

<style scoped>

.error-wrapper {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 30px;
    overflow: hidden;
    font-family: system-ui, -apple-system, sans-serif;
}

/* número gigante */

.error-background {
    position: absolute;
    font-weight: 900;
    font-size: clamp(180px, 35vw, 420px);
    opacity: 0.05;
    user-select: none;
}

/* contenido */

.error-content {
    max-width: 420px;
    z-index: 1;
}

/* empresa */

.company {
    font-size: 13px;
    letter-spacing: 1px;
    opacity: 0.7;
    margin-bottom: 20px;
}

/* títulos */

.error-title {
    font-size: clamp(24px, 3vw, 30px);
    font-weight: 600;
    margin-bottom: 12px;
}

.error-description {
    font-size: clamp(14px, 2vw, 16px);
    opacity: 0.75;
    margin-bottom: 28px;
}

/* botones */

.buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

button {
    font-size: 14px;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

/* botón principal */

.btn-primary {
    background: #027368;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #015a52;
}

/* botón secundario */

.btn-secondary {
    background: transparent;
    border: 1px solid rgba(0,0,0,0.15);
}

.btn-secondary:hover {
    background: rgba(0,0,0,0.05);
}

</style>