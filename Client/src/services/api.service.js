import axios from 'axios'
import { useUserStore } from '@/stores/user'
import AuthService from '@/services/auth.service'
import router from '@/router/index'
import Swal from 'sweetalert2'
const api = axios.create({
  baseURL: 'http://localhost:8000/api/',
  headers: {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
})

// Add a request interceptor to attach the access_token to the Authorization header
api.interceptors.request.use(
  (config) => {
    const userStore = useUserStore()
    const token = userStore.token
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}` // Attach token to the Authorization header
    }
    return config
  },
  (error) => {
    return Promise.reject(error) // Return error if request fails
  }
)

// Add a response interceptor to handle token expiration
let isRefreshing = false // Flag to keep track of whether the token is being refreshed
let refreshTokenPromise = null // Promise to hold the token refresh request

api.interceptors.response.use(
  (response) => {
    return response
  },
  async (error) => {
    const userStore = useUserStore()
    const status = error.response?.status || 500
    const originalRequest = error.config

    if (status === 401 && originalRequest.url !== `auth/login`) {
      // Check if the status code is 401 and the request is not the login request
      const refreshToken = userStore.refreshToken

      // If the token is being refreshed, wait for the promise to resolve
      if (!isRefreshing) {
        isRefreshing = true
        refreshTokenPromise = AuthService.refreshToken(refreshToken) // Call the refreshToken method from the AuthService
          .then((response) => {
            const { access_token, refresh_token } = response.data

            // Update tokens in user store
            userStore.setToken(access_token)
            userStore.setRefreshToken(refresh_token)

            // Retry original request with new access token
            originalRequest.headers['Authorization'] = `Bearer ${access_token}`
            return api(originalRequest)
          })
          // If the refresh token request fails, handle the error
          .catch((refreshError) => {
            userStore.clearUser()
            router.push('/login')
            if (refreshError.response?.status !== 401) {
              return Promise.reject(refreshError)
              // Clear user session and redirect to login
            }
          })
          .finally(() => {
            isRefreshing = false
          })

        // Return promise to wait for the refreshTokenPromise to resolve
        return refreshTokenPromise
      }
    } else if (status === 403) {
      alert('You are forbidden to access this resource.')
    } else if (status === 411) {
      Swal.fire({
        title: 'Thông báo',
        text: 'Đã hết phiên làm việc, vui lòng đăng nhập lại.',
        icon: 'warning',
        confirmButtonText: 'OK'
      })
      userStore.clearUser()
      router.push('/login')
    }

    return Promise.reject(error)
  }
)

export default api
