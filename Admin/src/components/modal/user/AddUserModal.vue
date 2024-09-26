<template>
  <div class="p-4 mx-auto bg-white overflow-auto mt-50">
    <h2 class="text-3xl font-semibold mb-4 overflow-auto text-center text-indigo-500">
      Thêm nhân viên mới
    </h2>
    <form class="overflow-auto" @submit.prevent="submitAddUser">
      <div class="mb-4">
        <label for="name" class="block text-gray-700">Tên:</label>
        <input
          v-model="newUser.name"
          id="name"
          type="text"
          class="border rounded w-full py-2 px-3"
        />
        <p v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</p>
      </div>
      <div class="mb-4">
        <label for="email" class="block text-gray-700">Email:</label>
        <input
          v-model="newUser.email"
          id="email"
          type="email"
          class="border rounded w-full py-2 px-3"
        />
        <p v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</p>
      </div>
      <div class="mb-4">
        <label for="phone" class="block text-gray-700">Số điện thoại:</label>
        <input
          v-model="newUser.phone"
          id="phone"
          type="text"
          class="border rounded w-full py-2 px-3"
        />
        <p v-if="errors.phone" class="text-red-500 text-sm">{{ errors.phone }}</p>
      </div>
      <div class="mb-4">
        <label for="duty" class="block text-gray-700">Chức vụ:</label>
        <input
          v-model="newUser.duty"
          id="duty"
          type="text"
          class="border rounded w-full py-2 px-3"
        />
        <p v-if="errors.duty" class="text-red-500 text-sm">{{ errors.duty }}</p>
      </div>
      <div class="mb-4">
        <label for="address" class="block text-gray-700">Địa chỉ:</label>
        <input
          v-model="newUser.address"
          id="address"
          type="text"
          class="border rounded w-full py-2 px-3"
        />
        <p v-if="errors.address" class="text-red-500 text-sm">{{ errors.address }}</p>
      </div>
      <div class="mb-4">
        <label for="birthday" class="block text-gray-700">Ngày sinh:</label>
        <input
          v-model="newUser.birthday"
          id="birthday"
          type="date"
          class="border rounded w-full py-2 px-3"
        />
        <p v-if="errors.birthday" class="text-red-500 text-sm">{{ errors.birthday }}</p>
      </div>
      <div class="mb-4">
        <label for="basic_salary" class="block text-gray-700">Lương cơ bản:</label>
        <input
          v-model="newUser.salary.basic_salary"
          id="basic_salary"
          type="number"
          class="border rounded w-full py-2 px-3"
        />
        <p v-if="errors.basic_salary" class="text-red-500 text-sm">{{ errors.basic_salary }}</p>
      </div>
      <!-- Thuế -->
      <div class="mb-4">
        <label for="tax" class="block text-gray-700">Thuế:</label>
        <input
          v-model="newUser.salary.tax"
          id="tax"
          type="number"
          class="border rounded w-full py-2 px-3"
        />
        <p v-if="errors.tax" class="text-red-500 text-sm">{{ errors.tax }}</p>
      </div>
      <!-- Bảo hiểm xã hội -->
      <div class="mb-4">
        <label for="social_insurance" class="block text-gray-700">Bảo hiểm xã hội:</label>
        <input
          v-model="newUser.salary.social_insurance"
          id="social_insurance"
          type="number"
          class="border rounded w-full py-2 px-3"
        />
        <p v-if="errors.social_insurance" class="text-red-500 text-sm">
          {{ errors.social_insurance }}
        </p>
      </div>

      <div class="mb-4">
        Đăng ký khuôn mặt
        <input type="file" />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Phân quyền:</label>
        <div class="radio-group">
          <div class="radio-container">
            <input v-model="newUser.role" id="admin" type="radio" value="1" class="radio-input" />
            <label for="admin" class="radio-label">Admin</label>
          </div>
          <div class="radio-container">
            <input
              v-model="newUser.role"
              id="employee"
              type="radio"
              value="2"
              class="radio-input"
            />
            <label for="employee" class="radio-label">Nhân Viên</label>
          </div>
        </div>
        <p v-if="errors.role" class="text-red-500 text-sm">{{ errors.role }}</p>
      </div>
      <div class="flex justify-end">
        <button
          type="button"
          @click="handleClose"
          class="bg-gray-500 text-white py-2 px-4 rounded mr-2"
        >
          Hủy
        </button>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Thêm</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import userServices from '@/services/user.service'
import Swal from 'sweetalert2'

const emit = defineEmits(['close'])

const newUser = ref({
  name: '',
  email: '',
  phone: '',
  duty: '',
  address: '',
  birthday: '',
  role: '',
  salary: {
    basic_salary: 0,
    tax: 0,
    social_insurance: 0,
    deductions: 0,
    total_salary: 0
  }
})
const errors = ref({})
const handleClose = () => {
  newUser.value = {
    name: '',
    email: '',
    phone: '',
    duty: '',
    address: '',
    birthday: '',
    role: '',
    salary: {
      basic_salary: 0,
      tax: 0,
      social_insurance: 0,
      deductions: 0,
      total_salary: 0
    }
  }
  emit('close')
}

const submitAddUser = async () => {
  try {
    // Xóa lỗi cũ trước khi gửi yêu cầu mới
    errors.value = {}

    await userServices.addUser(newUser.value)
    Swal.fire('Thành công', 'Thêm người dùng thành công', 'success')
    handleClose()
  } catch (error) {
    console.error('Lỗi khi thêm người dùng:', error)

    if (error.response && error.response.data.errors) {
      errors.value = error.response.data.errors
    } else {
      Swal.fire('Lỗi', 'Có lỗi xảy ra khi thêm người dùng', 'error')
    }
  }
}
</script>

<style></style>
