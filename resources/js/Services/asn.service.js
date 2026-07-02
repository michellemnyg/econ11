import { ASN_MOCK } from '@/Mocks/asn.mock'
import { useApiSource } from '@/Composables/useApiSource'
import axios from 'axios' // Axios diaktifkan

/**
 * Cari ASN berdasarkan NIP dan Nama (Case Insensitive)
 *
 * @param {string} nip
 * @param {string} nama
 * @returns {Promise<Object|null>}
 */
export async function findAsn(nip, nama) {
  if (!nip || !nama) return null

  const { isMock } = useApiSource()

  if (isMock) {
    await new Promise(r => setTimeout(r, 500))

    return ASN_MOCK.find((asn) => {
      const nipMatch = asn.nip === nip
      const nameMatch = asn.nama.toLowerCase().includes(nama.toLowerCase())
      return nipMatch && nameMatch
    }) || null
  }

  try {
    const response = await axios.post(`/api/asn/check`, { nip, nama })

    if (response.data && response.data.success) {
      return response.data.data
    }

    return null

  } catch (error) {
    console.error('[ASN SERVICE]', error.response?.data?.message || error.message)
    return null
  }
}
