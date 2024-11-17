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
  async totalOvertimeHoursInMonthForAllUsers(month, year) {
    try {
      const response = await api.get(`/overtimes/total-overtime-hours-in-month-for-all-users`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new OvertimeService()
