import { useApiSource } from '@/Composables/useApiSource'
import axios from 'axios'

// Import mock data yang masih dibutuhkan
import { CONSULTATION_RESULT_MOCK } from '@/Mocks/consultation.mock'
import { CONSULTATION_BOOKINGS_MOCK } from '@/Mocks/consultationBookings.mock'
import { CONSULTATION_TABLE_MOCK } from '@/Mocks/consultationTable.mock'

/* ============================
   WRITE (BOOKING / SUBMIT) - FASE 3
============================ */
export async function submitConsultation(payload) {
  const { isMock } = useApiSource()

  // MOCK MODE (Jika VITE_API_MODE=mock)
  if (isMock) {
    await new Promise(r => setTimeout(r, 1000))

    CONSULTATION_BOOKINGS_MOCK.push({
      date: payload.tanggal,
      session: payload.sesi,
      topic: payload.topik,
      narasumber: 'Admin BKN',
    })

    return {
      ...CONSULTATION_RESULT_MOCK,
      meeting: {
        ...CONSULTATION_RESULT_MOCK.meeting,
        tanggal: payload.tanggal,
        sesi: payload.sesi,
      },
    }
  }

  // API MODE (Menembak Backend Laravel)
  // Axios otomatis akan melempar error jika response status bukan 2xx (misal 422 Validasi Gagal)
  const response = await axios.post('/api/consultation/submit', payload)
  return response.data
}

/* ============================
   READ (CLIENT TABLE) - FASE 5 NANTI
============================ */
export async function fetchConsultations(params = {}) {
  /**
   * Biarkan menggunakan MOCK dulu.
   * Kita baru akan mengubah fungsi ini menembak Axios saat masuk Fase 5 (Admin Dashboard).
   */
  return CONSULTATION_TABLE_MOCK
}
