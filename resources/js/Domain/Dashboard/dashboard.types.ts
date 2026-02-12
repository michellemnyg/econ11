export interface DashboardPeriod {
  from: string // ISO
  to: string   // ISO
}

export interface DashboardCards {
  totalKlien: number
  konsultasiAktif: number
  konsultasiSelesai: number
}

export interface DashboardTopItem {
  label: string   // topik / instansi
  total: number
}

export interface NarasumberPerformance {
  nama: string
  total: number
}

export interface DashboardData {
  cards: DashboardCards

  topTopik: DashboardTopItem[]
  topInstansi: DashboardTopItem[]

  kinerjaNarasumber: NarasumberPerformance[]
}
