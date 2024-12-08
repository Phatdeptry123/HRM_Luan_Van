<template>
  <div class="flex flex-col row-span-3 bg-white shadow rounded-lg">
    <div class="px-6 py-5 font-semibold border-b border-gray-100">Bảng xếp hạng Overtime</div>
    <div class="p-4 flex-grow">
      <!-- Nếu chưa có dữ liệu, hiển thị thông báo chờ -->
      <div
        v-if="loading"
        class="flex items-center justify-center h-full px-4 py-24 text-gray-400 text-3xl font-semibold bg-gray-100 border-2 border-gray-200 border-dashed rounded-md"
      >
        Đang tải dữ liệu...
      </div>

      <!-- Hiển thị bảng xếp hạng nếu đã có dữ liệu -->
      <div v-else>
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200">
            <thead>
              <tr class="border-b">
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">#</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                  Tên người dùng
                </th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                  Số giờ Overtime
                </th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">
                  Ảnh đại diện
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in ranking" :key="item.user.id" class="border-b">
                <td class="px-6 py-4 text-sm text-gray-800">{{ index + 1 }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ item.user.name }}</td>
                <td class="px-6 py-4 text-sm text-gray-800">{{ item.total_hours }} giờ</td>
                <td class="px-6 py-4 text-sm text-gray-800">
                  <img
                    :src="item.user.avatar_img_url"
                    alt="Avatar"
                    class="w-10 h-10 rounded-full"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import overtimeService from '@/services/overtime.service'

// Khai báo các biến cần thiết
const ranking = ref([]) // Dữ liệu bảng xếp hạng
const loading = ref(true) // Biến để theo dõi trạng thái tải dữ liệu

// Hàm gọi API để lấy bảng xếp hạng
onMounted(async () => {
  try {
    const response = await overtimeService.getUserOvertimeRanking()
    ranking.value = response // Gán dữ liệu vào bảng xếp hạng
  } catch (error) {
    console.error('Có lỗi khi tải dữ liệu:', error)
  } finally {
    loading.value = false // Đánh dấu đã tải xong
  }
})
</script>

<style scoped>
/* Tùy chỉnh kiểu dáng cho bảng và các phần tử bên trong */
table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  padding: 12px;
  text-align: left;
}

th {
  background-color: #f7fafc;
}

tr:nth-child(even) {
  background-color: #f9fafb;
}
</style>
