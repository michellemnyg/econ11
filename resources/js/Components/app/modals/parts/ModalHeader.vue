<script setup>
import { CheckCircle2 } from 'lucide-vue-next'

defineProps({ type: String })
</script>

<template>
  <div
    class="px-6 pt-6 pb-4 text-center rounded-t-2xl"
    :class="type === 'error'
      ? 'bg-red-50 text-red-700'
      : 'bg-blue-700 text-white'"
  >
    <h3 class="text-lg font-semibold">
      {{ type === 'error'
        ? 'Terjadi Kesalahan'
        : 'Permintaan Konsultasi Diterima'
      }}
    </h3>

    <!-- ICON SUKSES -->
    <div v-if="type === 'success'" class="mt-4 flex justify-center">
      <div class="bg-white rounded-full p-2 shadow">
        <CheckCircle2 class="w-10 h-10 text-blue-600" />
      </div>
    </div>

    <!-- AKSEN MERAH -->
    <div
      v-if="type === 'success'"
      class="mt-4 mx-auto w-16 h-1 bg-red-600 rounded-full"
    />
  </div>
</template>
