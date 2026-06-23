<script setup>
import { computed } from 'vue'
import { UserPlus, Link as LinkIcon, Eye, ChevronLeft, ChevronRight, Ban, Calendar, Building2, User, Hash } from 'lucide-vue-next'
import { Button } from '@/Components/ui/button'
import { Badge } from '@/Components/ui/badge'
import ConsultationStatusBadge from '@/Components/ConsultationStatusBadge.vue'

const props = defineProps({
  data: { type: Array, required: true },
  currentPage: { type: Number, required: true },
  totalPages: { type: Number, required: true },
  perPage: { type: Number, required: true },
  totalItems: { type: Number, required: true },
  pageStart: { type: Number, required: true },
  pageEnd: { type: Number, required: true },
  pageSizes: { type: Array, default: () => [5, 10, 25, 50, 100] },
  role: { type: String, default: 'admin' }
})

const emit = defineEmits([
  'update:currentPage', 'update:perPage', 'open-assign', 'open-detail', 'copy-link'
])

const getTopicColor = (topicInput) => {
  if (!topicInput || topicInput === '-') return 'bg-slate-50 text-slate-600 border-slate-200'
  const key = topicInput.toLowerCase()
  if (key.includes('disiplin') || key.includes('pemberhentian') || key.includes('perlindungan') || key.includes('mutasi')) {
    return 'bg-red-50 text-red-700 border-red-200'
  }
  if (key.includes('promosi') || key.includes('karir') || key.includes('pangkat') || key.includes('kinerja') || key.includes('pengadaan')) {
    return 'bg-blue-50 text-blue-700 border-blue-200'
  }
  return 'bg-slate-50 text-slate-600 border-slate-200'
}
</script>

