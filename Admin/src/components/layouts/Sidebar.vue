<template>
  <div class="h-100vh">
    <ul class="menu rounded-box w-60">
      <li v-for="(item, index) in listMenu" :key="index" class="menu-item">
        <router-link :to="item.link" class="menu-link">
          <span class="menu-icon">
            <FontAwesomeIcon :icon="item.icon" />
          </span>
          <span class="menu-title">{{ item.title }}</span>
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const listMenu = ref([
  {
    title: 'Dashboard',
    icon: ['fas', 'list'],
    link: '/'
  },
  {
    title: 'Quản lí nhân viên',
    icon: ['fas', 'list'],
    link: '/home'
  },
  {
    title: 'Quản lí cấp bậc',
    icon: ['fas', 'list'],
    link: '/onlevel'
  },
  {
    title: 'Cập nhật khuôn mặt',
    icon: ['fas', 'list'],
    link: '/updateface'
  },
  {
    title: 'Quản lí chấm công',
    icon: ['fas', 'list'],
    link: '/attendance'
  },
  {
    title: 'Quản lí lương',
    icon: ['fas', 'list'],
    link: '/salary'
  }
])
</script>

<style></style>
