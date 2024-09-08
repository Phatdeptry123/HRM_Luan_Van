<template>
  <ValidationForm @submit="handleUpdate">
    <!-- Name -->
    <div class="mb-4">
      <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Tên</label>
      <Field
        name="name"
        type="text"
        id="name"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        placeholder="Name"
        rules="required|min:3|max:50"
        v-model="form.name"
      />
      <ErrorMessage name="name" class="text-red-500 text-xs mt-1" />
      <div v-if="errors.nameError" class="mt-4 text-red-500">{{ errors.nameError }}</div>
    </div>

    <!-- User Name -->
    <div class="mb-4">
      <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Mã Nhân Viên</label>
      <Field
        name="username"
        type="text"
        id="username"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        placeholder="User Name"
        rules="required|min:3|max:50"
        v-model="form.username"
      />
      <ErrorMessage name="username" class="text-red-500 text-xs mt-1" />
      <div v-if="errors.userNameError" class="mt-4 text-red-500">{{ errors.userNameError }}</div>
    </div>

    <!-- Email -->
    <div class="mb-4">
      <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
      <Field
        name="email"
        type="email"
        id="email"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        placeholder="Email"
        rules="required|email|max:50"
        v-model="form.email"
      />
      <ErrorMessage name="email" class="text-red-500 text-xs mt-1" />
      <div v-if="errors.emailError" class="mt-4 text-red-500">{{ errors.emailError }}</div>
    </div>

    <!-- Phone Number -->
    <div class="mb-4">
      <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Số điện thoại</label>
      <Field
        name="phone"
        type="text"
        id="phone"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        placeholder="Phone Number"
        rules="required|numeric|min:10|max:11"
        v-model="form.phone"
      />
      <ErrorMessage name="phone" class="text-red-500 text-xs mt-1" />
      <div v-if="errors.phoneNumberError" class="mt-4 text-red-500">
        {{ errors.phoneNumberError }}
      </div>
    </div>

    <div class="mb-4">
      <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Địa chỉ</label>
      <Field
        name="address"
        type="text"
        id="address"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        placeholder="address"
        rules="required"
        v-model="form.address"
      />
      <ErrorMessage name="address" class="text-red-500 text-xs mt-1" />
      <div v-if="errors.address" class="mt-4 text-red-500">{{ errors.address }}</div>
    </div>

    <!-- Submit and Cancel Buttons -->
    <div class="flex items-center justify-between mt-4">
      <button
        type="submit"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
      >
        Update
      </button>
      <button
        @click="$emit('cancel')"
        type="button"
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
      >
        Cancel
      </button>
    </div>

    <!-- General Error -->
    <div v-if="errors.generalError" class="mt-4 text-red-500">{{ errors.generalError }}</div>
  </ValidationForm>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      required: true
    },
    errors: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      form: {
        name: this.user.name || '',
        username: this.user.username || '',
        email: this.user.email || '',
        phone: this.user.phone || '',
        address: this.user.address || ''
      }
    }
  },
  methods: {
    handleUpdate(values) {
      const updateData = {
        name: values.name,
        username: values.username,
        email: values.email,
        phone: values.phone,
        address: values.address
      }
      this.$emit('update', updateData)
    }
  }
}
</script>
