import type { ConsultationRow } from './consultation.types'
import { getNowWita } from '@/Helpers/timezone'

const SESSION_MAP: Record<string, any> = {
  'sesi-1': { label: 'Sesi 1 (09:00 - 09:45)', start: '09:00', end: '09:45' },
  'sesi-2': { label: 'Sesi 2 (10:00 - 10:45)', start: '10:00', end: '10:45' },
  'sesi-3': { label: 'Sesi 3 (11:00 - 11:45)', start: '11:00', end: '11:45' },
  'sesi-4': { label: 'Sesi 4 (14:00 - 14:45)', start: '14:00', end: '14:45' },
  'sesi-5': { label: 'Sesi 5 (15:00 - 15:45)', start: '15:00', end: '15:45' },
}

export function parseSessionTime(sesi: string) {
  return SESSION_MAP[sesi] || null
}

export function computeConsultationStatus(row: any, now: Date = getNowWita()) {
  const session = parseSessionTime(row.sesi)
  if (!session) return 'akan_datang'

  const start = new Date(`${row.tanggal}T${session.start}:00`)
  const end = new Date(`${row.tanggal}T${session.end}:00`)

  if (now < start) return 'akan_datang'
  if (now >= start && now <= end) return 'berlangsung'
  return 'berlalu'
}

export function normalizeConsultationRow(row: any): ConsultationRow {
  const sessionTime = parseSessionTime(row.sesi)

  return {
    ...row,
    sesi: sessionTime ? sessionTime.label : row.sesi,
    sessionTime: sessionTime || undefined,

    petugas: row.narasumber || null,

    status: computeConsultationStatus(row)
  }
}
