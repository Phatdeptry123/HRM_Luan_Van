<template>
  <div class="base-form-modal-overlay" @click.self="handleClose">
    <div class="base-form-modal-content max-h-[80vh] overflow-auto">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {}
</script>

<style>
/* Cải thiện giao diện của radio buttons và căn chỉnh nằm ngang */
.radio-group {
  display: flex;
  gap: 1rem; /* Khoảng cách giữa các tùy chọn */
  align-items: center;
}

.radio-container {
  display: flex;
  align-items: center;
}

.radio-label {
  display: flex;
  align-items: center;
  cursor: pointer;
  font-size: 1rem;
  margin-left: 0.5rem; /* Khoảng cách giữa radio và nhãn */
}

.radio-input {
  appearance: none;
  border: 2px solid #d1d5db; /* Màu viền khi chưa được chọn */
  border-radius: 50%;
  width: 1.25rem;
  height: 1.25rem;
  margin-right: 0.5rem;
  transition: border-color 0.2s ease-in-out;
  position: relative;
}

.radio-input:checked {
  border-color: #3b82f6; /* Màu viền khi được chọn */
}

.radio-input:checked::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0.75rem;
  height: 0.75rem;
  background-color: #3b82f6; /* Màu điểm chọn */
  border-radius: 50%;
  transform: translate(-50%, -50%);
}

.base-form-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Màu nền mờ cho overlay */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Đảm bảo modal nổi lên trên cùng */
}

.base-form-modal-content {
  background-color: #f0fdfa; /* Màu nền của modal */
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  width: 100%;
  max-width: 1000px; /* Độ rộng tối đa của modal */
}
</style>
