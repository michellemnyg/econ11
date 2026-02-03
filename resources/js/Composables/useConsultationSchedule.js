import { computed, ref, watch } from 'vue'
import {
  isPastDate,
  isPastSessionToday,
  isTuesday,
} from '@/Helpers/dateTimeRules'
import { getTodayWitaDate } from '@/Helpers/timezone'
import { getBookedSessionsByDate } from '@/Services/booking.service'

export function useConsultationSchedule(form) {
  const sessions = [
    { label: 'Sesi 1 (09:00 - 09:45)', value: 'sesi-1', start: '09:00' },
    { label: 'Sesi 2 (10:00 - 10:45)', value: 'sesi-2', start: '10:00' },
    { label: 'Sesi 3 (11:00 - 11:45)', value: 'sesi-3', start: '11:00' },
    { label: 'Sesi 4 (14:00 - 14:45)', value: 'sesi-4', start: '14:00' },
    { label: 'Sesi 5 (15:00 - 15:45)', value: 'sesi-5', start: '15:00' },
  ]

  const bookedSessions = ref([])

  watch(
    () => form.value.tanggal,
    async (date) => {
      if (!date) return
      bookedSessions.value = await getBookedSessionsByDate(date)
    },
    { immediate: true }
  )

  const availableSessions = computed(() => {
    if (!form.value.tanggal) return sessions

    const today = getTodayWitaDate()

    return sessions.map((session) => {
      let disabled = false

      // ❌ tanggal lampau
      if (isPastDate(form.value.tanggal)) disabled = true

      // ❌ same-day sesi lewat
      if (
        form.value.tanggal === today &&
        isPastSessionToday(session)
      ) disabled = true

      // ❌ Selasa sesi 1
      if (
        isTuesday(form.value.tanggal) &&
        session.value === 'sesi-1'
      ) disabled = true

      // ❌ sesi sudah dibooking
      if (
        bookedSessions.value.some(
          (b) => b.session === session.value
        )
      ) disabled = true

      return {
        ...session,
        disabled,
      }
    })
  })

  return {
    availableSessions,
  }
}
