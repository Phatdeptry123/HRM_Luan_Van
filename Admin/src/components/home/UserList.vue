<template>
  <div>
    <h1 class="text-4xl font-bold text-center mt-10 mb-5">Danh sách Nhân Viên</h1>
    <BaseDarkButton @click="openModal" class="float-right mb-2"> Thêm người dùng </BaseDarkButton>
    <table class="min-w-full bg-teal-100 rounded-lg">
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
          <td class="py-2 px-4 border-b">{{ user.name }} - {{ user.username }}</td>
          <td class="py-2 px-4 border-b">{{ user.email }}</td>
          <td class="py-2 px-4 border-b">{{ user.phone }}</td>
          <td class="py-2 px-4 border-b">{{ user.duty }}</td>
          <td class="py-2 px-4 border-b">
            <div class="flex space-x-2">
              <BaseLightButton @click="editUser(user.id)">Sửa</BaseLightButton>

              <BaseLightButton @click="editManager(user.id)"> Sửa quản lí </BaseLightButton>
              <BaseLightButton @click="editFace(user.id)"> Cập nhật khuôn mặt </BaseLightButton>

              <BaseLightButton @click="checkinHistory(user.id)">
                Lịch sử chấm công
              </BaseLightButton>

              <BaseLightButton>
                <router-link :to="`/salary/${user.id}`">Quản lý lương</router-link>
              </BaseLightButton>
              <BaseLightButton @click="deleteUser(user.id)"> Xóa </BaseLightButton>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
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

    <BaseBigModal v-if="isVisibleCheckinHistory">
      <CheckinHistoryModal :userId="currentUser" @closeCheckinHistory="handleCloseCheckinHistory" />
    </BaseBigModal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import userServices from '@/services/user.service' // Đổi đường dẫn nếu cần
import BaseFormModal from '../modal/BaseFormModal.vue'
import BaseBigModal from '../modal/BaseBigModal.vue'
import BaseLightButton from '@/components/common/BaseLightButton.vue'
import BaseDarkButton from '@/components/common/BaseDarkButton.vue'
import addUserModal from '../modal/user/AddUserModal.vue'
import updateUserModal from '../modal/user/UpdateUserModal.vue'
import UpdateManager from '@/components/home/onlevel/UpdateManager.vue'
import UpdateFaceModal from '../modal/user/UpdateFaceModal.vue'
import CheckinHistoryModal from '../modal/user/CheckinHistoryModal.vue'
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

const isVisibleCheckinHistory = ref(false)
const checkinHistory = (id) => {
  currentUser.value = id
  isVisibleCheckinHistory.value = true
}
const handleCloseCheckinHistory = () => {
  isVisibleCheckinHistory.value = false
}
const handleCloseUpdateFace = () => {
  isVisibleUpdateFace.value = false
}

onMounted(fetchUsers)
</script>

<style scoped>
/* Thêm các kiểu CSS tùy chỉnh nếu cần */
</style>
