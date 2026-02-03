const today = new Date()
const y = today.getFullYear()
const m = String(today.getMonth() + 1).padStart(2, '0')

export const CALENDAR_MOCK = [
  {
    tanggal: `${y}-${m}-05`,
    sesi: [
      {
        label: '10:00 – 10:45',
        value: 'sesi-2',
        status: 'booked',
        narasumber: 'Narasumber A',
        topik: 'Pengadaan',
      },
      {
        label: '11:00 – 11:45',
        value: 'sesi-3',
        status: 'booked',
        narasumber: 'Narasumber A',
        topik: 'Kepegawaian',
      },
    ],
  },

  {
    tanggal: `${y}-${m}-12`,
    sesi: [
      {
        label: '09:00 – 09:45',
        value: 'sesi-1',
        status: 'booked',
        narasumber: 'Narasumber A',
        topik: 'Pengadaan',
      },
      {
        label: '10:00 – 10:45',
        value: 'sesi-2',
        status: 'booked',
        narasumber: 'Narasumber A',
        topik: 'Kepegawaian',
      },
      {
        label: '11:00 – 11:45',
        value: 'sesi-3',
        status: 'booked',
        narasumber: 'Narasumber A',
        topik: 'Pangkat dan Jabatan',
      },
      {
        label: '14:00 – 14:45',
        value: 'sesi-4',
        status: 'booked',
        narasumber: 'Narasumber A',
        topik: 'Disiplin ASN',
      },
      {
        label: '15:00 – 15:45',
        value: 'sesi-5',
        status: 'booked',
        narasumber: 'Narasumber A',
        topik: 'Lainnya',
      },
    ],
  },

    {
    tanggal: `${y}-${m}-17`,
    sesi: [
      {
        label: '10:00 – 10:45',
        value: 'sesi-2',
        status: 'booked',
        narasumber: 'Narasumber A',
        topik: 'Pengadaan',
      },
      {
        label: '11:00 – 11:45',
        value: 'sesi-3',
        status: 'booked',
        narasumber: 'Narasumber A',
        topik: 'Kepegawaian',
      },
    ],
  },
]
