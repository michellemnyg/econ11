<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { Link as LinkIcon } from 'lucide-vue-next'

// Components
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import ConsultationFilterBar from '@/Components/ConsultationFilterBar.vue'
import ConsultationPeriodModal from '@/Components/ConsultationPeriodModal.vue'
import ConsultationAdvancedFilter from '@/Components/ConsultationAdvancedFilter.vue'
import ConsultationTable from '@/Components/ConsultationTable.vue'

// Logic
import { useConsultationTable } from '@/Composables/useConsultationTable'
import { useClipboardToast } from '@/Composables/useClipboardToast'

defineOptions({ layout: AdminLayout })

// TERIMA 2 DATA DARI LARAVEL (Klien & Narasumber)
const props = defineProps({
  klienData: [Object, Array],
  narasumberList: Array
})

// Toast Logic
const { copy, toastMessage, showToast } = useClipboardToast()

// ANTI UNDEFINED: Jika props berupa array, langsung pakai. Jika object, ambil .data-nya.
const klienList = ref(Array.isArray(props.klienData) ? props.klienData : (props.klienData?.data || []))

// Table Logic (Composable)
const table = useConsultationTable(klienList)
const {
  paginatedData, totalPages, currentPage, perPage, search, periodMode, startDate, endDate, filterInstansi, filterStatus, instansiOptions,
} = table

// Local Helper State
const filterTanggal = ref('')
const showPeriodModal = ref(false)
const showAdvancedFilter = ref(false)
const showAssignModal = ref(false)
const showDetailModal = ref(false)

// Action State
const selectedKlien = ref(null)
const searchPetugas = ref('')
const selectedPetugas = ref(null)

/* --- ACTIONS --- */
function applyPeriod({ start, end }) {
  periodMode.value = start && end ? 'range' : 'default'
  startDate.value = start
  endDate.value = end
  filterTanggal.value = ''
  showPeriodModal.value = false
}

function applyAdvancedFilter({ instansi, status }) {
  filterInstansi.value = instansi
  filterStatus.value = status
  showAdvancedFilter.value = false
}

const openAssign = (row) => {
    selectedKlien.value = klienList.value.find( k => k.id === row.id )
    selectedPetugas.value = null
    showAssignModal.value = true
}

const openDetail = (row) => {
    selectedKlien.value = klienList.value.find( k => k.id === row.id )
    showDetailModal.value = true
}

// Fitur Assign (Sementara Update Frontend)
const assignPetugas = (nama) => {
    if (!selectedKlien.value) return
    selectedKlien.value.petugas = nama
    showAssignModal.value = false
}

const copyLink = (link) => {
    if (!link) return
    copy(link, 'Link Zoom berhasil disalin')
}

const copyText = (text) => {
    if (!text) return
    copy(text, 'Berhasil disalin')
}

// Helper untuk text pagination
const pageStart = computed(() => (currentPage.value - 1) * perPage.value + 1)
const pageEnd = computed(() => {
  const end = currentPage.value * perPage.value
  return end > klienList.value.length ? klienList.value.length : end
})
</script>

