<template>
  <div>
    <div class="flex-grow text-gray-800">
      <main class="p-6 sm:p-10 space-y-6">
        <div class="flex flex-col space-y-6 md:space-y-0 md:flex-row justify-between">
          <div class="mr-6">
            <h1 class="text-4xl font-semibold mb-2">Dashboard</h1>
            <h2 class="text-gray-600 ml-0.5">Mobile UX/UI Design course</h2>
          </div>
          <div class="flex flex-wrap items-start justify-end -mb-3">
            <button
              class="inline-flex px-5 py-3 text-purple-600 hover:text-purple-700 focus:text-purple-700 hover:bg-purple-100 focus:bg-purple-100 border border-purple-600 rounded-md mb-3"
            >
              <svg
                aria-hidden="true"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="flex-shrink-0 h-5 w-5 -ml-1 mt-0.5 mr-2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                />
              </svg>
              Manage dashboard
            </button>
            <button
              class="inline-flex px-5 py-3 text-white bg-purple-600 hover:bg-purple-700 focus:bg-purple-700 rounded-md ml-6 mb-3"
              @click="isVisible = true"
            >
              <font-awesome-icon
                :icon="['fas', 'plus']"
                class="flex-shrink-0 h-6 w-6 text-white -ml-1 mr-2"
              />
              Tạo nhận viên mới
            </button>
          </div>
        </div>
        <section class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
          <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
              class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-purple-600 bg-purple-100 rounded-full mr-6"
            >
              <svg
                aria-hidden="true"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="h-6 w-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
            </div>
            <div>
              <span class="block text-2xl font-bold">{{ countUsers }}</span>
              <span class="block text-gray-500">Nhân viên</span>
            </div>
          </div>
          <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
              class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-green-600 bg-green-100 rounded-full mr-6"
            >
              <font-awesome-icon class="h-6 w-6" :icon="['fas', 'money-bill-trend-up']" />
            </div>
            <div>
              <span class="block text-2xl font-bold">{{ averageSalary.toLocaleString() }}</span>
              <span class="block text-gray-500">Lương trung bình tháng của nhân viên</span>
            </div>
          </div>
          <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
              class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-red-600 bg-red-100 rounded-full mr-6"
            >
              <font-awesome-icon class="h-6 w-6" :icon="['fas', 'user-clock']" />
            </div>
            <div>
              <span class="inline-block text-2xl font-bold">{{ totalOvertime }}</span>
              <span class="block text-gray-500">Tổng số giờ tăng ca</span>
            </div>
          </div>
          <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
              class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-blue-600 bg-blue-100 rounded-full mr-6"
            >
              <svg
                aria-hidden="true"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="h-6 w-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                />
              </svg>
            </div>
            <div>
              <span class="block text-2xl font-bold">83%</span>
              <span class="block text-gray-500">Finished homeworks</span>
            </div>
          </div>
        </section>
        <section class="grid md:grid-cols-2 xl:grid-cols-4 xl:grid-rows-3 xl:grid-flow-col gap-6">
          <div class="flex flex-col md:col-span-2 md:row-span-2 bg-white shadow rounded-lg">
            <div class="px-6 py-5 font-semibold border-b border-gray-100">
              The number of applied and left students per month
            </div>
            <div class="p-4 flex-grow">
              <div
                class="flex items-center justify-center h-full px-4 py-16 text-gray-400 text-3xl font-semibold bg-gray-100 border-2 border-gray-200 border-dashed rounded-md"
              >
                Chart
              </div>
            </div>
          </div>
          <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
              class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-yellow-600 bg-yellow-100 rounded-full mr-6"
            >
              <svg
                aria-hidden="true"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="h-6 w-6"
              >
                <path fill="#fff" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path
                  fill="#fff"
                  d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"
                />
              </svg>
            </div>
            <div>
              <span class="block text-2xl font-bold">25</span>
              <span class="block text-gray-500">Lections left</span>
            </div>
          </div>
          <div class="flex items-center p-8 bg-white shadow rounded-lg">
            <div
              class="inline-flex flex-shrink-0 items-center justify-center h-16 w-16 text-teal-600 bg-teal-100 rounded-full mr-6"
            >
              <svg
                aria-hidden="true"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                class="h-6 w-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
            <div>
              <span class="block text-2xl font-bold">139</span>
              <span class="block text-gray-500">Hours spent on lections</span>
            </div>
          </div>
          <div class="row-span-3 bg-white shadow rounded-lg">
            <div
              class="flex items-center justify-between px-6 py-5 font-semibold border-b border-gray-100"
            >
              <span>Danh sách nhân viên</span>
            </div>
            <div class="overflow-y-auto" style="max-height: 24rem">
              <ul class="p-6 space-y-6">
                <li v-for="user in users" :key="user.id" class="flex items-center">
                  <div class="h-10 w-10 mr-3 bg-gray-100 rounded-full overflow-hidden">
                    <img :src="user.avatar_img_url" :alt="`${user.name} profile picture`" />
                  </div>
                  <span class="text-gray-600">{{ user.name }}</span>
                  <span class="ml-auto font-semibold">{{ user.username }}</span>
                </li>
              </ul>
            </div>
          </div>
          <div class="flex flex-col row-span-3 bg-white shadow rounded-lg">
            <div class="px-6 py-5 font-semibold border-b border-gray-100">
              Students by type of studying
            </div>
            <div class="p-4 flex-grow">
              <div
                class="flex items-center justify-center h-full px-4 py-24 text-gray-400 text-3xl font-semibold bg-gray-100 border-2 border-gray-200 border-dashed rounded-md"
              >
                Chart
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
    <BaseFormModal v-if="isVisible">
      <addUserModal @close="isVisible = false" />
    </BaseFormModal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import userService from '@/services/user.service'
import BaseFormModal from '@/components/modal/BaseFormModal.vue'
import AddUserModal from '@/components/modal/user/AddUserModal.vue'
import salaryService from '@/services/salary.service'
import overtimeService from '@/services/overtime.service'

const isVisible = ref(false)

const countUsers = ref(0)
const users = ref([])
const averageSalary = ref(0)
const totalOvertime = ref(0)

onMounted(async () => {
  const data = await userService.getUsers()
  users.value = data.data
  countUsers.value = users.value.length
  const month = (new Date().getMonth() + 1).toString().padStart(2, '0')
  const year = new Date().getFullYear()
  const res = await salaryService.getAverageSalaryByMonthAndYear(`${year}-${month}`)
  averageSalary.value = Number(res)
  const overtime = await overtimeService.totalOvertimeHoursInMonthForAllUsers()
  totalOvertime.value = overtime
  console.log('totalOvertime', totalOvertime.value)
})
</script>

<style></style>
