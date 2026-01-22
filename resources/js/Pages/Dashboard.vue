<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import EmptyState from '@/Components/EmptyState.vue'
import { Users, Activity, CheckCircle, Calendar, Building2, Inbox, Info, BarChart3 } from 'lucide-vue-next'
import { ref, computed, onMounted, watch, nextTick} from 'vue'

/* =======================
   CHART.JS
======================= */
import {Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip} from 'chart.js'
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip)

/* =======================
   USER
======================= */
const page = usePage()
const userName = page.props.auth?.user?.name ?? 'Administrator'
const userRole = page.props.auth?.user?.role ?? 'operator'

/* =======================
   FILTER WAKTU
======================= */
// input sementara (date picker)
const tempStartDate = ref('')
const tempEndDate = ref('')

const applyPeriod = () => {
  appliedStartDate.value = tempStartDate.value
  appliedEndDate.value = tempEndDate.value
  showPeriodPicker.value = false
}

// filter aktif (dipakai data)
const appliedStartDate = ref('')
const appliedEndDate = ref('')

/* =======================
   DEFAULT DATA (BASELINE)
======================= */
const defaultData = ref({
  stats: { total: 24, aktif: 7, selesai: 17 },

  instansi: [
    { nama: 'BKD Provinsi Jawa Barat', jumlah: 9 },
    { nama: 'BKD Provinsi Jawa Tengah', jumlah: 7 },
    { nama: 'BKD Provinsi Jawa Timur', jumlah: 5 },
    { nama: 'BKD Provinsi DKI Jakarta', jumlah: 3 },
  ],

  kinerja: [
    { nama: 'Narasumber A', jumlah: 8 },
    { nama: 'Narasumber B', jumlah: 6 },
    { nama: 'Narasumber C', jumlah: 5 },
    { nama: 'Narasumber D', jumlah: 3 },
  ],

  topik: [], // default memang kosong
})


/* =======================
   DATA PER TANGGAL (PASTI MINGGU INI)
======================= */
const today = new Date()
const todayStr = today.toISOString().slice(0, 10)
const yesterday = new Date(today)
yesterday.setDate(today.getDate() - 1)
const yesterdayStr = yesterday.toISOString().slice(0, 10)

const dataByDate = ref({
  [yesterdayStr]: {
    stats: { total: 10, aktif: 3, selesai: 7 },
    instansi: [
      { nama: 'BKD Provinsi Jawa Barat', jumlah: 4 },
      { nama: 'BKD Provinsi DKI Jakarta', jumlah: 2 },
      { nama: 'BKD Provinsi Banten', jumlah: 1 },
      { nama: 'BKD Provinsi Jawa Timur', jumlah: 2 },
      { nama: 'BKD Provinsi Jawa Tengah', jumlah: 1 },
    ],
    kinerja: [
      { nama: 'Narasumber A', jumlah: 4 },
      { nama: 'Narasumber B', jumlah: 3 },
      { nama: 'Narasumber C', jumlah: 2 },
      { nama: 'Narasumber D', jumlah: 1 },
    ],
    topik: [
      { nama: 'Pangkat dan Jabatan', jumlah: 2 },
      { nama: 'Pengadaan', jumlah: 1 },
    ],
  },

  [todayStr]: {
    stats: { total: 14, aktif: 4, selesai: 10 },
    instansi: [
      { nama: 'BKD Provinsi Jawa Tengah', jumlah: 5 },
      { nama: 'BKD Provinsi Jawa Barat', jumlah: 3 },
      { nama: 'BKD Provinsi Jawa Timur', jumlah: 2 },
      { nama: 'BKD Provinsi DKI Jakarta', jumlah: 2 },
      { nama: 'BKD Provinsi Bali', jumlah: 2 },
    ],
    kinerja: [
      { nama: 'Narasumber A', jumlah: 4 },
      { nama: 'Narasumber B', jumlah: 3 },
      { nama: 'Narasumber C', jumlah: 3 },
      { nama: 'Narasumber D', jumlah: 2 },
    ],
    topik: [
      { nama: 'Pangkat dan Jabatan', jumlah: 1 },
      { nama: 'Pengembangan Karir', jumlah: 2 },
    ],
  },
})

/* =======================
   HELPER TANGGAL
======================= */
const startOfWeek = (d) => {
  const x = new Date(d)
  const day = x.getDay() || 7
  x.setDate(x.getDate() - (day - 1))
  return x.toISOString().slice(0, 10)
}

const endOfWeek = (d) => {
  const x = new Date(d)
  const day = x.getDay() || 7
  x.setDate(x.getDate() + (7 - day))
  return x.toISOString().slice(0, 10)
}

