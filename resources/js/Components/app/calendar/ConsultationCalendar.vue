<script setup>
import { ref, computed, onMounted } from 'vue'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { getCalendarSessions } from '@/Services/calendar.service'
import { useCalendarStore } from '@/Stores/useCalendarStore'

const { calendarSessions, loadCalendar } = useCalendarStore()

onMounted(loadCalendar)

const emit = defineEmits(['select-session'])

const currentMonth = ref(new Date())

const WEEK_DAYS = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']

onMounted(async () => {
  sessions.value = await getCalendarSessions()
})

const monthLabel = computed(() =>
  currentMonth.value.toLocaleDateString('id-ID', {
    month: 'long',
    year: 'numeric',
  })
)

/**
 * Ambil sesi berdasarkan tanggal
 */
const getSessionsByDate = (date) => {
  const day = calendarSessions.value.find(d => d.tanggal === date)
  return day ? day.sesi : []
}

/**
 * Hari-hari dalam bulan + empty cell
 */
const daysInMonth = computed(() => {
  const year = currentMonth.value.getFullYear()
  const month = currentMonth.value.getMonth()

  const firstDay = new Date(year, month, 1)
  const lastDay = new Date(year, month + 1, 0)

  // Senin sebagai awal minggu
  const startOffset = (firstDay.getDay() + 6) % 7

  const days = []

  // empty cell sebelum tanggal 1
  for (let i = 0; i < startOffset; i++) {
    days.push({
      date: null,
      day: '',
      sessions: [],
      empty: true,
    })
  }

  // tanggal valid
  for (let d = 1; d <= lastDay.getDate(); d++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`

    days.push({
      date: dateStr,
      day: d,
      sessions: getSessionsByDate(dateStr),
      empty: false,
    })
  }

  return days
})

/**
 * Untuk tampilan mobile (hanya hari yang ada sesi)
 */
const daysWithSessions = computed(() =>
  daysInMonth.value
    .filter(d => !d.empty && d.sessions.length > 0)
    .map(d => {
      const dateObj = new Date(d.date)

      return {
        ...d,
        dayName: dateObj.toLocaleDateString('id-ID', {
          weekday: 'long',
        }),
      }
    })
)

/**
 * Navigasi bulan
 */
const prevMonth = () => {
  currentMonth.value = new Date(
    currentMonth.value.getFullYear(),
    currentMonth.value.getMonth() - 1,
    1
  )
}

const nextMonth = () => {
  currentMonth.value = new Date(
    currentMonth.value.getFullYear(),
    currentMonth.value.getMonth() + 1,
    1
  )
}
</script>

<template>
  <section
    id="calendar"
    class="max-w-7xl mx-auto px-4 sm:px-6 py-10 scroll-mt-24"
  >
    <!-- HEADER -->
    <div class="flex items-center justify-between mb-10">
      <button
        @click="prevMonth"
        class="p-2 rounded-xl hover:bg-slate-100 transition"
      >
        <ChevronLeft class="text-slate-600" />
      </button>

      <div class="text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-blue-700 capitalize">
          {{ monthLabel }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">
          Kalender Ketersediaan Konsultasi
        </p>
      </div>

      <button
        @click="nextMonth"
        class="p-2 rounded-xl hover:bg-slate-100 transition"
      >
        <ChevronRight class="text-slate-600" />
      </button>
    </div>

    <!-- ================= MOBILE ================= -->
    <div class="md:hidden">
    <!-- EMPTY STATE -->
    <div
        v-if="daysWithSessions.length === 0"
        class="rounded-2xl border border-dashed border-slate-300
            bg-slate-50 p-6 text-center text-slate-500"
    >
        <p class="text-sm font-medium">
        Belum ada sesi konsultasi tersedia
        </p>
        <p class="text-xs mt-1">
        Silakan pilih tanggal dan sesi melalui form di atas
        </p>
    </div>

    <!-- LIST SESSION -->
    <div v-else class="space-y-4">
        <div
        v-for="d in daysWithSessions"
        :key="d.date"
        class="rounded-2xl border border-slate-200 bg-white p-4"
        >
        <p class="text-sm font-semibold text-blue-700 mb-1">
            {{ d.dayName }}, {{ d.date }}
        </p>

        <div class="space-y-2 mt-3">
            <button
            v-for="s in d.sessions"
            :key="s.value"
            @click="emit('select-session', { ...s, tanggal: d.date })"
            class="w-full text-left text-sm rounded-xl px-3 py-2
                    bg-blue-50 border border-blue-200
                    hover:bg-blue-100 transition"
            >
            <div class="font-semibold text-blue-700">
                {{ s.label }}
            </div>
            <div class="text-xs text-slate-500">
                {{ s.topik }} · {{ s.narasumber }}
            </div>
            </button>
        </div>
        </div>
    </div>
    </div>

    <!-- ================= DESKTOP ================= -->

    <!-- HEADER HARI -->
    <div class="hidden md:grid grid-cols-7 gap-5 mb-3">
      <div
        v-for="day in ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']"
        :key="day"
        class="text-center text-sm font-semibold text-slate-600"
      >
        {{ day }}
      </div>
    </div>

    <!-- GRID KALENDER -->
    <div class="hidden md:grid grid-cols-7 gap-5">
      <div
        v-for="(d, i) in daysInMonth"
        :key="i"
        class="rounded-2xl p-4 min-h-[130px]"
        :class="d.empty
          ? 'bg-transparent'
          : 'border border-slate-200 bg-white hover:shadow-md transition'"
      >
        <!-- EMPTY CELL -->
        <template v-if="!d.empty">
          <p class="text-sm font-semibold text-slate-700 mb-3">
            {{ d.day }}
          </p>

          <div class="space-y-2">
            <button
              v-for="s in d.sessions"
              :key="s.value"
              @click="emit('select-session', { ...s, tanggal: d.date })"
              class="w-full text-left text-xs rounded-xl px-3 py-2
                     bg-blue-50 text-blue-700
                     border border-blue-200
                     hover:bg-blue-100 transition"
            >
              <div class="font-semibold">
                {{ s.label }}
              </div>
              <div class="text-[11px] text-slate-500">
                {{ s.topik }} · {{ s.narasumber }}
              </div>
            </button>
          </div>
        </template>
      </div>
    </div>
  </section>
</template>
