<script setup>
const notes = [
  'Mohon tepat waktu (toleransi 5 menit)',
  'Informasi meeting juga dikirim ke email',
  'Gunakan satu akun Zoom selama konsultasi',
]
</script>

<template>
  <div class="space-y-2 text-sm">
    <h4 class="font-semibold text-slate-800">
      Catatan
    </h4>

    <ul class="space-y-1">
      <li
        v-for="(note, i) in notes"
        :key="i"
        class="flex gap-2 text-slate-600"
      >
        <span class="text-red-600 font-bold">•</span>
        <span>{{ note }}</span>
      </li>
    </ul>
  </div>
</template>
