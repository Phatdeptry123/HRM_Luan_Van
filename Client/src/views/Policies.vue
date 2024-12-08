<template>
    <div class="p-8 bg-base-200 min-h-screen">
      <!-- Tiêu đề trang -->
      <div class="text-center mb-6">
        <h1 class="text-4xl font-bold text-primary">Quy Định Làm Việc</h1>
        <p class="text-lg text-gray-600">Các quy định và chính sách công ty</p>
      </div>
  
      <!-- Quy định -->
      <div class="space-y-6">
        <div class="card w-full bg-white shadow-xl">
          <div class="card-body">
            <h2 class="text-xl font-semibold text-primary">1. Giờ làm việc</h2>
            <p class="text-gray-700">
              Giờ làm việc của công ty là từ 8:00 sáng đến 5:00 chiều, từ thứ Hai đến thứ Sáu.
              Đảm bảo rằng bạn đến đúng giờ và tuân thủ lịch làm việc.
            </p>
          </div>
        </div>
  
        <div class="card w-full bg-white shadow-xl">
          <div class="card-body">
            <h2 class="text-xl font-semibold text-primary">2. Quy định về trang phục</h2>
            <p class="text-gray-700">
              Trang phục cần phải lịch sự, phù hợp với môi trường công sở. Đảm bảo vệ sinh và gọn gàng khi đến công ty.
            </p>
          </div>
        </div>
  
        <div class="card w-full bg-white shadow-xl">
          <div class="card-body">
            <h2 class="text-xl font-semibold text-primary">3. Chính sách nghỉ phép</h2>
            <p class="text-gray-700">
              Các nhân viên có thể xin nghỉ phép theo quy định của công ty. Mỗi nhân viên sẽ có số ngày phép cố định trong năm.
              Hãy thông báo trước ít nhất 2 tuần nếu bạn muốn xin nghỉ dài ngày.
            </p>
          </div>
        </div>
  
        <div class="card w-full bg-white shadow-xl">
          <div class="card-body">
            <h2 class="text-xl font-semibold text-primary">4. Quy định về bảo mật thông tin</h2>
            <p class="text-gray-700">
              Bảo mật thông tin là một yếu tố quan trọng. Mọi nhân viên cần tuân thủ quy định về bảo mật để đảm bảo an toàn cho dữ liệu công ty.
            </p>
          </div>
        </div>
  
        <div class="card w-full bg-white shadow-xl">
          <div class="card-body">
            <h2 class="text-xl font-semibold text-primary">5. Đánh giá hiệu quả công việc</h2>
            <p class="text-gray-700">
              Hiệu quả công việc sẽ được đánh giá dựa trên kết quả công việc thực hiện và thái độ làm việc. Các cuộc họp đánh giá sẽ được tổ chức hàng quý.
            </p>
          </div>
        </div>
      </div>
  
      <!-- Nút quay lại -->
      <div class="text-center mt-8">
        <button
          @click="goBack"
          class="btn btn-primary text-white px-6 py-2 rounded-full"
        >
          Quay lại
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useRouter } from 'vue-router'
  
  const router = useRouter()
  
  const goBack = () => {
    router.go(-1)  // Quay lại trang trước đó
  }
  </script>
  
  <style scoped>
  /* Các style tùy chỉnh nếu cần */
  </style>
  