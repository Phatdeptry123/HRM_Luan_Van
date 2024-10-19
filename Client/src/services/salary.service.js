import api from './api.service'

class SalaryService {
  async getSalaryByUserId(userId) {
    try {
      const response = await api.get(`/salary/${userId}/get-salary-by-user-id`)
      return response.data[0]
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new SalaryService()
