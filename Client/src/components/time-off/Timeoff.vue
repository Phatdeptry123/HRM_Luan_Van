<template>
  <div class="p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-lg font-bold">Danh sách đề xuất</h1>
      <div class="flex space-x-2">
        <button class="text-gray-600"><font-awesome-icon :icon="['far', 'calendar']" /></button>
        <button class="text-gray-600"><font-awesome-icon :icon="['fas', 'sliders']" /></button>
      </div>
    </div>

    <!-- Tabs -->
    <div class="flex justify-around mb-4">
      <button
        class="px-4 py-2"
        :class="activeTab === 'myRequests' ? 'bg-blue-600 text-white' : 'text-gray-500 border'"
        @click="setActiveTab('myRequests')"
      >
        Đề xuất của tôi
      </button>
      <button
        class="px-4 py-2"
        :class="activeTab === 'sentToMe' ? 'bg-blue-600 text-white' : 'text-gray-500 border'"
        @click="setActiveTab('sentToMe')"
      >
        Gửi đến tôi
      </button>
      <button
        class="px-4 py-2"
        :class="activeTab === 'history' ? 'bg-blue-600 text-white' : 'text-gray-500 border'"
        @click="setActiveTab('history')"
      >
        Lịch sử
      </button>
    </div>

    <!-- List Đề xuất của tôi -->
    <div v-if="activeTab === 'myRequests' && pendingRequests.length" class="space-y-4">
      <div
        v-for="request in pendingRequests"
        :key="request.id"
        class="flex justify-between items-center p-4 border-b"
      >
        <div class="flex items-center space-x-4">
          <img
            :src="'/path/to/avatar/' + request.user_id + '.jpg'"
            alt="User Avatar"
            class="w-12 h-12 rounded-full"
          />
          <div>
            <h2 class="font-semibold">{{ user.name }} - {{ request.type }}</h2>
            <p class="text-sm text-gray-500">{{ request.description }}</p>
          </div>
        </div>
        <div class="text-right">
          <span class="text-yellow-600 font-bold">Chưa duyệt</span>
          <p class="text-sm text-gray-500">{{ formatDate(request.request_date) }}</p>
        </div>
      </div>
    </div>

    <!-- List Gửi đến tôi -->
    <div v-if="activeTab === 'sentToMe' && pendingRequestsSentToMe.length" class="space-y-4">
      <div
        v-for="request in pendingRequestsSentToMe"
        :key="request.id"
        class="flex justify-between items-center p-4 border-b"
      >
        <div class="flex items-center space-x-4">
          <img
            :src="'/path/to/avatar/' + request.user_id + '.jpg'"
            alt="User Avatar"
            class="w-12 h-12 rounded-full"
          />
          <div>
            <h2 class="font-semibold">{{ request.user.name }} - {{ request.type }}</h2>
            <p class="text-sm text-gray-500">{{ request.description }}</p>
          </div>
        </div>
        <div class="text-right">
          <span class="text-yellow-600 font-bold mr-4">Chưa duyệt</span>
          <button
            class="text-blue-600 border border-blue-600 btn-sm mr-4 w-20"
            @click="confirmAction('approve', request.id)"
          >
            Duyệt
          </button>
          <button
            class="text-red-600 border border-red-600 btn-sm w-20"
            @click="confirmAction('reject', request.id)"
          >
            Từ chối
          </button>
          <p class="text-sm text-gray-500">{{ formatDate(request.request_date) }}</p>
        </div>
      </div>
    </div>

    <!-- List Lịch sử -->
    <div v-if="activeTab === 'history' && pastRequests.length" class="space-y-4">
      <div
        v-for="request in pastRequests"
        :key="request.id"
        class="flex justify-between items-center p-4 border-b"
      >
        <div class="flex items-center space-x-4">
          <img
            :src="'/path/to/avatar/' + request.user_id + '.jpg'"
            alt="User Avatar"
            class="w-12 h-12 rounded-full"
          />
          <div>
            <h2 class="font-semibold">{{ request.user.name }} - {{ request.type }}</h2>
            <p class="text-sm text-gray-500">{{ request.description }}</p>
          </div>
        </div>
        <div class="text-right">
          <span
            :class="{
              'text-green-600 font-bold': request.status === 'approved',
              'text-red-600 font-bold': request.status === 'rejected'
            }"
          >
            {{ request.status === 'approved' ? 'Đã duyệt' : 'Từ chối' }}
          </span>
          <p class="text-sm text-gray-500">{{ formatDate(request.request_date) }}</p>
        </div>
      </div>
    </div>

    <!-- Nếu không có request -->
    <div
      v-if="!pendingRequests.length && activeTab === 'myRequests'"
      class="text-center text-gray-500"
    >
      Không có đề xuất nào.
    </div>
    <div
      v-if="!pendingRequestsSentToMe.length && activeTab === 'sentToMe'"
      class="text-center text-gray-500"
    >
      Không có đề xuất nào gửi đến bạn.
    </div>
    <div v-if="!pastRequests.length && activeTab === 'history'" class="text-center text-gray-500">
      Không có lịch sử đề xuất.
    </div>

    <!-- Footer -->
    <div class="w-full bg-white p-4 border-t mt-4">
      <div class="flex justify-between mb-4">
        <div class="text-center">
          <p class="text-sm text-gray-500">Số ngày phép năm</p>
          <p class="text-xl font-bold">0.00</p>
        </div>
        <div class="text-center">
          <p class="text-sm text-gray-500">Số ngày phép bù</p>
          <p class="text-xl font-bold">0.00</p>
        </div>
      </div>
      <div class="flex justify-center">
        <button @click="openModal()" class="w-6/12 bg-blue-600 text-white py-2 rounded">
          Tạo đề xuất
        </button>
      </div>
    </div>

    <BaseModal v-if="isOpenModal">
      <AddRequestModal @closeModal="closeModal()" />
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import BaseModal from '@/components/modal/BaseFormModal.vue'
import AddRequestModal from '@/components/time-off/AddRequestModel.vue'
import RequestService from '@/services/request.service'
import { useUserStore } from '@/stores/user'
import Swal from 'sweetalert2'

