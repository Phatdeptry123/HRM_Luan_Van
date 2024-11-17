<template>
  <div>
    <h1 class="text-4xl font-bold text-center mt-10">Quản Lý Cấp Bậc</h1>
    <HierarchyChart :managersWithSubordinates="managersWithSubordinates"> </HierarchyChart>
    <h2 class="text-2xl font-bold mt-10">Danh sách quản lý</h2>
    <div
      v-for="(manager, index) in managersWithSubordinates"
      :key="index"
      class="mb-6 bg-teal-100 p-5 rounded-lg"
    >
      <div class="flex justify-between items-center border-b border-gray-200 py-2">
        <div>
          <p class="text-lg font-semibold">{{ manager.name }}</p>
          <p class="text-sm text-gray-500">{{ manager.duty }}</p>
        </div>
        <div>
          <BaseDarkButton @click="toggleSubordinates(index)">
            {{ showSubordinates[index] ? 'Ẩn cấp dưới' : 'Xem cấp dưới' }}
          </BaseDarkButton>
          <BaseLightButton @click="openAddSubordinateModal(manager)" class="ml-2">
            Thêm cấp dưới
          </BaseLightButton>
        </div>
      </div>
      <!-- Hiển thị danh sách cấp dưới khi người dùng nhấn nút -->
      <div v-if="showSubordinates[index]" class="pl-6 mt-4">
        <h3 class="text-lg font-semibold">Danh sách cấp dưới:</h3>
        <ul class="list-decimal pl-4">
          <li v-for="(subordinate, subIndex) in manager.managed_users" :key="subIndex">
            <p>{{ subordinate.name }} - {{ subordinate.duty }}</p>
          </li>
        </ul>
      </div>
    </div>

    <h2 class="text-2xl font-bold mt-10">Danh sách nhân viên</h2>
    <label class="input input-bordered flex items-center gap-2 w-60 my-2">
      <input
        v-model="searchKey"
        @keyup.enter="search"
        @keyup.space.prevent="search"
        type="text"
        class="grow"
        placeholder="tìm kiếm"
      />
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70"
      >
        <path
          fill-rule="evenodd"
          d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
          clip-rule="evenodd"
        />
      </svg>
    </label>
    <div class="grid grid-cols-4 gap-4">
      <div
        v-for="user in filteredUsers"
        :key="user.id"
        class="bg-teal-100 p-4 rounded-lg flex justify-between"
      >
        <div>
          <div class="text-lg font-semibold">{{ user.name }}</div>
          <div class="text-lg font-semibold">{{ user.username }}</div>
          <div class="text-sm text-gray-500">{{ user.duty }}</div>
        </div>
        <BaseLightButton @click="editManager(user.id)"> Sửa quản lí </BaseLightButton>
      </div>
    </div>

    <!-- Modal thêm cấp dưới -->
    <div
      v-if="showAddSubordinateModal"
      class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center"
    >
      <div class="bg-teal-50 p-6 rounded shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Thêm cấp dưới cho {{ currentManager.name }}</h2>

        <!-- Dropdown chọn user -->
        <select v-model="selectedSubordinateId" class="border border-gray-300 p-2 w-full mb-4">
          <option disabled value="">Chọn cấp dưới</option>
          <option v-for="user in allUsers" :key="user.id" :value="user.id">
            {{ user.name }} - {{ user.duty }}
          </option>
        </select>

        <div class="flex justify-end">
          <BaseDarkButton
            @click="addSubordinate"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2"
          >
            Thêm
          </BaseDarkButton>
          <BaseLightButton
            @click="closeAddSubordinateModal"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
          >
            Đóng
          </BaseLightButton>
        </div>
      </div>
    </div>
    <BaseFormModal v-if="isVisibleUpdateManager">
      <UpdateManager
        :users="allUsers"
        :userId="currentUser"
        @closeUpdate="isVisibleUpdateManager = false"
      />
    </BaseFormModal>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import managerService from '@/services/manager.service'
import userService from '@/services/user.service'
import BaseDarkButton from '@/components/common/BaseDarkButton.vue'
import BaseLightButton from '@/components/common/BaseLightButton.vue'
import HierarchyChart from '@/components/home/onlevel/HierarchyChart.vue'
import BaseFormModal from '@/components/modal/BaseFormModal.vue'
import UpdateManager from '@/components/home/onlevel/UpdateManager.vue'

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
  console.log('index:', index)
  console.log('showSubordinates:', showSubordinates.value)

  showSubordinates.value[index] = !showSubordinates.value[index]
  console.log('showSubordinates:', showSubordinates.value)
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
const isVisibleUpdateManager = ref(false)
const currentUser = ref({})
const editManager = (id) => {
  currentUser.value = id
  isVisibleUpdateManager.value = true
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

const searchKey = ref('')
const filteredUsers = computed(() => {
  if (!searchKey.value) return allUsers.value
  return allUsers.value.filter(
    (user) =>
      user.name.toLowerCase().includes(searchKey.value.toLowerCase()) ||
      user.username.toLowerCase().includes(searchKey.value.toLowerCase())
  )
})
</script>

<style scoped>
/* Style cho các phần tử nếu cần */
</style>
