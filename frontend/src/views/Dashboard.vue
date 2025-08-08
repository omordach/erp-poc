<template>
  <div class="grid grid-3">
    <div class="card">
      <h3>Membership</h3>
      <p class="muted">Manage unions, locals, and members.</p>
      <RouterLink to="/membership/members">Open Members</RouterLink>
    </div>
    <div class="card">
      <h3>Grievances</h3>
      <p class="muted">Track and update grievance cases.</p>
      <RouterLink to="/grievances">Open Grievances</RouterLink>
    </div>
    <div class="card">
      <h3>Events</h3>
      <p class="muted">Calendar and training sessions.</p>
      <RouterLink to="/events">Open Events</RouterLink>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
