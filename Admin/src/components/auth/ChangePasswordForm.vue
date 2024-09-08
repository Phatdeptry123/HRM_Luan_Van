<template>
  <ValidationForm @submit="handleChangePassword">
    <!-- Old Password -->
    <div class="mb-4 relative">
      <label for="old_password" class="block text-gray-700 text-sm font-bold mb-2">Old Password</label>
      <div class="relative">
        <Field
          name="old_password"
          :type="showOldPassword ? 'text' : 'password'"
          id="old_password"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pr-10"
          placeholder="Old Password"
          rules="required|min:6|max:50"
          v-model="form.old_password"
        />
        <button type="button" @click="toggleOldPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
          <font-awesome-icon :icon="showOldPassword ? 'eye-slash' : 'eye'" />
        </button>
      </div>
      <div v-if="errors.oldPasswordError" class="text-red-500 text-sm mt-1">{{ errors.oldPasswordError }}</div>
      <ErrorMessage name="old_password" class="text-red-500 text-xs mt-1"/>
    </div>

    <!-- New Password -->
    <div class="mb-4 relative">
      <label for="password" class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
      <div class="relative">
        <Field
          name="password"
          :type="showNewPassword ? 'text' : 'password'"
          id="password"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pr-10"
          placeholder="New Password"
          rules="required|min:6|max:50"
          v-model="form.password"
        />
        <button type="button" @click="toggleNewPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
          <font-awesome-icon :icon="showNewPassword ? 'eye-slash' : 'eye'" />
        </button>
      </div>
      <div v-if="errors.passwordError" class="text-red-500 text-sm mt-1">{{ errors.passwordError }}</div>
      <ErrorMessage name="password" class="text-red-500 text-xs mt-1"/>
    </div>

    <!-- Confirm New Password -->
    <div class="mb-4 relative">
      <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password</label>
      <div class="relative">
        <Field
          name="password_confirmation"
          :type="showPasswordConfirmation ? 'text' : 'password'"
          id="password_confirmation"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pr-10"
          placeholder="Confirm New Password"
          rules="confirmed:@password"
          v-model="form.password_confirmation"
        />
        <button type="button" @click="togglePasswordConfirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
          <font-awesome-icon :icon="showPasswordConfirmation ? 'eye-slash' : 'eye'" />
        </button>
      </div>
      <ErrorMessage name="password_confirmation" class="text-red-500 text-xs mt-1"/>
    </div>

    <!-- Submit and Cancel Buttons -->
    <div class="flex items-center justify-between mt-4">
      <button
        type="submit"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
      >
        Change Password
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
    errors: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      form: {
        old_password: '',
        password: '',
        password_confirmation: ''
      },
      showOldPassword: false,
      showNewPassword: false,
      showPasswordConfirmation: false
    }
  },
  methods: {
    handleChangePassword(values) {
      const passwordData = {
        old_password: values.old_password,
        password: values.password,
        password_confirmation: values.password_confirmation 
      }
      this.$emit('change-password', passwordData)
    },
    toggleOldPassword() {
      this.showOldPassword = !this.showOldPassword;
    },
    toggleNewPassword() {
      this.showNewPassword = !this.showNewPassword;
    },
    togglePasswordConfirmation() {
      this.showPasswordConfirmation = !this.showPasswordConfirmation;
    }
  }
}
</script>

<style>
input[type='password']::-ms-reveal,
input[type='password']::-ms-clear {
  display: none;
}
</style>
