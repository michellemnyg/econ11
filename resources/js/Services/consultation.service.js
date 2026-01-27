import { CONSULTATION_RESULT_MOCK } from '@/Mocks/consultation.mock'
import { useApiSource } from '@/Composables/useApiSource'

export async function submitConsultation(payload) {
  const { isMock } = useApiSource()

  if (isMock) {
    return CONSULTATION_RESULT_MOCK
  }

  // return axios.post('/api/consultations', payload)
}
