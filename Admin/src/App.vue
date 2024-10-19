<script setup>
import defaultLayout from '@/views/layouts/DefaultLayout.vue'
import { ModalsContainer } from 'vue-final-modal'
import BaseDarkButton from '@/components/common/BaseDarkButton.vue'
import BaseLightButton from '@/components/common/BaseLightButton.vue'
</script>

<template>
  <div class="w-full text-stone-500 bg-teal-50">
    <defaultLayout>
      <ModalsContainer />
      <router-view class="container mx-auto" />
    </defaultLayout>
  </div>
</template>

<style></style>
