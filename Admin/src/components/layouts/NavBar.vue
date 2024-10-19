<template>
  <header
    class="header sticky top-0 bg-teal-100 shadow-md flex items-center px-8 py-2 w-full"
    style="z-index: 1"
  >
    <div class="flex items-center w-6/12 md:w-3/12">
      <!-- mobile menu button -->
      <div class="block md:hidden mr-2">
        <button @click="toggleMobileMenu">
          <font-awesome-icon :icon="['fas', 'bars']" />
        </button>
      </div>
      <!-- logo -->
      <h1>
        <router-link to="/">
          <img
            src="@/assets/logo.png"
            alt="Logo"
            class="h-10 md:h-20 w-auto hover:text-green-500 duration-200"
          />
        </router-link>
      </h1>
    </div>

    <!-- navigation -->
    <nav class="nav font-semibold text-lg hidden md:block">
      <ul class="flex items-center">
        <li
          class="p-4 border-b-2 border-green-500 border-opacity-0 hover:border-opacity-100 hover:text-green-500 duration-200 cursor-pointer"
        >
          <router-link to="/">Trang Chủ</router-link>
        </li>
        <li
          class="p-4 border-b-2 border-green-500 border-opacity-0 hover:border-opacity-100 hover:text-green-500 duration-200 cursor-pointer"
        >
          <router-link to="/payslips">Tạo phiếu lương tháng</router-link>
        </li>
      </ul>
    </nav>

    <!-- mobile menu -->
    <nav
      v-if="isMobileMenuOpen"
      class="absolute top-16 left-0 w-full bg-teal-50 shadow-md md:hidden"
    >
      <ul class="flex flex-col items-center">
        <li class="p-4 w-full text-center border-b">
          <router-link to="/" @click="toggleMobileMenu">Home</router-link>
        </li>
      </ul>
    </nav>

    <!-- buttons -->
    <div class="w-6/12 md:w-6/12 flex relative justify-end items-center ml-auto">
      <!-- Logout Button -->
      <LogoutButton v-if="isLoggedIn" class="mr-4" />
      <!-- User Dropdown -->
      <UserDropdown v-if="isLoggedIn && user" :user="user" />
      <router-link v-if="!isLoggedIn" to="/login" class="mr-4">
        <font-awesome-icon :icon="['fas', 'user']" />
      </router-link>
    </div>
  </header>
</template>

<script>
import { useUserStore } from '@/stores/user'
import LogoutButton from '@/components/auth/LogoutButton.vue'
import { ref, onMounted } from 'vue'
import { watch } from 'vue'
import UserDropdown from '@/components/layouts/UserDropdown.vue'

export default {
  components: {
    LogoutButton,
    UserDropdown
  },
  setup() {
    const userStore = useUserStore()
    const isLoggedIn = ref(!!userStore.token)
    const user = ref(userStore.user)
    const isMobileMenuOpen = ref(false)
    const showSearchForm = ref(false)
    onMounted(() => {
      userStore.loadUserFromLocalStorage()
    })

    watch(userStore, () => {
      isLoggedIn.value = !!userStore.token
      user.value = userStore.user
    })

    const toggleMobileMenu = () => {
      isMobileMenuOpen.value = !isMobileMenuOpen.value
    }

    const toggleSearchForm = () => {
      showSearchForm.value = !showSearchForm.value
    }

    return {
      isLoggedIn,
      user,
      isMobileMenuOpen,
      toggleMobileMenu,
      showSearchForm,
      toggleSearchForm
    }
  }
}
</script>

<style scoped>
.header .nav ul li.active {
  border-bottom: 2px solid green;
}
</style>
