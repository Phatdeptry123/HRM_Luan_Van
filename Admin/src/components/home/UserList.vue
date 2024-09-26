<template>
  <div>
    <h1 class="text-4xl font-bold text-center mt-10">Danh sách Nhân Viên</h1>
    <table class="min-w-full bg-white border border-gray-200">
      <thead>
        <tr>
          <th class="py-2 px-4 border-b">ID</th>
          <th class="py-2 px-4 border-b">Tên</th>
          <th class="py-2 px-4 border-b">Email</th>
          <th class="py-2 px-4 border-b">Số điện thoại</th>
          <th class="py-2 px-4 border-b">Chức vụ</th>
          <th class="py-2 px-4 border-b">Hành động</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td class="py-2 px-4 border-b">{{ user.id }}</td>
          <td class="py-2 px-4 border-b">{{ user.name }}</td>
          <td class="py-2 px-4 border-b">{{ user.email }}</td>
          <td class="py-2 px-4 border-b">{{ user.phone }}</td>
          <td class="py-2 px-4 border-b">{{ user.duty }}</td>
          <td class="py-2 px-4 border-b">
            <div class="flex space-x-2">
              <button
                @click="editUser(user.id)"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none"
              >
                Sửa
              </button>
              <button
                @click="deleteUser(user.id)"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none"
              >
                Xóa
              </button>
              <button
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 focus:outline-none"
                @click="editManager(user.id)"
              >
                Sửa người quản lí
              </button>
              <button
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 focus:outline-none"
                @click="editFace(user.id)"
              >
                Cập nhật khuôn mặt
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <button @click="openModal" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded">
      Thêm người dùng
    </button>
    <BaseFormModal v-if="isVisible">
      <addUserModal @close="handleClose" />
    </BaseFormModal>
    <BaseFormModal v-if="isVisibleUpdate">
      <updateUserModal :userId="currentUser" @closeUpdate="handleClose" />
    </BaseFormModal>
    <BaseFormModal v-if="isVisibleUpdateManager">
      <UpdateManager :users="users" :userId="currentUser" @closeUpdate="handleCloseUpdateManager" />
    </BaseFormModal>
    <BaseFormModal v-if="isVisibleUpdateFace">
      <UpdateFaceModal :userId="currentUser" @closeUpdateFace="handleCloseUpdateFace" />
    </BaseFormModal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import userServices from '@/services/user.service' // Đổi đường dẫn nếu cần
import BaseFormModal from '../modal/BaseFormModal.vue'
import addUserModal from '../modal/user/AddUserModal.vue'
import updateUserModal from '../modal/user/UpdateUserModal.vue'
import UpdateManager from '@/components/home/onlevel/UpdateManager.vue'
import UpdateFaceModal from '../modal/user/UpdateFaceModal.vue'
import Swal from 'sweetalert2'
const users = ref([])
const currentUser = ref({})

const fetchUsers = async () => {
  try {
    const response = await userServices.getUsers() // Đổi đường dẫn nếu cần
    users.value = response.data
  } catch (error) {
    console.error('Lỗi khi lấy danh sách người dùng:', error)
  }
}

const isVisible = ref(false)
const isVisibleUpdate = ref(false)

const handleClose = () => {
  isVisible.value = false
  isVisibleUpdate.value = false
  fetchUsers()
}
const handleCloseUpdateManager = () => {
  isVisibleUpdateManager.value = false
}

const openModal = () => {
  isVisible.value = true
}
const editUser = (id) => {
  currentUser.value = id
  isVisibleUpdate.value = true
}

const isVisibleUpdateManager = ref(false)
const editManager = (id) => {
  currentUser.value = id
  isVisibleUpdateManager.value = true
}

const deleteUser = async (id) => {
  try {
    await userServices.deleteUser(id) // Đổi đường dẫn nếu cần
    fetchUsers()
  } catch (error) {
    Swal.fire('Lỗi', 'Xóa người dùng thất bại', 'error')
  }
}

const isVisibleUpdateFace = ref(false)
const editFace = (id) => {
  currentUser.value = id
  isVisibleUpdateFace.value = true
}

const handleCloseUpdateFace = () => {
  isVisibleUpdateFace.value = false
}
onMounted(fetchUsers)
</script>

<style scoped>
/* Thêm các kiểu CSS tùy chỉnh nếu cần */
</style>
