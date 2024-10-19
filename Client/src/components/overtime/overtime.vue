<template>
  <div class="p-4">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-lg font-bold">Đề xuất tăng ca</h1>
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
            <h2 class="font-semibold">{{ user.name }} - {{ request.request_hour }} giờ</h2>
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
            <h2 class="font-semibold">{{ request.user.name }} - {{ request.request_hour }} giờ</h2>
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
            <h2 class="font-semibold">{{ request.user.name }} - {{ request.request_hour }} giờ</h2>
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

    <!-- Không có đề xuất -->
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

    <div class="w-full bg-white p-4 border-t mt-4">
      <div class="flex justify-between mb-4">
        <div class="text-center">
          <p class="text-sm text-gray-500">Số giờ tăng ca tháng này</p>
          <p class="text-xl font-bold">{{ countOvertimeHours }} giờ</p>
        </div>
        <div class="text-center">
          <p class="text-sm text-gray-500">Tổng lương tăng ca</p>
          <p class="text-xl font-bold">{{ overtimeSalary }} vnđ</p>
        </div>
      </div>
      <div class="flex justify-center">
        <button @click="openModal()" class="w-6/12 bg-blue-600 text-white py-2 rounded">
          Tạo đề xuất tăng ca
        </button>
      </div>
    </div>

    <BaseModal v-if="isOpenModal">
      <AddOvertimeRequestModel @closeModal="closeModal()" />
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import BaseModal from '@/components/modal/BaseFormModal.vue'
import AddOvertimeRequestModel from '@/components/overtime/AddOvertimeRequestModel.vue'
import { useUserStore } from '@/stores/user'
import OvertimeService from '@/services/overtime.service'
import SalaryService from '@/services/salary.service'
import Swal from 'sweetalert2'

const userStore = useUserStore()
const user = userStore.user
const overtimeRequests = ref([])
const overtimeRequestsSentToMe = ref([])
const activeTab = ref('myRequests')

const isOpenModal = ref(false)

const countOvertimeHours = computed(() => {
  return overtimeRequests.value
    .filter((request) => request.status === 'approved')
    .reduce((acc, request) => acc + request.request_hour, 0)
})

const pendingRequests = computed(() => {
  return overtimeRequests.value.filter((request) => request.status === 'pending')
})

const pendingRequestsSentToMe = computed(() => {
  return overtimeRequestsSentToMe.value.filter((request) => request.status === 'pending')
})

const pastRequests = computed(() => {
  return [...overtimeRequests.value, ...overtimeRequestsSentToMe.value].filter(
    (request) => request.status === 'approved' || request.status === 'rejected'
  )
})

const salary = ref({})

const overtimeSalary = computed(() => {
  return Math.round(
    (salary.value.basic_salary / 26 / 8) * 1.5 * countOvertimeHours.value
  ).toLocaleString()
})
const fetchSalary = async () => {
  await SalaryService.getSalaryByUserId(user.id)
    .then((res) => {
      salary.value = res
    })
    .catch((err) => {
      console.log(err)
    })

  console.log('salary', salary.value)
}
const openModal = () => {
  isOpenModal.value = true
}

const closeModal = () => {
  fetchOvertimeRequests()
  isOpenModal.value = false
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const setActiveTab = (tab) => {
  activeTab.value = tab
}

const fetchOvertimeRequests = async () => {
  await OvertimeService.getovertimesForUser(user.id)
    .then((res) => {
      overtimeRequests.value = res
    })
    .catch((err) => {
      console.log(err)
    })

  await OvertimeService.getovertimesForManager(user.id).then((res) => {
    overtimeRequestsSentToMe.value = res
  })
}

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
    OvertimeService.approveOvertime(id).then(() => {
      fetchOvertimeRequests()
    })
    Swal.fire('Thành công', 'Duyệt đề xuất thành công', 'success')
  } catch (error) {
    Swal.fire('Lỗi', 'Có lỗi xảy ra khi duyệt đề xuất', 'error')
  }
}

const rejectRequest = (id) => {
  try {
    OvertimeService.rejectOvertime(id).then(() => {
      fetchOvertimeRequests()
    })
    Swal.fire('Thành công', 'Từ chối đề xuất thành công', 'success')
  } catch (error) {
    Swal.fire('Lỗi', 'Có lỗi xảy ra khi từ chối đề xuất', 'error')
  }
}

onMounted(async () => {
  await fetchOvertimeRequests()
  await fetchSalary()
  console.log('overtimeRequests', overtimeRequests.value)
  console.log('overtimeRequestsSentToMe', overtimeRequestsSentToMe.value)

  console.log('pastRequests', pastRequests.value)
})
</script>
