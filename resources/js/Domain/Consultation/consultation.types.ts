/**
 * Status konsultasi berdasarkan waktu aktual
 */
export type ConsultationStatus =
  | 'akan_datang'
  | 'berlangsung'
  | 'berlalu'

/**
 * Representasi waktu sesi hasil parsing
 * (TIDAK dikirim backend, hanya frontend domain)
 */
export interface ConsultationSessionTime {
  label: string        // "Sesi 1"
  start: string        // "09:00"
  end: string          // "09:45"
}

/**
 * Informasi integrasi meeting
 */
export interface ConsultationIntegration {
  meetingId: string | number | null
  linkZoom: string | null
  passcode: string | null
  createdAt: string | null
}

/**
 * Entity utama Consultation (domain-level)
 * Digunakan oleh table, dashboard, modal, dll
 */
export interface ConsultationRow {
  id: number

  // Waktu (raw dari backend)
  tanggal: string        // ISO date (YYYY-MM-DD)
  sesi: string           // "Sesi 1 (09:00 - 09:45)"

  // Waktu (hasil normalisasi frontend)
  sessionTime?: ConsultationSessionTime

  // Klien
  nip: number
  nama: string
  instansi: string
  topik: string

  // Status (boleh dari backend, boleh dioverride frontend)
  status?: ConsultationStatus
  petugas: string | null

  // Deskripsi masalah
  deskripsiMasalah?: string

  // Integrasi meeting
  integrasi: ConsultationIntegration

  // Metadata
  createdAt: string
}
