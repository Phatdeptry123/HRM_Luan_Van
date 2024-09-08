<script setup>
import defaultLayout from '@/views/layouts/DefaultLayout.vue'
import { ModalsContainer } from 'vue-final-modal'
</script>

<template>
  <div class="w-full">
    <defaultLayout>
      <ModalsContainer />
      <router-view class="container mx-auto"/>
    </defaultLayout>
  </div>
</template>

<style></style>
