<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    required: true,
  },
})

// Normalisasi: ubah ke huruf kecil dan ganti spasi jadi underscore
const normalizedStatus = computed(() => {
  if (!props.status) return 'berlalu'
  return props.status.toLowerCase().trim().replace(' ', '_')
})
</script>

<template>
  <span
    v-if="normalizedStatus === 'berlangsung'"
    class="px-2.5 py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 border border-emerald-200"
  >
    Berlangsung
  </span>

  <span
    v-else-if="normalizedStatus === 'akan_datang'"
    class="px-2.5 py-1 text-xs font-medium rounded-md bg-blue-100 text-blue-700 border border-blue-200"
  >
    Akan Datang
  </span>

  <span
    v-else
    class="px-2.5 py-1 text-xs font-medium rounded-md bg-slate-100 text-slate-600 border border-slate-200"
  >
    Berlalu
  </span>
</template>
