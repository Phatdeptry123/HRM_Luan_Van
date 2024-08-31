<template>
  <div class="w-full">
    <vHeader />
    <slot></slot>
    <vFooter />
  </div>
</template>

<script setup>
import vHeader from '@/components/layouts/DefaultHeader.vue'
import vFooter from '@/components/layouts/DefaultFooter.vue'
</script>
<style></style>
