<template>
  <div class="card">
    <h2>Locals</h2>
    <div class="row">
      <input v-model="filter.union_id" placeholder="Filter union_id" />
      <button @click="load(1)">Search</button>
    </div>
    <div class="space"></div>
    <table>
      <thead><tr><th>ID</th><th>Union</th><th>Name</th><th>Number</th></tr></thead>
      <tbody>
        <tr v-for="l in items" :key="l.id">
          <td>{{ l.id }}</td>
          <td>{{ l.attributes.union_id }}</td>
          <td>{{ l.attributes.name }}</td>
          <td>{{ l.attributes.number }}</td>
        </tr>
      </tbody>
    </table>
    <div class="space"></div>
    <Paginator :meta="meta" @page="load" />
    <div class="space"></div>
    <h3>Create</h3>
    <div class="row">
      <input v-model.number="form.union_id" placeholder="union_id" />
      <input v-model="form.name" placeholder="name" />
      <input v-model="form.number" placeholder="number" />
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
const filter = ref({ union_id: '' })
const form = ref({ union_id: '', name: '', number: '' })
const error = ref('')

async function load(page = 1) {
  try {
    const res = await api.getLocals({ page, 'filter[union_id]': filter.value.union_id })
    items.value = res.data
    meta.value = res.meta
  } catch (e) { error.value = e.message }
}

async function create() {
  try {
    await api.createLocal(form.value)
    form.value = { union_id: '', name: '', number: '' }
    await load(meta.value?.current_page || 1)
  } catch (e) { error.value = e.message }
}

onMounted(() => load(1))
</script>
