import { CONSULTATION_RESULT_MOCK } from '@/Mocks/consultation.mock'
import { CONSULTATION_BOOKINGS_MOCK } from '@/Mocks/consultationBookings.mock'

export async function submitConsultation(form) {
  // Simpan booking (mock)
  CONSULTATION_BOOKINGS_MOCK.push({
    date: form.tanggal,
    session: form.sesi,
    topic: form.topik,
    narasumber: 'Admin BKN',
  })

  return {
    ...CONSULTATION_RESULT_MOCK,
    meeting: {
      ...CONSULTATION_RESULT_MOCK.meeting,
      tanggal: form.tanggal,
      sesi: form.sesi,
    },
  }
}
