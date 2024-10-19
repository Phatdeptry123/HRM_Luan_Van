<template>
  <div class="min-h-screen flex items-center justify-center w-full bg-gray-100">
    <div class="bg-teal-50 p-8 rounded shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900 text-center">Register</h2>
      <form @submit.prevent="handleSubmit">
        <!-- Name -->
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
          <input
            type="text"
            id="name"
            v-model="name"
            class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Enter your name"
          />
          <div v-if="nameError" class="text-red-500 text-sm mt-1">{{ nameError }}</div>
        </div>
        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
          <input
            type="text"
            id="email"
            v-model="email"
            rules="email"
            class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Enter your email"
          />
          <div v-if="emailError" class="text-red-500 text-sm mt-1">{{ emailError }}</div>
        </div>

        <!-- Password -->
        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700 mb-2"
            >Password</label
          >
          <div class="relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              v-model="password"
              class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Enter your password"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
            >
              <font-awesome-icon :icon="showPassword ? 'eye-slash' : 'eye'" />
            </button>
          </div>
          <div v-if="passwordError" class="text-red-500 text-sm mt-1">{{ passwordError }}</div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
          <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2"
            >Confirm Password</label
          >
          <div class="relative">
            <input
              :type="showConfirmPassword ? 'text' : 'password'"
              id="confirmPassword"
              v-model="confirmPassword"
              class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Confirm your password"
            />
            <button
              type="button"
              @click="showConfirmPassword = !showConfirmPassword"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
            >
              <font-awesome-icon :icon="showConfirmPassword ? 'eye-slash' : 'eye'" />
            </button>
          </div>
          <div v-if="confirmPasswordError" class="text-red-500 text-sm mt-1">
            {{ confirmPasswordError }}
          </div>
        </div>

        <!-- Register Button -->
        <div class="flex items-center justify-between">
          <button
            type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
          >
            Register
          </button>

          <router-link
            to="/login"
            class="text-xs text-indigo-500 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Already have an account? Log in</router-link
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import AuthService from '@/services/auth.service'

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      confirmPassword: '',
      nameError: '',
      emailError: '',
      passwordError: '',
      confirmPasswordError: '',
      showPassword: false,
      showConfirmPassword: false,
      userName: '', // Add userName data property
      userNameError: '' // Add userName error handling
    }
  },
  methods: {
    async handleSubmit() {
      this.clearErrors()
      try {
        // Call AuthService to register user
        const response = await AuthService.register(
          this.name,
          this.email,
          this.password,
          this.confirmPassword
        )

        console.log('Registration successful!', response)
        this.$router.push('/login')
      } catch (error) {
        if (error.response && error.response.status === 422) {
          const errors = error.response.data
          if (errors.email) {
            this.emailError = errors.email[0]
          }
          if (errors.password) {
            this.passwordError = errors.password[0]
          }
          if (errors.name) {
            this.nameError = errors.name[0]
          }
          if (errors.user_name) {
            // Handle user_name error
            this.userNameError = errors.user_name[0]
          }
        } else {
          this.errorMessage = 'An error occurred. Please try again.'
        }
      }
    },
    clearErrors() {
      this.nameError = ''
      this.emailError = ''
      this.passwordError = ''
      this.confirmPasswordError = ''
      this.userNameError = '' // Clear userName error
    }
  }
}
</script>

<style scoped>
input[type='password']::-ms-reveal,
input[type='password']::-ms-clear {
  display: none;
}
</style>
