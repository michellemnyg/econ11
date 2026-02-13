<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import { Link as LinkIcon } from 'lucide-vue-next'

// Components
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import ConsultationFilterBar from '@/Components/ConsultationFilterBar.vue'
import ConsultationPeriodModal from '@/Components/ConsultationPeriodModal.vue'
import ConsultationAdvancedFilter from '@/Components/ConsultationAdvancedFilter.vue'
// NEW COMPONENT
import ConsultationTable from '@/Components/ConsultationTable.vue'

// Logic & Mocks
import { CONSULTATION_TABLE_MOCK as consultationsMock } from '@/Mocks/consultationTable.mock'
import { narasumberMock } from '@/Mocks/narasumber.mock'
import { useConsultationTable } from '@/Composables/useConsultationTable'
import { useClipboardToast } from '@/Composables/useClipboardToast'

defineOptions({ layout: AdminLayout })

// Toast Logic
const { copy, toastMessage, showToast } = useClipboardToast()

// Data Source
const klienList = ref(consultationsMock)

// Table Logic (Composable)
const table = useConsultationTable(klienList)
const {
  paginatedData,
  totalPages,
  currentPage,
  perPage,
  search,
  periodMode,
  startDate,
  endDate,
  filterInstansi,
  filterStatus,
  instansiOptions,
} = table

// Local Helper State
const filterTanggal = ref('')

// Modal Visibility State
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
      <p class="text-sm text-slate-500 mt-1">Daftar klien yang terdaftar untuk konsultasi hari ini</p>
    </div>

    <ConsultationFilterBar
      :search="search"
      @update:search="search = $event"
      @open-period="showPeriodModal = true"
      @open-filter="showAdvancedFilter = true"
    />

    <ConsultationPeriodModal
      v-if="showPeriodModal"
      :start-date="startDate"
      :end-date="endDate"
      @close="showPeriodModal = false"
      @apply="applyPeriod"
    />

    <ConsultationAdvancedFilter
      v-if="showAdvancedFilter"
      :instansi-options="instansiOptions"
      :filter-instansi="filterInstansi"
      :filter-status="filterStatus"
      @close="showAdvancedFilter = false"
      @apply="applyAdvancedFilter"
    />

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
        </DialogHeader>

        <Input placeholder="Cari nama atau unit..." v-model="searchPetugas" class="mb-3" />

        <div class="space-y-2 max-h-60 overflow-y-auto">
          <div v-for="p in narasumberMock" :key="p.nama" class="flex justify-between items-center border rounded-lg p-2">
            <div>
              <p class="font-medium">{{ p.nama }}</p>
              <p class="text-xs text-slate-500">{{ p.unit }}</p>
            </div>
            <Button size="sm" variant="outline" @click="selectedPetugas = p.nama">Pilih</Button>
          </div>
        </div>

        <DialogFooter>
          <Button variant="secondary" @click="showAssignModal = false">Batal</Button>
          <Button :disabled="!selectedPetugas" class="bg-blue-600 hover:bg-blue-700 text-white" @click="assignPetugas(selectedPetugas)">Simpan</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <Dialog v-model:open="showDetailModal">
      <DialogContent class="max-w-lg border-t-4 border-blue-500">
        <DialogHeader>
          <DialogTitle>Detail Konsultasi</DialogTitle>
        </DialogHeader>

        <div class="space-y-2 text-sm">
          <p><strong>Nama:</strong> {{ selectedKlien?.nama }}</p>
          <p><strong>NIP:</strong> {{ selectedKlien?.nip }}</p>
          <p><strong>Instansi:</strong> {{ selectedKlien?.instansi }}</p>
          <p><strong>Tanggal:</strong> {{ selectedKlien?.tanggal }}</p>
          <p><strong>Sesi:</strong> {{ selectedKlien?.sesi }}</p>
          <p><strong>Topik:</strong> {{ selectedKlien?.topik }}</p>
          <p><strong>Petugas:</strong> {{ selectedKlien?.petugas ?? '-' }}</p>
          <hr class="my-3" />

          <div class="space-y-2">
            <h4 class="font-semibold text-slate-700 flex items-center gap-2">
              <LinkIcon class="w-4 h-4" /> Detail Zoom Meeting
            </h4>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 space-y-2 text-sm">
              <div class="flex justify-between items-center">
                <span class="font-medium">Link Meeting</span>
                <Button size="xs" variant="ghost" @click="copyText(selectedKlien?.integrasi?.linkZoom)">Salin</Button>
              </div>
              <p class="text-blue-700 font-medium break-all">{{ selectedKlien?.integrasi?.linkZoom ?? '-' }}</p>

              <div class="flex justify-between items-center">
                 <span class="font-medium">Meeting ID</span>
                 <Button size="xs" variant="ghost" @click="copyText(selectedKlien?.integrasi?.meetingId)">Salin</Button>
              </div>
              <p>{{ selectedKlien?.integrasi?.meetingId ?? '-' }}</p>

              <div class="flex justify-between items-center">
                 <span class="font-medium">Passcode</span>
                 <Button size="xs" variant="ghost" @click="copyText(selectedKlien?.integrasi?.passcode)">Salin</Button>
              </div>
              <p>{{ selectedKlien?.integrasi?.passcode ?? '-' }}</p>
            </div>
          </div>

          <div class="space-y-1 mt-3">
             <h4 class="font-semibold text-red-700">Deskripsi Singkat Masalah</h4>
             <div class="bg-red-50/40 border border-red-200 rounded-lg p-3 text-sm text-slate-700">
                {{ selectedKlien?.deskripsiMasalah ?? 'Tidak ada deskripsi.' }}
             </div>
          </div>
        </div>

        <DialogFooter>
           <Button class="border border-slate-300 text-slate-700 hover:bg-slate-100" @click="showDetailModal = false">Tutup</Button>
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
        <div
        v-if="showToast"
        style="z-index: 9999;"
        class="fixed bottom-10 left-1/2 -translate-x-1/2 pointer-events-auto bg-slate-900 text-white px-6 py-3 rounded-xl shadow-2xl text-sm flex items-center gap-2"
        >
        <span>{{ toastMessage }}</span>
        </div>
    </Transition>
    </Teleport>
</template>
