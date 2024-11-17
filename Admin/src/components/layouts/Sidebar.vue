<template>
  <div class="fixed backdrop-blur-2xl h-dvh hover:w-60 w-14 overflow-hidden duration-300 z-10 mt-1">
    <ul class="menu rounded-box">
      <li v-for="(item, index) in listMenu" :key="index" class="menu-item">
        <router-link :to="item.link" class="menu-link">
          <span class="menu-icon">
            <FontAwesomeIcon :icon="item.icon" class="size-6" />
          </span>
          <span class="menu-title text-nowrap">{{ item.title }}</span>
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { ref } from 'vue'

const listMenu = ref([
  {
    title: 'Dashboard',
    icon: ['fas', 'list'],
    link: '/'
  },
  {
    title: 'Quản lí nhân viên',
    icon: ['fas', 'users'],
    link: '/employments'
  },
  {
    title: 'Quản lí cấp bậc',
    icon: ['fas', 'users-line'],
    link: '/onlevel'
  },
  {
    title: 'Cập nhật khuôn mặt',
    icon: ['fas', 'user-shield'],
    link: '/updateface'
  },
  {
    title: 'Quản lí chấm công',
    icon: ['fas', 'user-clock'],
    link: '/attendance'
  },
  {
    title: 'Quản lí lương',
    icon: ['fas', 'hand-holding-dollar'],
    link: '/payslips'
  }
])
</script>

<style></style>
