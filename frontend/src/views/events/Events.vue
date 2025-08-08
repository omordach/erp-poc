<template>
  <div class="card">
    <h2>Events</h2>
    <div class="row">
      <input v-model="from" placeholder="from (YYYY-MM-DD)" />
      <input v-model="to" placeholder="to (YYYY-MM-DD)" />
      <button @click="load(1)">Filter</button>
    </div>
    <div class="space"></div>
    <table>
      <thead><tr><th>ID</th><th>Title</th><th>Starts</th><th>Ends</th><th>Location</th></tr></thead>
      <tbody>
        <tr v-for="e in items" :key="e.id">
          <td>{{ e.id }}</td>
          <td>{{ e.attributes.title }}</td>
          <td>{{ e.attributes.starts_at }}</td>
          <td>{{ e.attributes.ends_at }}</td>
          <td>{{ e.attributes.location || '-' }}</td>
        </tr>
      </tbody>
    </table>
    <div class="space"></div>
    <Paginator :meta="meta" @page="load" />

    <div class="space"></div>
    <h3>Create</h3>
    <div class="grid grid-2">
      <input v-model="form.title" placeholder="Title" />
      <input v-model="form.starts_at" placeholder="Starts (YYYY-MM-DD HH:MM:SS)" />
      <input v-model="form.ends_at" placeholder="Ends (optional)" />
      <input v-model="form.location" placeholder="Location (optional)" />
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
const from = ref(''), to = ref('')
const form = ref({ title: '', starts_at: '', ends_at: '', location: '' })

async function load(page = 1) {
  try {
    const res = await api.getEvents({ page, 'filter[from]': from.value, 'filter[to]': to.value })
    items.value = res.data; meta.value = res.meta
  } catch (e) { error.value = e.message }
}

async function create() {
  try {
    await api.createEvent(form.value)
    form.value = { title: '', starts_at: '', ends_at: '', location: '' }
    await load(meta.value?.current_page || 1)
  } catch (e) { error.value = e.message }
}

onMounted(() => load(1))
</script>
