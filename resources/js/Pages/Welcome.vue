<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Search, RefreshCcw } from 'lucide-vue-next'
import ConsultationFeedbackModal from '@/Components/app/modals/ConsultationFeedbackModal.vue'

// composables
import { useCaptcha } from '@/Composables/useCaptcha'
import { useConsultationFormValidation } from '@/Composables/useConsultationFormValidation'
import { useConsultationSchedule } from '@/Composables/useConsultationSchedule'

// services
import { findAsnByNip } from '@/Services/asn.service'
import { submitConsultation } from '@/Services/consultation.service'

// mocks
import { CONSULTATION_TOPICS } from '@/Mocks/consultation-topics.mock'

/**
 * State
 */
const nip = ref('')
const asn = ref(null)
const nipError = ref('')
const consultationResult = ref(null)

const form = ref({
  topik: '',
  deskripsi: '',
  tanggal: '',
  sesi: '',
  email: '',
  hp: '',
  captchaInput: '',
})

/**
 * Composables
 */
const { captcha, resetCaptcha, validateCaptcha } = useCaptcha()

const {
  errors: formErrors,
  validate,
  isFormComplete,
  clearErrors,
} = useConsultationFormValidation(form)

const { availableSessions } = useConsultationSchedule(form)

/**
 * Modal state
 */
const modalOpen = ref(false)
const modalType = ref('error')

/**
 * Methods
 */
async function cekNip() {
  nipError.value = ''
  asn.value = await findAsnByNip(nip.value)

  if (!asn.value) {
    nipError.value = 'NIP tidak ditemukan'
  }
}

async function submitForm() {
  if (!validate()) return

  if (!validateCaptcha(form.value.captchaInput)) {
    modalType.value = 'error'
    modalOpen.value = true
    resetCaptcha()
    form.value.captchaInput = ''
    return
  }

  consultationResult.value = await submitConsultation(form.value)
  modalType.value = 'success'
  modalOpen.value = true
}

function resetForm() {
  nip.value = ''
  asn.value = null
  nipError.value = ''
  clearErrors()

  form.value = {
    topik: '',
    deskripsi: '',
    tanggal: '',
    sesi: '',
    email: '',
    hp: '',
    captchaInput: '',
  }

  resetCaptcha()
}

function handleModalClose() {
  modalOpen.value = false

  if (modalType.value === 'success') {
    resetForm()
  } else {
    resetCaptcha()
    form.value.captchaInput = ''
  }
}

/**
 * Scroll
 */
const formSection = ref(null)
const scrollToForm = () => {
  formSection.value?.scrollIntoView({ behavior: 'smooth' })
}
</script>

