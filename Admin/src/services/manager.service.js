import api from './api.service'

class ManagerService {
  async getManagers() {
    try {
      const response = await api.get('/managers')
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
  async getSubordinates(id) {
    try {
      const response = await api.get(`/managers/${id}/subordinates`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
  async managersWithSubordinates() {
    try {
      const response = await api.get('/managers/managers-with-subordinates')
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
  async addSubordinate(data, id) {
    try {
      // Gửi payload dưới dạng đối tượng với trường 'user_id'
      const response = await api.post(`/managers/${id}/add-subordinate`, { user_id: data })
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new ManagerService()
