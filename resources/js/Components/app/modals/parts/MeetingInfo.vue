<script setup>
import { Copy, Check, Video } from 'lucide-vue-next'
import { ref, computed } from 'vue'

const props = defineProps({
  meeting: {
    type: Object,
    default: () => ({})
  }
})

const copied = ref(false)

// 1. BARU: Buat penerjemah kode sesi menjadi jam
const jadwalWaktu = {
  'sesi-1': '09:00 - 09:45 WITA',
  'sesi-2': '10:00 - 10:45 WITA',
  'sesi-3': '11:00 - 11:45 WITA',
  'sesi-4': '14:00 - 14:45 WITA',
  'sesi-5': '15:00 - 15:45 WITA',
}

// 2. BARU: Computed property agar otomatis mendeteksi jam
const jamKonsultasi = computed(() => {
  return props.meeting?.waktu || jadwalWaktu[props.meeting?.sesi] || '-'
})

const copyMeetingInfo = async () => {
  const text = `
Jadwal Konsultasi e-con BKN
---------------------------
Topik: ${props.meeting?.topic || '-'}
Tanggal: ${props.meeting?.tanggal || '-'}
Sesi: ${props.meeting?.sesi || '-'}
Waktu: ${jamKonsultasi.value}

Zoom Link: ${props.meeting?.link || '-'}
Passcode: ${props.meeting?.passcode || 'econ11'}
  `.trim()

  try {
    await navigator.clipboard.writeText(text)
    copied.value = true
    setTimeout(() => { copied.value = false }, 2000)
  } catch (err) {
    console.error('Gagal menyalin teks', err)
  }
}
</script>

<template>
  <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
        <Video class="w-5 h-5" />
      </div>
      <div>
        <h4 class="font-bold text-blue-900">Zoom Meeting</h4>
        <p class="text-xs text-blue-600 font-medium">Link & akses otomatis digenerate</p>
      </div>
    </div>

    <div class="space-y-3 bg-white p-4 rounded-xl border border-slate-100 mb-4">
      <div>
        <span class="block text-xs text-slate-500 mb-0.5">Topik Konsultasi</span>
        <strong class="text-sm text-slate-800">{{ meeting?.topic || 'Konsultasi Kepegawaian' }}</strong>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <span class="block text-xs text-slate-500 mb-0.5">Tanggal</span>
          <strong class="text-sm text-slate-800">{{ meeting?.tanggal }}</strong>
        </div>
        <div>
          <span class="block text-xs text-slate-500 mb-0.5">Sesi</span>
          <strong class="text-sm text-slate-800">{{ meeting?.sesi }}</strong>
        </div>

        <div class="col-span-2">
          <span class="block text-xs text-slate-500 mb-0.5">Waktu</span>
          <strong class="text-sm text-slate-800">{{ jamKonsultasi }}</strong>
        </div>
      </div>

      <div class="pt-2 border-t border-slate-100">
        <span class="block text-xs text-slate-500 mb-1">Passcode Zoom</span>
        <strong class="text-sm text-red-600 font-mono tracking-widest bg-red-50 px-2 py-1 rounded">{{ meeting?.passcode || 'econ11' }}</strong>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
      <a
        v-if="meeting?.link"
        :href="meeting.link"
        target="_blank"
        class="flex items-center justify-center py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow-sm"
      >
        Buka Link Zoom
      </a>

      <button
        @click="copyMeetingInfo"
        class="flex items-center justify-center gap-2 py-2.5 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl text-sm font-medium transition border border-slate-200"
      >
        <Check v-if="copied" class="w-4 h-4 text-green-500" />
        <Copy v-else class="w-4 h-4" />
        {{ copied ? 'Berhasil Disalin!' : 'Salin Detail Meeting' }}
      </button>
    </div>
  </div>
</template>