<template>
  <div class="space-y-4">
    <div class="hidden md:block bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-[1150px] w-full text-sm">
          <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
            <tr>
              <th class="px-5 py-3.5 text-left font-semibold text-xs uppercase tracking-wider">Status</th>
              <th class="px-4 py-3.5 text-left font-semibold text-xs uppercase tracking-wider">Tanggal</th>
              <th class="px-4 py-3.5 text-left font-semibold text-xs uppercase tracking-wider">Sesi</th>
              <th class="px-4 py-3.5 text-left font-semibold text-xs uppercase tracking-wider">Klien</th>
              <th class="px-4 py-3.5 text-left font-semibold text-xs uppercase tracking-wider">Topik & Instansi</th>
              <th class="px-4 py-3.5 text-left font-semibold text-xs uppercase tracking-wider">Narasumber</th>
              <th class="px-4 py-3.5 text-center font-semibold text-xs uppercase tracking-wider w-[110px]">Zoom</th>
              <th class="px-4 py-3.5 text-center font-semibold text-xs uppercase tracking-wider w-[100px]">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 bg-white">
            <tr v-if="data.length === 0">
              <td colspan="8" class="px-4 py-16 text-center text-slate-500">
                <div class="flex flex-col items-center justify-center">
                  <Ban class="w-8 h-8 text-slate-300 mb-2" />
                  <p class="font-medium text-slate-600">Tidak ada data konsultasi</p>
                </div>
              </td>
            </tr>
            <tr
              v-for="k in data"
              :key="k.id"
              class="transition-colors hover:bg-slate-50/80"
            >
              <td class="px-5 py-4 align-middle whitespace-nowrap"><ConsultationStatusBadge :status="k.status" /></td>

              <td class="px-4 py-4 align-middle whitespace-nowrap">
                <span class="font-medium text-slate-700">{{ k.tanggal }}</span>
              </td>
              <td class="px-4 py-4 align-middle whitespace-nowrap">
                <span class="text-sm font-medium text-slate-700">{{ k.sesi }}</span>
              </td>

              <td class="px-4 py-4 align-middle">
                <div class="flex flex-col">
                  <span class="font-semibold text-slate-800">{{ k.nama }}</span>
                  <span class="text-xs text-slate-500 font-mono mt-0.5">{{ k.nip }}</span>
                </div>
              </td>

              <td class="px-4 py-4 align-middle">
                <div class="flex flex-col items-start gap-1.5">
                  <Badge :class="['border font-medium text-[11px]', getTopicColor(k.topik)]">{{ k.topik }}</Badge>
                  <span class="text-xs text-slate-500 truncate max-w-[220px]" :title="k.instansi">{{ k.instansi }}</span>
                </div>
              </td>

              <td class="px-4 py-4 align-middle">
                <div v-if="k.petugas && k.petugas !== 'Belum Diplot'" class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs border border-slate-200">
                    {{ k.petugas.charAt(0) }}
                  </div>
                  <span class="text-slate-700 font-medium text-sm">{{ k.petugas }}</span>
                </div>
                <span v-else class="text-slate-400 italic text-sm">Belum diplot</span>
              </td>

              <td class="px-4 py-4 align-middle text-center">
                <button
                  v-if="k.integrasi?.linkZoom"
                  @click="$emit('copy-link', k.integrasi.linkZoom)"
                  class="mx-auto flex items-center justify-center gap-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 hover:text-slate-900 px-3 py-1.5 rounded-md text-xs font-medium transition-colors shadow-sm"
                >
                  <LinkIcon class="w-3 h-3" /> Salin
                </button>
                <span v-else class="text-slate-400 text-xs italic">-</span>
              </td>

              <td class="px-4 py-4 align-middle text-center">
                <div class="flex items-center justify-center gap-1">
                  <Button v-if="role === 'ketua_tim'" variant="ghost" size="sm" class="h-8 w-8 p-0 text-slate-500 hover:text-blue-600" @click="$emit('open-assign', k)" title="Assign Petugas">
                    <UserPlus class="w-4 h-4" />
                  </Button>
                  <Button variant="ghost" size="sm" class="h-8 w-8 p-0 text-slate-500 hover:text-slate-900" @click="$emit('open-detail', k)" title="Lihat Detail">
                    <Eye class="w-4 h-4" />
                  </Button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="md:hidden space-y-3">
       <div v-if="data.length === 0" class="text-center p-8 border rounded-xl bg-white text-slate-500 italic shadow-sm">Tidak ada data konsultasi.</div>
       <div v-for="k in data" :key="k.id" class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-3">
          <div class="flex justify-between items-start">
             <ConsultationStatusBadge :status="k.status" />
              <div class="text-xs text-slate-500 flex flex-col items-end">
                <span class="flex items-center gap-1"><Calendar class="w-3 h-3"/> {{ k.tanggal }}</span>
                <span class="font-medium text-slate-700">{{ k.sesi }}</span>
             </div>
          </div>
          <div class="space-y-1">
             <h3 class="font-bold text-slate-800 text-base leading-tight">{{ k.nama }}</h3>
             <p class="text-xs text-slate-500 flex items-center gap-1"><Hash class="w-3 h-3"/> {{ k.nip }}</p>
             <p class="text-sm text-slate-600 flex items-center gap-1 pt-1"><Building2 class="w-3.5 h-3.5 text-slate-400"/> {{ k.instansi }}</p>
             <div class="pt-2"><Badge :class="['text-[10px] border font-normal', getTopicColor(k.topik)]">{{ k.topik }}</Badge></div>
          </div>
          <div class="pt-2 border-t border-slate-100 flex justify-between items-center">
             <div class="text-xs">
                <span v-if="k.petugas && k.petugas !== 'Belum Diplot'" class="text-slate-700 font-medium flex items-center gap-1"><User class="w-3 h-3"/> {{ k.petugas }}</span>
                <span v-else class="text-slate-400 italic">Belum ditunjuk</span>
             </div>
          </div>
          <div class="grid grid-cols-2 gap-2 pt-1">
             <Button v-if="role === 'ketua_tim'" variant="outline" size="sm" class="w-full text-xs h-8" @click="$emit('open-assign', k)">Assign</Button>
             <Button variant="secondary" size="sm" class="w-full text-xs h-8 bg-slate-100 hover:bg-slate-200" @click="$emit('open-detail', k)">Detail</Button>
          </div>
       </div>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm">
      <p class="text-slate-500 whitespace-nowrap text-center sm:text-left">
        Menampilkan <span class="font-medium text-slate-800">{{ totalItems > 0 ? pageStart : 0 }}–{{ pageEnd }}</span> dari <span class="font-medium text-slate-800">{{ totalItems }}</span>
      </p>

      <div class="flex items-center gap-1.5 justify-center">
        <Button size="sm" variant="outline" class="h-8 w-8 p-0" :disabled="currentPage === 1" @click="$emit('update:currentPage', currentPage - 1)">
          <ChevronLeft class="w-4 h-4" />
        </Button>
        <span class="text-slate-600 text-sm font-medium px-3">
          Hal <span class="font-bold text-slate-800">{{ currentPage }}</span> / {{ totalPages === 0 ? 1 : totalPages }}
        </span>
        <Button size="sm" variant="outline" class="h-8 w-8 p-0" :disabled="currentPage >= totalPages" @click="$emit('update:currentPage', currentPage + 1)">
          <ChevronRight class="w-4 h-4" />
        </Button>
      </div>

      <div class="flex items-center gap-2 justify-center sm:justify-end whitespace-nowrap">
        <span class="text-slate-500">Baris per halaman:</span>
        <select
          :value="perPage"
          @change="$emit('update:perPage', Number($event.target.value))"
          class="rounded-md border border-slate-300 bg-white pl-3 pr-8 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-slate-400 cursor-pointer min-w-[70px]"
        >
          <option v-for="n in pageSizes" :key="n" :value="n">{{ n }}</option>
        </select>
      </div>
    </div>

  </div>
</template>
