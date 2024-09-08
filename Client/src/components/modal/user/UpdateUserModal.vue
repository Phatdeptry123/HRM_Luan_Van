<template>
    <div class="p-4 mx-auto bg-white mt-3">
      <h2 class="text-xl font-semibold mb-4">Cập nhật thông tin người dùng</h2>
      <form @submit.prevent="submitUpdateUser">
        <div class="mb-4">
          <label for="name" class="block text-gray-700">Tên:</label>
          <input
            v-model="currentUser.name"
            id="name"
            type="text"
            class="border rounded w-full py-2 px-3"
          />
          <p v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</p>
        </div>
        <div class="mb-4">
          <label for="email" class="block text-gray-700">Email:</label>
          <input
            v-model="currentUser.email"
            id="email"
            type="email"
            class="border rounded w-full py-2 px-3"
          />
          <p v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</p>
        </div>
        <div class="mb-4">
          <label for="phone" class="block text-gray-700">Số điện thoại:</label>
          <input
            v-model="currentUser.phone"
            id="phone"
            type="text"
            class="border rounded w-full py-2 px-3"
          />
          <p v-if="errors.phone" class="text-red-500 text-sm">{{ errors.phone }}</p>
        </div>
        <div class="mb-4">
          <label for="duty" class="block text-gray-700">Chức vụ:</label>
          <input
            v-model="currentUser.duty"
            id="duty"
            type="text"
            class="border rounded w-full py-2 px-3"
          />
          <p v-if="errors.duty" class="text-red-500 text-sm">{{ errors.duty }}</p>
        </div>
        <div class="mb-4">
          <label for="address" class="block text-gray-700">Địa chỉ:</label>
          <input
            v-model="currentUser.address"
            id="address"
            type="text"
            class="border rounded w-full py-2 px-3"
          />
          <p v-if="errors.address" class="text-red-500 text-sm">{{ errors.address }}</p>
        </div>
        <div class="mb-4">
          <label for="birthday" class="block text-gray-700">Ngày sinh:</label>
          <input
            v-model="currentUser.birthday"
            id="birthday"
            type="date"
            class="border rounded w-full py-2 px-3"
          />
          <p v-if="errors.birthday" class="text-red-500 text-sm">{{ errors.birthday }}</p>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Phân quyền:</label>
          <div class="radio-group">
            <div class="radio-container">
              <input
                v-model="currentUser.role"
                id="admin"
                type="radio"
                value="1"
                class="radio-input"
              />
              <label for="admin" class="radio-label">Admin</label>
            </div>
            <div class="radio-container">
              <input
                v-model="currentUser.role"
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
            @click="handleCloseUpdate"
            class="bg-gray-500 text-white py-2 px-4 rounded mr-2"
          >
            Hủy
          </button>
          <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Cập nhật</button>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import userServices from '@/services/user.service'
  import Swal from 'sweetalert2'
  
  const emit = defineEmits(['closeUpdate'])
  const props = defineProps({
    userId: {
      type: Number,
      required: true,
    }
  })
  
  const currentUser = ref({
    name: '',
    email: '',
    phone: '',
    duty: '',
    address: '',
    birthday: '',
    role: ''
  })
  const errors = ref({})
  
  const fetchUserDetails = async () => {
    try {
      const response = await userServices.getUser(props.userId)
      currentUser.value = response.data
      console.log(currentUser.value);
      
    } catch (error) {
      Swal.fire('Lỗi', 'Không thể tải thông tin người dùng', 'error')
    }
  }
  
  onMounted(() => {
    fetchUserDetails()
  })
  
  const handleCloseUpdate = () => {
    console.log('closeUpdate');
    
    emit('closeUpdate')
  }
  
  const submitUpdateUser = async () => {
    try {
      errors.value = {}
  
      await userServices.updateUser(props.userId, currentUser.value)
      Swal.fire('Thành công', 'Cập nhật thông tin người dùng thành công', 'success')
      handleCloseUpdate()
    } catch (error) {
      console.error('Lỗi khi cập nhật người dùng:', error)
  
      if (error.response && error.response.data.errors) {
        errors.value = error.response.data.errors
      } else {
        Swal.fire('Lỗi', 'Có lỗi xảy ra khi cập nhật thông tin người dùng', 'error')
      }
    }
  }
  </script>
  
  <style>
  /* CSS tùy chỉnh nếu cần */
  </style>
  