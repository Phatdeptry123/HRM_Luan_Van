<template>
    <div class="relative py-3" @mouseover="showDropdown = true" @mouseleave="showDropdown = false">
      <div class="">
        <router-link to="/profile" class="mr-4">{{ user.name }}</router-link>
      </div>
      <div v-if="showDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg">
        <ul>
          <li class="p-2 border-b hover:bg-gray-100">
            <router-link to="/profile" class="block">Profile</router-link>
          </li>
          <li class="p-2 hover:bg-gray-100">
            <router-link to="/order" class="block">My Orders</router-link>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      user: {
        type: Object,
        required: true,
      },
    },
    data() {
      return {
        showDropdown: false,
      };
    },
  };
  </script>
  
  <style scoped>
  /* You can add some custom styles here */
  </style>
  