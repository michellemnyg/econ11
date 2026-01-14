<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import {
  UserPlus,
  Link as LinkIcon,
  Eye,
  Search,
  ChevronLeft,
  ChevronRight,
  ChevronUp,
  ChevronDown,
} from 'lucide-vue-next'

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Badge } from '@/Components/ui/badge'
import { Select, SelectItem } from '@/Components/ui/select'

/* ======================
   ROLE (dummy)
====================== */
const role = 'operator' // operator | ketua_tim | narasumber

/* ======================
   FILTER STATE
====================== */
const search = ref('')
const filterSesi = ref('all')
const filterTanggal = ref('2026-01-14')
const sortBy = ref('sesi') // sesi | nama | instansi | topik
const sortDirection = ref('asc') // asc | desc

/* ======================
   PAGINATION
====================== */
const currentPage = ref(1)
const perPage = 5

/* ======================
   DUMMY DATA
====================== */
const klienList = ref([
  {
    id: 1,
    tanggal: '2026-01-14',
    sesi: 'Sesi 1 (09:00 - 09:45)',
    nip: '198901012019031001',
    nama: 'Ahmad Fauzi',
    instansi: 'BKD Provinsi Jawa Tengah',
    topik: 'Pangkat dan Jabatan',
    petugas: null,
    linkZoom: null,
    meetingId: null,
    passcode: null,
    deskripsi: null,
  },
  {
    id: 2,
    tanggal: '2026-01-14',
    sesi: 'Sesi 3 (11:00 - 11:45)',
    nip: '197805102010122002',
    nama: 'Siti Rahmawati',
    instansi: 'Kemenhub',
    topik: 'Pengembangan Karir',
    petugas: 'Narasumber B',
    linkZoom: 'https://zoom.us/j/123456789',
    meetingId: '123 456 7890',
    passcode: 'econ11',
    deskripsi:
      'Konsultasi terkait persyaratan kenaikan pangkat dari golongan III/c ke III/d. Membutuhkan penjelasan mengenai dokumen yang diperlukan.',
  },
  {
    id: 3,
    tanggal: '2026-01-14',
    sesi: 'Sesi 2 (10:00 - 10:45)',
    nip: '198112122008121001',
    nama: 'Budi Santoso',
    instansi: 'Pemprov Jawa Timur',
    topik: 'Disiplin',
    petugas: 'Narasumber A',
    linkZoom: null,
    meetingId: null,
    passcode: null,
    deskripsi: null,
  },
])

const narasumberList = ref([
  { nama: 'Narasumber A', unit: 'Manajemen ASN' },
  { nama: 'Narasumber B', unit: 'Pengembangan Karir' },
  { nama: 'Narasumber C', unit: 'Pengadaan' },
])

/* ======================
   FILTERED DATA
====================== */
const filteredData = computed(() => {
  return klienList.value.filter((k) => {
    const matchSearch =
      k.nama.toLowerCase().includes(search.value.toLowerCase()) ||
      k.nip.includes(search.value) ||
      k.instansi.toLowerCase().includes(search.value.toLowerCase())

    const matchSesi =
      filterSesi.value === 'all' || k.sesi.includes(filterSesi.value)

    const matchTanggal = k.tanggal === filterTanggal.value

    return matchSearch && matchSesi && matchTanggal
  })
})

const totalPages = computed(() =>
  Math.ceil(filteredData.value.length / perPage)
)

/* ======================
   SORTING
====================== */
const extractSesiNumber = (sesi) => {
  const match = sesi.match(/Sesi\s(\d+)/)
  return match ? Number(match[1]) : 0
}

const sortedData = computed(() => {
  return [...filteredData.value].sort((a, b) => {
    let valA = ''
    let valB = ''

    switch (sortBy.value) {
      case 'nama':
        valA = a.nama.toLowerCase()
        valB = b.nama.toLowerCase()
        break

      case 'instansi':
        valA = a.instansi.toLowerCase()
        valB = b.instansi.toLowerCase()
        break

      case 'topik':
        valA = a.topik.toLowerCase()
        valB = b.topik.toLowerCase()
        break

      case 'sesi':
      default:
        valA = extractSesiNumber(a.sesi)
        valB = extractSesiNumber(b.sesi)
        break
    }

    if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1
    if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1
    return 0
  })
})

