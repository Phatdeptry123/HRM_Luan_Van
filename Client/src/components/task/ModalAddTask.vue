<template>
  <div class="space-y-4">
    <button @click="abc" class="btn btn-sm btn-outline">Close</button>
    <div>
      <label class="block font-medium mb-2">Tiêu đề</label>
      <input
        type="text"
        v-model="task.title"
        class="input input-bordered w-full"
        placeholder="Enter task title"
        required
      />
    </div>

    <div>
      <label class="block font-medium mb-2">Description</label>
      <textarea
        v-model="task.description"
        class="textarea textarea-bordered w-full"
        placeholder="Enter task description"
      ></textarea>
    </div>

    <div>
      <label class="block font-medium mb-2">Assigned To</label>
      <multiselect
        v-model="task.assigned_to"
        :options="users"
        :searchable="true"
        :close-on-select="true"
        :clear-on-select="false"
        placeholder="Select a user"
        label="name"
        track-by="id"
        class="w-full"
      />
    </div>

    <div>
      <label class="block font-medium mb-2">Due Date</label>
      <input type="date" v-model="task.due_date" class="input input-bordered w-full" />
    </div>

    <div>
      <label class="block font-medium mb-2">Status</label>
      <select v-model="task.status" class="select select-bordered w-full">
        <option value="todo">To Do</option>
        <option value="pending">Pending</option>
        <option value="in_progress">In Progress</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <button @click="submitForm" class="btn btn-primary w-full">Save Task</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import TaskService from '@/services/task.service'
import UserService from '@/services/user.service'

const emit = defineEmits(['formSubmitted', 'close'])
const task = ref({
  title: '',
  description: '',
  assigned_to: '',
  due_date: '',
  status: 'todo'
})

const users = ref([])
const user = JSON.parse(localStorage.getItem('user'))

onMounted(async () => {
  await loadUsers()
  console.log('user', user)
})

const abc = () => {
  emit('close')
}

const loadUsers = async () => {
  const res = await UserService.getUsers()
  users.value = res.data
}

const submitForm = async () => {
  if (task.value.id) {
    await TaskService.updateTask(task.value.id, {
      title: task.value.title,
      description: task.value.description,
      assigned_to: task.value.assigned_to.id,
      created_by: user.id,
      due_date: task.value.due_date,
      status: task.value.status
    })
  } else {
    await TaskService.createTask({
      title: task.value.title,
      description: task.value.description,
      assigned_to: task.value.assigned_to.id,
      created_by: user.id,
      due_date: task.value.due_date,
      status: task.value.status
    })
  }
  // Emit event để thông báo form đã được submit
  resetForm()
  emit('formSubmitted')
}

const resetForm = () => {
  task.value = {
    title: '',
    description: '',
    assigned_to: '',
    due_date: '',
    status: 'todo'
  }
}
</script>
