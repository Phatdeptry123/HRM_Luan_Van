<template>
  <div class="flex justify-center p-6 bg-base-100 rounded-lg shadow-lg">
    <div>
      <h1 class="text-2xl font-bold mb-4">Cập nhật lương cơ bảng của</h1>
      <div class="mb-4">
        <h2 class="text-lg font-semibold">
          {{ user.name }} - {{ user.username }} - {{ user.duty }}
        </h2>
      </div>

      <div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="card bg-base-200 p-4">
            <h3 class="text-xl font-semibold">Mức lương hiện tại</h3>
            <input
              v-model.number="salaryData.basic_salary"
              type="number"
              class="input input-bordered w-full"
            />
            <p class="text-sm text-gray-500">Miêu tả: Mức lương hợp đồng hiện tại</p>
          </div>
        </div>
        <div class="flex justify-between mt-4">
          <button
            class="bg-red-500 text-white py-2 px-4 rounded w-3/12 ml-20"
            @click="$emit('close')"
          >
            Hủy
          </button>
          <button
            @click="storeBasicSalary"
            class="bg-green-500 text-white py-2 mr-20 px-4 rounded w-3/12"
          >
            Lưu
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref } from 'vue'
import SalaryService from '@/services/salary.service'
import Swal from 'sweetalert2'

const props = defineProps({
  userId: String,
  user: Object,
  salaryData: Object
})
const userId = ref(props.userId)
const user = ref(props.user)
const salaryData = ref(props.salaryData)

const storeBasicSalary = async () => {
  try {
    const payload = {
      user_id: userId.value,
      basic_salary: salaryData.value.basic_salary
    }
    await SalaryService.createOrUpdateBasicSalary(payload)
    Swal.fire({
      icon: 'success',
      title: 'Thành công',
      text: 'Mức lương đã được cập nhật thành công!'
    })
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Lỗi',
      text: 'Đã xảy ra lỗi khi cập nhật mức lương. Vui lòng thử lại sau.'
    })
  }
}
</script>

<style scoped>
/* Add custom styles here */
</style>
