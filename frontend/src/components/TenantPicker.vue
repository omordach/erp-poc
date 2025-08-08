<template>
  <div class="row">
    <input v-model="tenant" placeholder="tenant key (e.g., opeiu33)" />
    <button @click="save">Set Tenant</button>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { auth } from '../store/auth'

const tenant = ref(auth.tenant || '')

function save() {
  auth.tenant = tenant.value.trim()
  alert(`Tenant set to: ${auth.tenant}`)
}
</script>
