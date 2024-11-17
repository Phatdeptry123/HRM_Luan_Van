<template>
  <div>
    <h1>Task Manager</h1>

    <form @submit.prevent="handleSubmit">
      <input v-model="newTask.title" placeholder="Task Title" />
      <input v-model="newTask.description" placeholder="Task Description" />
      <button type="submit">Create Task</button>
    </form>

    <div v-if="tasks.length">
      <h2>Task List</h2>
      <ul>
        <li v-for="task in tasks" :key="task.id">
          <h3>{{ task.title }}</h3>
          <p>{{ task.description }}</p>
          <button @click="deleteTask(task.id)">Delete</button>
          <button @click="updateStatus(task.id, 'completed')">Mark as Completed</button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import TaskService from '@/services/TaskService'

const tasks = ref([])
const newTask = ref({ title: '', description: '' })

const fetchTasks = async () => {
  try {
    tasks.value = await TaskService.getTaskList()
  } catch (error) {
    console.error('Error fetching tasks:', error)
  }
}

const handleSubmit = async () => {
  try {
    await TaskService.createTask(newTask.value)
    await fetchTasks() // Refresh task list
    newTask.value = { title: '', description: '' }
  } catch (error) {
    console.error('Error creating task:', error)
  }
}

const deleteTask = async (id) => {
  try {
    await TaskService.deleteTask(id)
    await fetchTasks() // Refresh task list
  } catch (error) {
    console.error('Error deleting task:', error)
  }
}

const updateStatus = async (id, status) => {
  try {
    await TaskService.updateTaskStatus(id, status)
    await fetchTasks() // Refresh task list
  } catch (error) {
    console.error('Error updating task status:', error)
  }
}

onMounted(fetchTasks)
</script>
