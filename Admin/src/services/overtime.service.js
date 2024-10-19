// OvertimeService.js
import api from './api.service'

class OvertimeService {
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