/* ======================
   PAGINATED DATA
====================== */
const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return sortedData.value.slice(start, start + perPage)
})

/* ======================
   SORT HANDLER (HEADER CLICK)
====================== */
const handleSort = (field) => {
  if (sortBy.value === field) {
    sortDirection.value =
      sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortBy.value = field
    sortDirection.value = 'asc'
  }
    currentPage.value = 1
}

const isSorted = (field) => sortBy.value === field

/* ======================
   MODAL STATE
====================== */
const showAssignModal = ref(false)
const showDetailModal = ref(false)
const selectedKlien = ref(null)
const searchPetugas = ref('')
const selectedPetugas = ref(null)

/* ======================
   ACTIONS
====================== */
const openAssign = (klien) => {
  selectedKlien.value = klien
  selectedPetugas.value = null
  showAssignModal.value = true
}

const openDetail = (klien) => {
  selectedKlien.value = klien
  showDetailModal.value = true
}

const assignPetugas = (nama) => {
  selectedKlien.value.petugas = nama
  showAssignModal.value = false
}

const copyLink = (link) => {
  navigator.clipboard.writeText(link)
  alert('Link Zoom berhasil disalin')
}

const copyText = (text) => {
  if (!text) return
  navigator.clipboard.writeText(text)
  alert('Berhasil disalin')
}
</script>

