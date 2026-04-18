import axios from "axios"

const config = {
    headers: {
        Authorization: `Bearer ${localStorage.getItem('api-token')}`,
        Accept: 'application/json'
    }
}

export default {

    async getAll(){
        const res = await axios.get('/api/tkt-departaments',config)
        return res.data
    },

    async create(data:any){
        return axios.post('/api/tkt-departaments',data,config)
    },

    async update(id:number,data:any){
        return axios.put(`/api/tkt-departaments/${id}`,data,config)
    }

}