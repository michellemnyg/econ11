import { ASN_MOCK } from '@/Mocks/asn.mock'
import { useApiSource } from '@/Composables/useApiSource'
// import axios from 'axios' // aktifkan nanti

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

  // ==========================
  // MOCK MODE (FRONTEND PHASE)
  // ==========================
  if (isMock) {
    // Simulasi delay loading agar terasa seperti aplikasi nyata
    await new Promise(r => setTimeout(r, 500))

    return ASN_MOCK.find((asn) => {
        // Cek NIP sama persis
        const nipMatch = asn.nip === nip
        // Cek Nama (tidak peduli huruf besar/kecil)
        const nameMatch = asn.nama.toLowerCase().includes(nama.toLowerCase())

        return nipMatch && nameMatch
    }) || null
  }

  // ==========================
  // API MODE (BACKEND PHASE)
  // ==========================
  try {
    // kirim body post: { nip: '...', nama: '...' }
    // const response = await axios.post(`/api/asn/check`, { nip, nama })
    // return response.data

    throw new Error('API mode not implemented yet')
  } catch (error) {
    console.error('[ASN SERVICE]', error)
    return null
  }
}
