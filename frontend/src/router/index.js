import { createRouter, createWebHistory } from 'vue-router'
import { auth } from '../store/auth'
import Login from '../views/Login.vue'
import Dashboard from '../views/Dashboard.vue'

import Unions from '../views/membership/Unions.vue'
import Locals from '../views/membership/Locals.vue'
import Members from '../views/membership/Members.vue'
import Grievances from '../views/grievances/Grievances.vue'
import Events from '../views/events/Events.vue'
import Invoices from '../views/payments/Invoices.vue'
import Payments from '../views/payments/Payments.vue'

const routes = [
  { path: '/login', component: Login, meta: { public: true } },
  { path: '/', component: Dashboard },
  { path: '/membership/unions', component: Unions },
  { path: '/membership/locals', component: Locals },
  { path: '/membership/members', component: Members },
  { path: '/grievances', component: Grievances },
  { path: '/events', component: Events },
  { path: '/payments/invoices', component: Invoices },
  { path: '/payments/payments', component: Payments },
]

const router = createRouter({ history: createWebHistory(), routes })

router.beforeEach((to) => {
  if (to.meta.public) return true
  if (!auth.isAuthenticated()) {
    return { path: '/login', query: { redirect: to.fullPath } }
  }
  return true
})

export default router
