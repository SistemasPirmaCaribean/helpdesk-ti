import axios from 'axios';

const config = {
    headers: {
        Authorization: `Bearer ${localStorage.getItem('api-token')}`,
        Accept: 'application/json',
    },
};

export default {
    async getAll() {
        const res = await axios.get('/api/tkt-tickets', config);
        return res.data;
    },

    async create(data: any) {
        return axios.post('/api/tkt-tickets', data, config);
    },

    async update(id: number, data: any) {
        return axios.put(`/api/tkt-tickets/${id}`, data, config);
    },

    async getCategories() {
        const res = await axios.get('/api/tkt-categories', config);
        return res.data;
    },

    async getPriorities() {
        const res = await axios.get('/api/tkt-priorities', config);
        return res.data;
    },

    async getCommentsByTicket(ticketId: number) {
        const res = await axios.get(`/api/tkt-ticket-comments/${ticketId}`, config);
        return res.data;
    },

    async addComment(idticket: number, comment: string) {
        return axios.post(`/api/tkt-ticket-comments`, { idticket, comment }, config);
    },

    async getTechnicians() {
        const res = await axios.get('/api/tkt-technicians', config);
        return res.data;
    },

    async getClaimsByTicket(idticket: number) {
        const res = await axios.get(`/api/tkt-claims/${idticket}`, config);
        return res.data;
    },

    async submitClaim(claimData: any) {
        return axios.post('/api/tkt-claims', claimData, config);
    },

    async approveClaim(idclaim: number) {
        return axios.post(`/api/tkt-claims/${idclaim}/approve`, {}, config);
    },

    async rejectClaim(idclaim: number) {
        return axios.post(`/api/tkt-claims/${idclaim}/reject`, {}, config);
    },

    async approveUnclaim(idclaim: number) {
        return axios.post(`/api/tkt-claims/${idclaim}/approve-unclaim`, {}, config);
    },

    async rejectUnclaim(idclaim: number) {
        return axios.post(`/api/tkt-claims/${idclaim}/reject-unclaim`, {}, config);
    },

    async statusProcess(idticket: number, idstatus: number) {
        return axios.post(`/api/tkt-tickets/${idticket}/status`, { idstatus }, config);
    }
};
