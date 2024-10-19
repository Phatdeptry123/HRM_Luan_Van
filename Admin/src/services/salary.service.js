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

  async getAllSalaries() {
    try {
      const response = await api.get('/salary')
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async createOrUpdateBasicSalary(data) {
    console.log('data', data)

    try {
      const response = await api.post('/salary/create-or-update-basic-salary', data)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async createMonthlySalary(data) {
    try {
      const response = await api.post('/monthly-salary', data)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getMonthlySalaryByMonthAndYear(month) {
    try {
      const response = await api.get('/monthly-salary/get-monthly-salary-by-month-and-year', {
        params: { month } // Gửi tham số month qua query string
      })
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new SalaryService()