/* =======================
   FILTERED DATA (INTI)
======================= */
const filteredData = computed(() => {
  if (!appliedStartDate.value || !appliedEndDate.value) {
    return defaultData.value
  }

  const dates = Object.keys(dataByDate.value).filter(
    d => d >= appliedStartDate.value && d <= appliedEndDate.value
  )

  if (!dates.length) {
    return {
      stats: { total: 0, aktif: 0, selesai: 0 },
      instansi: [],
      kinerja: [],
      topik: [],
    }
  }

  // AGREGRASI
  const stats = { total: 0, aktif: 0, selesai: 0 }
  const instansiMap = {}
  const kinerjaMap = {}
  const topikMap = {}

  dates.forEach(date => {
    const dayData = dataByDate.value[date]
    if (!dayData) return

    stats.total += dayData.stats.total
    stats.aktif += dayData.stats.aktif
    stats.selesai += dayData.stats.selesai

    dayData.instansi?.forEach(i => {
      instansiMap[i.nama] = (instansiMap[i.nama] || 0) + i.jumlah
    })

    dayData.kinerja?.forEach(k => {
      kinerjaMap[k.nama] = (kinerjaMap[k.nama] || 0) + k.jumlah
    })

    dayData.topik?.forEach(t => {
      topikMap[t.nama] = (topikMap[t.nama] || 0) + t.jumlah
    })
  })

  return {
    stats,

    instansi: Object.entries(instansiMap)
      .map(([nama, jumlah]) => ({ nama, jumlah }))
      .sort((a, b) => b.jumlah - a.jumlah)
      .slice(0, 5),

    kinerja: Object.entries(kinerjaMap)
      .map(([nama, jumlah]) => ({ nama, jumlah }))
      .filter(item => item.jumlah > 0),

    topik: Object.entries(topikMap)
      .map(([nama, jumlah]) => ({ nama, jumlah }))
      .sort((a, b) => b.jumlah - a.jumlah),
  }
})

const hasTopikData = computed(() => filteredData.value.topik.length > 0)
const hasInstansiData = computed(() => filteredData.value.instansi.length > 0)
const hasKinerjaData = computed(() => filteredData.value.kinerja.length > 0)

/* =======================
   CHART
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
        backgroundColor: '#3b82f6', // biru (Tailwind blue-500)
        borderRadius: 6,
        barThickness: 18,
      }],
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false, // hilangkan keterangan warna
        },
        tooltip: {
          displayColors: false, // <<< HILANGKAN KOTAK WARNA
          callbacks: {
            title: (items) => `Narasumber: ${items[0].label}`,
            label: (item) => `Jumlah konsultasi: ${item.raw}`,
          },
        },
      },
      scales: {
        x: { grid: { display: false } },
        y: { grid: { display: false } },
      },
    },
  })
}

/* =======================
   LIFECYCLE
======================= */
onMounted(() => {
  const start = startOfWeek(today)
  const end = endOfWeek(today)

  appliedStartDate.value = start
  appliedEndDate.value = end

  tempStartDate.value = start
  tempEndDate.value = end
})

const kinerjaChartData = computed(() =>
  filteredData.value.kinerja.filter(i => i.jumlah > 0)
)

watch(
  kinerjaChartData,
  async (val) => {
    if (!val.length) {
      chartInstance?.destroy()
      chartInstance = null
      return
    }

    await nextTick()
    renderChart(val)
  },
  { immediate: true }
)

/* =======================
   UI STATE
======================= */
const periodLabel = computed(() =>
  appliedStartDate.value && appliedEndDate.value
    ? `${appliedStartDate.value} - ${appliedEndDate.value}`
    : 'Minggu ini'
)

