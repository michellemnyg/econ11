<template>
  <div
    class="flex flex-col items-center justify-center
           border border-dashed border-slate-300
           rounded-lg p-6 text-center w-full h-full"
  >
    <component
      :is="icon"
      class="w-6 h-6 text-slate-400 mb-2"
    />

    <p class="text-sm text-slate-400 italic">
      {{ message }}
    </p>

    <p
      v-if="hint"
      class="text-xs text-slate-400 mt-1"
    >
      {{ hint }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  message: {
    type: String,
    required: true
  },
  hint: {
    type: String,
    default: ''
  },
  icon: {
    type: [Object, Function],
    required: true
  }
})
</script>
