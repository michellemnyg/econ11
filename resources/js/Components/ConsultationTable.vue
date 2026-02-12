<script setup>
import { computed } from 'vue'
import {
  UserPlus,
  Link as LinkIcon,
  Eye,
  ChevronLeft,
  ChevronRight,
  Ban,
  Calendar,
  Building2,
  User,
  Hash
} from 'lucide-vue-next'
import { Button } from '@/Components/ui/button'
import { Badge } from '@/Components/ui/badge'
import ConsultationStatusBadge from '@/Components/ConsultationStatusBadge.vue'

const props = defineProps({
  data: { type: Array, required: true },
  isLoading: { type: Boolean, default: false },
  // Pagination Props
  currentPage: { type: Number, required: true },
  totalPages: { type: Number, required: true },
  perPage: { type: Number, required: true },
  totalItems: { type: Number, required: true },
  pageStart: { type: Number, required: true },
  pageEnd: { type: Number, required: true },
  pageSizes: { type: Array, default: () => [5, 10, 25, 50, 100] },
  // Role
  role: { type: String, default: 'admin' }
})

const emit = defineEmits([
  'update:currentPage',
  'update:perPage',
  'open-assign',
  'open-detail',
  'copy-link'
])

// --- LOGIC PEWARNAAN TOPIK ---
// Anda bisa sesuaikan key ini dengan data real dari DB Anda
const getTopicColor = (topicName) => {
  const t = topicName?.toLowerCase() || ''

  if (t.includes('kepegawaian') || t.includes('hukum') || t.includes('disiplin')) {
    // Aksen MERAH (Sesuai request)
    return 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100'
  } else if (t.includes('infrastruktur') || t.includes('jaringan') || t.includes('server')) {
    return 'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200'
  } else if (t.includes('aplikasi') || t.includes('sistem')) {
    return 'bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100'
  } else {
    // Default
    return 'bg-slate-50 text-slate-600 border-slate-200'
  }
}
</script>

