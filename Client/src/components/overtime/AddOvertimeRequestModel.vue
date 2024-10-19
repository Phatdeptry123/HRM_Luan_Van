<template>
  <div class="p-6">
    <div class="flex items-center justify-center mb-8">
      <h1 class="text-3xl font-bold text-red-500">Đề Xuất Tăng Ca</h1>
    </div>
    <div class="">
      <h2 class="text-xl font-semibold mb-4">Chọn ngày đề xuất tăng ca</h2>
      <DatePicker
        v-model:value="date"
        type="date"
        inline
        :append-to-body="false"
        :format="'DD-MM-YYYY'"
        :input-props="{ placeholder: 'Chọn ngày' }"
        class="mb-6"
      />
      <div>
        <p class="text-xl font-semibold mb-4">Mô Tả</p>
        <textarea
          v-model="description"
          class="border rounded w-full py-2 px-3 mb-6"
          rows="5"
          placeholder="Nhập Mô Tả"
        />
      </div>
      <div>
        <p class="text-xl font-semibold mb-4">Số giờ tăng ca</p>
        <input
          v-model="overtimeHours"
          type="number"
          class="border rounded w-full py-2 px-3 mb-6"
          placeholder="Nhập số giờ tăng ca"
        />
      </div>
      <h2 class="text-xl font-semibold">
        Gửi đề xuất đến:
        <p class="text-blue-600 font-bold">{{ managerName }}</p>
      </h2>
    </div>
    <div class="flex gap-4">
      <button
        @click="$emit('closeModal')"
        class="flex-1 bg-red-500 text-white py-3 rounded-lg font-bold hover:bg-red-600 transition"
      >
        Hủy
      </button>
      <button
        @click="sendRequest"
        class="flex-1 bg-green-500 text-white py-3 rounded-lg font-bold hover:bg-green-600 transition"
        :disabled="!date"
        :class="{ 'opacity-50 cursor-not-allowed': !date || !overtimeHours }"
      >
        Gửi Đề Xuất
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import DatePicker from 'vue-datepicker-next'
import { useUserStore } from '@/stores/user'
import userService from '@/services/user.service'
import OvertimeService from '@/services/overtime.service'
import Swal from 'sweetalert2'

const userStore = useUserStore()
const user = userStore.user
const emit = defineEmits(['closeModal'])
const date = ref('')
const description = ref('')
const formattedDate = computed(() => {
  if (!date.value) return ''
  const d = new Date(date.value)
  const year = d.getFullYear()
  const month = ('0' + (d.getMonth() + 1)).slice(-2)
  const day = ('0' + d.getDate()).slice(-2)
  return `${year}-${month}-${day}`
})
const overtimeHours = ref('')
const managerName = ref('')

onMounted(() => {
  userService.getManagerName(user.id).then((res) => {
    managerName.value = res.manager_name
  })
})

const sendRequest = () => {
  const data = {
    description: description.value,
    request_date: formattedDate.value,
    manager_id: user.manager_id,
    user_id: user.id,
    request_hour: overtimeHours.value
  }
  OvertimeService.addOvertime(data)
    .then(() => {
      handleAddSuccess()
    })
    .catch(() => {
      Swal.fire('Lỗi', 'Có lỗi xảy ra khi gửi đề xuất', 'error')
    })
}

const handleAddSuccess = () => {
  emit('closeModal')
  Swal.fire('Thành công', 'Gửi đề xuất thành công', 'success')
}
</script>

<style></style>