const showPeriodPicker = ref(false)
const dateInputRef = ref(null)
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout>
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
      <div>
        <h2 class="text-2xl font-bold text-slate-800">
          Selamat Datang, {{ userName }}
        </h2>
        <p class="text-sm text-slate-500 mt-1">
          Ringkasan aktivitas konsultasi ASN
        </p>
      </div>

    <!-- ACTIONS -->
    <div class="relative flex items-center gap-3">
    <!-- LABEL -->
    <span class="text-sm font-medium text-slate-600">
        Pilih Periode
    </span>

    <!-- BUTTON -->
    <button
        type="button"
        @click="showPeriodPicker = !showPeriodPicker"
        class="flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"
    >
        <Calendar class="w-4 h-4 text-slate-500" />
        <span>
            {{ periodLabel }}
        </span>
    </button>

    <!-- DROPDOWN -->
    <div
        v-if="showPeriodPicker"
        class="absolute right-0 top-full mt-2 w-72 rounded-xl border border-slate-200 bg-white p-4 shadow-lg z-20"
    >
        <p class="text-sm font-medium text-slate-700 mb-3">
        Pilih Rentang Tanggal
        </p>

        <div class="space-y-3">
        <div>
            <label class="text-xs text-slate-500">Dari</label>
            <input
            type="date"
            v-model="tempStartDate"
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
            />
        </div>

        <div>
            <label class="text-xs text-slate-500">Sampai</label>
            <input
            type="date"
            v-model="tempEndDate"
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
            />
        </div>
        </div>

        <div class="mt-4 flex justify-end">
        <button
            class="text-sm text-blue-600 hover:underline"
            @click="applyPeriod"
        >
            Terapkan
        </button>
        </div>
    </div>
    </div>
    </div>

    <!-- CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <!-- TOTAL -->
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-slate-600">Total Klien</p>
        <Users class="w-6 h-6 text-blue-500" />
        </div>
        <p class="text-3xl font-bold text-slate-800 mt-2">
        {{ filteredData.stats.total }}
        </p>
        <div class="mt-3 h-1 w-full bg-blue-100 rounded">
        <div class="h-1 bg-blue-500 rounded w-full"></div>
        </div>
    </div>

    <!-- AKTIF -->
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-slate-600">Konsultasi Aktif</p>
        <Activity class="w-6 h-6 text-red-500" />
        </div>
        <p class="text-3xl font-bold text-slate-800 mt-2">
        {{ filteredData.stats.aktif }}
        </p>
        <div class="mt-3 h-1 w-full bg-red-100 rounded">
        <div class="h-1 bg-red-500 rounded w-1/2"></div>
        </div>
    </div>

    <!-- SELESAI -->
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-slate-600">Konsultasi Selesai</p>
        <CheckCircle class="w-6 h-6 text-emerald-500" />
        </div>
        <p class="text-3xl font-bold text-slate-800 mt-2">
        {{ filteredData.stats.selesai }}
        </p>
        <div class="mt-3 h-1 w-full bg-emerald-100 rounded">
        <div class="h-1 bg-emerald-500 rounded w-3/4"></div>
        </div>
    </div>
    </div>

    <!-- CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- TOPIK POPULER -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col">
        <h3 class="text-lg font-semibold text-slate-800">
            Topik Populer
        </h3>

        <!-- DESKRIPSI -->
        <p class="text-sm text-slate-500 mt-1">
            Topik konsultasi yang paling sering muncul pada periode terpilih
        </p>

        <!-- CONTENT -->
        <div class="flex-1 mt-4">
            <ul v-if="hasTopikData" class="space-y-3">
            <li
                v-for="topik in filteredData.topik"
                :key="topik.nama"
                class="flex items-center justify-between"
            >
                <span class="text-sm text-slate-600">
                {{ topik.nama }}
                </span>
                <span
                class="rounded-full bg-blue-100 text-blue-600
                        text-xs font-medium px-2 py-1"
                >
                {{ topik.jumlah }}
                </span>
            </li>
            </ul>

            <!-- EMPTY STATE -->
            <div v-else class="h-full flex items-center justify-center">
            <EmptyState
                :icon="Inbox"
                message="Tidak ada data topik pada periode ini"
                hint="Coba ubah periode atau filter"
            />
            </div>
        </div>
        </div>

        <!-- INSTANSI TERBANYAK -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col">
        <h3 class="text-lg font-semibold text-slate-800">
            Instansi Terbanyak Konsultasi
        </h3>

        <!-- DESKRIPSI -->
        <p class="text-sm text-slate-500 mt-1">
            Instansi dengan jumlah konsultasi terbanyak pada periode terpilih
        </p>

        <!-- CONTENT -->
        <div class="flex-1 mt-4">
            <ul v-if="hasInstansiData" class="space-y-3">
            <li
                v-for="i in filteredData.instansi"
                :key="i.nama"
                class="flex items-center justify-between"
            >
                <span class="text-sm text-slate-600 truncate">
                {{ i.nama }}
                </span>
                <span
                class="rounded-full bg-violet-100 text-violet-600
                        text-xs font-medium px-2 py-1"
                >
                {{ i.jumlah }}
                </span>
            </li>
            </ul>

            <!-- EMPTY STATE -->
            <div v-else class="h-full flex items-center justify-center">
            <EmptyState
                :icon="Inbox"
                message="Tidak ada data instansi pada periode ini"
                hint="Data akan muncul jika ada konsultasi"
            />
            </div>
        </div>
        </div>

      <!-- CHART -->
      <div
        class="bg-white rounded-xl border border-slate-200 p-4"
      >
        <h3 class="text-lg font-semibold text-slate-800 mb-2">
          Capaian Kinerja Narasumber
        </h3>
        <p class="text-sm text-slate-500 mb-4">
          Jumlah konsultasi yang ditangani oleh masing-masing narasumber pada periode terpilih
        </p>

        <!-- CHART.JS CANVAS -->
        <div class="relative h-64 sm:h-72">
          <canvas
            v-if="hasKinerjaData"
            ref="chartRef"
            ></canvas>

            <!-- EMPTY STATE -->
            <div
            v-else
            class="absolute inset-0 flex items-center justify-center"
            >
            <EmptyState
                :icon="BarChart3"
                message="Tidak ada data kinerja pada periode ini"
                hint="Belum ada konsultasi yang tercatat"
            />
            </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