<template>
  <Head title="e con - Konsultasi Online" />

  <FrontLayout @scroll-form="scrollToForm">
    <!-- FORM -->
    <template #form>
      <div ref="formSection" class="w-full flex justify-center items-start sm:items-center">
        <div class="w-full max-w-md sm:max-w-lg">
          <div class="bg-white/95 backdrop-blur-sm rounded-2xl border border-slate-200 p-6 sm:p-8 relative">

            <!-- HEADER -->
            <div class="mb-6 text-center sm:text-left">
              <h3 class="text-xl sm:text-2xl font-bold text-red-600">
                FORM KONSULTASI
              </h3>
              <p class="text-sm text-slate-500 mt-1">
                Silahkan input NIP dan form untuk mendapatkan link dan jadwal konsultasi.
              </p>
            </div>

            <!-- CEK NIP -->
            <div class="space-y-3">
              <label class="text-sm font-medium text-slate-700">NIP</label>
              <div class="flex gap-2">
                <input
                  v-model="nip"
                  type="text"
                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Masukkan NIP"
                />
                <button
                  @click="cekNip"
                  class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm"
                >
                  <Search class="w-4 h-4" />
                  Cari
                </button>
              </div>
              <p v-if="nipError" class="text-sm text-red-500">
                {{ nipError }}
              </p>
            </div>

            <!-- BIODATA -->
            <div
              v-if="asn"
              class="mt-6 bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-2"
            >
              <p><strong>Nama:</strong> {{ asn.nama }}</p>
              <p><strong>Jabatan:</strong> {{ asn.jabatan }}</p>
              <p><strong>Instansi:</strong> {{ asn.instansi }}</p>
            </div>

            <!-- FORM LANJUTAN -->
            <div v-if="asn" class="mt-6 space-y-4">
              <!-- TOPIK -->
              <div>
                <label class="text-sm font-medium text-slate-700">
                  Topik Konsultasi
                </label>
                <select
                v-model="form.topik"
                class="w-full mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
                <option value="">Pilih topik</option>
                <option
                    v-for="topic in CONSULTATION_TOPICS"
                    :key="topic.value"
                    :value="topic.value"
                >
                    {{ topic.title }}
                </option>
                </select>
                <p v-if="formErrors.topik" class="text-xs text-red-500 mt-1">
                {{ formErrors.topik }}
                </p>
              </div>

              <!-- DESKRIPSI -->
              <div>
                <label class="text-sm font-medium text-slate-700">
                  Deskripsi Singkat Permasalahan
                </label>
                <textarea
                  v-model="form.deskripsi"
                  rows="3"
                  class="w-full mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                ></textarea>
                <p v-if="formErrors.deskripsi" class="text-xs text-red-500 mt-1">
                {{ formErrors.deskripsi }}
                </p>
              </div>

              <!-- TANGGAL & SESI -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-medium text-slate-700">
                    Tanggal Konsultasi
                  </label>
                  <input
                    type="date"
                    v-model="form.tanggal"
                    :min="new Date().toISOString().slice(0, 10)"
                    class="w-full mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                    />
                  <p v-if="formErrors.tanggal" class="text-xs text-red-500 mt-1">
                    {{ formErrors.tanggal }}
                  </p>
                </div>

                <div>
                  <label class="text-sm font-medium text-slate-700">
                    Sesi
                  </label>
                  <select
                    v-model="form.sesi"
                    class="w-full mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                    >
                    <option value="">Pilih sesi</option>
                    <option
                        v-for="session in availableSessions"
                        :key="session.value"
                        :value="session.value"
                        :disabled="session.disabled"
                    >
                        {{ session.label }}
                    </option>
                    </select>
                  <p v-if="formErrors.sesi" class="text-xs text-red-500 mt-1">
                    {{ formErrors.sesi }}
                  </p>
                </div>
              </div>

              <!-- EMAIL & HP -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="Email"
                  class="rounded-lg border border-slate-300 px-3 py-2 text-sm"
                />
                <p v-if="formErrors.email" class="text-xs text-red-500 mt-1">
                  {{ formErrors.email }}
                </p>
                <input
                  v-model="form.hp"
                  type="text"
                  placeholder="Nomor Handphone"
                  class="rounded-lg border border-slate-300 px-3 py-2 text-sm"
                />
                <p v-if="formErrors.hp" class="text-xs text-red-500 mt-1">
                  {{ formErrors.hp }}
                </p>
              </div>

              <!-- CAPTCHA -->
              <div>
                <label class="text-sm font-medium text-slate-700">Captcha</label>
                <div class="flex items-center gap-2 mt-1">
                  <div class="px-4 py-2 bg-slate-200 rounded-lg font-mono text-sm">
                    {{ captcha }}
                  </div>
                  <button
                    @click="resetCaptcha"
                    type="button"
                    class="text-red-600 hover:text-red-800"
                  >
                    <RefreshCcw class="w-4 h-4" />
                  </button>
                  <input
                    v-model="form.captchaInput"
                    type="text"
                    placeholder="Masukkan captcha"
                    class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                  />
                  <p v-if="formErrors.captchaInput" class="text-xs text-red-500 mt-1">
                    {{ formErrors.captchaInput }}
                  </p>
                </div>
              </div>

              <!-- SUBMIT -->
              <button
                @click="submitForm"
                :disabled="!isFormComplete"
                class="w-full py-2.5 rounded-xl font-medium
                    bg-red-500 hover:bg-red-600 text-white
                    disabled:bg-slate-300 disabled:cursor-not-allowed"
                >
                Submit Request
                </button>
            </div>
          </div>
        </div>
      </div>
    </template>
  </FrontLayout>
  <ConsultationFeedbackModal
    :open="modalOpen"
    :type="modalType"
    :data="consultationResult"
    @close="handleModalClose"
  />
</template>
