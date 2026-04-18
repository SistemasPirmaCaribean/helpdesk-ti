import axios from "axios"

const config = {
    headers: {
        Authorization: `Bearer ${localStorage.getItem('api-token')}`,
        Accept: 'application/json'
    }
}

export default {

    async getAllTickets(){
        const res = await axios.get('/api/tkt-tickets',config)
        return res.data
    }

    

}