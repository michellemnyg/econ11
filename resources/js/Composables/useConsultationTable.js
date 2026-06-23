import { ref, computed, watch } from 'vue'
import { normalizeConsultationRow } from '@/Domain/Consultation/consultation.session.adapter'

export function useConsultationTable(source) {
  const search = ref('')

  // DEFAULT KOSONG AGAR SEMUA DATA DARI DATABASE MUNCUL DI AWAL
  const periodMode = ref('default')
  const startDate = ref('')
  const endDate = ref('')

  const filterInstansi = ref('')
  const filterStatus = ref('')
  const currentPage = ref(1)
  const perPage = ref(10)

  const normalizedSource = computed(() => {
    const data = Array.isArray(source.value) ? source.value : []
    return data.map(normalizeConsultationRow)
  })

  const filteredData = computed(() => {
    return normalizedSource.value.filter((row) => {
      // Jika start/end date kosong, anggap match (true)
      const matchTanggal = (!startDate.value || !endDate.value) ? true : (row.tanggal >= startDate.value && row.tanggal <= endDate.value)

      const q = search.value.toLowerCase()
      const matchSearch = !q ||
        row.nama.toLowerCase().includes(q) ||
        String(row.nip).includes(q) ||
        row.instansi.toLowerCase().includes(q)

      const matchInstansi = !filterInstansi.value || row.instansi === filterInstansi.value
      const matchStatus = !filterStatus.value || row.status === filterStatus.value

      return matchTanggal && matchSearch && matchInstansi && matchStatus
    })
  })

  const sortedData = computed(() => {
    return [...filteredData.value].sort((a, b) => {
      const dateComparison = b.tanggal.localeCompare(a.tanggal)
      if (dateComparison !== 0) return dateComparison
      
      const sesiA = a.sessionTime ? a.sessionTime.start : (a.sesi || '')
      const sesiB = b.sessionTime ? b.sessionTime.start : (b.sesi || '')
      return sesiA.localeCompare(sesiB)
    })
  })

  const totalPages = computed(() => Math.ceil(sortedData.value.length / perPage.value))

  const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * perPage.value
    return sortedData.value.slice(start, start + perPage.value)
  })

  const instansiOptions = computed(() =>
    [...new Set(normalizedSource.value.map((r) => r.instansi))].sort()
  )

  watch([search, startDate, endDate, filterInstansi, filterStatus, perPage, periodMode], () => {
    currentPage.value = 1
  })

  return {
    search, periodMode, startDate, endDate, filterInstansi, filterStatus,
    instansiOptions, currentPage, perPage, totalPages, paginatedData,
  }
}
