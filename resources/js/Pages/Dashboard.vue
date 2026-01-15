<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import {
  Users,
  Activity,
  CheckCircle,
  FileDown,
} from 'lucide-vue-next'
import { ref, computed, onMounted } from 'vue'

/* =======================
   CHART.JS IMPORT
======================= */
import {
  Chart,
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
} from 'chart.js'

Chart.register(
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip
)

/**
 * User
 */
const page = usePage()
const userName = page.props.auth?.user?.name ?? 'Administrator'
const userRole = page.props.auth?.user?.role ?? 'operator'

/**
 * Filter waktu (UI dulu)
 */
const selectedRange = ref('today')

/**
 * Dummy data
 */
const stats = ref({
  total: 24,
  aktif: 7,
  selesai: 17,
})

const topikPopuler = ref([
  { nama: 'Pangkat dan Jabatan', jumlah: 5 },
  { nama: 'Pengadaan', jumlah: 4 },
  { nama: 'Pengembangan Karir', jumlah: 3 },
  { nama: 'Penggajian dan Tunjangan', jumlah: 3 },
  { nama: 'Disiplin', jumlah: 2 },
])

/**
 * Dummy kinerja narasumber
 */
const kinerjaNarasumber = ref([
  { nama: 'Narasumber A', jumlah: 8 },
  { nama: 'Narasumber B', jumlah: 6 },
  { nama: 'Narasumber C', jumlah: 5 },
  { nama: 'Narasumber D', jumlah: 3 },
])

/**
 * Chart refs
 */
const chartRef = ref(null)
let chartInstance = null

onMounted(() => {
  if (!chartRef.value) return

  const labels = kinerjaNarasumber.value.map(i => i.nama)
  const data = kinerjaNarasumber.value.map(i => i.jumlah)

  chartInstance = new Chart(chartRef.value, {
    type: 'bar',
    data: {
      labels,
      datasets: [
        {
          label: 'Jumlah Konsultasi',
          data,
          backgroundColor: '#3b82f6', // blue-500
          borderRadius: 6,
          barThickness: 14,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: 'y',
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#0f172a',
          titleColor: '#ffffff',
          bodyColor: '#ffffff',
          displayColors: false,
          callbacks: {
            title: (items) => items[0].label,
            label: (item) => `Jumlah Konsultasi: ${item.raw}`,
          },
        },
      },
      scales: {
        x: {
          grid: {
            color: '#e2e8f0',
          },
          ticks: {
            precision: 0,
          },
        },
        y: {
          grid: {
            display: false,
          },
        },
      },
    },
  })
})

/**
 * Export (dummy)
 */
const exportPdf = () => {
  alert('Export PDF akan diimplementasikan setelah backend siap')
}
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
      <div class="flex items-center gap-2">
        <select
          v-model="selectedRange"
          class="rounded-lg border border-slate-300 text-sm pl-3 pr-10 py-2 focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="today">Hari Ini</option>
          <option value="week">Minggu ini</option>
          <option value="month">Bulan Ini</option>
        </select>

        <button
          @click="exportPdf"
          class="flex items-center gap-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2"
        >
          <FileDown class="w-4 h-4" />
          Export PDF
        </button>
      </div>
    </div>

    <!-- CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
      <!-- TOTAL -->
      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex items-center justify-between">
          <p class="text-sm font-medium text-slate-600">
            Total Klien Hari Ini
          </p>
          <Users class="w-6 h-6 text-blue-500" />
        </div>
        <p class="text-3xl font-bold text-slate-800 mt-2">
          {{ stats.total }}
        </p>
        <div class="mt-3 h-1 w-full bg-blue-100 rounded">
          <div class="h-1 bg-blue-500 rounded w-full"></div>
        </div>
      </div>

      <!-- AKTIF -->
      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex items-center justify-between">
          <p class="text-sm font-medium text-slate-600">
            Konsultasi Aktif
          </p>
          <Activity class="w-6 h-6 text-red-500" />
        </div>
        <p class="text-3xl font-bold text-slate-800 mt-2">
          {{ stats.aktif }}
        </p>
        <div class="mt-3 h-1 w-full bg-red-100 rounded">
          <div class="h-1 bg-red-500 rounded w-1/2"></div>
        </div>
      </div>

      <!-- SELESAI -->
      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex items-center justify-between">
          <p class="text-sm font-medium text-slate-600">
            Konsultasi Selesai
          </p>
          <CheckCircle class="w-6 h-6 text-emerald-500" />
        </div>
        <p class="text-3xl font-bold text-slate-800 mt-2">
          {{ stats.selesai }}
        </p>
        <div class="mt-3 h-1 w-full bg-emerald-100 rounded">
          <div class="h-1 bg-emerald-500 rounded w-3/4"></div>
        </div>
      </div>
    </div>

    <!-- CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- TOPIK POPULER -->
      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">
          Topik Populer
        </h3>

        <ul class="space-y-3">
          <li
            v-for="topik in topikPopuler"
            :key="topik.nama"
            class="flex items-center justify-between"
          >
            <span class="text-sm text-slate-600">
              {{ topik.nama }}
            </span>
            <span
              class="rounded-full bg-blue-100 text-blue-600 text-xs font-medium px-2 py-1"
            >
              {{ topik.jumlah }}
            </span>
          </li>
        </ul>
      </div>

      <!-- CHART -->
      <div
        class="bg-white rounded-xl border border-slate-200 p-4 lg:col-span-2"
      >
        <h3 class="text-lg font-semibold text-slate-800 mb-2">
          Capaian Kinerja Narasumber
        </h3>
        <p class="text-sm text-slate-500 mb-4">
          Jumlah konsultasi yang ditangani oleh masing-masing narasumber
        </p>

        <!-- CHART.JS CANVAS -->
        <div class="relative h-64 sm:h-72">
          <canvas ref="chartRef"></canvas>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
