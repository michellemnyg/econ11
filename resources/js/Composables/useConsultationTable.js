import { ref, computed, watch } from 'vue'
import { normalizeConsultationRow } from '@/Domain/Consultation/consultation.session.adapter'

/**
 * Government-grade consultation table logic
 * - Deterministic
 * - Domain-driven
 * - UI-agnostic
 */
export function useConsultationTable(source) {
  /* ======================
     SEARCH
  ====================== */
  const search = ref('')

  /* ======================
     PERIOD (PRIMARY FILTER)
     Default: HARI INI
  ====================== */
  const today = new Date().toISOString().slice(0, 10)

  const periodMode = ref('range')
  const startDate = ref(today)
  const endDate = ref(today)

  /* ======================
     ADVANCED FILTER (SECONDARY)
  ====================== */
  const filterInstansi = ref('')
  const filterStatus = ref('')

  /* ======================
     PAGINATION
  ====================== */
  const currentPage = ref(1)
  const perPage = ref(10)

  /* ======================
     NORMALIZE DATA (SINGLE SOURCE)
  ====================== */
  const normalizedSource = computed(() =>
    source.value.map(normalizeConsultationRow),
  )

  /* ======================
     FILTERING (DETERMINISTIC)
     Order:
     1. Period (PRIMARY)
     2. Search
     3. Advanced filter
  ====================== */
  const filteredData = computed(() => {
    return normalizedSource.value.filter((row) => {
      /* PERIOD */
      const matchTanggal =
        row.tanggal >= startDate.value &&
        row.tanggal <= endDate.value

      /* SEARCH */
      const q = search.value.toLowerCase()
      const matchSearch =
        !q ||
        row.nama.toLowerCase().includes(q) ||
        String(row.nip).includes(q) ||
        row.instansi.toLowerCase().includes(q)

      /* ADVANCED FILTER */
      const matchInstansi =
        !filterInstansi.value || row.instansi === filterInstansi.value

      const matchStatus =
        !filterStatus.value || row.status === filterStatus.value

      return (
        matchTanggal &&
        matchSearch &&
        matchInstansi &&
        matchStatus
      )
    })
  })

  /* ======================
     SORT (DOMAIN RULE)
     tanggal ASC → session start ASC
  ====================== */
  const sortedData = computed(() => {
    return [...filteredData.value].sort((a, b) => {
      const dateA = `${a.tanggal} ${a.sessionTime?.start ?? '00:00'}`
      const dateB = `${b.tanggal} ${b.sessionTime?.start ?? '00:00'}`
      return dateA.localeCompare(dateB)
    })
  })

  /* ======================
     PAGINATION RESULT
  ====================== */
  const totalPages = computed(() =>
    Math.ceil(sortedData.value.length / perPage.value),
  )

  const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * perPage.value
    return sortedData.value.slice(start, start + perPage.value)
  })

  /* ======================
     FILTER OPTIONS (DERIVED)
  ====================== */
  const instansiOptions = computed(() =>
    [...new Set(normalizedSource.value.map((r) => r.instansi))].sort(),
  )

  const statusOptions = [
    { label: 'Berlangsung', value: 'berlangsung' },
    { label: 'Akan Datang', value: 'akan_datang' },
    { label: 'Berlalu', value: 'berlalu' },
  ]

  /* ======================
     RESET PAGE ON CHANGE
  ====================== */
  watch(
    [
      search,
      startDate,
      endDate,
      filterInstansi,
      filterStatus,
      perPage,
    ],
    () => {
      currentPage.value = 1
    },
  )

  /* ======================
     PUBLIC API
  ====================== */
  return {
    // search
    search,

    // period
    periodMode,
    startDate,
    endDate,

    // advanced filter
    filterInstansi,
    filterStatus,
    instansiOptions,
    statusOptions,

    // pagination
    currentPage,
    perPage,
    totalPages,

    // data
    paginatedData,
  }
}
