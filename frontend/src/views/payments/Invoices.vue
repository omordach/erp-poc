<template>
  <div class="card">
    <h2>Invoices</h2>
    <div class="row">
      <select v-model="status">
        <option value="">All</option>
        <option value="draft">draft</option>
        <option value="issued">issued</option>
        <option value="paid">paid</option>
        <option value="void">void</option>
      </select>
      <button @click="load(1)">Filter</button>
    </div>
    <div class="space"></div>
    <table>
      <thead><tr><th>ID</th><th>Number</th><th>Amount</th><th>Status</th><th>Issued</th><th>Due</th></tr></thead>
      <tbody>
        <tr v-for="i in items" :key="i.id">
          <td>{{ i.id }}</td>
          <td>{{ i.attributes.number }}</td>
          <td>${{ i.attributes.amount.toFixed(2) }}</td>
          <td><span class="badge">{{ i.attributes.status }}</span></td>
          <td>{{ i.attributes.issued_at }}</td>
          <td>{{ i.attributes.due_at }}</td>
        </tr>
      </tbody>
    </table>
    <div class="space"></div>
    <Paginator :meta="meta" @page="load" />

    <div class="space"></div>
    <h3>Create</h3>
    <div class="grid grid-2">
      <input v-model="form.number" placeholder="Number" />
      <input v-model="form.amount" placeholder="Amount" />
      <select v-model="form.status">
        <option value="draft">draft</option>
        <option value="issued">issued</option>
        <option value="paid">paid</option>
        <option value="void">void</option>
      </select>
      <input v-model="form.issued_at" placeholder="Issued at (YYYY-MM-DD HH:MM:SS)" />
      <input v-model="form.due_at" placeholder="Due at (YYYY-MM-DD HH:MM:SS)" />
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
const form = ref({ number: '', amount: '0', status: 'draft', issued_at: '', due_at: '' })

async function load(page = 1) {
  try {
    const res = await api.getInvoices({ page, 'filter[status]': status.value })
    items.value = res.data; meta.value = res.meta
  } catch (e) { error.value = e.message }
}

async function create() {
  try {
    await api.createInvoice({ ...form.value, amount: Number(form.value.amount) })
    form.value = { number: '', amount: '0', status: 'draft', issued_at: '', due_at: '' }
    await load(meta.value?.current_page || 1)
  } catch (e) { error.value = e.message }
}

onMounted(() => load(1))
</script>
