<template>
  <div class="container" style="max-width:420px">
    <div class="card">
      <h2>Login</h2>
      <p class="muted">Set tenant key first (top-right). Then use demo creds.</p>
      <div class="space"></div>
      <form @submit.prevent="submit">
        <div class="grid">
          <input v-model="email" type="email" placeholder="Email" required />
          <input v-model="password" type="password" placeholder="Password" required />
          <button type="submit">Get Token</button>
        </div>
      </form>
      <div class="space"></div>
      <p class="muted">Demo: admin@example.test / password</p>
      <p v-if="error" class="danger">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { api } from '../api/client'
import { auth } from '../store/auth'
import { useRouter, useRoute } from 'vue-router'

const email = ref('admin@example.test')
const password = ref('password')
const error = ref('')
const router = useRouter()
const route = useRoute()

async function submit() {
  error.value = ''
  try {
    if (!auth.tenant) {
      error.value = 'Please set tenant key (e.g., opeiu33) using the top-right input.'
      return
    }
    const res = await api.login(email.value, password.value)
    auth.token = res?.data?.attributes?.token || ''
    if (!auth.token) throw new Error('No token in response')
    router.replace(route.query.redirect || '/')
  } catch (e) {
    error.value = e.message
  }
}
</script>
