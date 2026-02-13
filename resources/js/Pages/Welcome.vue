<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, onMounted, computed, watch } from 'vue'
import { Search, RefreshCcw, Calendar, User, FileText } from 'lucide-vue-next'
import ConsultationFeedbackModal from '@/Components/app/modals/ConsultationFeedbackModal.vue'
import ConsultationCalendar from '@/Components/app/calendar/ConsultationCalendar.vue'
import ConsultationSessionDetailModal from '@/Components/app/modals/ConsultationSessionDetailModal.vue'

import { useCalendarStore } from '@/Stores/useCalendarStore'

// composables
import { useCaptcha } from '@/Composables/useCaptcha'
import { useConsultationFormValidation } from '@/Composables/useConsultationFormValidation'
import { useConsultationSchedule } from '@/Composables/useConsultationSchedule'

// services
// Ubah import function sesuai nama baru di service
import { findAsn } from '@/Services/asn.service'
import { submitConsultation } from '@/Services/consultation.service'

// mocks
import { CONSULTATION_TOPICS } from '@/Mocks/consultation-topics.mock'

/**
 * Calendar Store
 */
const { addBookedSession, loadCalendar } = useCalendarStore()

onMounted(loadCalendar)

/**
 * State
 */
const nip = ref('')
const nama = ref('') // Tambahan state Nama
const asn = ref(null)
const searchError = ref('') // Rename nipError jadi searchError biar umum
const isSearching = ref(false) // State loading saat mencari
const consultationResult = ref(null)
const isSubmitting = ref(false)

const form = ref({
  topik: '',
  deskripsi: '',
  tanggal: '',
  sesi: '',
  email: '',
  hp: '',
  captchaInput: '',
})

watch([nip, nama], () => {
  if (searchError.value) searchError.value = ''
})

/**
 * Session modal
 */
const selectedSession = ref(null)
const sessionModalOpen = ref(false)

const openSessionDetail = (session) => {
  selectedSession.value = session
  sessionModalOpen.value = true
}

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
 * Feedback modal
 */
const modalOpen = ref(false)
const modalType = ref('error')

/**
 * Computed
 */
// Tombol search hanya aktif jika NIP & Nama sudah diisi
const canSearch = computed(() => {
    return nip.value.length > 0 && nama.value.length > 0
})

/**
 * Methods
 */
async function cekDataAsn() {
  if(!canSearch.value) return

  isSearching.value = true
  searchError.value = ''
  asn.value = null // Reset hasil sebelumnya

  // Panggil service baru dengan 2 parameter
  asn.value = await findAsn(nip.value, nama.value)

  if (!asn.value) {
    searchError.value = 'Data tidak ditemukan. Pastikan NIP dan Nama sesuai.'
  }

  isSearching.value = false
}

async function submitForm() {
  if (!asn.value) {
    searchError.value = 'Silahkan validasi data ASN terlebih dahulu.';
    return;
  }

  if (!validate() || isSubmitting.value) return

  if (!validateCaptcha(form.value.captchaInput)) {
    modalType.value = 'error'
    modalOpen.value = true
    resetCaptcha()
    form.value.captchaInput = ''
    return
  }

  isSubmitting.value = true

  try {
    consultationResult.value = await submitConsultation(form.value)

    addBookedSession({
      tanggal: form.value.tanggal,
      sesi: {
        value: form.value.sesi,
        label: availableSessions.value.find(
          s => s.value === form.value.sesi
        )?.label,
        narasumber: 'Narasumber A',
        topik: form.value.topik,
      },
    })

    modalType.value = 'success'
    modalOpen.value = true
  } finally {
    isSubmitting.value = false
  }
}