const userStore = useUserStore()
const user = userStore.user
const requests = ref([])
const requestsSentToMe = ref([])
const activeTab = ref('myRequests')
const isOpenModal = ref(false)

const openModal = () => {
  isOpenModal.value = true
}

const closeModal = () => {
  isOpenModal.value = false
  fetchRequests()
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const setActiveTab = (tab) => {
  activeTab.value = tab
}

const fetchRequests = () => {
  RequestService.getRequestsForUser(user.id).then((res) => {
    requests.value = res
  })

  RequestService.getRequestsForManager(user.id).then((res) => {
    requestsSentToMe.value = res
  })
}

const pendingRequests = computed(() => {
  return requests.value.filter((request) => request.status === 'pending')
})

const pendingRequestsSentToMe = computed(() => {
  return requestsSentToMe.value.filter((request) => request.status === 'pending')
})

const pastRequests = computed(() => {
  return [...requests.value, ...requestsSentToMe.value].filter(
    (request) => request.status === 'approved' || request.status === 'rejected'
  )
})

const confirmAction = (action, id) => {
  Swal.fire({
    title: 'Bạn có chắc chắn?',
    text: `Bạn có muốn ${action === 'approve' ? 'duyệt' : 'từ chối'} đề xuất này không?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Có',
    cancelButtonText: 'Không'
  }).then((result) => {
    if (result.isConfirmed) {
      if (action === 'approve') {
        approveRequest(id)
      } else {
        rejectRequest(id)
      }
    }
  })
}

const approveRequest = (id) => {
  try {
    RequestService.approveRequest(id).then(() => {
      fetchRequests()
    })
    Swal.fire('Thành công', 'Duyệt đề xuất thành công', 'success')
  } catch (error) {
    Swal.fire('Lỗi', 'Có lỗi xảy ra khi duyệt đề xuất', 'error')
  }
}

const rejectRequest = (id) => {
  try {
    RequestService.rejectRequest(id).then(() => {
      fetchRequests()
    })
    Swal.fire('Thành công', 'Từ chối đề xuất thành công', 'success')
  } catch (error) {
    Swal.fire('Lỗi', 'Có lỗi xảy ra khi từ chối đề xuất', 'error')
  }
}

onMounted(() => {
  fetchRequests()
})
</script>
