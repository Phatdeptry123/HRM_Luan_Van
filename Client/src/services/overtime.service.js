import api from './api.service'

class OvertimeService {
  async getovertimes() {
    try {
      const response = await api.get('/overtimes')
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async updateOvertime(id, data) {
    try {
      const response = await api.put(`/overtimes/update/${id}`, data)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async addOvertime(data) {
    try {
      const response = await api.post('/overtimes', data)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getovertimesForUser(id) {
    try {
      const response = await api.get(`/overtimes/user/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getovertimesForManager(id) {
    try {
      const response = await api.get(`/overtimes/manager/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async approveOvertime(id) {
    try {
      const response = await api.put(`/overtimes/approve/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async rejectOvertime(id) {
    try {
      const response = await api.put(`/overtimes/reject/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getOvertimeListByUserId(userId) {
    try {
      const response = await api.get(`/overtimes/user/${userId}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new OvertimeService()
