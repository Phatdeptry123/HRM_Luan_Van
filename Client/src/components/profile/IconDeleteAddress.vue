<template>
    <div class="cursor-pointer shadow content-center appearance-none border rounded bg-red-400 justy py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mt-1">
      <font-awesome-icon
        @click="deleteAddress"
        :icon="['fas', 'trash']"
        class=""
      />
    </div>
  </template>
  
  <script>
  import { useAddressStore } from '@/stores/address';
  
  export default {
    props: {
      addressId: {
        type: Number,
        required: true,
      },
    },
    methods: {

      // Method to delete an address
      async deleteAddress() {
        const addressStore = useAddressStore();
        try {
          await addressStore.deleteAddress(this.addressId);
          console.log('Address deleted successfully');
        } catch (error) {
          console.error('Error deleting address:', error);
        }
      },
    },
  };
  </script>
  
  <style scoped>

  </style>
  