import { createRouter, createWebHistory } from 'vue-router'
import { Preferences } from '@capacitor/preferences'
import AttendancePage from '@/views/AttendancePage.vue'
import DashboardPage from '@/views/DashboardPage.vue'
import LoginPage from '@/views/LoginPage.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginPage,
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardPage,
    },
    {
      path: '/attendance',
      name: 'attendance',
      component: AttendancePage,
    },
  ],
})

router.beforeEach(async (to, from, next) => {

  const token = await Preferences.get({ key: 'access_token' })

  if (to.name !== 'login' && token === undefined) next({ name: 'login' })
  else next()
})

export default router
