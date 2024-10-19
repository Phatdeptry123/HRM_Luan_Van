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
      path: '/test',
      name: 'test',
      component: () => import('@/components/Test.vue')
    },
    {
      path: '/salary/:id',
      name: 'salary',
      component: () => import('@/views/Salary.vue')
    },
    {
      path: '/payslips',
      name: 'payslips',
      component: () => import('@/components/salary/Payslips.vue')
    },
    {
      path: '/history-payslips',
      name: 'history-payslips',
      component: () => import('@/components/salary/HistoryPayslips.vue')
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