<template>
  <Head title="Klien" />

    <div class="mb-6">
      <h2 class="text-2xl font-bold text-slate-800">Daftar Antrian Klien</h2>
      <p class="text-sm text-slate-500 mt-1">Kelola pendaftaran konsultasi dan penugasan narasumber</p>
    </div>

    <ConsultationFilterBar :search="search" @update:search="search = $event" @open-period="showPeriodModal = true" @open-filter="showAdvancedFilter = true" />

    <ConsultationPeriodModal v-if="showPeriodModal" :start-date="startDate" :end-date="endDate" @close="showPeriodModal = false" @apply="applyPeriod" />

    <ConsultationAdvancedFilter v-if="showAdvancedFilter" :instansi-options="instansiOptions" :filter-instansi="filterInstansi" :filter-status="filterStatus" @close="showAdvancedFilter = false" @apply="applyAdvancedFilter" />

    <ConsultationTable
      class="mt-6"
      :data="paginatedData"
      :current-page="currentPage"
      :total-pages="totalPages"
      :per-page="perPage"
      :total-items="klienList.length"
      :page-start="pageStart"
      :page-end="pageEnd"
      role="admin"
      @update:currentPage="currentPage = $event"
      @update:perPage="perPage = $event"
      @open-assign="openAssign"
      @open-detail="openDetail"
      @copy-link="copyLink"
    />

    <Dialog v-model:open="showAssignModal">
      <DialogContent class="max-w-lg border-t-4 border-blue-500">
        <DialogHeader>
          <DialogTitle>Assign Narasumber</DialogTitle>
          <DialogDescription class="hidden">Pilih narasumber untuk sesi konsultasi ini.</DialogDescription>
        </DialogHeader>

        <Input placeholder="Cari nama narasumber..." v-model="searchPetugas" class="mb-3" />

        <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
          <div v-for="p in props.narasumberList" :key="p.nama" class="flex justify-between items-center border rounded-lg p-2 hover:bg-slate-50 transition">
            <div>
              <p class="font-medium text-slate-800">{{ p.nama }}</p>
              <p class="text-xs text-slate-500">{{ p.unit }}</p>
            </div>
            <Button size="sm" :variant="selectedPetugas === p.nama ? 'default' : 'outline'" @click="selectedPetugas = p.nama">
              {{ selectedPetugas === p.nama ? 'Terpilih' : 'Pilih' }}
            </Button>
          </div>
        </div>

        <DialogFooter class="mt-4 pt-4 border-t">
          <Button variant="outline" @click="showAssignModal = false">Batal</Button>
          <Button :disabled="!selectedPetugas" class="bg-blue-600 hover:bg-blue-700 text-white" @click="assignPetugas(selectedPetugas)">Simpan Penugasan</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <Dialog v-model:open="showDetailModal">
      <DialogContent class="max-w-lg border-t-4 border-blue-500">
        <DialogHeader>
          <DialogTitle>Detail Konsultasi</DialogTitle>
          <DialogDescription class="hidden">Informasi lengkap klien dan link zoom meeting.</DialogDescription>
        </DialogHeader>

        <div class="space-y-3 text-sm mt-2">
          <div class="grid grid-cols-3 border-b pb-2">
            <span class="text-slate-500 font-medium col-span-1">Nama</span>
            <span class="font-semibold text-slate-800 col-span-2">{{ selectedKlien?.nama }}</span>
          </div>
          <div class="grid grid-cols-3 border-b pb-2">
            <span class="text-slate-500 font-medium col-span-1">NIP</span>
            <span class="col-span-2 font-mono text-slate-700">{{ selectedKlien?.nip }}</span>
          </div>
          <div class="grid grid-cols-3 border-b pb-2">
            <span class="text-slate-500 font-medium col-span-1">Instansi</span>
            <span class="col-span-2">{{ selectedKlien?.instansi }}</span>
          </div>
          <div class="grid grid-cols-3 border-b pb-2">
            <span class="text-slate-500 font-medium col-span-1">Jadwal</span>
            <span class="col-span-2">{{ selectedKlien?.tanggal }} &bull; {{ selectedKlien?.sesi }}</span>
          </div>
          <div class="grid grid-cols-3 border-b pb-2">
            <span class="text-slate-500 font-medium col-span-1">Topik</span>
            <span class="text-blue-600 font-bold col-span-2">{{ selectedKlien?.topik }}</span>
          </div>
          <div class="grid grid-cols-3 pb-2">
            <span class="text-slate-500 font-medium col-span-1">Petugas</span>
            <span class="col-span-2" :class="selectedKlien?.petugas && selectedKlien?.petugas !== 'Belum Diplot' ? 'text-slate-800' : 'text-red-500 italic'">
              {{ selectedKlien?.petugas && selectedKlien?.petugas !== 'Belum Diplot' ? selectedKlien?.petugas : 'Belum ditugaskan' }}
            </span>
          </div>

          <div class="mt-6">
            <h4 class="font-semibold text-slate-800 flex items-center gap-2 mb-3">
              <LinkIcon class="w-4 h-4 text-blue-500" /> Detail Zoom Meeting
            </h4>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3 text-sm">
              <div class="flex justify-between items-center bg-white p-2 rounded-lg border">
                <span class="font-medium text-slate-600">Link Zoom</span>
                <Button size="sm" variant="secondary" class="h-7 text-xs" @click="copyText(selectedKlien?.integrasi?.linkZoom)">Salin</Button>
              </div>
              <p class="text-blue-600 text-xs break-all px-2">{{ selectedKlien?.integrasi?.linkZoom ?? '-' }}</p>

              <div class="grid grid-cols-2 gap-3 mt-3">
                <div class="bg-white p-3 rounded-lg border">
                  <span class="block text-xs text-slate-500 mb-1">Meeting ID</span>
                  <div class="flex justify-between items-center">
                    <strong class="font-mono text-slate-700">{{ selectedKlien?.integrasi?.meetingId ?? '-' }}</strong>
                    <button @click="copyText(selectedKlien?.integrasi?.meetingId)" class="text-blue-500 hover:text-blue-700 text-xs">Salin</button>
                  </div>
                </div>
                <div class="bg-white p-3 rounded-lg border">
                  <span class="block text-xs text-slate-500 mb-1">Passcode</span>
                  <div class="flex justify-between items-center">
                    <strong class="font-mono text-slate-700">{{ selectedKlien?.integrasi?.passcode ?? '-' }}</strong>
                    <button @click="copyText(selectedKlien?.integrasi?.passcode)" class="text-blue-500 hover:text-blue-700 text-xs">Salin</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <DialogFooter class="mt-4 pt-4 border-t">
           <Button variant="outline" @click="showDetailModal = false">Tutup</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <Teleport to="body">
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform translate-y-4 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="showToast" style="z-index: 9999;" class="fixed bottom-10 left-1/2 -translate-x-1/2 pointer-events-auto bg-slate-900 text-white px-6 py-3 rounded-xl shadow-2xl text-sm flex items-center gap-2">
          <span>{{ toastMessage }}</span>
        </div>
    </Transition>
    </Teleport>
</template>
