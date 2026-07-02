export type ConsultationStatus =
  | 'akan_datang'
  | 'berlangsung'
  | 'berlalu'

export interface ConsultationSessionTime {
  label: string
  start: string
  end: string
}

export interface ConsultationIntegration {
  meetingId: string | number | null
  linkZoom: string | null
  passcode: string | null
  createdAt: string | null
}

export interface ConsultationRow {
  id: number
  tanggal: string
  sesi: string
  sessionTime?: ConsultationSessionTime
  nip: number
  nama: string
  instansi: string
  topik: string
  status?: ConsultationStatus
  petugas: string | null
  deskripsiMasalah?: string
  integrasi: ConsultationIntegration
  createdAt: string
}
