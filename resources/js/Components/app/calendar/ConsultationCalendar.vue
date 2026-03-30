<script setup>
import { ref, computed, onMounted } from 'vue'
import { ChevronLeft, ChevronRight, Calendar as CalendarIcon } from 'lucide-vue-next'
import { useCalendarStore } from '@/Stores/useCalendarStore'

const { calendarSessions, loadCalendar } = useCalendarStore()

onMounted(loadCalendar)

const emit = defineEmits(['select-session'])

const currentMonth = ref(new Date())

const monthLabel = computed(() =>
  currentMonth.value.toLocaleDateString('id-ID', {
    month: 'long',
    year: 'numeric',
  })
)

const getSessionsByDate = (date) => {
  const day = calendarSessions.value.find(d => d.tanggal === date)
  return day ? day.sesi : []
}

const daysInMonth = computed(() => {
  const year = currentMonth.value.getFullYear()
  const month = currentMonth.value.getMonth()

  const firstDay = new Date(year, month, 1)
  const lastDay = new Date(year, month + 1, 0)

  const startOffset = (firstDay.getDay() + 6) % 7
  const days = []

  for (let i = 0; i < startOffset; i++) {
    days.push({ date: null, day: '', sessions: [], empty: true })
  }

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

const daysWithSessions = computed(() =>
  daysInMonth.value
    .filter(d => !d.empty && d.sessions.length > 0)
    .map(d => {
      const dateObj = new Date(d.date)
      return {
        ...d,
        dayName: dateObj.toLocaleDateString('id-ID', { weekday: 'long' }),
      }
    })
)

const prevMonth = () => {
  currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() - 1, 1)
}
const nextMonth = () => {
  currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() + 1, 1)
}
</script>

<template>
  <section id="calendar" class="max-w-7xl mx-auto px-4 sm:px-6 py-16 scroll-mt-24">

    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-8">
      <div>
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight capitalize flex items-center gap-3">
          <CalendarIcon class="w-8 h-8 text-blue-600" />
          {{ monthLabel }}
        </h2>
        <p class="mt-1 text-sm text-slate-500 font-medium border-l-2 border-red-500 pl-3 ml-1 mt-2">
          Jadwal Konsultasi Terjadwal
        </p>
      </div>

      <div class="flex items-center gap-2">
        <button @click="prevMonth" class="p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-blue-300 text-slate-600 transition shadow-sm">
          <ChevronLeft class="w-5 h-5" />
        </button>
        <button @click="nextMonth" class="p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-blue-300 text-slate-600 transition shadow-sm">
          <ChevronRight class="w-5 h-5" />
        </button>
      </div>
    </div>

    <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden relative">
      <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 to-red-500 z-10"></div>

      <div class="md:hidden p-6 pt-8">
        <div v-if="daysWithSessions.length === 0" class="py-10 text-center">
          <CalendarIcon class="w-10 h-10 mx-auto text-slate-300 mb-3" />
          <p class="text-sm font-semibold text-slate-600">Belum ada sesi</p>
        </div>

        <div v-else class="space-y-4">
          <div v-for="d in daysWithSessions" :key="d.date" class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
            <p class="text-sm font-bold text-slate-800 mb-3 border-b border-slate-200 pb-2">
              {{ d.dayName }}, <span class="text-slate-500 font-medium">{{ d.date }}</span>
            </p>
            <div class="space-y-2">
              <button
                v-for="s in d.sessions"
                :key="s.value"
                @click="emit('select-session', { ...s, tanggal: d.date })"
                class="w-full text-left rounded-xl px-4 py-3 bg-white border-y border-r border-l-4 border-slate-100 border-l-blue-500 hover:border-blue-400 hover:shadow-md transition group"
              >
                <div class="font-bold text-blue-700 text-sm">{{ s.label }}</div>
                <div class="text-[11px] text-slate-500 mt-1 flex flex-col gap-0.5">
                  <span class="font-medium text-slate-700">{{ s.topik }}</span>
                  <span>Petugas: {{ s.narasumber }}</span>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="hidden md:block pt-1.5">
        <div class="grid grid-cols-7 gap-px bg-slate-100 border-b border-slate-200">
          <div
            v-for="day in ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']"
            :key="day"
            class="text-center py-4 text-xs font-bold text-slate-500 uppercase tracking-wider bg-white"
          >
            {{ day }}
          </div>
        </div>

        <div class="grid grid-cols-7 gap-px bg-slate-100">
          <div
            v-for="(d, i) in daysInMonth"
            :key="i"
            class="min-h-[160px] bg-white p-3 transition-colors hover:bg-slate-50/80 group"
            :class="{ 'bg-slate-50/30': d.empty }"
          >
            <template v-if="!d.empty">
              <div class="flex justify-between items-center mb-3">
                <span
                  class="text-sm font-bold flex items-center justify-center w-7 h-7 rounded-full transition-colors group-hover:bg-blue-100 group-hover:text-blue-700"
                  :class="i % 7 === 6 ? 'text-red-500' : 'text-slate-700'"
                >
                  {{ d.day }}
                </span>
              </div>

              <div class="space-y-1.5 max-h-[110px] overflow-y-auto custom-scrollbar pr-1">
                <button
                  v-for="s in d.sessions"
                  :key="s.value"
                  @click="emit('select-session', { ...s, tanggal: d.date })"
                  class="w-full text-left rounded-md px-2.5 py-2 bg-slate-50 border-y border-r border-l-4 border-slate-100 border-l-blue-500 hover:bg-blue-600 hover:border-blue-600 hover:text-white transition shadow-sm"
                >
                  <div class="font-bold text-blue-700 text-[11px] hover:text-white leading-tight mb-0.5 inherit-text">
                    {{ s.label.split('(')[0].trim() }}
                  </div>
                  <div class="text-[10px] text-slate-500 truncate inherit-text opacity-90">
                    {{ s.topik }}
                  </div>
                </button>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.inherit-text {
  color: inherit;
}
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 10px;
}
</style>
