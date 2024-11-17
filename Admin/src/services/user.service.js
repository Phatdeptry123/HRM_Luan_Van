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
      const response = await api.post(`/users/update/${id}`, data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
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

  async updateManager(id, data) {
    try {
      const response = await api.put(`/users/${id}/update-manager`, data)
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

  async searchUsers(query) {
    try {
      const response = await api.get(`/users/search`, {
        params: { keyword: query }
      })
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async updateFace(id, data) {
    try {
      const response = await api.post(`/users/update-face-id/${id}`, data, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async countUsers() {
    try {
      const response = await api.get('/users/count-users')
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new UserService()
