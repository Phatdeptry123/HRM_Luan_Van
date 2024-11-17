// TaskService.js
import api from './api.service'

class TaskService {
  async getTaskList() {
    try {
      const response = await api.get('/tasks')
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getTaskById(id) {
    try {
      const response = await api.get(`/tasks/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async createTask(taskData) {
    try {
      const response = await api.post('/tasks', taskData)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async updateTask(id, taskData) {
    try {
      const response = await api.put(`/tasks/${id}`, taskData)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async deleteTask(id) {
    try {
      const response = await api.delete(`/tasks/${id}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getUserTasks(userId) {
    try {
      const response = await api.get(`/tasks/user/${userId}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async getAssignedTasks(userId) {
    try {
      const response = await api.get(`/tasks/assigned/${userId}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async updateTaskStatus(id, status) {
    try {
      const response = await api.put(`/tasks/${id}/status`, { status })
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async updateAssignedTo(id, assignedTo) {
    try {
      const response = await api.put(`/tasks/${id}/assigned-to`, { assignedTo })
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async updateDueDate(id, dueDate) {
    try {
      const response = await api.put(`/tasks/${id}/due-date`, { dueDate })
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }

  async userOrAssignedTasks(userId) {
    console.log('dsasddsasd', userId)

    try {
      const response = await api.get(`/tasks/userOrAssignedTasks/${userId}`)
      return response.data
    } catch (error) {
      return Promise.reject(error)
    }
  }
}

export default new TaskService()
