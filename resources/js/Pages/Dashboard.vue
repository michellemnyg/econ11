<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import EmptyState from '@/Components/EmptyState.vue'
import { Users, Activity, CheckCircle, Calendar, Inbox, BarChart3, TrendingUp } from 'lucide-vue-next'
import { ref, computed, watch, nextTick } from 'vue'
import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip } from 'chart.js'

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip)

const props = defineProps({
  filters: Object,
  stats: Object,
  topik: Array,
  instansi: Array,
  kinerja: Array,
})

const page = usePage()
const userName = page.props.auth?.user?.name ?? 'Administrator'

const showPeriodPicker = ref(false)
const tempStartDate = ref(props.filters?.start_date || '')
const tempEndDate = ref(props.filters?.end_date || '')

const applyPeriod = () => {
  showPeriodPicker.value = false
  router.get('/dashboard', { start_date: tempStartDate.value, end_date: tempEndDate.value }, { preserveState: true, preserveScroll: true })
}

const periodLabel = computed(() => {
  if (!props.filters?.start_date) return 'Semua Waktu'
  if (props.filters.start_date === props.filters.end_date) return props.filters.start_date
  return `${props.filters.start_date} s/d ${props.filters.end_date}`
})

const hasTopikData = computed(() => props.topik && props.topik.length > 0)
const hasInstansiData = computed(() => props.instansi && props.instansi.length > 0)
const hasKinerjaData = computed(() => props.kinerja && props.kinerja.length > 0)

const chartRef = ref(null)
let chartInstance = null

const renderChart = (data) => {
  if (!chartRef.value) return
  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(chartRef.value, {
    type: 'bar',
    data: {
      labels: data.map(d => d.nama),
      datasets: [{
        data: data.map(d => d.jumlah),
        backgroundColor: '#2563eb',
        hoverBackgroundColor: '#1d4ed8',
        borderRadius: 6,
        barThickness: 24,
      }],
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          displayColors: false, backgroundColor: 'rgba(15, 23, 42, 0.9)', padding: 12, cornerRadius: 8,
          titleFont: { size: 13, family: 'Inter' }, bodyFont: { size: 12, family: 'Inter' },
          callbacks: { title: (items) => `Petugas: ${items[0].label}`, label: (item) => ` Menangani ${item.raw} Sesi` },
        },
      },
      scales: {
        x: { grid: { display: false, drawBorder: false }, ticks: { stepSize: 1, font: { family: 'Inter' } } },
        y: { grid: { display: false, drawBorder: false }, ticks: { font: { family: 'Inter', size: 12, weight: '500' } } },
      },
    },
  })
}

