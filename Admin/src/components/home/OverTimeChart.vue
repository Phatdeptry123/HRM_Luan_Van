<template>
  <div class="flex flex-col md:col-span-2 md:row-span-2 bg-white shadow rounded-lg">
    <div class="px-6 py-5 font-semibold border-b border-gray-100">Thời gian tăng ca hàng tháng</div>
    <div class="p-4 flex-grow">
      <div
        class="flex items-center justify-center px-4 py-16 text-gray-400 text-3xl font-semibold bg-gray-100 border-2 border-gray-200 border-dashed rounded-md"
      >
        <canvas ref="chartCanvas" class="w-full"></canvas>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import overtimeService from '@/services/overtime.service'
import Chart from 'chart.js/auto'

const chartCanvas = ref(null)
let chartInstance = null

onMounted(async () => {
  // Lấy dữ liệu từ API
  const overtime = await overtimeService.getMonthlyOvertimeHours()

  // Lấy dữ liệu cho biểu đồ
  const months = overtime.map((item) => item.month)
  const totalHours = overtime.map((item) => parseInt(item.total_hours))

  // Vẽ biểu đồ khi dữ liệu có sẵn
  if (chartCanvas.value) {
    const ctx = chartCanvas.value.getContext('2d')

    // Nếu có biểu đồ cũ, hủy nó trước khi tạo mới
    if (chartInstance) {
      chartInstance.destroy()
    }

    chartInstance = new Chart(ctx, {
      type: 'bar', // Loại biểu đồ là cột
      data: {
        labels: months, // Nhãn cho trục X (tháng)
        datasets: [
          {
            label: 'tổng thời gian tăng ca tháng',
            data: totalHours, // Dữ liệu cho trục Y (giờ làm thêm)
            backgroundColor: '#4CAF50', // Màu nền cột
            borderColor: '#388E3C', // Màu viền cột
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true // Đảm bảo trục Y bắt đầu từ 0
          }
        }
      }
    })
  }
})
</script>

<style scoped>
canvas {
  max-width: 100%;
  max-height: 100%;
}
</style>
