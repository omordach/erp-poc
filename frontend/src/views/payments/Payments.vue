<template>
  <div class="card">
    <h2>Payments</h2>
    <div class="row">
      <input v-model="invoiceId" placeholder="Filter by invoice_id" />
      <button @click="load(1)">Filter</button>
    </div>
    <div class="space"></div>
    <table>
      <thead><tr><th>ID</th><th>Invoice</th><th>Amount</th><th>Paid At</th><th>Method</th></tr></thead>
      <tbody>
        <tr v-for="p in items" :key="p.id">
          <td>{{ p.id }}</td>
          <td>{{ p.attributes.invoice_id }}</td>
          <td>${{ p.attributes.amount.toFixed(2) }}</td>
          <td>{{ p.attributes.paid_at }}</td>
          <td>{{ p.attributes.method }}</td>
        </tr>
      </tbody>
    </table>
    <div class="space"></div>
    <Paginator :meta="meta" @page="load" />

    <div class="space"></div>
    <h3>Create</h3>
    <div class="grid grid-2">
      <input v-model="form.invoice_id" placeholder="Invoice ID" />
      <input v-model="form.amount" placeholder="Amount" />
      <input v-model="form.paid_at" placeholder="Paid at (YYYY-MM-DD HH:MM:SS)" />
      <input v-model="form.method" placeholder="Method" />
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
const invoiceId = ref('')
const form = ref({ invoice_id: '', amount: '0', paid_at: '', method: '' })

async function load(page = 1) {
  try {
    const res = await api.getPayments({ page, 'filter[invoice_id]': invoiceId.value })
    items.value = res.data; meta.value = res.meta
  } catch (e) { error.value = e.message }
}

async function create() {
  try {
    await api.createPayment({ ...form.value, amount: Number(form.value.amount) })
    form.value = { invoice_id: '', amount: '0', paid_at: '', method: '' }
    await load(meta.value?.current_page || 1)
  } catch (e) { error.value = e.message }
}

onMounted(() => load(1))
</script>
