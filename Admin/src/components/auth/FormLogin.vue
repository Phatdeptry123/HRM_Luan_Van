<template>
  <div class="min-h-screen flex items-center justify-center w-full bg-gray-100">
    <div class="bg-teal-50 shadow-md rounded-lg px-8 py-6 max-w-md">
      <h1 class="text-2xl font-bold text-center mb-4 text-gray-900">Hệ Thống HMR</h1>
      <ValidationForm @submit="handleSubmit">
        <!-- show login error -->
        <div v-if="loginError" class="mb-4 text-red-500 text-sm text-center">
          {{ loginError }}
        </div>

        <!-- Email or Username -->
        <div class="mb-4 w-80">
          <label for="emailOrUsername" class="block text-sm font-medium text-gray-700 mb-2"
            >Email</label
          >
          <Field
            v-model="emailOrUsername"
            name="emailOrUsername"
            type="text"
            id="emailOrUsername"
            rules="required"
            class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="nhập email của bạn"
          />
          <ErrorMessage name="emailOrUsername" class="mt-1 text-red-500 text-sm" />
        </div>

        <!-- Password -->
        <div class="mb-4 w-80 relative">
          <label for="password" class="block text-sm font-medium text-gray-700 mb-2"
            >Mật Khẩu</label
          >
          <div class="relative w-80">
            <Field
              v-model="password"
              name="password"
              :type="showPassword ? 'text' : 'password'"
              id="password"
              :rules="{ required: true }"
              class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="nhập mật khẩu của bạn"
            />
            <button
              type="button"
              @click="togglePasswordVisibility"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
            >
              <font-awesome-icon :icon="showPassword ? 'eye-slash' : 'eye'" />
            </button>
          </div>
          <ErrorMessage name="password" class="mt-1 text-red-500 text-sm w-80" />
        </div>
        <a
          href="#"
          class="text-xs text-gray-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >Forgot Password?</a
        >

        <!-- Remember me -->
        <div class="flex items-center justify-between mb-4 mt-2">
          <div class="flex items-center">
            <Field
              type="checkbox"
              name="remember"
              id="remember"
              class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:outline-none"
              :value="true"
            />
            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
          </div>
          <router-link
            to="/register"
            class="text-xs text-indigo-500 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >Create Account</router-link
          >
        </div>

        <!-- Login Button -->
        <button
          type="submit"
          class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Login
        </button>
      </ValidationForm>
    </div>
  </div>
</template>

<script>
import AuthService from '@/services/auth.service'
import { useUserStore } from '@/stores/user'

export default {
  data() {
    return {
      emailOrUsername: '',
      password: '',
      showPassword: false,
      loginError: ''
    }
  },
  computed: {
    userStore() {
      return useUserStore()
    }
  },
  methods: {
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword
    },
    async handleSubmit(values) {
      try {
        const response = await AuthService.login(values.emailOrUsername, values.password)
        const { access_token, refresh_token, user } = response.data
        console.log('response:', response)
        if (response.data.user.role !== 1) {
          this.loginError = 'You are not authorized to access this page.'
          return
        }
        this.userStore.setToken(access_token)
        this.userStore.setRefreshToken(refresh_token)
        this.userStore.setUser(user)
        console.log('Login success!')
        this.$router.push('/')
      } catch (error) {
        if (error.response && error.response.data && error.response.data.error) {
          this.loginError = error.response.data.error
        } else {
          this.loginError = 'An unexpected error occurred. Please try again.'
        }
        console.error('Login failed:', error)
      }
    }
  }
}
</script>

<style>
input[type='password']::-ms-reveal,
input[type='password']::-ms-clear {
  display: none;
}
</style>
