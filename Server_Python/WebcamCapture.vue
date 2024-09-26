<template>
  <div class="flex flex-col items-center justify-center h-screen bg-gray-100">
    <div class="card w-96 bg-base-100 shadow-xl p-4">
      <figure>
        <video ref="video" autoplay class="w-full rounded-lg"></video>
      </figure>
      <div class="card-body items-center">
        <input 
          type="text" 
          v-model="name" 
          placeholder="Nhập tên của bạn" 
          class="input input-bordered w-full my-2"
        />
        <button 
          @click="captureImage" 
          class="btn btn-primary w-full"
        >
          Chụp Ảnh
        </button>
      </div>
      <canvas ref="canvas" class="hidden"></canvas>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const video = ref(null);
const canvas = ref(null);
const name = ref('');

const startCamera = async () => {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.value.srcObject = stream;
  } catch (error) {
    console.error('Error accessing camera:', error);
  }
};

const captureImage = () => {
  const videoElement = video.value;
  const canvasElement = canvas.value;
  canvasElement.width = videoElement.videoWidth;
  canvasElement.height = videoElement.videoHeight;
  canvasElement.getContext('2d').drawImage(videoElement, 0, 0);

  // Convert canvas image to base64
  const imageData = canvasElement.toDataURL('image/png').replace('data:image/png;base64,', '');

  // Send image to server
  sendImageToServer(imageData);
};

const sendImageToServer = async (imageData) => {
  try {
    await fetch('http://localhost:5000/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ image: imageData, name: name.value })
    });
    alert('Image sent successfully!');
  } catch (error) {
    console.error('Error sending image:', error);
  }
};

onMounted(() => {
  startCamera();
});
</script>

<style scoped>
/* Custom styling if needed */
</style>
