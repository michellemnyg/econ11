import {
  CONSULTATION_RESULT_MOCK,
} from '@/Mocks/consultation.mock'

import {
  CONSULTATION_BOOKINGS_MOCK,
} from '@/Mocks/consultationBookings.mock'

import {
  CONSULTATION_TABLE_MOCK,
} from '@/Mocks/consultationTable.mock'

/* ============================
   WRITE (BOOKING / SUBMIT)
============================ */
export async function submitConsultation(form) {
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

/* ============================
   READ (CLIENT TABLE)
============================ */
export async function fetchConsultations(params = {}) {
  /**
   * params nanti:
   * - page
   * - perPage
   * - search
   * - sortBy
   * - sortDirection
   * - dateFrom / dateTo
   */
  return CONSULTATION_TABLE_MOCK
}
