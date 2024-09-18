<template>
  <div class="p-6">
    <div class="flex items-center justify-center mb-8">
      <h1 class="text-3xl font-bold text-red-500">Đề Xuất Nghỉ Phép</h1>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 mb-8">
      <!-- Chọn loại đề xuất -->
      <div class="lg:w-6/12">
        <h2 class="text-xl font-semibold mb-4">Chọn loại đề xuất</h2>
        <ul class="menu menu-compact bg-base-200 rounded-lg shadow-lg">
          <li @click="setCurrentType('leave')" class="hover:bg-blue-200">
            <a>Nghỉ Phép</a>
          </li>
          <li @click="setCurrentType('remote')" class="hover:bg-blue-200">
            <a>Remote</a>
          </li>
          <li @click="setCurrentType('late')" class="hover:bg-blue-200">
            <a>Đi Trễ / Về Sớm</a>
          </li>
        </ul>
      </div>

      <!-- Chọn ngày đề xuất và thông tin quản lý -->
      <div class="lg:w-6/12">
        <div v-if="currentType">
          <h2 class="text-xl font-semibold mb-4">
            Chọn ngày đề xuất:
            <p class="text-blue-600">{{ currentTypeVn }}</p>
          </h2>
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
            <p class="text-xl font-semibold mb-4">Lý do</p>
            <textarea
              v-model="description"
              class="border rounded w-full py-2 px-3 mb-6"
              rows="5"
              placeholder="Nhập lý do"
            />
          </div>
          />
          <h2 class="text-xl font-semibold">
            Gửi đề xuất đến:
            <p class="text-blue-600 font-bold">{{ managerName }}</p>
          </h2>
        </div>
      </div>
    </div>

    <!-- Nút hành động -->
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
        :disabled="!date || !currentType"
        :class="{ 'opacity-50 cursor-not-allowed': !date || !currentType }"
      >
        Gửi Đề Xuất
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import DatePicker from 'vue-datepicker-next'
import { useUserStore } from '@/stores/user'
import userService from '@/services/user.service'
import RequestService from '@/services/request.service'
import Swal from 'sweetalert2'

const emit = defineEmits(['closeModal'])
const userStore = useUserStore()
const user = userStore.user
const currentType = ref('')
const setCurrentType = (type) => {
  currentType.value = type
}

const currentTypeVn = computed(() => {
  switch (currentType.value) {
    case 'leave':
      return 'Nghỉ Phép'
    case 'remote':
      return 'Remote'
    case 'late':
      return 'Đi Trễ / Về Sớm'
    default:
      return ''
  }
})

const date = ref('')
const description = ref('')
// Hàm định dạng ngày thành dd-mm-yyyy
const formattedDate = computed(() => {
  return date.value ? new Date(date.value).toLocaleDateString('vi-VN') : ''
})

const managerName = ref('')
onMounted(() => {
  userService.getManagerName(user.id).then((res) => {
    managerName.value = res.manager_name
  })
})

const sendRequest = () => {
  const data = {
    type: currentType.value,
    description: description.value,
    request_date: formattedDate.value,
    manager_id: user.manager_id,
    user_id: user.id
  }
  RequestService.addRequest(data)
    .then(() => {
      handleAddSuccess()
    })
    .catch((error) => {
      Swal.fire('Lỗi', 'Có lỗi xảy ra khi gửi đề xuất', 'error')
    })
}

const handleAddSuccess = () => {
  emit('closeModal')
  Swal.fire('Thành công', 'Gửi đề xuất thành công', 'success')
}
</script>

<style>
.vue-datepicker {
  width: 100%;
  max-width: 400px;
}

.vue-datepicker .calendar {
  box-shadow: none;
  border: 1px solid #ccc;
  border-radius: 8px;
}
</style>
