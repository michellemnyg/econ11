export const WITA_TIMEZONE = 'Asia/Makassar'

export function getTodayWitaDate() {
  return new Intl.DateTimeFormat('en-CA', {
    timeZone: WITA_TIMEZONE,
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
  }).format(new Date())
}

export function getNowWita() {
  return new Date(
    new Date().toLocaleString('en-US', {
      timeZone: WITA_TIMEZONE,
    })
  )
}
