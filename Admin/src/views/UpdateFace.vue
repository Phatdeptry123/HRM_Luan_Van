<template>
  <div>
    <h1 class="text-2xl font-bold text-center mt-4">Cập nhật khuôn mặt</h1>
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
    <div class="flex flex-wrap justify-center">
      <div
        v-for="user in filteredUsers"
        :key="user.id"
        class="w-2/12 mt-10 flex flex-col items-center relative"
      >
        <div class="avatar relative">
          <div class="w-24 rounded-full relative">
            <img :src="user.face_img_url" />
            <!-- Icon edit đặt ở góc dưới phải của avatar -->
          </div>
          <font-awesome-icon
            :icon="['fas', 'pen-to-square']"
            class="absolute bottom-0 right-0 text-gray-500 hover:text-gray-700 cursor-pointer"
            @click="editFace(user.id)"
          />
        </div>
        <div class="text-center mt-4">
          <div>{{ user.name }}</div>
          <div>{{ user.username }}</div>
        </div>
      </div>
    </div>

    <!-- Modal chỉ hiển thị khi isVisibleUpdateFace = true -->
    <BaseFormModal v-if="isVisibleUpdateFace">
      <UpdateFaceModal :userId="currentIdUser" @closeUpdateFace="handleCloseUpdateFace" />
    </BaseFormModal>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import userService from '@/services/user.service'
import BaseFormModal from '@/components/modal/BaseFormModal.vue'
import UpdateFaceModal from '@/components/modal/user/UpdateFaceModal.vue'

const searchKey = ref('')
const users = ref([])
const isVisibleUpdateFace = ref(false)
const currentIdUser = ref(null) // Biến lưu ID của user hiện tại

onMounted(async () => {
  const response = await userService.getUsers()
  users.value = response.data
})

const editFace = (userId) => {
  currentIdUser.value = userId // Cập nhật ID của user
  isVisibleUpdateFace.value = true // Hiển thị modal
}

const handleCloseUpdateFace = () => {
  // Đóng modal và reset ID
  isVisibleUpdateFace.value = false
  currentIdUser.value = null
  userService.getUsers().then((response) => {
    users.value = response.data
  })
}

const filteredUsers = computed(() => {
  if (!searchKey.value) return users.value
  return users.value.filter(
    (user) =>
      user.name.toLowerCase().includes(searchKey.value.toLowerCase()) ||
      user.username.toLowerCase().includes(searchKey.value.toLowerCase())
  )
})
</script>

<style></style>
