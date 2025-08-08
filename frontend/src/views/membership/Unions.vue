<template>
  <div class="card">
    <h2>Unions</h2>
    <div class="row">
      <input v-model="filterName" placeholder="Filter by name" />
      <button @click="load(1)">Search</button>
    </div>
    <div class="space"></div>
    <table>
      <thead><tr><th>ID</th><th>Name</th><th>Code</th></tr></thead>
      <tbody>
        <tr v-for="u in items" :key="u.id">
          <td>{{ u.id }}</td>
          <td>{{ u.attributes.name }}</td>
          <td><span class="badge">{{ u.attributes.code }}</span></td>
        </tr>
      </tbody>
    </table>
    <div class="space"></div>
    <Paginator :meta="meta" @page="load" />
    <div class="space"></div>
    <h3>Create</h3>
    <div class="row">
      <input v-model="form.name" placeholder="Name" />
      <input v-model="form.code" placeholder="Code" />
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
const filterName = ref('')
const form = ref({ name: '', code: '' })
const error = ref('')

async function load(page = 1) {
  error.value = ''
  try {
    const res = await api.getUnions({ page, 'filter[name]': filterName.value })
    items.value = res.data
    meta.value = res.meta
  } catch (e) { error.value = e.message }
}

async function create() {
  error.value = ''
  try {
    await api.createUnion(form.value)
    form.value = { name: '', code: '' }
    await load(meta.value?.current_page || 1)
  } catch (e) { error.value = e.message }
}

onMounted(() => load(1))
</script>
