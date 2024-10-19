<template>
  <div class="w-full">
    <vHeader />
    <div class="flex">
      <vSidebar class="w-60" />
      <slot></slot>
    </div>
    <vFooter />
  </div>
</template>

<script setup>
import vHeader from '@/components/layouts/DefaultHeader.vue'
import vSidebar from '@/components/layouts/Sidebar.vue'
import vFooter from '@/components/layouts/DefaultFooter.vue'
</script>
<style></style>
