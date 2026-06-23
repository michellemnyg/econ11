import { computed, ref, watch } from 'vue'
import {
  isPastDate,
  isPastSessionToday,
  isTuesday,
} from '@/Helpers/dateTimeRules'
import { getTodayWitaDate } from '@/Helpers/timezone'
import { getBookedSessionsByDate } from '@/Services/booking.service'
import { fetchHolidays, checkIsHoliday } from '@/Services/holiday.service'

export function useConsultationSchedule(form) {
  // Ambil data hari libur di background
  fetchHolidays()
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
      // Reset pilihan sesi dan kosongkan daftar booking tiap kali tanggal diganti
      form.value.sesi = ''
      bookedSessions.value = []

      if (!date) return

      // Pengaman agar selalu menjadi array, mencegah error '.some()'
      bookedSessions.value = (await getBookedSessionsByDate(date)) || []
    },
    { immediate: true }
  )

  const availableSessions = computed(() => {
    if (!form.value.tanggal) return sessions

    const today = getTodayWitaDate()

    // Ambil index hari (0 = Minggu, 1 = Senin, ..., 6 = Sabtu)
    const selectedDateObj = new Date(form.value.tanggal)
    const dayOfWeek = selectedDateObj.getDay()

    return sessions.map((session) => {
      let disabled = false

      // ❌ BARU: Libur Sabtu (6) dan Minggu (0)
      if (dayOfWeek === 0 || dayOfWeek === 6) disabled = true

      // ❌ BARU: Libur Nasional / Cuti Bersama
      if (checkIsHoliday(form.value.tanggal)) disabled = true

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
        Array.isArray(bookedSessions.value) &&
        bookedSessions.value.some((b) => b.session === session.value)
      ) {
        disabled = true
      }

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