<template>
  <div class="space-y-4">

    <div class="hidden md:block bg-white border border-slate-200 rounded-xl shadow-sm relative overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-[1100px] w-full text-sm">
          <thead class="sticky top-0 z-10 bg-gradient-to-r from-blue-50 via-slate-50 to-blue-50 text-slate-700 border-b border-slate-200">
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
            <tr v-if="data.length === 0">
              <td colspan="10" class="px-4 py-8 text-center text-slate-500 italic">
                Tidak ada data konsultasi.
              </td>
            </tr>
            <tr
              v-for="k in data"
              :key="k.id"
              class="border-t transition even:bg-slate-50/50 hover:bg-blue-50/40"
            >
              <td class="px-4 py-3"><ConsultationStatusBadge :status="k.status" /></td>
              <td class="px-4 py-3">{{ k.tanggal }}</td>
              <td class="px-4 py-3">
                <span class="inline-block rounded-md bg-blue-50 text-blue-700 px-2 py-0.5 text-xs font-medium">
                  {{ k.sesi }}
                </span>
              </td>
              <td class="px-4 py-3">{{ k.nip }}</td>
              <td class="px-4 py-3 font-semibold text-slate-800">{{ k.nama }}</td>
              <td class="px-4 py-3">{{ k.instansi }}</td>
              <td class="px-4 py-3">
                <Badge :class="['border font-normal', getTopicColor(k.topik)]">
                  {{ k.topik }}
                </Badge>
              </td>
              <td class="px-4 py-3">
                <span v-if="k.petugas" class="text-slate-700 font-medium">{{ k.petugas }}</span>
                <span v-else class="italic text-slate-400">-</span>
              </td>
              <td class="px-4 py-3 w-[90px]">
                <div class="flex items-center gap-1">
                  <button
                    v-if="k.integrasi?.linkZoom"
                    @click="$emit('copy-link', k.integrasi.linkZoom)"
                    class="text-blue-700 hover:text-blue-800 flex items-center gap-1 text-xs font-medium"
                  >
                    <LinkIcon class="w-4 h-4" /> Copy
                  </button>
                  <span v-else class="inline-flex items-center gap-1 rounded-md bg-red-50 text-red-600 px-2 py-0.5 text-xs font-medium">
                    <Ban class="w-3 h-3" /> Belum
                  </span>
                </div>
              </td>
              <td class="px-4 py-3 text-center space-x-2">
                <Button v-if="role !== 'narasumber'" size="sm" variant="outline" @click="$emit('open-assign', k)">
                  <UserPlus class="w-4 h-4" />
                </Button>
                <Button size="sm" variant="secondary" @click="$emit('open-detail', k)">
                  <Eye class="w-4 h-4" />
                </Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="md:hidden space-y-3">
      <div v-if="data.length === 0" class="text-center p-8 border rounded-xl bg-white text-slate-500 italic shadow-sm">
        Tidak ada data konsultasi.
      </div>

      <div
        v-for="k in data"
        :key="k.id"
        class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-3 relative overflow-hidden"
      >
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-red-500"></div>

        <div class="flex justify-between items-start pl-2">
          <ConsultationStatusBadge :status="k.status" />
          <div class="text-xs text-slate-500 flex flex-col items-end">
             <span class="flex items-center gap-1"><Calendar class="w-3 h-3"/> {{ k.tanggal }}</span>
             <span class="font-medium text-blue-600">{{ k.sesi }}</span>
          </div>
        </div>

        <div class="pl-2 space-y-1">
          <h3 class="font-bold text-slate-800 text-base leading-tight">{{ k.nama }}</h3>
          <p class="text-xs text-slate-500 flex items-center gap-1">
            <Hash class="w-3 h-3"/> {{ k.nip }}
          </p>
          <p class="text-sm text-slate-600 flex items-center gap-1 pt-1">
            <Building2 class="w-3.5 h-3.5 text-slate-400"/> {{ k.instansi }}
          </p>

          <div class="pt-2">
            <Badge :class="['text-xs border font-normal', getTopicColor(k.topik)]">
               {{ k.topik }}
            </Badge>
          </div>
        </div>

        <div class="pl-2 pt-2 border-t border-slate-100 flex justify-between items-center">
             <div class="text-xs">
                <span v-if="k.petugas" class="text-slate-700 flex items-center gap-1">
                   <User class="w-3 h-3"/> {{ k.petugas }}
                </span>
                <span v-else class="text-red-500 italic flex items-center gap-1">
                   <UserPlus class="w-3 h-3"/> Belum ditunjuk
                </span>
             </div>

             <button
                v-if="k.integrasi?.linkZoom"
                @click="$emit('copy-link', k.integrasi.linkZoom)"
                class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-medium flex items-center gap-1 active:scale-95 transition"
              >
                <LinkIcon class="w-3 h-3" /> Zoom
              </button>
        </div>

        <div class="pl-2 grid grid-cols-2 gap-2 pt-1">
           <Button v-if="role !== 'narasumber'" variant="outline" size="sm" class="w-full text-xs h-8" @click="$emit('open-assign', k)">
              Assign
           </Button>
           <Button variant="secondary" size="sm" class="w-full text-xs h-8 bg-slate-100 hover:bg-slate-200" @click="$emit('open-detail', k)">
              Detail
           </Button>
        </div>
      </div>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
      <p class="text-slate-500 whitespace-nowrap text-center sm:text-left">
        Menampilkan <span class="font-medium text-slate-700">{{ pageStart }}–{{ pageEnd }}</span> dari <span class="font-medium text-slate-700">{{ totalItems }}</span>
      </p>

      <div class="flex items-center gap-2 justify-center">
        <Button size="sm" variant="outline" :disabled="currentPage === 1" @click="$emit('update:currentPage', currentPage - 1)">
          <ChevronLeft class="w-4 h-4" />
        </Button>
        <span class="text-slate-600">
          Hal <span class="font-medium text-slate-800">{{ currentPage }}</span> / {{ totalPages }}
        </span>
        <Button size="sm" variant="outline" :disabled="currentPage === totalPages" @click="$emit('update:currentPage', currentPage + 1)">
          <ChevronRight class="w-4 h-4" />
        </Button>
      </div>

      <div class="flex items-center gap-2 justify-center sm:justify-end whitespace-nowrap">
        <span class="text-slate-500 hidden sm:inline">Tampilkan</span>
        <select
          :value="perPage"
          @change="$emit('update:perPage', Number($event.target.value))"
          class="rounded-md border border-slate-300 bg-white px-3 pr-8 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option v-for="n in pageSizes" :key="n" :value="n">{{ n }}</option>
        </select>
        <span class="text-slate-500 hidden sm:inline">baris</span>
      </div>
    </div>

  </div>
</template>
