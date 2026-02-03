/**
 * Return true jika tanggal < hari ini
 */
import { getTodayWitaDate } from './timezone'

export function isPastDate(dateString) {
  const today = getTodayWitaDate()
  return dateString < today
}

/**
 * Return true jika waktu sesi sudah lewat (hari ini)
 */
import { getNowWita } from './timezone'

export function isPastSessionToday(session) {
  if (!session?.start) return false

  const now = getNowWita()

  const [hour, minute] = session.start.split(':')
  const sessionTime = new Date(now)
  sessionTime.setHours(hour, minute, 0, 0)

  return now >= sessionTime
}

/**
 * Selasa = 2 (0 = Minggu)
 */
export function isTuesday(dateString) {
  return new Date(dateString).getDay() === 2
}
