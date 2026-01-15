<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Search, RefreshCcw } from 'lucide-vue-next'

/**
 * Dummy database ASN
 */
const dummyASN = [
  {
    nip: '198901012019031001',
    nama: 'Ahmad Fauzi',
    jabatan: 'Analis Kepegawaian',
    instansi: 'BKD Provinsi Jawa Tengah',
  },
  {
    nip: '197805102010122002',
    nama: 'Siti Rahmawati',
    jabatan: 'Kepala Subbagian',
    instansi: 'Kementerian Perhubungan',
  },
]

/**
 * State
 */
const nip = ref('')
const asn = ref(null)
const nipError = ref('')
const captcha = ref(generateCaptcha())

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
 * Methods
 */
function cekNip() {
  nipError.value = ''
  asn.value = null

  const found = dummyASN.find((a) => a.nip === nip.value)

  if (!found) {
    nipError.value = 'NIP tidak ditemukan'
    return
  }

  asn.value = found
}

function submitForm() {
  if (form.value.captchaInput !== captcha.value) {
    alert('Captcha tidak sesuai')
    return
  }

  console.log({
    nip: nip.value,
    biodata: asn.value,
    form: form.value,
  })

  alert('Permintaan konsultasi berhasil dikirim (dummy)')
}

function generateCaptcha() {
  return Math.random().toString(36).substring(2, 8).toUpperCase()
}

function refreshCaptcha() {
  captcha.value = generateCaptcha()
}
</script>

<template>
  <Head title="e con - Konsultasi Online" />

  <FrontLayout>
    <!-- FORM SLOT -->
    <template #form>
      <h3 class="text-2xl font-bold text-blue-600">
        FORM KONSULTASI
        </h3>
        <p class="text-sm text-slate-500 mt-1 mb-4">
        Silahkan input NIP dan form untuk mendapatkan link dan jadwal konsultasi.
        </p>

      <!-- CEK NIP -->
      <div class="space-y-3">
        <label class="text-sm font-medium text-slate-700">
          NIP
        </label>
        <div class="flex gap-2">
          <input
            v-model="nip"
            type="text"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Masukkan NIP"
          />
          <button
            @click="cekNip"
            class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm"
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
            <option>Pangkat dan Jabatan</option>
            <option>Pengadaan</option>
            <option>Pengembangan Karir</option>
            <option>Disiplin</option>
            <option>Penggajian dan Tunjangan</option>
          </select>
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
              class="w-full mt-1 rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
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
              <option>Sesi 1 (09:00 - 09:45)</option>
              <option>Sesi 2 (10:00 - 10:45)</option>
              <option>Sesi 3 (11:00 - 11:45)</option>
              <option>Sesi 4 (14:00 - 14:45)</option>
              <option>Sesi 5 (15:00 - 15:45)</option>
            </select>
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
          <input
            v-model="form.hp"
            type="text"
            placeholder="Nomor Handphone"
            class="rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>

        <!-- CAPTCHA -->
        <div>
          <label class="text-sm font-medium text-slate-700">
            Captcha
          </label>
          <div class="flex items-center gap-2 mt-1">
            <div
              class="px-4 py-2 bg-slate-200 rounded-lg font-mono text-sm"
            >
              {{ captcha }}
            </div>
            <button
              @click="refreshCaptcha"
              type="button"
              class="text-blue-600 hover:text-blue-800"
            >
              <RefreshCcw class="w-4 h-4" />
            </button>
            <input
              v-model="form.captchaInput"
              type="text"
              placeholder="Masukkan captcha"
              class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>
        </div>

        <!-- SUBMIT -->
        <button
          @click="submitForm"
          class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 rounded-xl"
        >
          Submit Request
        </button>
      </div>
    </template>

    <!-- CONTENT SLOT (KOSONG DULU) -->
  </FrontLayout>
</template>
