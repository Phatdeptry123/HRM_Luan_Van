import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/stores/user'
import HomeView from '../views/HomeView.vue'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/components/auth/FormLogin.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/components/auth/FormRegister.vue')
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('@/views/Profile.vue')
    },
    {
      path: '/timeoff',
      name: 'timeoff',
      component: () => import('@/views/TimeOff.vue')
    },
    {
      path: '/overtime',
      name: 'overtime',
      component: () => import('@/components/overtime/overtime.vue')
    },
    {
      path: '/attendance',
      name: 'attendance',
      component: () => import('@/components/attendance/Attendance.vue')
    },
    {
      path: '/salary',
      name: 'salary',
      component: () => import('@/components/salary/Salary.vue')
    }
  ]
})
router.beforeEach((to, from, next) => {
  const publicPages = ['/login', '/register']
  const authRequired = !publicPages.includes(to.path)
  const userStore = useUserStore()

  if (authRequired && !userStore.token) {
    return next({ path: '/login', query: { returnUrl: to.fullPath } })
  }
  next()
})

export default router
