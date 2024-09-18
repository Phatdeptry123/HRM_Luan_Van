<template>
  <div>
    <h1 class="text-4xl font-bold text-center mt-10">Quản Lý Cấp Bậc</h1>
    <h2 class="text-2xl font-bold mt-10">Danh sách quản lý</h2>
    <div v-for="(manager, index) in managersWithSubordinates" :key="index" class="mb-6">
      <div class="flex justify-between items-center border-b border-gray-200 py-2">
        <div>
          <p class="text-lg font-semibold">{{ manager.name }}</p>
          <p class="text-sm text-gray-500">{{ manager.duty }}</p>
        </div>
        <div>
          <button
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none mr-3 min-w-40"
            @click="toggleSubordinates(index)"
          >
            {{ showSubordinates[index] ? 'Ẩn cấp dưới' : 'Xem cấp dưới' }}
          </button>
          <button
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 focus:outline-none"
            @click="openAddSubordinateModal(manager)"
          >
            Thêm cấp dưới
          </button>
        </div>
      </div>

      <!-- Hiển thị danh sách cấp dưới khi người dùng nhấn nút -->
      <div v-if="showSubordinates[index]" class="pl-6 mt-4">
        <h3 class="text-lg font-semibold">Danh sách cấp dưới:</h3>
        <ul class="list-disc pl-4">
          <li v-for="(subordinate, subIndex) in manager.managed_users" :key="subIndex">
            <p>{{ subordinate.name }} - {{ subordinate.duty }}</p>
          </li>
        </ul>
      </div>
    </div>

    <!-- Modal thêm cấp dưới -->
    <div
      v-if="showAddSubordinateModal"
      class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center"
    >
      <div class="bg-white p-6 rounded shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Thêm cấp dưới cho {{ currentManager.name }}</h2>

        <!-- Dropdown chọn user -->
        <select v-model="selectedSubordinateId" class="border border-gray-300 p-2 w-full mb-4">
          <option disabled value="">Chọn cấp dưới</option>
          <option v-for="user in allUsers" :key="user.id" :value="user.id">
            {{ user.name }} - {{ user.duty }}
          </option>
        </select>

        <div class="flex justify-end">
          <button
            @click="addSubordinate"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2"
          >
            Thêm
          </button>
          <button
            @click="closeAddSubordinateModal"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
          >
            Đóng
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import managerService from '@/services/manager.service'
import userService from '@/services/user.service'

// State để lưu danh sách quản lý và cấp dưới
const managersWithSubordinates = ref([])

// State để lưu trạng thái hiển thị của cấp dưới
const showSubordinates = ref([])

// State cho modal thêm cấp dưới
const showAddSubordinateModal = ref(false)
const currentManager = ref(null)
const selectedSubordinateId = ref('')
const allUsers = ref([]) // Danh sách tất cả users

// Hàm mở modal thêm cấp dưới
const openAddSubordinateModal = (manager) => {
  currentManager.value = manager
  showAddSubordinateModal.value = true
}

// Hàm đóng modal thêm cấp dưới
const closeAddSubordinateModal = () => {
  showAddSubordinateModal.value = false
  selectedSubordinateId.value = ''
}

// Hàm để bật/tắt hiển thị danh sách cấp dưới của từng manager
const toggleSubordinates = (index) => {
  showSubordinates.value[index] = !showSubordinates.value[index]
}

// Hàm để thêm cấp dưới
const addSubordinate = async () => {
  try {
    if (!selectedSubordinateId.value) {
      alert('Vui lòng chọn một cấp dưới!')
      return
    }

    // Gửi yêu cầu API để thêm cấp dưới
    await managerService.addSubordinate(selectedSubordinateId.value, currentManager.value.id)

    // Cập nhật lại danh sách cấp dưới của người quản lý
    const response = await managerService.managersWithSubordinates()
    managersWithSubordinates.value = response

    // Đóng modal sau khi thêm thành công
    closeAddSubordinateModal()
  } catch (error) {
    console.log('Lỗi khi thêm cấp dưới:', error)
  }
}

// Lấy danh sách quản lý và cấp dưới khi component được mount
onMounted(async () => {
  try {
    const managerResponse = await managerService.managersWithSubordinates()
    managersWithSubordinates.value = managerResponse

    // Khởi tạo trạng thái hiển thị của cấp dưới cho mỗi manager
    showSubordinates.value = managersWithSubordinates.value.map(() => false)

    // Lấy danh sách tất cả users
    const userResponse = await userService.getUsers()
    allUsers.value = userResponse.data
  } catch (error) {
    console.log('Lỗi khi tải danh sách:', error)
  }
})
</script>

<style scoped>
/* Style cho các phần tử nếu cần */
</style>
