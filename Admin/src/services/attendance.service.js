// AttendanceService.js
import api from './api.service'

class AttendanceService {
  async getAttendanceByUserId(userId) {
    try {
      const response = await api.get(`/attendance/${userId}/get-attendance-by-user-id`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async countDaysCheckinInMonthForAllUsers() {
    try {
      const response = await api.get('/attendance/count-days-checkin-in-month-for-all-users')
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async countDaysCheckinLateOrCheckoutEarlyForAllUsers() {
    try {
      const response = await api.get(
        '/attendance/count-days-checkin-late-or-checkout-early-for-all-users'
      )
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new AttendanceService()
