<script setup>
import ModalHeader from './parts/ModalHeader.vue'
import ErrorNotice from './parts/ErrorNotice.vue'
import MeetingInfo from './parts/MeetingInfo.vue'
import ConsultationNotes from './parts/ConsultationNotes.vue'
import FormFeedback from './parts/FormFeedback.vue'

defineProps({
  open: Boolean,
  type: String,
  data: Object,
})

const emit = defineEmits(['close'])

function closeModal() {
  emit('close')
}
</script>

<template>
  <div
    v-if="open"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
  >
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl relative">
      <button
        class="absolute top-4 right-4 text-slate-400 hover:text-slate-600"
        @click="closeModal"
      >
        ✕
      </button>

      <ModalHeader :type="type" />

      <div class="p-6">
        <!-- ERROR -->
        <ErrorNotice
          v-if="type === 'error'"
          @close="emit('close')"
        />

        <!-- SUCCESS -->
        <div v-else class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
              <MeetingInfo :meeting="data.meeting" />
            </div>

            <div class="border-l border-slate-200 pl-6">
              <ConsultationNotes />
            </div>
          </div>

          <FormFeedback />
        </div>
      </div>
    </div>
  </div>
</template>
