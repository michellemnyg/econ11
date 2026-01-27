import { computed } from 'vue'
import {
  isPastDate,
  isPastSessionToday,
  isTuesday,
} from '@/Helpers/dateTimeRules'

export function useConsultationSchedule(form) {
  const sessions = [
    {
      label: 'Sesi 1 (09:00 - 09:45)',
      value: 'sesi-1',
      start: '09:00',
    },
    {
      label: 'Sesi 2 (10:00 - 10:45)',
      value: 'sesi-2',
      start: '10:00',
    },
    {
      label: 'Sesi 3 (11:00 - 11:45)',
      value: 'sesi-3',
      start: '11:00',
    },
    {
      label: 'Sesi 4 (14:00 - 14:45)',
      value: 'sesi-4',
      start: '14:00',
    },
    {
      label: 'Sesi 5 (15:00 - 15:45)',
      value: 'sesi-5',
      start: '15:00',
    },
  ]

  /**
   * Filter sesi berdasarkan rule bisnis
   */
  const availableSessions = computed(() => {
    if (!form.value.tanggal) return sessions

    return sessions.map((session) => {
      let disabled = false

      // Tidak boleh tanggal lampau
      if (isPastDate(form.value.tanggal)) {
        disabled = true
      }

      // Same day → sesi yang sudah lewat dikunci
      const today = new Date().toISOString().slice(0, 10)
      if (
        form.value.tanggal === today &&
        isPastSessionToday(session)
      ) {
        disabled = true
      }

      // Selasa → sesi 1 dikunci
      if (
        isTuesday(form.value.tanggal) &&
        session.value === 'sesi-1'
      ) {
        disabled = true
      }

      return {
        ...session,
        disabled,
      }
    })
  })

  /**
   * Disable tanggal di date picker
   */
  const isDateDisabled = (dateString) => {
    return isPastDate(dateString)
  }

  return {
    availableSessions,
    isDateDisabled,
  }
}
