import { ref } from 'vue'
import { getCalendarSessions } from '@/Services/calendar.service'

const calendarSessions = ref([])
const isLoaded = ref(false)

export function useCalendarStore() {
  async function loadCalendar() {
    if (isLoaded.value) return

    calendarSessions.value = await getCalendarSessions()
    isLoaded.value = true
  }

  function addBookedSession({ tanggal, sesi }) {
    const day = calendarSessions.value.find(d => d.tanggal === tanggal)

    if (day) {
      // ❌ prevent duplicate booking
      if (day.sesi.some(s => s.value === sesi.value)) return

      day.sesi.push({
        ...sesi,
        status: 'booked',
      })
    } else {
      calendarSessions.value.push({
        tanggal,
        sesi: [
          {
            ...sesi,
            status: 'booked',
          },
        ],
      })
    }
  }

  function isSessionBooked(tanggal, sessionValue) {
    const day = calendarSessions.value.find(d => d.tanggal === tanggal)
    return day
      ? day.sesi.some(s => s.value === sessionValue)
      : false
  }

  return {
    calendarSessions,
    loadCalendar,
    addBookedSession,
    isSessionBooked,
  }
}
