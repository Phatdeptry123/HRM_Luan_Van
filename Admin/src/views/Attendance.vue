<template>
  <div>
    <h1 class="text-2xl font-bold text-center mt-4">Cập nhật Chấm Công</h1>
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
        v-for="attendance in filteredAttendances"
        :key="attendance.user.id"
        class="w-1/5 mt-4 flex items-center relative bg-teal-200 ml-4 rounded-lg p-4"
      >
        <div class="avatar relative">
          <div class="w-24 rounded-full relative">
            <img :src="attendance.user.avatar_img_url" />
          </div>
        </div>
        <div class="mt-4 ml-3 flex flex-col items-start">
          <div class="whitespace-nowrap">{{ attendance.user.name }}</div>
          <div class="whitespace-nowrap">{{ attendance.user.username }}</div>

          <base-light-button
            class="mt-1"
            @click="
              () => {
                currentUserId = attendance.user.id
                isVisisbleHistoryCheckinModal = true
              }
            "
          >
            <div>{{ attendance.total }} Ngày công</div>
          </base-light-button>
        </div>
      </div>
    </div>

    <BaseBigModal v-if="isVisisbleHistoryCheckinModal">
      <CheckinHistoryModal
        @closeCheckinHistory="isVisisbleHistoryCheckinModal = false"
        :user-id="currentUserId"
      />
    </BaseBigModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import attendanceService from '@/services/attendance.service'
import BaseBigModal from '@/components/modal/BaseBigModal.vue'
import CheckinHistoryModal from '@/components/modal/user/CheckinHistoryModal.vue'

const searchKey = ref('')
const attendances = ref([])
const currentUserId = ref(null)
const isVisisbleHistoryCheckinModal = ref(false)

// Tính toán danh sách đã lọc
const filteredAttendances = computed(() => {
  if (!searchKey.value.trim()) {
    return attendances.value // Trả lại toàn bộ danh sách nếu không có từ khóa
  }
  return attendances.value.filter((attendance) => {
    const name = attendance.user.name.toLowerCase()
    const username = attendance.user.username.toLowerCase()
    const key = searchKey.value.toLowerCase().trim()
    return name.includes(key) || username.includes(key)
  })
})

const search = () => {
  // Hàm này chỉ để gọi khi người dùng nhấn Enter hoặc Space, logic lọc đã được tính toán trong filteredAttendances
  console.log('Tìm kiếm từ khóa:', searchKey.value)
}

onMounted(async () => {
  const response = await attendanceService.countDaysCheckinInMonthForAllUsers()
  attendances.value = response
})
</script>

<style></style>
