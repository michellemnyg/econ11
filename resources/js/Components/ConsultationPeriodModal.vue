<template>
  <Dialog :open="true" @update:open="$emit('close')">
    <DialogContent class="max-w-md">
      <DialogHeader>
        <DialogTitle>Periode Konsultasi</DialogTitle>
      </DialogHeader>

      <div class="space-y-4">
        <div>
          <label class="text-sm text-slate-600 mb-1 block">Tanggal Mulai</label>
          <input
            type="date"
            v-model="localStart"
            class="w-full border rounded-lg px-3 py-2 text-sm"
          />
        </div>

        <div>
          <label class="text-sm text-slate-600 mb-1 block">Tanggal Akhir</label>
          <input
            type="date"
            v-model="localEnd"
            class="w-full border rounded-lg px-3 py-2 text-sm"
          />
        </div>
      </div>

      <div class="flex justify-between items-center mt-6">
        <button
          class="text-sm text-slate-500 hover:text-slate-700"
          @click="clear"
        >
          Reset Periode
        </button>

        <div class="flex gap-2">
          <button
            class="px-4 py-2 text-sm rounded-lg border text-slate-600 hover:bg-slate-100"
            @click="$emit('close')"
          >
            Batal
          </button>

          <button
            class="px-4 py-2 text-sm rounded-lg bg-slate-900 text-white hover:bg-slate-800"
            :disabled="!localStart || !localEnd"
            @click="apply"
          >
            Terapkan
          </button>
        </div>
      </div>
    </DialogContent>
  </Dialog>
</template>

<script setup>
import { ref } from 'vue'
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/Components/ui/dialog'

const props = defineProps({
  startDate: String,
  endDate: String,
})

const emit = defineEmits(['close', 'apply'])

const localStart = ref(props.startDate || '')
const localEnd = ref(props.endDate || '')

function apply() {
  emit('apply', {
    start: localStart.value,
    end: localEnd.value,
  })
  emit('close')
}

function clear() {
  localStart.value = ''
  localEnd.value = ''
  emit('apply', { start: '', end: '' })
  emit('close')
}
</script>
