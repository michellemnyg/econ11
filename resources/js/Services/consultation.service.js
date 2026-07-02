import { useApiSource } from '@/Composables/useApiSource'
import axios from 'axios'

import { CONSULTATION_RESULT_MOCK } from '@/Mocks/consultation.mock'
import { CONSULTATION_BOOKINGS_MOCK } from '@/Mocks/consultationBookings.mock'
import { CONSULTATION_TABLE_MOCK } from '@/Mocks/consultationTable.mock'

export async function submitConsultation(payload) {
  const { isMock } = useApiSource()

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
  const response = await axios.post('/api/consultation/submit', payload)
  return response.data
}

export async function fetchConsultations(params = {}) {
  return CONSULTATION_TABLE_MOCK
}
