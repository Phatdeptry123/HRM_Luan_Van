<template>
  <div class="flex flex-col items-center justify-center h-screen bg-gray-100">
    <h1 class="text-4xl font-bold text-center mt-10">
      Cập nhật khuôn mặt cho {{ fullName }} - {{ userName }}
    </h1>
    <div class="card w-96 bg-base-100 shadow-xl p-4">
      <figure>
        <video ref="video" autoplay class="w-full rounded-lg"></video>
      </figure>
      <div class="card-body items-center">
        <button @click="captureImage" class="btn btn-primary w-full">Chụp Ảnh</button>
        <button @click="closeModal()" class="btn btn-secondary w-full mt-2">Đóng</button>
      </div>
      <canvas ref="canvas" class="hidden"></canvas>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import userService from '@/services/user.service'
import Swal from 'sweetalert2'
const props = defineProps({
  userId: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['closeUpdateFace'])

const video = ref(null)
const canvas = ref(null)

const closeModal = () => {
  emit('closeUpdateFace')
  stopCamera()
}
const startCamera = async () => {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true })
    video.value.srcObject = stream
  } catch (error) {
    console.error('Error accessing camera:', error)
  }
}

const stopCamera = () => {
  const stream = video.value.srcObject
  const tracks = stream.getTracks()
  tracks.forEach((track) => track.stop())
}

const captureImage = () => {
  const videoElement = video.value
  const canvasElement = canvas.value
  canvasElement.width = videoElement.videoWidth
  canvasElement.height = videoElement.videoHeight
  canvasElement.getContext('2d').drawImage(videoElement, 0, 0)

  // Convert canvas image to base64
  const imageData = canvasElement.toDataURL('image/png').replace('data:image/png;base64,', '')

  // Send image to server
  sendImageToServer(imageData)
}

const sendImageToServer = async (imageData) => {
  try {
    await fetch('http://localhost:5000/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ image: imageData, name: userName.value })
    })
    Swal.fire('Thành công', 'Đã cập nhật khuôn mặt', 'success')
    emit('closeUpdateFace')
    stopCamera()
  } catch (error) {
    console.error('Error sending image:', error)
  }
}
const userName = ref('')
const fullName = ref('')
const fetchUserDetails = async () => {
  try {
    const response = await userService.getUser(props.userId)
    userName.value = response.data.username
    fullName.value = response.data.name
  } catch (error) {
    Swal.fire('Lỗi', 'Không thể tải thông tin người dùng', 'error')
  }
}

onMounted(() => {
  startCamera()
  fetchUserDetails()
})
</script>

<style scoped>
/* Custom styling if needed */
</style>