watch(() => props.kinerja, async (newVal) => {
    if (!newVal || newVal.length === 0) {
      if (chartInstance) chartInstance.destroy()
      chartInstance = null
      return
    }
    await nextTick()
    renderChart(newVal)
  }, { immediate: true, deep: true })
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
      <div>
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Dashboard Overview</h2>
        <p class="text-sm text-slate-500 mt-1 font-medium">Selamat datang kembali, <span class="text-blue-600 font-bold">{{ userName }}</span>.</p>
      </div>

      <div class="relative flex items-center gap-3">
        <button type="button" @click="showPeriodPicker = !showPeriodPicker" class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 transition-all shadow-sm">
          <Calendar class="w-4 h-4" /> <span>{{ periodLabel }}</span>
        </button>

        <div v-if="showPeriodPicker" class="absolute right-0 top-full mt-2 w-72 rounded-2xl border border-slate-200 bg-white p-5 shadow-xl z-20">
          <p class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">Atur Periode Statistik</p>
          <div class="space-y-4">
            <div>
              <label class="text-xs font-semibold text-slate-500">Tanggal Mulai</label>
              <input type="date" v-model="tempStartDate" class="mt-1 w-full rounded-lg border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 bg-slate-50" />
            </div>
            <div>
              <label class="text-xs font-semibold text-slate-500">Tanggal Akhir</label>
              <input type="date" v-model="tempEndDate" class="mt-1 w-full rounded-lg border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 bg-slate-50" />
            </div>
          </div>
          <div class="mt-5"><button class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 rounded-lg transition-colors" @click="applyPeriod">Terapkan Filter</button></div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-blue-600"></div>
        <div class="flex items-center justify-between mb-2">
          <p class="text-xs font-bold text-slate-500 tracking-wider uppercase">Total Klien</p>
          <div class="p-2.5 bg-blue-50 rounded-xl group-hover:bg-blue-600 group-hover:text-white text-blue-600 transition-colors"><Users class="w-5 h-5" /></div>
        </div>
        <div class="flex items-baseline gap-2">
          <p class="text-4xl font-extrabold text-slate-800">{{ stats?.total || 0 }}</p>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-red-500 to-red-600"></div>
        <div class="flex items-center justify-between mb-2">
          <p class="text-xs font-bold text-slate-500 tracking-wider uppercase">Sesi Aktif</p>
          <div class="p-2.5 bg-red-50 rounded-xl group-hover:bg-red-600 group-hover:text-white text-red-600 transition-colors"><Activity class="w-5 h-5" /></div>
        </div>
        <div class="flex items-baseline gap-2">
          <p class="text-4xl font-extrabold text-slate-800">{{ stats?.aktif || 0 }}</p>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
        <div class="flex items-center justify-between mb-2">
          <p class="text-xs font-bold text-slate-500 tracking-wider uppercase">Telah Selesai</p>
          <div class="p-2.5 bg-emerald-50 rounded-xl group-hover:bg-emerald-600 group-hover:text-white text-emerald-600 transition-colors"><CheckCircle class="w-5 h-5" /></div>
        </div>
        <div class="flex items-baseline gap-2">
          <p class="text-4xl font-extrabold text-slate-800">{{ stats?.selesai || 0 }}</p>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-4"><TrendingUp class="w-5 h-5 text-blue-600" /> Topik Populer</h3>
        <div class="flex-1">
          <ul v-if="hasTopikData" class="space-y-3">
            <li v-for="(t, index) in topik" :key="t.nama" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50 hover:border-blue-200 transition-colors">
              <div class="flex items-center gap-3 overflow-hidden">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-white border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">{{ index + 1 }}</span>
                <span class="text-sm font-semibold text-slate-700 truncate">{{ t.nama }}</span>
              </div>
              <span class="rounded-full bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1">{{ t.jumlah }}</span>
            </li>
          </ul>
          <div v-else class="h-full flex items-center justify-center py-8"><EmptyState :icon="Inbox" message="Data kosong" /></div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-4"><Users class="w-5 h-5 text-red-600" /> Instansi Teraktif</h3>
        <div class="flex-1">
          <ul v-if="hasInstansiData" class="space-y-3">
            <li v-for="(i, index) in instansi" :key="i.nama" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50 hover:border-red-200 transition-colors">
              <div class="flex items-center gap-3 overflow-hidden">
                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-white border border-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">{{ index + 1 }}</span>
                <span class="text-sm font-semibold text-slate-700 truncate">{{ i.nama }}</span>
              </div>
              <span class="rounded-full bg-red-100 text-red-700 text-xs font-bold px-3 py-1">{{ i.jumlah }}</span>
            </li>
          </ul>
          <div v-else class="h-full flex items-center justify-center py-8"><EmptyState :icon="Inbox" message="Data kosong" /></div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2"><BarChart3 class="w-5 h-5 text-emerald-600" /> Kinerja Narasumber</h3>
        <div class="relative h-[280px]">
          <canvas v-if="hasKinerjaData" ref="chartRef"></canvas>
          <div v-else class="absolute inset-0 flex items-center justify-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
            <EmptyState :icon="BarChart3" message="Grafik belum tersedia" />
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
