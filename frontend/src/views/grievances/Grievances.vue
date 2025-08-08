<template>
  <div class="card">
    <h2>Grievances</h2>
    <div class="row">
      <select v-model="status">
        <option value="">All</option>
        <option value="open">open</option>
        <option value="pending">pending</option>
        <option value="closed">closed</option>
      </select>
      <button @click="load(1)">Filter</button>
    </div>
    <div class="space"></div>
    <table>
      <thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Opened</th></tr></thead>
      <tbody>
        <tr v-for="g in items" :key="g.id">
          <td>{{ g.id }}</td>
          <td>{{ g.attributes.title }}</td>
          <td><span class="badge">{{ g.attributes.status }}</span></td>
          <td class="muted">{{ g.attributes.opened_at }}</td>
        </tr>
      </tbody>
    </table>
    <div class="space"></div>
    <Paginator :meta="meta" @page="load" />

    <div class="space"></div>
    <h3>Create</h3>
    <div class="grid grid-2">
      <input v-model="form.title" placeholder="Title" />
      <select v-model="form.status">
        <option value="open">open</option>
        <option value="pending">pending</option>
        <option value="closed">closed</option>
      </select>
      <input v-model="form.description" placeholder="Description (optional)" />
      <button @click="create">Create</button>
      <span class="danger" v-if="error">{{ error }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from '../../api/client'
import Paginator from '../../components/Paginator.vue'

const items = ref([]), meta = ref(null), error = ref('')
const status = ref('')
const form = ref({ title: '', status: 'open', description: '' })

async function load(page = 1) {
  try {
    const res = await api.getGrievances({ page, 'filter[status]': status.value })
    items.value = res.data; meta.value = res.meta
  } catch (e) { error.value = e.message }
}

async function create() {
  try {
    await api.createGrievance(form.value)
    form.value = { title: '', status: 'open', description: '' }
    await load(meta.value?.current_page || 1)
  } catch (e) { error.value = e.message }
}

onMounted(() => load(1))
</script>