<template>
  <Head title="Klien" />

  <AdminLayout>
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
    <div
      class="bg-white border border-slate-200 rounded-xl p-4 mb-4 flex flex-col lg:flex-row gap-3"
    >
      <Input
        v-model="search"
        placeholder="Cari nama / NIP / instansi..."
        class="lg:w-1/3"
      />

      <select
        v-model="filterSesi"
        class="rounded-lg border border-slate-300 pl-3 pr-10 py-2 text-sm"
      >
        <option value="all">Semua Sesi</option>
        <option value="Sesi 1">Sesi 1</option>
        <option value="Sesi 2">Sesi 2</option>
        <option value="Sesi 3">Sesi 3</option>
        <option value="Sesi 4">Sesi 4</option>
        <option value="Sesi 5">Sesi 5</option>
      </select>

      <select
        v-model="sortBy"
        class="rounded-lg border border-slate-300 pl-3 pr-10 py-2 text-sm"
        >
        <option value="sesi">Urutkan: Sesi</option>
        <option value="nama">Urutkan: Nama</option>
        <option value="instansi">Urutkan: Instansi</option>
        <option value="topik">Urutkan: Topik</option>
      </select>

      <select
        v-model="sortDirection"
        class="rounded-lg border border-slate-300 pl-3 pr-10 py-2 text-sm"
        >
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
      </select>

      <input
        type="date"
        v-model="filterTanggal"
        class="rounded-lg border border-slate-300 px-3 py-2 text-sm"
      />
    </div>

    <!-- TABLE -->
    <div
    class="bg-white border border-slate-200 rounded-xl overflow-x-auto shadow-sm"
    >
    <table class="w-full text-sm">
        <!-- STICKY HEADER -->
        <thead
        class="sticky top-0 z-10
                bg-gradient-to-r from-blue-50 via-slate-50 to-blue-50
                text-slate-700 border-b border-slate-200"
        >
        <tr>
            <th class="px-4 py-3 text-left font-semibold">
            Tanggal
            </th>

            <!-- SESI -->
           <th
            class="px-4 py-3 text-left font-semibold cursor-pointer
                    hover:text-blue-700 transition select-none"
            @click="handleSort('sesi')"
            >
            <div class="flex items-center gap-1">
                Sesi

                <ChevronUp
                v-if="isSorted('sesi') && sortDirection === 'asc'"
                class="w-3 h-3 text-blue-600"
                />

                <ChevronDown
                v-if="isSorted('sesi') && sortDirection === 'desc'"
                class="w-3 h-3 text-blue-600"
                />
            </div>
            </th>

            <th class="px-4 py-3 text-left font-semibold">
            NIP
            </th>

            <!-- NAMA -->
            <th
            class="px-4 py-3 text-left font-semibold cursor-pointer
                    hover:text-blue-700 transition"
            @click="handleSort('nama')"
            >
            <div class="flex items-center gap-1">
                Nama
                <ChevronUp
                v-if="isSorted('nama') && sortDirection === 'asc'"
                class="w-3 h-3 text-blue-600"
                />

                <ChevronDown
                v-if="isSorted('nama') && sortDirection === 'desc'"
                class="w-3 h-3 text-blue-600"
                />
            </div>
            </th>


            <!-- INSTANSI -->
            <th
            class="px-4 py-3 text-left font-semibold cursor-pointer
                    hover:text-blue-700 transition"
            @click="handleSort('instansi')"
            >
            <div class="flex items-center gap-1">
            Instansi
                <ChevronUp
                v-if="isSorted('instansi') && sortDirection === 'asc'"
                class="w-3 h-3 text-blue-600"
                />

                <ChevronDown
                v-if="isSorted('instansi') && sortDirection === 'desc'"
                class="w-3 h-3 text-blue-600"
                />
            </div>
            </th>


            <th class="px-4 py-3 text-left font-semibold">
            Topik
            </th>

            <th class="px-4 py-3 text-left font-semibold">
            Petugas
            </th>

            <th class="px-4 py-3 text-left font-semibold">
            Link
            </th>

            <th class="px-4 py-3 text-center font-semibold">
            Aksi
            </th>
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

            <td class="px-4 py-3">
            <button
                v-if="k.linkZoom"
                @click="copyLink(k.linkZoom)"
                class="text-blue-700 hover:text-blue-800
                    hover:underline flex items-center gap-1 font-medium"
            >
                <LinkIcon class="w-4 h-4" /> Copy
            </button>

            <span
                v-else
                class="inline-block rounded-md bg-red-50 text-red-600
                    px-2 py-0.5 text-xs font-medium"
            >
                Belum
            </span>
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

    <!-- PAGINATION -->
    <div class="flex justify-between items-center mt-4">
    <p class="text-sm text-slate-500">
        Halaman {{ currentPage }} dari {{ totalPages }}
    </p>

    <div class="flex gap-2">
        <Button
        size="sm"
        variant="outline"
        :disabled="currentPage === 1"
        @click="currentPage--"
        >
        <ChevronLeft class="w-4 h-4" />
        </Button>

        <Button
        size="sm"
        variant="outline"
        :disabled="currentPage === totalPages"
        @click="currentPage++"
        >
        <ChevronRight class="w-4 h-4" />
        </Button>
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
            v-for="p in narasumberList"
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
                        @click="copyText(selectedKlien?.linkZoom)"
                    >
                        Salin
                    </Button>
                    </div>
                    <p class="text-blue-700 font-medium break-all">
                    {{ selectedKlien?.linkZoom ?? '-' }}
                    </p>

                    <div class="flex justify-between items-center">
                    <span class="font-medium">Meeting ID</span>
                    <Button
                        size="xs"
                        variant="ghost"
                        @click="copyText(selectedKlien?.meetingId)"
                    >
                        Salin
                    </Button>
                    </div>
                    <p>{{ selectedKlien?.meetingId ?? '-' }}</p>

                    <div class="flex justify-between items-center">
                    <span class="font-medium">Passcode</span>
                    <Button
                        size="xs"
                        variant="ghost"
                        @click="copyText(selectedKlien?.passcode)"
                    >
                        Salin
                    </Button>
                    </div>
                    <p>{{ selectedKlien?.passcode ?? '-' }}</p>
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
                    {{ selectedKlien?.deskripsi ?? 'Tidak ada deskripsi.' }}
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
  </AdminLayout>
</template>
