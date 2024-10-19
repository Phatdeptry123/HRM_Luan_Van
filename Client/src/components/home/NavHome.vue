<template>
  <div>
    <h1 class="text-4xl font-bold text-center mt-10">Home View</h1>
    <SlideBar />
    <div class="grid grid-cols-4 gap-4 p-4">
      <div
        v-for="(item, index) in menuItems"
        :key="index"
        class="flex flex-col items-center p-4 rounded-lg bg-gray-100 cursor-pointer"
        @click="handleItemClick(item)"
      >
        <font-awesome-icon :icon="item.icon" :class="['text-4xl mb-2', item.color]" />
        <span class="text-center text-sm font-medium">{{ item.label }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import SlideBar from '@/components/home/SlideBar.vue'

const router = useRouter()

const menuItems = [
  { label: 'Hồ sơ', icon: 'user', color: 'text-red-500', route: '/profile' },
  { label: 'Bảng lương', icon: 'wallet', color: 'text-blue-500', route: '/salary' },
  { label: 'Chấm công', icon: 'clock', color: 'text-green-500', route: '/attendance' },
  { label: 'Timeoff', icon: 'bolt', color: 'text-yellow-500', route: '/timeoff' },
  { label: 'Việc cần làm', icon: 'check', color: 'text-purple-500', route: '/tasks' },
  { label: 'Tăng ca', icon: 'bullseye', color: 'text-pink-500', route: '/overtime' },
  { label: 'Sự nghiệp', icon: 'briefcase', color: 'text-teal-500', route: '/career' },
  { label: 'Quy định làm việc', icon: 'gavel', color: 'text-orange-500', route: '/policies' }
]

const handleItemClick = (item) => {
  if (item.route) {
    router.push(item.route)
  }
}
</script>
