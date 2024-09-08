<template>
  <!-- views/UserProfile.vue -->
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-gray-900">Profile</h2>
      <div v-if="user">
        <ProfileInfo
          v-if="!editing && !changingPassword"
          :user="user"
          @edit="startEditing"
          @change-password="startChangingPassword"
        />
        <ProfileEditForm
          v-else-if="editing"
          :user="user"
          @cancel="cancelEditing"
          @update="updateUser"
          :errors="errors"
        />
        <ChangePasswordForm
          v-else
          @cancel="cancelChangingPassword"
          @change-password="changePassword"
          :errors="errors"
        />
      </div>
      <div v-else>
        <p class="text-red-500">User information not found. Please log in.</p>
      </div>
    </div>
  </div>
</template>

<script>
import { useUserStore } from '@/stores/user'
import userService from '@/services/user.service'
import ProfileInfo from '@/components/profile/ProfileInfo.vue'
import ProfileEditForm from '@/components/profile/ProfileEditForm.vue'
import ChangePasswordForm from '@/components/auth/ChangePasswordForm.vue'
import Swal from 'sweetalert2'
export default {
  components: {
    ProfileInfo,
    ProfileEditForm,
    ChangePasswordForm
  },
  computed: {
    user() {
      return useUserStore().user
    }
  },
  data() {
    return {
      editing: false,
      changingPassword: false,
      errors: {
        nameError: '',
        emailError: '',
        userNameError: '',
        phoneNumberError: '',
        passwordError: '',
        oldPasswordError: '',
        generalError: ''
      }
    }
  },
  methods: {
    startEditing() {
      this.editing = true
    },
    cancelEditing() {
      this.editing = false
      this.clearErrors()
    },
    startChangingPassword() {
      this.changingPassword = true
    },
    cancelChangingPassword() {
      this.changingPassword = false
      this.clearErrors()
    },
    async updateUser(userData) {
      this.clearErrors()
      console.log(this.user, userData)
      try {
        const response = await userService.updateUser(this.user.id, userData)
        Swal.fire('Success', 'Profile updated successfully.', 'success')
        useUserStore().setUser(response.data)
        this.editing = false
      } catch (error) {
        Swal.fire('Error', 'An error occurred. Please try again.', 'error')
        this.handleError(error)
      }
    },
    async changePassword(passwordData) {
      this.clearErrors()
      try {
        const response = await userService.updateUser(this.user.user_id, passwordData)
        useUserStore().setUser(response)
        console.log('Password updated successfully.')
        this.changingPassword = false
      } catch (error) {
        this.handleError(error)
      }
    },
    clearErrors() {
      this.errors = {
        nameError: '',
        emailError: '',
        userNameError: '',
        phoneNumberError: '',
        passwordError: '',
        oldPasswordError: '',
        generalError: ''
      }
    },
    handleError(error) {
      if (error.response && error.response.data) {
        const messages = error.response.data.errors
        if (error.response.data.old_password) {
          this.errors.oldPasswordError = error.response.data.old_password
          console.log('Old password error:', this.errors.oldPasswordError)
        }
        this.errors = {
          nameError: messages.name ? messages.name[0] : '',
          userNameError: messages.user_name ? messages.user_name[0] : '',
          emailError: messages.email ? messages.email[0] : '',
          phoneNumberError: messages.phone_number ? messages.phone_number[0] : '',
          passwordError: messages.password ? messages.password[0] : '',
          oldPasswordError: messages.old_password ? messages.old_password[0] : '',
          generalError: messages.error || ''
        }
        console.log('Error messages:', this.errors)
      } else {
        this.errors.generalError = 'An unexpected error occurred.'
      }
      console.error('An error occurred:', error)
    }
  }
}
</script>
