import api from './api.service'

class UserService {
  async getUsers() {
    try {
      const response = await api.get('/users')
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async updateUser(id, data) {
    try {
      const response = await api.put(`/user/update/${id}`, data)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new UserService()
