import type {
  ConsultationRow,
  ConsultationSessionTime,
  ConsultationStatus,
} from './consultation.types'

/**
 * Parse string sesi:
 * "Sesi 1 (09:00 - 09:45)"
 */
export function parseSessionTime(
  sesi: string,
): ConsultationSessionTime | null {
  const match = sesi.match(
    /(Sesi\s+\d+)\s*\((\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})\)/,
  )

  if (!match) return null

  return {
    label: match[1],
    start: match[2],
    end: match[3],
  }
}

/**
 * Gabungkan tanggal + jam menjadi Date
 */
function buildDateTime(date: string, time: string): Date {
  return new Date(`${date}T${time}:00`)
}

/**
 * Hitung status otomatis berdasarkan waktu sekarang
 */
export function computeConsultationStatus(
  row: ConsultationRow,
  now: Date = new Date(),
): ConsultationStatus {
  const session = row.sessionTime ?? parseSessionTime(row.sesi)
  if (!session) return row.status

  const start = buildDateTime(row.tanggal, session.start)
  const end = buildDateTime(row.tanggal, session.end)

  if (now < start) return 'akan_datang'
  if (now >= start && now <= end) return 'berlangsung'
  return 'berlalu'
}

/**
 * Normalize row dari backend → domain-safe
 */
export function normalizeConsultationRow(
  row: ConsultationRow,
): ConsultationRow {
  const sessionTime = parseSessionTime(row.sesi)

  return {
    ...row,
    sessionTime: sessionTime ?? undefined,
    status: computeConsultationStatus({
      ...row,
      sessionTime: sessionTime ?? undefined,
    }),
  }
}
