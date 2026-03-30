<script setup>
const props = defineProps({
  open: Boolean,
  session: Object
})
const emit = defineEmits(['close'])
</script>

<template>
  <Teleport to="body">
    <div v-if="open" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center px-4">
      <div class="bg-white max-w-md w-full rounded-2xl p-6 relative shadow-2xl">
        <button @click="emit('close')" class="absolute top-4 right-4 text-slate-400 hover:text-red-500 transition">✕</button>

        <h3 class="text-lg font-bold text-blue-700 mb-4">Detail Sesi Konsultasi</h3>

        <div class="space-y-4">
          <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
            Sesi ini sudah dibooking.
          </div>

          <ul class="text-sm space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
            <li>
              <span class="text-slate-500">Tanggal:</span>
              <strong class="block text-slate-800">{{ session?.tanggal }}</strong>
            </li>
            <li>
              <span class="text-slate-500">Sesi:</span>
              <strong class="block text-slate-800">{{ session?.label }}</strong>
            </li>
            <li v-if="session?.topik">
              <span class="text-slate-500">Topik:</span>
              <strong class="block text-blue-600">{{ session.topik }}</strong>
            </li>
            <li v-if="session?.narasumber">
              <span class="text-slate-500">Status Petugas:</span>
              <strong class="block text-slate-800">{{ session.narasumber }}</strong>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </Teleport>
</template>
