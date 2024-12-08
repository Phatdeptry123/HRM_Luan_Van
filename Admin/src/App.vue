<script setup>
import defaultLayout from '@/views/layouts/DefaultLayout.vue'
import { ModalsContainer } from 'vue-final-modal'
</script>

<template>
  <div class="w-full min-h-dvh text-stone-500 bg-teal-50">
    <defaultLayout>
      <ModalsContainer />
      <router-view class="container mx-auto" />
    </defaultLayout>
  </div>
</template>

<style></style>
