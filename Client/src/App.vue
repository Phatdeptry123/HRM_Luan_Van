<script setup>
import defaultLayout from '@/views/layouts/DefaultLayout.vue'
import { ModalsContainer } from 'vue-final-modal'
</script>

<template>
  <div class="w-full">
    <defaultLayout>
      <ModalsContainer />
      <router-view class="container mx-auto" />
    </defaultLayout>
  </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>
