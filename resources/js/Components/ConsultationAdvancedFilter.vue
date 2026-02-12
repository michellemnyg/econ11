<template>
  <Dialog open :open="true" @update:open="emit('close')">
    <DialogContent class="max-w-md">
      <DialogHeader>
        <DialogTitle>Filter Lanjutan</DialogTitle>
      </DialogHeader>

      <div class="space-y-4">
        <!-- Instansi -->
        <div>
          <label class="text-sm text-slate-600 mb-1 block">
            Instansi
          </label>
          <select
            v-model="localInstansi"
            class="w-full border rounded-lg px-3 py-2 text-sm"
          >
            <option value="">Semua Instansi</option>
            <option
              v-for="i in instansiOptions"
              :key="i"
              :value="i"
            >
              {{ i }}
            </option>
          </select>
        </div>

        <!-- Status -->
        <div>
          <label class="text-sm text-slate-600 mb-1 block">
            Status
          </label>
          <select
            :value="localStatus"
            @change="localStatus = $event.target.value"
            class="w-full border rounded-lg px-3 py-2 text-sm"
            >
            <option value="">Semua Status</option>
            <option value="akan_datang">Akan Datang</option>
            <option value="berlangsung">Berlangsung</option>
            <option value="berlalu">Berlalu</option>
            </select>
        </div>
      </div>

      <div class="flex justify-between items-center mt-6">
        <button
          class="text-sm text-slate-500 hover:text-slate-700"
          @click="clear"
        >
          Reset Filter
        </button>

        <div class="flex gap-2">
          <button
            class="px-4 py-2 text-sm rounded-lg border text-slate-600 hover:bg-slate-100"
            @click="emit('close')"
          >
            Batal
          </button>

          <button
            class="px-4 py-2 text-sm rounded-lg bg-slate-900 text-white hover:bg-slate-800"
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
import { ref, watch } from 'vue'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/Components/ui/dialog'

const props = defineProps({
  instansiOptions: {
    type: Array,
    required: true,
  },
  filterInstansi: String,
  filterStatus: String,
})

const emit = defineEmits(['close', 'apply'])

const localInstansi = ref(props.filterInstansi || '')
const localStatus = ref(props.filterStatus || '')

watch(
  () => [props.filterInstansi, props.filterStatus],
  ([i, s]) => {
    localInstansi.value = i || ''
    localStatus.value = s || ''
  },
)

function apply() {
  emit('apply', {
    instansi: localInstansi.value,
    status: localStatus.value,
  })
  emit('close')
}

function clear() {
  localInstansi.value = ''
  localStatus.value = ''
  emit('apply', { instansi: '', status: '' })
  emit('close')
}
</script>
