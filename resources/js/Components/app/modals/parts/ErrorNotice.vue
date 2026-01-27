<script setup>
const emit = defineEmits(['close'])
</script>

<template>
  <div class="text-center py-10 space-y-4">
    <div class="mx-auto w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
      <span class="text-red-600 text-xl font-bold">!</span>
    </div>

    <h4 class="font-semibold text-slate-800">
      Captcha Tidak Sesuai
    </h4>

    <p class="text-sm text-slate-600 max-w-md mx-auto">
      Silakan periksa kembali captcha yang Anda masukkan.
    </p>

    <button
      class="mt-4 px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm"
      @click="emit('close')"
    >
      Tutup
    </button>
  </div>
</template>
