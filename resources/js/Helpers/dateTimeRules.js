import { getTodayWitaDate } from './timezone'

export function isPastDate(dateString) {
  const today = getTodayWitaDate()
  return dateString < today
}

import { getNowWita } from './timezone'

export function isPastSessionToday(session) {
  if (!session?.start) return false

  const now = getNowWita()

  const [hour, minute] = session.start.split(':')
  const sessionTime = new Date(now)
  sessionTime.setHours(hour, minute, 0, 0)

  return now >= sessionTime
}

export function isTuesday(dateString) {
  return new Date(dateString).getDay() === 2
}
