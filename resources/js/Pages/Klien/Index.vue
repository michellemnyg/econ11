<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import { Link as LinkIcon } from 'lucide-vue-next'

import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import ConsultationFilterBar from '@/Components/ConsultationFilterBar.vue'
import ConsultationPeriodModal from '@/Components/ConsultationPeriodModal.vue'
import ConsultationAdvancedFilter from '@/Components/ConsultationAdvancedFilter.vue'
import ConsultationTable from '@/Components/ConsultationTable.vue'

import { useConsultationTable } from '@/Composables/useConsultationTable'
import { useClipboardToast } from '@/Composables/useClipboardToast'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  klienData: [Object, Array],
  narasumberList: Array
})

const { copy, toastMessage, showToast } = useClipboardToast()

// State Data Klien
const klienList = ref(Array.isArray(props.klienData) ? props.klienData : (props.klienData?.data || []))

// KUNCI AUTO-RELOAD: Pantau perubahan props dari Laravel dan langsung update tabel
watch(() => props.klienData, (newData) => {
  klienList.value = Array.isArray(newData) ? newData : (newData?.data || [])
}, { deep: true })

const table = useConsultationTable(klienList)
const {
  paginatedData, totalPages, currentPage, perPage, search, periodMode, startDate, endDate, filterInstansi, filterStatus, instansiOptions,
} = table

const filterTanggal = ref('')
const showPeriodModal = ref(false)
const showAdvancedFilter = ref(false)
const showAssignModal = ref(false)
const showDetailModal = ref(false)

const selectedKlien = ref(null)
const searchPetugas = ref('')
const selectedPetugas = ref(null)

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
    if (!selectedKlien.value || !nama) return

    // Tembak data ke API Laravel dan biarkan halaman refresh datanya tanpa kedip
    router.patch(`/klien/${selectedKlien.value.id}/assign`, { narasumber: nama }, {
        preserveScroll: true,
        onSuccess: () => {
            showAssignModal.value = false
            toastMessage.value = 'Narasumber berhasil ditugaskan!'
            showToast.value = true
            setTimeout(() => { showToast.value = false }, 3000)
        }
    })
}

const copyLink = (link) => {
    if (!link) return
    copy(link, 'Link Zoom berhasil disalin')
}

