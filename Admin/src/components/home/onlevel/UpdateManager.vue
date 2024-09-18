<template>
  <div class="p-4 mx-auto bg-white mt-3 scroll-auto">
    <h2 class="text-xl font-semibold mb-4">
      Cập nhật người quản lí của
      <span class="text-xl font-semibold mb-4 text-red-600">
        {{ user.name }} - {{ user.username }}
      </span>
    </h2>
    <form @submit.prevent="submitUpdateUser">
      <div class="mb-4">
        <label for="manager" class="block text-gray-700">Chọn người quản lí</label>
        <select
          id="manager"
          v-model="selectedManagerId"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
          <option value="" disabled>Chọn người quản lí</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }} - {{ user.username }}
          </option>
        </select>
      </div>

      <div class="flex justify-end">
        <button
          type="button"
          @click="handleCloseUpdate"
          class="bg-gray-500 text-white py-2 px-4 rounded mr-2"
        >
          Hủy
        </button>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Cập nhật</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import userServices from '@/services/user.service'
import Swal from 'sweetalert2'

// Nhận props và emit từ cha
const emit = defineEmits(['closeUpdate'])
const props = defineProps({
  userId: {
    type: Number,
    required: true
  },
  users: {
    type: Array
  }
})

const users = ref(props.users)
const user = users.value.find((user) => user.id === props.userId)
// Biến lưu manager được chọn
const selectedManagerId = ref(null)

// Hàm đóng form cập nhật
const handleCloseUpdate = () => {
  emit('closeUpdate')
}

// Hàm xử lý khi submit form
const submitUpdateUser = async () => {
  try {
    // Gọi API để cập nhật người quản lý cho user
    await userServices.updateManager(props.userId, { manager_id: selectedManagerId.value })

    // Thông báo thành công
    Swal.fire({
      icon: 'success',
      title: 'Cập nhật thành công!',
      text: 'Người quản lý đã được cập nhật.',
      timer: 2000,
      showConfirmButton: false
    })

    // Đóng form sau khi cập nhật
    handleCloseUpdate()
  } catch (error) {
    // Thông báo lỗi nếu có
    Swal.fire({
      icon: 'error',
      title: 'Lỗi!',
      text: 'Đã xảy ra lỗi khi cập nhật người quản lý.',
      timer: 2000,
      showConfirmButton: false
    })
  }
}

onMounted(() => {
  if (user.manager_id) {
    selectedManagerId.value = user.manager_id
  }
})
</script>

<style scoped>
/* CSS tùy chỉnh nếu cần */
</style>
