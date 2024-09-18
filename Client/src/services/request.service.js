import api from './api.service'

class RequestService {
  async getRequests() {
    try {
      const response = await api.get('/requests')
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async updateRequest(id, data) {
    try {
      const response = await api.put(`/requests/update/${id}`, data)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async addRequest(data) {
    try {
      const response = await api.post('/requests', data)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getRequestsForUser(id) {
    try {
      const response = await api.get(`/requests/user/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getRequestsForManager(id) {
    try {
      const response = await api.get(`/requests/manager/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async approveRequest(id) {
    try {
      const response = await api.put(`/requests/approve/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async rejectRequest(id) {
    try {
      const response = await api.put(`/requests/reject/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new RequestService()
