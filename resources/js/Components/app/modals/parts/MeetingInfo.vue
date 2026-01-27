<script setup>
defineProps({ meeting: Object })
</script>

<template>
  <div class="space-y-3 text-sm">
    <h4 class="font-semibold text-slate-800">
      Meeting Information
    </h4>

    <p><strong>Platform:</strong> {{ meeting.platform }}</p>
    <p>
      <strong>Link:</strong>
      <a :href="meeting.link" class="text-blue-600 underline">
        {{ meeting.link }}
      </a>
    </p>
    <p><strong>Meeting ID:</strong> {{ meeting.meetingId }}</p>
    <p><strong>Password:</strong> {{ meeting.password }}</p>
    <p><strong>Tanggal:</strong> {{ meeting.tanggal }}</p>
    <p><strong>Sesi:</strong> {{ meeting.sesi }}</p>
  </div>
</template>
