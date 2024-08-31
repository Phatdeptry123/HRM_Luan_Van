<template>
  <!-- Logout Button -->
  <button @click="handleLogout" class="mr-4">
    <font-awesome-icon :icon="['fas', 'sign-out-alt']" />
  </button>
</template>

<script>
import AuthService from '@/services/auth.service'
import { useUserStore } from '@/stores/user'

export default {
  computed: {
    userStore() {
      return useUserStore()
    },
    user() {
      return this.userStore.user
    }
  },
  methods: {
    async handleLogout() {
      try {
        await AuthService.logout(this.userStore.token)
        this.userStore.logout()
        this.$router.push('/login')
      } catch (error) {
        console.error('Failed to logout:', error)
      }
    }
  },
}
</script>

<style>
</style>
