<template>
  <div class="mx-auto p-6 bg-gray-100 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Quản lí công việc</h2>

    <!-- Button để mở modal -->
    <button @click="showModal = true" class="btn btn-primary mb-6">Tạo task mới</button>

    <!-- Modal hiển thị Form Add Task -->
    <BaseFormModal v-if="showModal">
      <TaskForm @formSubmitted="handleFormSubmitted" @close="showModal = false" />
    </BaseFormModal>

    <!-- Danh sách công việc dạng bảng -->
    <div class="bg-white rounded-lg shadow-md">
      <div class="p-4 bg-gray-50 rounded-t-lg">
        <h3 class="text-xl font-bold">Task List</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
          <thead>
            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
              <th class="py-3 px-6 text-left">Tiêu đề</th>
              <th class="py-3 px-6 text-left">Phụ trách</th>
              <th class="py-3 px-6 text-left">Deadline</th>
              <th class="py-3 px-6 text-center">trạng thái</th>
            </tr>
          </thead>
          <tbody class="text-gray-600 text-sm font-light">
            <tr
              v-for="task in taskList"
              :key="task.id"
              class="border-b border-gray-200 hover:bg-gray-100"
              @click="editTask(task)"
            >
              <td class="py-3 px-6 text-left whitespace-nowrap">
                <div class="flex items-center">
                  <span class="font-medium">{{ task.title }}</span>
                </div>
              </td>
              <td class="py-3 px-6 text-left">
                <span>{{ getUserName(task.assigned_to) }}</span>
              </td>
              <td class="py-3 px-6 text-left">
                <span>{{ task.due_date }}</span>
              </td>
              <td class="py-3 px-6 text-center">
                <span
                  :class="{
                    'bg-blue-200 text-blue-600': task.status === 'in_progress',
                    'bg-green-200 text-green-600': task.status === 'completed',
                    'bg-yellow-200 text-yellow-600': task.status === 'pending',
                    'bg-red-200 text-red-600': task.status === 'cancelled'
                  }"
                  class="py-1 px-3 rounded-full text-xs font-bold"
                >
                  {{ task.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-if="showSidebarEdit">
      <transition name="slide">
        <div
          v-if="showSidebarEdit"
          class="fixed inset-y-0 right-0 w-1/3 bg-white shadow-lg z-50 p-6 overflow-y-auto"
        >
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Edit Task</h3>
            <button @click="closeSidebar" class="text-gray-500 hover:text-gray-800">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Form chỉnh sửa công việc -->
          <form @submit.prevent="submitEditForm">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
              <input
                v-model="currentTask.title"
                type="text"
                class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-300"
                :class="{ 'pointer-events-none': !isEditing }"
                required
              />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
              <textarea
                v-model="currentTask.description"
                :class="{ 'pointer-events-none': !isEditing }"
                class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-300"
              ></textarea>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
              <select
                :class="{ 'pointer-events-none': !isEditing }"
                v-model="currentTask.assigned_to"
                class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-300"
              >
                <option v-for="user in users" :key="user.id" :value="user.id">
                  {{ user.name }}
                </option>
              </select>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
              <input
                :class="{ 'pointer-events-none': !isEditing }"
                v-model="currentTask.due_date"
                type="date"
                class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-300"
                required
              />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
              <select
                v-model="currentTask.status"
                :class="{ 'pointer-events-none': !isEditing }"
                class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-300"
              >
                <option value="todo">To Do</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Overdue</option>
              </select>
            </div>

            <div class="flex justify-between">
              <button
                @click="(showSidebarEdit = false), (isEditing = false)"
                type="button"
                class="btn btn-secondary"
              >
                Cancel
              </button>
              <button v-if="isEditing" type="submit" class="btn btn-primary mr-2">
                Lưu thay đổi
              </button>
              <div v-else>
                <button @click="isEditing = true" class="btn btn-accent mr-2">chỉnh sửa</button>
              </div>
            </div>
          </form>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import BaseFormModal from '@/components/modal/BaseFormModal.vue'
import TaskForm from '@/components/task/ModalAddTask.vue'
import TaskService from '@/services/task.service'
import UserService from '@/services/user.service'

// Trạng thái modal
const showModal = ref(false)
const showSidebarEdit = ref(false)
const isEditing = ref(false)
const taskList = ref([
  {
    id: '',
    title: '',
    description: '',
    assigned_to: '',
    due_date: '',
    status: ''
  }
])
const users = ref([])
const user = JSON.parse(localStorage.getItem('user'))

const currentTask = ref({
  id: '',
  title: '',
  description: '',
  assigned_to: '',
  due_date: '',
  status: ''
})

const editTask = (task) => {
  currentTask.value = { ...task }
  showSidebarEdit.value = true
}

// Lấy danh sách người dùng
const loadUsers = async () => {
  const res = await UserService.getUsers()
  users.value = res.data
}
// Lấy danh sách công việc
const loadTasks = async () => {
  const res = await TaskService.userOrAssignedTasks(user.id)
  taskList.value = res
}

// Xử lý khi form đã được submit
const handleFormSubmitted = () => {
  showModal.value = false
  loadTasks()
}

// Lấy tên người dùng dựa vào id
const getUserName = (userId) => {
  const user = users.value.find((user) => user.id === userId)
  return user ? user.name : ''
}

// Xử lý khi form chỉnh sửa công việc đã được submit
const submitEditForm = async () => {
  await TaskService.updateTask(currentTask.value.id, {
    title: currentTask.value.title,
    assigned_to: currentTask.value.assigned_to,
    due_date: currentTask.value.due_date,
    status: currentTask.value.status
  })
  showSidebarEdit.value = false
  loadTasks()
}

onMounted(() => {
  loadTasks()
  loadUsers()
  console.log('users', user)
})
</script>
