<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import {
  UserPlus,
  Link as LinkIcon,
  Eye,
  ChevronLeft,
  ChevronRight,
  Ban,
} from 'lucide-vue-next'

import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Badge } from '@/Components/ui/badge'

import { CONSULTATION_TABLE_MOCK as consultationsMock } from '@/Mocks/consultationTable.mock'
import { narasumberMock } from '@/Mocks/narasumber.mock'
import { useConsultationTable } from '@/Composables/useConsultationTable'
import ConsultationStatusBadge from '@/Components/ConsultationStatusBadge.vue'
import { useClipboardToast } from '@/Composables/useClipboardToast'
import ConsultationFilterBar from '@/Components/ConsultationFilterBar.vue'
import ConsultationPeriodModal from '@/Components/ConsultationPeriodModal.vue'
import ConsultationAdvancedFilter from '@/Components/ConsultationAdvancedFilter.vue'

const { copy, toastMessage, showToast } = useClipboardToast()

/* ======================
   TABLE SOURCE
====================== */
const klienList = ref(consultationsMock)

/* ======================
   TABLE LOGIC (SINGLE SOURCE OF TRUTH)
====================== */
const table = useConsultationTable(klienList)

const {
  // Data
  paginatedData,

  // Pagination
  totalPages,
  currentPage,
  perPage,

  // Filters
  search,
  periodMode,
  startDate,
  endDate,
  filterInstansi,
  filterStatus,
  instansiOptions,
} = table

/* ======================
   LOCAL UI STATE
====================== */
const filterTanggal = ref('')

const showPeriodModal = ref(false)
const showAdvancedFilter = ref(false)

/* ======================
   PERIOD APPLY
====================== */
function applyPeriod({ start, end }) {
  periodMode.value = start && end ? 'range' : 'default'
  startDate.value = start
  endDate.value = end

  filterTanggal.value = ''

  showPeriodModal.value = false
}

/* ======================
   ADVANCED FILTER APPLY
====================== */
function applyAdvancedFilter({ instansi, status }) {
  filterInstansi.value = instansi
  filterStatus.value = status

  showAdvancedFilter.value = false
}

/* ======================
   MODAL STATE (Lainnya)
====================== */
const showAssignModal = ref(false)
const showDetailModal = ref(false)
const selectedKlien = ref(null)
const searchPetugas = ref('')
const selectedPetugas = ref(null)

/* ======================
   PAGINATION UI HELPER
====================== */
const pageSizes = [5, 10, 25, 50, 100]

watch(perPage, () => {
  currentPage.value = 1
})

const pageStart = computed(() => {
  return (currentPage.value - 1) * perPage.value + 1
})

const pageEnd = computed(() => {
  const end = currentPage.value * perPage.value
  return end > klienList.value.length ? klienList.value.length : end
})

/* ======================
   ACTIONS
====================== */
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

defineOptions({
  layout: AdminLayout,
})
</script>

