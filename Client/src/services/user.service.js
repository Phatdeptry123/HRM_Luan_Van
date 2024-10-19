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
      const response = await api.put(`/users/update/${id}`, data)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async addUser(data) {
    try {
      const response = await api.post('/users/store', data)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async deleteUser(id) {
    try {
      const response = await api.delete(`/users/delete/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getUser(id) {
    try {
      const response = await api.get(`/users/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getManagerName(id) {
    try {
      const response = await api.get(`/users/get-managerName/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getUserName(id) {
    try {
      const response = await api.get(`/users/get-userName/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getCheckinHistory(id) {
    try {
      const response = await api.get(`attendance/${id}/get-attendance-by-user-id`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new UserService()
