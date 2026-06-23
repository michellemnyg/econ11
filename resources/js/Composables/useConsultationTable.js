import { ref, computed, watch } from 'vue'
import { normalizeConsultationRow } from '@/Domain/Consultation/consultation.session.adapter'
import { getTodayWitaDate } from '@/Helpers/timezone'

export function useConsultationTable(source) {
  const search = ref('')
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
    const today = getTodayWitaDate()

    return [...filteredData.value].sort((a, b) => {
      const getRank = (row) => {
        if (row.tanggal === today) {
          if (row.status === 'berlangsung') return 1
          if (row.status === 'akan_datang') return 2
          return 3
        }
        if (row.tanggal > today) return 4
        return 5
      }

      const rankA = getRank(a)
      const rankB = getRank(b)

      if (rankA !== rankB) {
        return rankA - rankB
      }

      const dateA = a.tanggal
      const dateB = b.tanggal
      const sesiA = a.sessionTime ? a.sessionTime.start : (a.sesi || '')
      const sesiB = b.sessionTime ? b.sessionTime.start : (b.sesi || '')

      if (rankA === 4) {
        if (dateA !== dateB) return dateA.localeCompare(dateB)
        return sesiA.localeCompare(sesiB)
      }

      if (rankA === 5) {
        if (dateA !== dateB) return dateB.localeCompare(dateA)
        return sesiB.localeCompare(sesiA)
      }

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
