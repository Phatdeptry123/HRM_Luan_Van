<template>
  <!-- components/profile/ProfileInfo.vue -->
  <div>
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
export default {
  props: {
    user: {
      type: Object,
      required: true
    }
  }
}
</script>