const copyText = (text) => {
    if (!text) return
    copy(text, 'Berhasil disalin')
}

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
      <DialogContent class="max-w-lg border-t-4 border-blue-600">
        <DialogHeader>
          <DialogTitle>Assign Narasumber</DialogTitle>
          <DialogDescription class="hidden">Pilih narasumber untuk sesi ini.</DialogDescription>
        </DialogHeader>

        <Input placeholder="Cari nama narasumber..." v-model="searchPetugas" class="mb-3" />
        <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
          <div v-for="p in props.narasumberList" :key="p.nama" class="flex justify-between items-center border rounded-xl p-3 hover:bg-slate-50 hover:border-blue-200 transition-all cursor-pointer" @click="selectedPetugas = p.nama">
            <div>
              <p class="font-bold text-slate-800">{{ p.nama }}</p>
              <p class="text-xs text-slate-500 font-medium">{{ p.unit }}</p>
            </div>
            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors" :class="selectedPetugas === p.nama ? 'border-blue-600 bg-blue-600' : 'border-slate-300'">
              <div v-if="selectedPetugas === p.nama" class="w-2 h-2 bg-white rounded-full"></div>
            </div>
          </div>
        </div>

        <DialogFooter class="mt-4 pt-4 border-t border-slate-100">
          <Button variant="outline" @click="showAssignModal = false">Batal</Button>
          <Button :disabled="!selectedPetugas" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold" @click="assignPetugas(selectedPetugas)">Simpan Penugasan</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <Dialog v-model:open="showDetailModal">
      <DialogContent class="max-w-lg border-t-4 border-blue-600">
        <DialogHeader>
          <DialogTitle>Detail Konsultasi</DialogTitle>
          <DialogDescription class="hidden">Detail data klien.</DialogDescription>
        </DialogHeader>

        <div class="space-y-3 text-sm mt-2">
          <div class="grid grid-cols-3 border-b border-slate-100 pb-2"><span class="text-slate-500 font-medium col-span-1">Nama</span><span class="font-bold text-slate-800 col-span-2">{{ selectedKlien?.nama }}</span></div>
          <div class="grid grid-cols-3 border-b border-slate-100 pb-2"><span class="text-slate-500 font-medium col-span-1">NIP</span><span class="col-span-2 font-mono text-slate-700">{{ selectedKlien?.nip }}</span></div>
          <div class="grid grid-cols-3 border-b border-slate-100 pb-2"><span class="text-slate-500 font-medium col-span-1">Instansi</span><span class="col-span-2 font-medium">{{ selectedKlien?.instansi }}</span></div>
          <div class="grid grid-cols-3 border-b border-slate-100 pb-2"><span class="text-slate-500 font-medium col-span-1">Jadwal</span><span class="col-span-2 font-medium">{{ selectedKlien?.tanggal }} &bull; {{ selectedKlien?.sesi }}</span></div>
          <div class="grid grid-cols-3 border-b border-slate-100 pb-2"><span class="text-slate-500 font-medium col-span-1">Topik</span><span class="text-blue-600 font-bold col-span-2">{{ selectedKlien?.topik }}</span></div>
          <div class="grid grid-cols-3 pb-2">
            <span class="text-slate-500 font-medium col-span-1">Petugas</span>
            <span class="col-span-2 font-bold" :class="selectedKlien?.petugas && selectedKlien?.petugas !== 'Belum Diplot' ? 'text-slate-800' : 'text-red-500'">
              {{ selectedKlien?.petugas && selectedKlien?.petugas !== 'Belum Diplot' ? selectedKlien?.petugas : 'Belum ditugaskan' }}
            </span>
          </div>

          <div class="mt-6">
            <h4 class="font-bold text-slate-800 flex items-center gap-2 mb-3">
              <LinkIcon class="w-4 h-4 text-blue-600" /> Detail Zoom Meeting
            </h4>
            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 space-y-3 text-sm">
              <div class="flex justify-between items-center bg-white p-2.5 rounded-lg border border-slate-200 shadow-sm">
                <span class="font-semibold text-slate-700">Link Zoom</span>
                <Button size="sm" variant="secondary" class="h-7 text-xs font-semibold" @click="copyText(selectedKlien?.integrasi?.linkZoom)">Salin</Button>
              </div>
              <p class="text-blue-700 text-xs break-all px-2 font-medium">{{ selectedKlien?.integrasi?.linkZoom ?? '-' }}</p>
              <div class="grid grid-cols-2 gap-3 mt-3">
                <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-sm">
                  <span class="block text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">Meeting ID</span>
                  <div class="flex justify-between items-center">
                    <strong class="font-mono text-slate-800">{{ selectedKlien?.integrasi?.meetingId ?? '-' }}</strong>
                    <button @click="copyText(selectedKlien?.integrasi?.meetingId)" class="text-blue-600 hover:text-blue-800 text-xs font-bold">Salin</button>
                  </div>
                </div>
                <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-sm">
                  <span class="block text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">Passcode</span>
                  <div class="flex justify-between items-center">
                    <strong class="font-mono text-slate-800">{{ selectedKlien?.integrasi?.passcode ?? '-' }}</strong>
                    <button @click="copyText(selectedKlien?.integrasi?.passcode)" class="text-blue-600 hover:text-blue-800 text-xs font-bold">Salin</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <DialogFooter class="mt-4 pt-4 border-t border-slate-100">
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
        <div v-if="showToast" style="z-index: 9999;" class="fixed bottom-10 left-1/2 -translate-x-1/2 pointer-events-auto bg-slate-900 text-white px-6 py-3 rounded-xl shadow-2xl text-sm font-medium flex items-center gap-2">
          <span>{{ toastMessage }}</span>
        </div>
    </Transition>
    </Teleport>
</template>
