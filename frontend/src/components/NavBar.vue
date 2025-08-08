<template>
  <div style="background:#fafafa;border-bottom:1px solid #eee">
    <div class="container row" style="justify-content:space-between;">
      <div class="row">
        <RouterLink to="/" class="row" style="font-weight:700">UnionImpact</RouterLink>
        <div class="row" style="gap:1rem;margin-left:1rem;">
          <RouterLink to="/membership/unions">Unions</RouterLink>
          <RouterLink to="/membership/locals">Locals</RouterLink>
          <RouterLink to="/membership/members">Members</RouterLink>
          <RouterLink to="/grievances">Grievances</RouterLink>
          <RouterLink to="/events">Events</RouterLink>
          <RouterLink to="/payments/invoices">Invoices</RouterLink>
          <RouterLink to="/payments/payments">Payments</RouterLink>
        </div>
      </div>
      <div class="row">
        <TenantPicker />
        <div class="space"></div>
        <template v-if="isAuth">
          <span class="badge">Logged in</span>
          <button @click="logout">Logout</button>
        </template>
        <template v-else>
          <RouterLink to="/login">Login</RouterLink>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { auth } from '../store/auth'
import { useRouter, RouterLink } from 'vue-router'
import TenantPicker from './TenantPicker.vue'

const router = useRouter()
const isAuth = computed(() => auth.isAuthenticated())

function logout() {
  auth.clear()
  router.push('/login')
}
</script>
