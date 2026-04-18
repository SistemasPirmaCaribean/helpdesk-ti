<template>
    <v-dialog v-model="model" max-width="700">
        <v-card height="600" class="d-flex flex-column">
            <v-card-title class="d-flex align-center">
                <v-icon class="me-2">mdi-message</v-icon>

                Mensajes Ticket #{{ props.ticket?.reference_number }}

                <v-spacer></v-spacer>

                <v-btn icon="mdi-close" variant="text" @click="cerrar" />
            </v-card-title>

            <v-divider></v-divider>

            <!-- MENSAJES -->
            <v-card-text class="pa-4 flex-grow-1 overflow-y-auto" ref="chatContainer">
                <!-- MENSAJE CUANDO NO HAY MENSAJES -->
                <div v-if="chatMessages.length === 0" class="d-flex flex-column align-center text-grey justify-center" style="height: 100%">
                    <v-icon size="50" class="mb-2">mdi-message-outline</v-icon>
                    <div>No hay mensajes en este ticket</div>
                </div>

                <div v-else v-for="msg in chatMessages" :key="msg.id" class="d-flex mb-3" :class="msg.mine ? 'justify-end' : 'justify-start'">
                    <v-avatar v-if="!msg.mine" size="32" class="me-2" color="primary">
                        {{ msg.user.charAt(0) }}
                    </v-avatar>

                    <v-sheet
                        rounded="lg"
                        class="pa-3"
                        :max-width="350"
                        :color="msg.mine ? 'primary' : 'grey-lighten-3'"
                        :class="msg.mine ? 'text-white' : ''"
                    >
                        <div class="text-caption mb-1">
                            {{ msg.user }}
                        </div>

                        <div>
                            {{ msg.message }}
                        </div>

                        <div class="text-caption d-flex align-center mt-1 justify-end text-right">
                            {{ msg.time }}

                            <v-icon v-if="msg.mine" size="16" class="ms-1" :color="msg.seen ? 'blue' : 'grey'"> mdi-check-all </v-icon>
                        </div>
                    </v-sheet>

                    <v-avatar v-if="msg.mine" size="32" class="ms-2" color="success">
                        {{ msg.user.charAt(0) }}
                    </v-avatar>
                </div>
            </v-card-text>

            <v-divider></v-divider>

            <!-- INPUT -->
            <v-card-actions class="pa-3">
                <v-text-field
                    v-model="newMessage"
                    placeholder="Escribe un mensaje..."
                    variant="outlined"
                    density="comfortable"
                    hide-details
                    @keyup.enter="enviar"
                />

                <v-btn color="primary" class="ms-2" icon="mdi-send" @click="enviar" />
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import service from '@/components/tickets/ticket/services/TicketService';
import { computed, nextTick, ref, watch } from 'vue';

/* =========================
INTERFACES
========================= */

interface Ticket {
    id: number;
    reference_number: string | number;
    title: string;
    description: string;
}

interface Usuario {
    id: number;
    nombre: string;
}

interface Comment {
    id: number;
    idticket: number;
    iduser: number;
    comment: string;
    seen?: number;
    seen_at?: string;
    created_at?: string;
    user?: Usuario;
}

interface ChatMessage {
    id: number;
    user: string;
    message: string;
    time: string;
    mine: boolean;
    seen?: number;
}

/* =========================
PROPS
========================= */

const props = defineProps<{
    modelValue: boolean;
    ticket: Ticket | null;
    messages: Comment[];
}>();

const emit = defineEmits(['update:modelValue', 'reload']);

/* =========================
MODEL
========================= */

const model = ref(false);

watch(
    () => props.modelValue,
    (v) => (model.value = v),
);

watch(model, (v) => emit('update:modelValue', v));

/* =========================
USUARIO ACTUAL
========================= */

const currentUser = ref<Usuario | null>(null);

const userStorage = localStorage.getItem('user');

if (userStorage) {
    currentUser.value = JSON.parse(userStorage);
}

/* =========================
CHAT
========================= */

const chatContainer = ref();

const newMessage = ref('');

/* =========================
CONVERTIR COMMENTS A CHAT
========================= */

const chatMessages = computed<ChatMessage[]>(() => {
    return props.messages.map((c) => ({
        id: c.id,
        user: c.user?.nombre ?? 'Usuario',
        message: c.comment,
        time: formatDate(c.created_at),
        mine: c.iduser === currentUser.value?.id,
        seen: c.seen,
    }));
});

/* =========================
SCROLL
========================= */

const scrollBottom = async () => {
    await nextTick();

    if (chatContainer.value?.$el) {
        chatContainer.value.$el.scrollTop = chatContainer.value.$el.scrollHeight;
    }
};

watch(
    () => props.messages,
    () => scrollBottom(),
    { deep: true },
);

/* =========================
ENVIAR MENSAJE
========================= */

const enviar = async () => {
    if (!newMessage.value.trim() || !props.ticket) return;

    try {
        await service.addComment(props.ticket.id, newMessage.value);

        emit('reload');

        newMessage.value = '';

        scrollBottom();
    } catch (e) {
        console.error(e);
    }
};

/* =========================
CERRAR
========================= */

const cerrar = () => {
    model.value = false;
};

const formatDate = (dateString?: string) => {
    if (!dateString) return '';

    const date = new Date(dateString);

    return date.toLocaleString('es-MX', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>