<template>
  <Head title="Klien" />
    <!-- HEADER -->
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-slate-800">
        Daftar Antrian Klien
      </h2>
      <p class="text-sm text-slate-500 mt-1">
        Daftar klien yang terdaftar untuk konsultasi hari ini
      </p>
    </div>

    <!-- FILTER BAR -->
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

    <!-- TABLE -->
    <div
    class="bg-white border border-slate-200 rounded-xl shadow-sm relative"
    >
    <div class="overflow-x-auto">
    <table class="min-w-[1100px] w-full text-sm">
        <!-- STICKY HEADER -->
        <thead
        class="sticky top-0 z-10
                bg-gradient-to-r from-blue-50 via-slate-50 to-blue-50
                text-slate-700 border-b border-slate-200"
        >
        <tr>
            <th class="px-4 py-3 text-left font-semibold">Status</th>
            <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
            <th class="px-4 py-3 text-left font-semibold">Sesi</th>
            <th class="px-4 py-3 text-left font-semibold">NIP</th>
            <th class="px-4 py-3 text-left font-semibold">Nama</th>
            <th class="px-4 py-3 text-left font-semibold">Instansi</th>
            <th class="px-4 py-3 text-left font-semibold">Topik</th>
            <th class="px-4 py-3 text-left font-semibold">Petugas</th>
            <th class="px-4 py-3 text-left font-semibold">Link</th>
            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
        </tr>
        </thead>


        <tbody>
        <tr
        v-for="k in paginatedData"
        :key="k.id"
        class="border-t transition
                even:bg-slate-50/50
                hover:bg-blue-50/40"
        >
            <td class="px-4 py-3">
                <ConsultationStatusBadge :status="k.status" />
            </td>

            <td class="px-4 py-3">
            {{ k.tanggal }}
            </td>

            <td class="px-4 py-3">
            <span
                class="inline-block rounded-md bg-blue-50 text-blue-700
                    px-2 py-0.5 text-xs font-medium"
            >
                {{ k.sesi }}
            </span>
            </td>

            <td class="px-4 py-3">
            {{ k.nip }}
            </td>

            <td class="px-4 py-3 font-semibold text-slate-800">
            {{ k.nama }}
            </td>

            <td class="px-4 py-3">
            {{ k.instansi }}
            </td>

            <td class="px-4 py-3">
            <Badge class="bg-slate-100 text-slate-700 border border-slate-200">
                {{ k.topik }}
            </Badge>
            </td>

            <td class="px-4 py-3">
            <span
                v-if="k.petugas"
                class="text-slate-700 font-medium"
            >
                {{ k.petugas }}
            </span>
            <span
                v-else
                class="italic text-slate-400"
            >
                -
            </span>
            </td>

            <td class="px-4 py-3 w-[90px]">
            <div class="flex items-center gap-1">
                <button
                v-if="k.integrasi?.linkZoom"
                @click="copyLink(k.integrasi.linkZoom)"
                class="text-blue-700 hover:text-blue-800
                        flex items-center gap-1 text-xs font-medium"
                >
                <LinkIcon class="w-4 h-4" />
                Copy
                </button>

                <span
                v-else
                class="inline-flex items-center gap-1
                        rounded-md bg-red-50 text-red-600
                        px-2 py-0.5 text-xs font-medium"
                >
                <Ban class="w-3 h-3" />
                Belum
                </span>
            </div>
            </td>


            <td class="px-4 py-3 text-center space-x-2">
            <Button
                v-if="role !== 'narasumber'"
                size="sm"
                variant="outline"
                @click="openAssign(k)"
            >
                <UserPlus class="w-4 h-4" />
            </Button>

            <Button
                size="sm"
                variant="secondary"
                @click="openDetail(k)"
            >
                <Eye class="w-4 h-4" />
            </Button>
            </td>
        </tr>
        </tbody>
    </table>
    </div>
    </div>

    <!-- PAGINATION BAR -->
    <div
    class="
        mt-4
        flex flex-col
        sm:flex-row
        sm:items-center
        sm:justify-between
        gap-3
        text-sm
    "
    >
    <!-- INFO RANGE (LEFT) -->
    <p class="text-slate-500 whitespace-nowrap">
    Menampilkan
    <span class="font-medium text-slate-700">
        {{ pageStart }}–{{ pageEnd }}
    </span>
    dari
    <span class="font-medium text-slate-700">
        {{ klienList.length }}
    </span>
    </p>


    <!-- NAVIGATION (CENTER) -->
    <div class="flex items-center gap-2 justify-center">
    <Button
        size="sm"
        variant="outline"
        :disabled="currentPage === 1"
        @click="currentPage--"
    >
        <ChevronLeft class="w-4 h-4" />
    </Button>

    <span class="text-slate-600">
        Halaman
        <span class="font-medium text-slate-800">
        {{ currentPage }}
        </span>
        /
        {{ totalPages }}
    </span>

    <Button
        size="sm"
        variant="outline"
        :disabled="currentPage === totalPages"
        @click="currentPage++"
    >
        <ChevronRight class="w-4 h-4" />
    </Button>
    </div>

    <!-- PAGE SIZE (RIGHT) -->
    <div class="flex items-center gap-2 whitespace-nowrap ml-auto">
        <span class="text-slate-500">Tampilkan</span>

        <select
        v-model="perPage"
        class="
            rounded-md
            border border-slate-300
            bg-white
            px-3 pr-8 py-1.5
            text-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500
        "
        >
        <option v-for="n in pageSizes" :key="n" :value="n">
            {{ n }}
        </option>
        </select>

        <span class="text-slate-500">baris</span>
    </div>
    </div>

    <!-- ASSIGN MODAL -->
    <Dialog v-model:open="showAssignModal">
      <DialogContent
      class="max-w-lg border-t-4 border-blue-500"
      >
        <DialogHeader>
          <DialogTitle>Assign Narasumber</DialogTitle>
        </DialogHeader>

        <Input
          placeholder="Cari nama atau unit..."
          v-model="searchPetugas"
          class="mb-3"
        />

        <div class="space-y-2 max-h-60 overflow-y-auto">
          <div
            v-for="p in narasumberMock"
            :key="p.nama"
            class="flex justify-between items-center border rounded-lg p-2"
          >
            <div>
              <p class="font-medium">{{ p.nama }}</p>
              <p class="text-xs text-slate-500">{{ p.unit }}</p>
            </div>
            <Button
            size="sm"
            variant="outline"
            @click="selectedPetugas = p.nama"
            >
            Pilih
            </Button>
          </div>
        </div>

        <DialogFooter>
          <Button
            variant="secondary"
            @click="showAssignModal = false"
        >
            Batal
        </Button>

        <Button
            :disabled="!selectedPetugas"
            class="bg-blue-600 hover:bg-blue-700 text-white"
            @click="assignPetugas(selectedPetugas)"
        >
            Simpan
        </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- DETAIL MODAL -->
    <Dialog v-model:open="showDetailModal">
      <DialogContent
      class="max-w-lg border-t-4 border-blue-500"
      >
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
                    <LinkIcon class="w-4 h-4" />
                    Detail Zoom Meeting
                </h4>

                <div
                    class="bg-blue-50 border border-blue-200
            rounded-lg p-3 space-y-2 text-sm"
                >
                    <div class="flex justify-between items-center">
                    <span class="font-medium">Link Meeting</span>
                    <Button
                        size="xs"
                        variant="ghost"
                        @click="copyText(selectedKlien?.integrasi?.linkZoom)"
                    >
                        Salin
                    </Button>
                    </div>
                    <p class="text-blue-700 font-medium break-all">
                    {{ selectedKlien?.integrasi?.linkZoom ?? '-' }}
                    </p>

                    <div class="flex justify-between items-center">
                    <span class="font-medium">Meeting ID</span>
                    <Button
                        size="xs"
                        variant="ghost"
                        @click="copyText(selectedKlien?.integrasi?.meetingId)"
                    >
                        Salin
                    </Button>
                    </div>
                    <p>{{ selectedKlien?.integrasi?.meetingId ?? '-' }}</p>

                    <div class="flex justify-between items-center">
                    <span class="font-medium">Passcode</span>
                    <Button
                        size="xs"
                        variant="ghost"
                        @click="copyText(selectedKlien?.integrasi?.passcode)"
                    >
                        Salin
                    </Button>
                    </div>
                    <p>{{ selectedKlien?.integrasi?.passcode ?? '-' }}</p>
                </div>
                </div>
            <div class="space-y-1 mt-3">
                <h4 class="font-semibold text-red-700">
                    Deskripsi Singkat Masalah
                </h4>
                <div
                    class="bg-red-50/40 border border-red-200
                    rounded-lg p-3 text-sm text-slate-700"
                >
                    {{ selectedKlien?.deskripsiMasalah ?? 'Tidak ada deskripsi.' }}
                </div>
                </div>
        </div>

        <DialogFooter class="flex justify-between">
            <Button
            class="border border-slate-300
                    text-slate-700
                    hover:bg-slate-100"
            @click="showDetailModal = false"
            >
            Tutup
            </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <Teleport to="body">
    <div
    v-if="showToast"
    class="
        fixed z-50
        bottom-4
        left-1/2 -translate-x-1/2
        sm:left-auto sm:right-4 sm:translate-x-0
        bg-slate-900 text-white
        px-4 py-2 rounded-lg
        shadow-lg text-sm
        max-w-[90vw]
        text-center
    "
    >
    {{ toastMessage }}
    </div>
    </Teleport>
</template>
