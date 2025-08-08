<template>
  <div class="card">
    <h2>Members</h2>
    <div class="row">
      <input v-model="filters.q" placeholder="Search name/email" />
      <input v-model="filters.local_id" placeholder="Filter local_id" />
      <button @click="load(1)">Search</button>
    </div>
    <div class="space"></div>
    <table>
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Local</th></tr></thead>
      <tbody>
        <tr v-for="m in items" :key="m.id">
          <td>{{ m.id }}</td>
          <td>{{ m.attributes.first_name }} {{ m.attributes.last_name }}</td>
          <td>{{ m.attributes.email }}</td>
          <td>{{ m.attributes.local_id }}</td>
        </tr>
      </tbody>
    </table>
    <div class="space"></div>
    <Paginator :meta="meta" @page="load" />

    <div class="space"></div>
    <h3>Create</h3>
    <div class="grid grid-2">
      <input v-model="form.first_name" placeholder="First name" />
      <input v-model="form.last_name" placeholder="Last name" />
      <input v-model="form.email" placeholder="Email" />
      <input v-model="form.local_id" placeholder="Local ID" />
      <button @click="create">Create</button>
      <span class="danger" v-if="error">{{ error }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from '../../api/client'
import Paginator from '../../components/Paginator.vue'

const items = ref([])
const meta = ref(null)
const filters = ref({ q: '', local_id: '' })
const form = ref({ first_name: '', last_name: '', email: '', local_id: '' })
const error = ref('')

async function load(page = 1) {
  try {
    const res = await api.getMembers({
      page,
      'filter[q]': filters.value.q,
      'filter[local_id]': filters.value.local_id
    })
    items.value = res.data
    meta.value = res.meta
  } catch (e) { error.value = e.message }
}

async function create() {
  try {
    await api.createMember(form.value)
    form.value = { first_name: '', last_name: '', email: '', local_id: '' }
    await load(meta.value?.current_page || 1)
  } catch (e) { error.value = e.message }
}

onMounted(() => load(1))
</script>
