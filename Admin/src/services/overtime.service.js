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

  async getMonthlyOvertimeHours() {
    try {
      const response = await api.get(`/overtimes/monthly-hours`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getUserOvertimeRanking() {
    try {
      const response = await api.get(`/overtimes/count-overtime-hours-in-month-for-all-users`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new OvertimeService()
