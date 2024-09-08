<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Face Recognition</h1>

      <!-- Form Enroll New User -->
      <form @submit.prevent="enrollNewUser" class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            id="name"
            v-model="userInfo.name"
            type="text"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter your name"
            required
          />
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            id="email"
            v-model="userInfo.email"
            type="email"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter your email"
            required
          />
        </div>

        <button
          type="submit"
          class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
          Enroll New User
        </button>
      </form>

      <!-- Form Authenticate User -->
      <div class="mt-6">
        <button
          @click="authenticateUser"
          class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
          Authenticate User
        </button>
      </div>

      <!-- Modal faceIO -->
      <div id="faceio-modal"></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const userInfo = ref({
  name: '',
  email: ''
})

// Khai báo faceio tại setup
let faceio = null
onMounted(() => {
  faceio = new faceIO('fioa8avb') // Thay bằng app-public-id của bạn
})

// Hàm enroll
const enrollNewUser = () => {
  if (!faceio) return

  faceio
    .enroll({
      payload: {
        name: userInfo.value.name,
        email: userInfo.value.email
      }
    })
    .then((response) => {
      console.log('User enrolled successfully:', response)
    })
    .catch((errCode) => {
      console.error('Error during enrollment:', errCode)
    })
}

// Hàm authenticate
const authenticateUser = () => {
  if (!faceio) return

  faceio
    .authenticate()
    .then((response) => {
      console.log('User authenticated successfully:', response)
    })
    .catch((errCode) => {
      console.error('Error during authentication:', errCode)
    })
}
</script>
