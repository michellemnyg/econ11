<script setup>
defineProps({
  status: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <span
    v-if="status === 'berlangsung'"
    class="px-2 py-0.5 text-xs rounded-md
           bg-green-100 text-green-700"
  >
    Berlangsung
  </span>

  <span
    v-else-if="status === 'akan_datang'"
    class="px-2 py-0.5 text-xs rounded-md
           bg-blue-100 text-blue-700"
  >
    Akan Datang
  </span>

  <span
    v-else
    class="px-2 py-0.5 text-xs rounded-md
           bg-slate-100 text-slate-600"
  >
    Berlalu
  </span>
</template>
