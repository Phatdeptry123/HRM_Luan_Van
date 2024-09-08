import { defineStore } from 'pinia'
import AuthService from '@/services/auth.service'
export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    token: localStorage.getItem('access_token') || '',
    refreshToken: localStorage.getItem('refresh_token') || ''
  }),
  actions: {
    setUser(user) {
      this.user = user
      localStorage.setItem('user', JSON.stringify(user))
    },
    setToken(token) {
      this.token = token
      localStorage.setItem('access_token', token)
    },
    // Set the refresh token in the store and localStorage
    setRefreshToken(refreshToken) {
      this.refreshToken = refreshToken
      localStorage.setItem('refresh_token', refreshToken)
    },
    clearUser() {
      this.user = null
      this.token = ''
      this.refreshToken = ''
      localStorage.removeItem('user')
      localStorage.removeItem('access_token')
      localStorage.removeItem('refresh_token')
    },
    loadUserFromLocalStorage() {
      const user = localStorage.getItem('user')
      try {
        if (user) {
          this.user = JSON.parse(user)
        }
      } catch (error) {
        console.error('Failed to parse user from localStorage:', error)
        localStorage.removeItem('user')
      }
    },
    async logout() {
      try {
        await AuthService.logout()
        this.clearUser()
      } catch (error) {
        console.error('Failed to logout:', error)
      }
    }
  }
})
