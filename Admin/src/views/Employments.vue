<template>
  <div>
    <h1 class="text-4xl font-bold text-center mt-10 mb-5">Danh sách Nhân Viên</h1>
    <div class="flex justify-between">
      <label class="input input-bordered flex items-center gap-2 w-60">
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
      <BaseDarkButton @click="openModal" class="float-right mb-2"> Thêm người dùng </BaseDarkButton>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th class="py-2 px-4 border-b text-left">ID</th>
          <th class="py-2 px-4 border-b text-left">Tên</th>
          <th class="py-2 px-4 border-b text-left">Vị trí</th>
          <th class="py-2 px-4 border-b text-left">Email</th>
          <th class="py-2 px-4 border-b text-left">Số điện thoại</th>
          <th class="py-2 px-4 border-b text-left">Chức vụ</th>
          <th class="py-2 px-4 border-b text-left">Hành động</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td class="py-2 px-4 border-b">{{ user.id }}</td>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar">
                <div class="mask mask-circle h-12 w-12">
                  <img :src="user.avatar_img_url" alt="Avatar Tailwind CSS Component" />
                </div>
              </div>
              <div>
                <div class="font-bold">{{ user.name }}</div>
                <div class="text-sm opacity-50">{{ user.username }}</div>
              </div>
            </div>
          </td>
          <td class="py-2 px-4 border-b">{{ user.duty }}</td>
          <td class="py-2 px-4 border-b">{{ user.email }}</td>
          <td class="py-2 px-4 border-b">{{ user.phone }}</td>
          <td class="py-2 px-4 border-b">{{ user.duty }}</td>
          <td class="py-2 px-4 border-b">
            <div class="flex space-x-2">
              <BaseDarkButton @click="editUser(user.id)">Sửa</BaseDarkButton>
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
import BaseFormModal from '@/components/modal/BaseFormModal.vue'
import BaseBigModal from '@/components/modal/BaseBigModal.vue'
import BaseLightButton from '@/components/common/BaseLightButton.vue'
import BaseDarkButton from '@/components/common/BaseDarkButton.vue'
import addUserModal from '@/components/modal/user/AddUserModal.vue'
import updateUserModal from '@/components/modal/user/UpdateUserModal.vue'
import UpdateManager from '@/components/home/onlevel/UpdateManager.vue'
import UpdateFaceModal from '@/components/modal/user/UpdateFaceModal.vue'
import CheckinHistoryModal from '@/components/modal/user/CheckinHistoryModal.vue'
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
const searchKey = ref('')

const search = async () => {
  try {
    const response = await userServices.searchUsers(searchKey.value) // Gửi query string
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