function resetForm() {
  nip.value = ''
  nama.value = ''
  asn.value = null
  searchError.value = ''
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
 * Scroll helpers
 */
const formSection = ref(null)

const scrollToForm = () => {
  formSection.value?.scrollIntoView({ behavior: 'smooth' })
}

const scrollToCalendar = () => {
  const el = document.getElementById('calendar')
  if (!el) return

  el.scrollIntoView({
    behavior: 'smooth',
    block: 'start',
  })
}
</script>

<template>
  <Head title="e con - Konsultasi Online" />

  <FrontLayout @scroll-form="scrollToForm">

    <template #form>
      <div ref="formSection" class="w-full flex justify-center items-start sm:items-center">
        <div class="w-full max-w-md sm:max-w-lg">
          <div class="bg-white/95 backdrop-blur-sm rounded-2xl border border-slate-200 p-6 sm:p-8 relative shadow-sm">

            <div class="mb-6 text-center sm:text-left">
              <h3 class="text-xl sm:text-2xl font-bold text-red-600">
                FORM KONSULTASI
              </h3>
              <p class="text-sm text-slate-500 mt-1">
                Verifikasi data ASN anda untuk mendapatkan link dan jadwal konsultasi.
              </p>
            </div>

            <div class="space-y-4" @keyup.enter="cekDataAsn">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">NIP</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400">
                                <FileText class="w-4 h-4"/>
                            </span>
                            <input
                                v-model="nip"
                                type="text"
                                class="w-full pl-9 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                placeholder="NIP"
                            />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400">
                                <User class="w-4 h-4"/>
                            </span>
                            <input
                                v-model="nama"
                                type="text"
                                class="w-full pl-9 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                placeholder="Nama sesuai data ASN"
                            />
                        </div>
                    </div>
                </div>

                <button
                    @click="cekDataAsn"
                    :disabled="!canSearch || isSearching"
                    class="w-full flex justify-center items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <RefreshCcw v-if="isSearching" class="w-4 h-4 animate-spin" />
                    <Search v-else class="w-4 h-4" />
                    {{ isSearching ? 'Memeriksa...' : 'Validasi Data ASN' }}
                </button>

                <div v-if="searchError" class="p-3 bg-red-50 border border-red-100 text-red-600 rounded-lg text-sm flex items-center gap-2">
                    <span class="font-bold">!</span> {{ searchError }}
                </div>
            </div>

            <transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
            >
                <div v-if="asn" class="mt-8 pt-6 border-t border-slate-200" @keyup.enter="submitForm">

                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6">
                         <h4 class="text-xs font-bold text-slate-400 uppercase mb-2">Data Terverifikasi</h4>
                         <div class="grid grid-cols-1 gap-1 text-sm text-slate-700">
                            <div class="flex">
                                <span class="w-24 font-medium text-slate-500">Jabatan:</span>
                                <span>{{ asn.jabatan }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-24 font-medium text-slate-500">Instansi:</span>
                                <span>{{ asn.instansi }}</span>
                            </div>
                         </div>
                    </div>

                    <div class="space-y-4 animate-fadeIn">

                        <div>
                            <label class="text-sm font-medium text-slate-700">Topik Konsultasi</label>
                            <select v-model="form.topik" class="w-full mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm">
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

                        <div>
                            <label class="text-sm font-medium text-slate-700">
                            Deskripsi Singkat Permasalahan
                            </label>
                            <textarea
                            v-model="form.deskripsi"
                            rows="3"
                            class="w-full mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            placeholder="Jelaskan masalah anda..."
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                            <label class="text-sm font-medium text-slate-700">Tanggal</label>
                            <input
                                type="date"
                                v-model="form.tanggal"
                                :min="new Date().toISOString().slice(0, 10)"
                                class="w-full mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm"
                            />
                            </div>

                            <div>
                            <label class="text-sm font-medium text-slate-700">Sesi</label>
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

                            <button
                                type="button"
                                @click="scrollToCalendar"
                                class="mt-2 text-xs text-red-600 hover:text-red-800 flex items-center gap-1 font-medium"
                            >
                                <Calendar class="w-3 h-3" />
                                Cek Jadwal Tersedia
                            </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-slate-700">Email</label>
                                <input
                                v-model="form.email"
                                type="email"
                                placeholder="Email Aktif"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                />
                                <p v-if="formErrors.email" class="text-xs text-red-500 mt-1">
                                {{ formErrors.email }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-700">Nomor Handphone / WA</label>
                                <input
                                v-model="form.hp"
                                type="text"
                                placeholder="Nomor Handphone / WA"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                />
                                <p v-if="formErrors.hp" class="text-xs text-red-500 mt-1">
                                {{ formErrors.hp }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-slate-700">Keamanan</label>
                            <div class="flex items-center gap-2 mt-1">
                            <div class="px-4 py-2 bg-slate-100 border border-slate-200 rounded-lg font-mono text-lg font-bold tracking-widest text-slate-600 select-none">
                                {{ captcha }}
                            </div>
                            <button
                                @click="resetCaptcha"
                                type="button"
                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors"
                                title="Refresh Captcha"
                            >
                                <RefreshCcw class="w-4 h-4" />
                            </button>
                            <div class="flex-1">
                                <input
                                    v-model="form.captchaInput"
                                    type="text"
                                    placeholder="Ketik kode di samping"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                                />
                                <p v-if="formErrors.captchaInput" class="text-xs text-red-500 mt-1">
                                    {{ formErrors.captchaInput }}
                                </p>
                            </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button
                            @click="submitForm"
                            :disabled="!isFormComplete || isSubmitting"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:shadow-none"
                            >
                            {{ isSubmitting ? 'Mengirim Permintaan...' : 'Kirim Permintaan Konsultasi' }}
                            </button>
                        </div>
                    </div>
                </div>
            </transition>

          </div>
        </div>
      </div>
    </template>

    <template #content>
      <section ref="calendarSection" class="max-w-7xl mx-auto px-6">
        <ConsultationCalendar
          @select-session="openSessionDetail"
        />
      </section>
    </template>

  </FrontLayout>

  <ConsultationFeedbackModal
    :open="modalOpen"
    :type="modalType"
    :data="consultationResult"
    @close="handleModalClose"
  />

  <ConsultationSessionDetailModal
    :open="sessionModalOpen"
    :session="selectedSession"
    :is-admin="false"
    @close="sessionModalOpen = false"
  />
</template>
