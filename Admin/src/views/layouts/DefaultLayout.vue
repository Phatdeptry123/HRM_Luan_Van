<template>
  <div class="w-full">
    <vHeader />
    <div class="flex">
      <vSidebar />
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
import vHeader from '@/components/layouts/DefaultHeader.vue'
import vSidebar from '@/components/layouts/Sidebar.vue'
</script>
<style></style>
