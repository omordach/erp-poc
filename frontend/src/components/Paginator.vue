<template>
  <div class="row" v-if="meta">
    <button :disabled="meta.current_page<=1" @click="$emit('page', meta.current_page-1)">Prev</button>
    <span class="muted">Page {{ meta.current_page }} / {{ meta.last_page }}</span>
    <button :disabled="meta.current_page>=meta.last_page" @click="$emit('page', meta.current_page+1)">Next</button>
  </div>
</template>

<script setup>
defineProps({ meta: Object })
defineEmits(['page'])
</script>
