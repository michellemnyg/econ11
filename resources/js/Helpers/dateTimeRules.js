/**
 * Return true jika tanggal < hari ini
 */
export function isPastDate(dateString) {
  const today = new Date()
  today.setHours(0, 0, 0, 0)

  const selected = new Date(dateString)
  selected.setHours(0, 0, 0, 0)

  return selected < today
}

/**
 * Return true jika waktu sesi sudah lewat (hari ini)
 */
export function isPastSessionToday(session, now = new Date()) {
  if (!session?.start) return false

  const [hour, minute] = session.start.split(':')
  const sessionTime = new Date()
  sessionTime.setHours(hour, minute, 0, 0)

  return now >= sessionTime
}

/**
 * Selasa = 2 (0 = Minggu)
 */
export function isTuesday(dateString) {
  return new Date(dateString).getDay() === 2
}
