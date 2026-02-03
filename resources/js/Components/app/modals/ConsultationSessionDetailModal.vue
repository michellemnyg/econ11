<script setup>
const props = defineProps({
  open: Boolean,
  session: Object
})
const emit = defineEmits(['close'])
</script>

<template>
  <Teleport to="body">
    <div
      v-if="open"
      class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center px-4"
    >
      <div class="bg-white max-w-md w-full rounded-2xl p-6 relative">
        <button
          @click="emit('close')"
          class="absolute top-4 right-4 text-slate-500 hover:text-red-500"
        >
          ✕
        </button>

        <h3 class="text-lg font-bold text-blue-700 mb-4">
          Detail Sesi Konsultasi
        </h3>

        <ul class="text-sm space-y-2">
          <li><strong>Tanggal:</strong> {{ session?.tanggal }}</li>
          <li><strong>Sesi:</strong> {{ session?.label }}</li>
          <li><strong>Status:</strong>
            <span
              :class="session?.status === 'booked'
                ? 'text-red-600'
                : 'text-green-600'"
            >
              {{ session?.status }}
            </span>
          </li>
          <li v-if="session?.narasumber">
            <strong>Narasumber:</strong> {{ session.narasumber }}
          </li>
          <li v-if="session?.topik">
            <strong>Topik:</strong> {{ session.topik }}
          </li>
        </ul>
      </div>
    </div>
  </Teleport>
</template>
