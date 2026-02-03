<script setup>
import { ref, computed, onMounted } from 'vue'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { getCalendarSessions } from '@/Services/calendar.service'

const emit = defineEmits(['select-session'])

const sessions = ref([])
const currentMonth = ref(new Date())

onMounted(async () => {
  sessions.value = await getCalendarSessions()
})

const monthLabel = computed(() =>
  currentMonth.value.toLocaleDateString('id-ID', {
    month: 'long',
    year: 'numeric'
  })
)

const daysInMonth = computed(() => {
  const y = currentMonth.value.getFullYear()
  const m = currentMonth.value.getMonth()
  const lastDay = new Date(y, m + 1, 0).getDate()

  return Array.from({ length: lastDay }, (_, i) => {
    const date = new Date(y, m, i + 1)
    const iso = date.toISOString().slice(0, 10)

    const dayData = sessions.value.find(d => d.tanggal === iso)

    return {
      date: iso,
      day: i + 1,
      sessions: dayData
        ? dayData.sesi.filter(s => s.status === 'booked')
        : [],
    }
  })
})

const daysWithSessions = computed(() =>
  daysInMonth.value.filter(d => d.sessions.length > 0)
)

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
    class="max-w-7xl mx-auto px-4 sm:px-6 py-20 scroll-mt-24"
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
    <div class="space-y-4 md:hidden">
      <div
        v-for="d in daysWithSessions"
        :key="d.date"
        class="rounded-2xl border border-slate-200 bg-white p-4"
      >
        <p class="text-sm font-semibold text-blue-700 mb-3">
          {{ d.date }}
        </p>

        <div class="space-y-2">
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

    <!-- ================= DESKTOP ================= -->
    <div class="hidden md:grid grid-cols-7 gap-5">
      <div
        v-for="d in daysInMonth"
        :key="d.date"
        class="rounded-2xl border border-slate-200 bg-white p-4 min-h-[130px]
               hover:shadow-md transition"
      >
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
      </div>
    </div>
  </section>
</template>
