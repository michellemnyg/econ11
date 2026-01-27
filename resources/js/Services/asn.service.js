import { ASN_MOCK } from '@/Mocks/asn.mock'
import { useApiSource } from '@/Composables/useApiSource'
// import axios from 'axios' // aktifkan nanti

/**
 * Cari ASN berdasarkan NIP
 *
 * @param {string} nip
 * @returns {Promise<Object|null>}
 */
export async function findAsnByNip(nip) {
  if (!nip) return null

  const { isMock } = useApiSource()

  // ==========================
  // MOCK MODE (FRONTEND PHASE)
  // ==========================
  if (isMock) {
    return (
      ASN_MOCK.find((asn) => asn.nip === nip) || null
    )
  }

  // ==========================
  // API MODE (BACKEND PHASE)
  // ==========================
  try {
    // const response = await axios.get(`/api/asn/${nip}`)
    // return response.data

    throw new Error('API mode not implemented yet')
  } catch (error) {
    console.error('[ASN SERVICE]', error)
    return null
  }
}
