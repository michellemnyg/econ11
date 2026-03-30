<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, usePage, router } from '@inertiajs/vue3' // Tambahkan router
import EmptyState from '@/Components/EmptyState.vue'
import { Users, Activity, CheckCircle, Calendar, Inbox, BarChart3 } from 'lucide-vue-next'
import { ref, computed, onMounted, watch, nextTick } from 'vue'

/* =======================
   CHART.JS
======================= */
import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip } from 'chart.js'
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip)

/* =======================
   PROPS DARI LARAVEL (DATA ASLI)
======================= */
const props = defineProps({
  filters: Object,
  stats: Object,
  topik: Array,
  instansi: Array,
  kinerja: Array,
})

const page = usePage()
const userName = page.props.auth?.user?.name ?? 'Administrator'

/* =======================
   FILTER WAKTU (MENGGUNAKAN INERTIA)
======================= */
const showPeriodPicker = ref(false)
const tempStartDate = ref(props.filters.start_date)
const tempEndDate = ref(props.filters.end_date)

const applyPeriod = () => {
  showPeriodPicker.value = false
  // Minta Laravel menghitung ulang berdasarkan tanggal baru
  router.get('/dashboard', {
    start_date: tempStartDate.value,
    end_date: tempEndDate.value
  }, {
    preserveState: true,
    preserveScroll: true
  })
}

const periodLabel = computed(() => {
  if (props.filters.start_date === props.filters.end_date) return props.filters.start_date
  return `${props.filters.start_date} s/d ${props.filters.end_date}`
})

/* =======================
   COMPUTED BOOLEANS
======================= */
const hasTopikData = computed(() => props.topik && props.topik.length > 0)
const hasInstansiData = computed(() => props.instansi && props.instansi.length > 0)
const hasKinerjaData = computed(() => props.kinerja && props.kinerja.length > 0)

/* =======================
   CHART.JS RENDERER
======================= */
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
        backgroundColor: '#3b82f6',
        borderRadius: 6,
        barThickness: 18,
      }],
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          displayColors: false,
          callbacks: {
            title: (items) => `Narasumber: ${items[0].label}`,
            label: (item) => `Jumlah konsultasi: ${item.raw}`,
          },
        },
      },
      scales: {
        x: { grid: { display: false }, ticks: { stepSize: 1 } },
        y: { grid: { display: false } },
      },
    },
  })
}

watch(
  () => props.kinerja,
  async (newVal) => {
    if (!newVal || newVal.length === 0) {
      if (chartInstance) chartInstance.destroy()
      chartInstance = null
      return
    }
    await nextTick()
    renderChart(newVal)
  },
  { immediate: true, deep: true }
)
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
      <div>
        <h2 class="text-2xl font-bold text-slate-800">Selamat Datang, {{ userName }}</h2>
        <p class="text-sm text-slate-500 mt-1">Ringkasan aktivitas konsultasi ASN</p>
      </div>

      <div class="relative flex items-center gap-3">
        <span class="text-sm font-medium text-slate-600">Pilih Periode</span>
        <button
          type="button"
          @click="showPeriodPicker = !showPeriodPicker"
          class="flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"
        >
          <Calendar class="w-4 h-4 text-slate-500" />
          <span>{{ periodLabel }}</span>
        </button>

        <div v-if="showPeriodPicker" class="absolute right-0 top-full mt-2 w-72 rounded-xl border border-slate-200 bg-white p-4 shadow-lg z-20">
          <p class="text-sm font-medium text-slate-700 mb-3">Pilih Rentang Tanggal</p>
          <div class="space-y-3">
            <div>
              <label class="text-xs text-slate-500">Dari</label>
              <input type="date" v-model="tempStartDate" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
              <label class="text-xs text-slate-500">Sampai</label>
              <input type="date" v-model="tempEndDate" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" />
            </div>
          </div>
          <div class="mt-4 flex justify-end">
            <button class="text-sm text-blue-600 font-semibold hover:underline" @click="applyPeriod">Terapkan</button>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
          <p class="text-sm font-medium text-slate-600">Total Klien</p>
          <Users class="w-6 h-6 text-blue-500" />
        </div>
        <p class="text-3xl font-bold text-slate-800 mt-2">{{ stats?.total || 0 }}</p>
        <div class="mt-3 h-1 w-full bg-blue-100 rounded">
          <div class="h-1 bg-blue-500 rounded w-full"></div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
          <p class="text-sm font-medium text-slate-600">Konsultasi Aktif</p>
          <Activity class="w-6 h-6 text-amber-500" />
        </div>
        <p class="text-3xl font-bold text-slate-800 mt-2">{{ stats?.aktif || 0 }}</p>
        <div class="mt-3 h-1 w-full bg-amber-100 rounded">
          <div class="h-1 bg-amber-500 rounded" :style="`width: ${stats?.total ? (stats.aktif / stats.total) * 100 : 0}%`"></div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
          <p class="text-sm font-medium text-slate-600">Konsultasi Selesai</p>
          <CheckCircle class="w-6 h-6 text-emerald-500" />
        </div>
        <p class="text-3xl font-bold text-slate-800 mt-2">{{ stats?.selesai || 0 }}</p>
        <div class="mt-3 h-1 w-full bg-emerald-100 rounded">
          <div class="h-1 bg-emerald-500 rounded" :style="`width: ${stats?.total ? (stats.selesai / stats.total) * 100 : 0}%`"></div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col shadow-sm">
        <h3 class="text-lg font-semibold text-slate-800">Topik Populer</h3>
        <p class="text-sm text-slate-500 mt-1">Topik konsultasi yang paling sering muncul</p>
        <div class="flex-1 mt-4">
          <ul v-if="hasTopikData" class="space-y-3">
            <li v-for="t in topik" :key="t.nama" class="flex items-center justify-between">
              <span class="text-sm text-slate-600 truncate mr-2">{{ t.nama }}</span>
              <span class="rounded-full bg-blue-100 text-blue-600 text-xs font-bold px-2.5 py-1">{{ t.jumlah }}</span>
            </li>
          </ul>
          <div v-else class="h-full flex items-center justify-center py-6">
            <EmptyState :icon="Inbox" message="Tidak ada data topik" hint="Ubah filter tanggal" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col shadow-sm">
        <h3 class="text-lg font-semibold text-slate-800">Instansi Terbanyak</h3>
        <p class="text-sm text-slate-500 mt-1">Berdasarkan asal instansi klien</p>
        <div class="flex-1 mt-4">
          <ul v-if="hasInstansiData" class="space-y-3">
            <li v-for="i in instansi" :key="i.nama" class="flex items-center justify-between">
              <span class="text-sm text-slate-600 truncate mr-2">{{ i.nama }}</span>
              <span class="rounded-full bg-violet-100 text-violet-600 text-xs font-bold px-2.5 py-1">{{ i.jumlah }}</span>
            </li>
          </ul>
          <div v-else class="h-full flex items-center justify-center py-6">
            <EmptyState :icon="Inbox" message="Tidak ada data instansi" hint="Data akan muncul jika ada klien" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-800 mb-1">Capaian Narasumber</h3>
        <p class="text-sm text-slate-500 mb-4">Jumlah sesi yang ditangani petugas</p>
        <div class="relative h-64 sm:h-72">
          <canvas v-if="hasKinerjaData" ref="chartRef"></canvas>
          <div v-else class="absolute inset-0 flex items-center justify-center">
            <EmptyState :icon="BarChart3" message="Tidak ada data kinerja" hint="Lakukan plotting petugas terlebih dahulu" />
          </div>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>
