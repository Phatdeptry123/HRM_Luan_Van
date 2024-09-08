import api from './api.service'

const API_URL = 'auth/'

class AuthService {
  async register(name, email, password, confirmPassword) {
    const response = await api.post(API_URL + 'register', {
      name,
      email,
      password,
      c_password: confirmPassword
    })
    return response.data
  }

  async login(loginField, password) {
    const response = await api.post(API_URL + 'login', {
      [loginField.includes('@') ? 'email' : 'user_name']: loginField,
      password
    })
    return response.data
  }

  async logout() {
    await api.post(API_URL + 'logout')
  }

  async getProfile() {
    const response = await api.get(API_URL + 'profile')
    return response.data
  }

  // Refresh the access_token using the refresh_token
  async refreshToken(refreshToken) {
    const response = await api.post(API_URL + 'refresh', {
      refresh_token: refreshToken
    })
    return response
  }
}

export default new AuthService()
