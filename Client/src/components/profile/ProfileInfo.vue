<template>
  <!-- components/profile/ProfileInfo.vue -->
  <div class="flex">
    <div class="w-1/3">
      <img :src="user.avatar_img_url" alt="User Avatar" class="w-48 h-48 rounded-full" />
    </div>
    <div class="w-1/3">
      <!-- Personal Information Section -->
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Tên</label>
        <p
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          {{ user.name || 'Not yet declared ...' }}
        </p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Mã Nhân Viên</label>
        <p
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          {{ user.username || 'Not yet declared ...' }}
        </p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Chức Vụ</label>
        <p
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          {{ user.duty || 'Not yet declared ...' }}
        </p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <p
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          {{ user.email || 'Not yet declared ...' }}
        </p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Số Điện Thoại</label>
        <p
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          {{ user.phone || 'Not yet declared ...' }}
        </p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Địa Chỉ</label>
        <p
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          {{ user.address || 'Not yet declared ...' }}
        </p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Ngày Sinh</label>
        <p
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
          {{ user.birthday || 'Not yet declared ...' }}
        </p>
      </div>
    </div>

    <div class="w-1/2 pl-6">
      <!-- Thông tin về cấp trên (Manager) -->
      <div class="mb-6">
        <h2 class="text-lg font-semibold">Cấp Trên:</h2>
        <div v-if="manager">
          <div class="flex items-center space-x-4">
            <img
              :src="manager.avatar_img_url"
              alt="Manager Avatar"
              class="w-16 h-16 rounded-full"
            />
            <div>
              <p class="font-semibold">{{ manager.name }}</p>
              <p class="text-sm text-gray-500">{{ manager.duty }}</p>
              <p class="text-sm text-gray-500">{{ manager.email }}</p>
              <p class="text-sm text-gray-500">{{ manager.phone }}</p>
            </div>
          </div>
        </div>
        <div v-else>
          <p class="text-sm text-gray-500">Chưa có cấp trên</p>
        </div>
      </div>

      <!-- Thông tin về cấp dưới (Subordinates) -->
      <div class="mb-6">
        <h2 class="text-lg font-semibold">Cấp Dưới:</h2>
        <div v-if="subordinates.length > 0">
          <div
            v-for="subordinate in subordinates"
            :key="subordinate.id"
            class="flex items-center space-x-4 mb-4"
          >
            <img
              :src="subordinate.avatar_img_url"
              alt="Subordinate Avatar"
              class="w-16 h-16 rounded-full"
            />
            <div>
              <p class="font-semibold">{{ subordinate.name }}</p>
              <p class="text-sm text-gray-500">{{ subordinate.duty }}</p>
              <p class="text-sm text-gray-500">{{ subordinate.email }}</p>
              <p class="text-sm text-gray-500">{{ subordinate.phone }}</p>
            </div>
          </div>
        </div>
        <div v-else>
          <p class="text-sm text-gray-500">Không có cấp dưới</p>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <button
      @click="$emit('edit')"
      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
    >
      Chỉnh Sửa Thông Tin
    </button>
    <button
      @click="$emit('change-password')"
      class="ml-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
    >
      Thay Đổi Mật Khẩu
    </button>
  </div>
</template>

<script>
import managerService from '@/services/manager.service'

export default {
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      subordinates: [],
      manager: null
    }
  },
  methods: {
    async getSubordinates() {
      const res = await managerService.getSubordinates(this.user.id)
      this.subordinates = res || []
    },
    async getManager() {
      const res = await managerService.getManager(this.user.id)
      this.manager = res || null
    }
  },
  mounted() {
    this.getSubordinates()
    this.getManager()
  }
}
</script>
