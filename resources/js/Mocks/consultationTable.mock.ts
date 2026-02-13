// src/Mocks/consultations.mock.ts
import { ConsultationRow } from '@/Domain/Consultation/consultation.types'

export const CONSULTATION_TABLE_MOCK: ConsultationRow[] = [
  /**
   * 1️⃣ Akan datang
   * - Belum ada petugas
   * - Meeting SUDAH tergenerate
   */
  {
    id: 1,
    tanggal: '2026-02-10',
    sesi: 'Sesi 1 (09:00 - 09:45)',

    nip: 198901012019031001,
    nama: 'Ahmad Fauzi',
    instansi: 'BKD Provinsi Jawa Tengah',
    topik: 'Pangkat dan Jabatan',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: null,

    integrasi: {
      meetingId: '73493806160',
      linkZoom: 'https://zoom.us/j/73493806160',
      passcode: 'bknkr11',
      createdAt: '2026-02-05T08:00:00Z',
    },

    createdAt: '2026-02-05T07:50:00Z',
  },

  /**
   * 2️⃣ Sedang berlangsung
   * - Petugas sudah di-assign
   * - Link tersedia & bisa dicopy dari tabel
   */
  {
    id: 2,
    tanggal: '2026-02-05',
    sesi: 'Sesi 3 (11:00 - 11:45)',

    nip: 197805102010122002,
    nama: 'Siti Rahmawati',
    instansi: 'Kementerian Perhubungan',
    topik: 'Pengembangan Karir',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: 'Narasumber B',

    integrasi: {
      meetingId: '1234567890',
      linkZoom: 'https://zoom.us/j/1234567890',
      passcode: 'econ11',
      createdAt: '2026-02-04T10:00:00Z',
    },

    createdAt: '2026-02-04T09:55:00Z',
  },

  /**
   * 3️⃣ Akan datang
   * - Petugas sudah ada
   * - Meeting BELUM tergenerate
   */
  {
    id: 3,
    tanggal: '2026-02-12',
    sesi: 'Sesi 2 (10:00 - 10:45)',

    nip: 198112122008121001,
    nama: 'Budi Santoso',
    instansi: 'Pemprov Jawa Timur',
    topik: 'Disiplin',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: 'Narasumber A',

    integrasi: {
      meetingId: null,
      linkZoom: null,
      passcode: null,
      createdAt: null,
    },

    createdAt: '2026-02-05T08:10:00Z',
  },

  /**
   * 4️⃣ Sudah berlalu
   * - Lengkap
   * - Untuk badge "berlalu"
   */
  {
    id: 4,
    tanggal: '2026-01-20',
    sesi: 'Sesi 5 (15:00 - 15:45)',

    nip: 197912302005011003,
    nama: 'Rina Kurniasih',
    instansi: 'BKD Kota Bandung',
    topik: 'Penggajian dan Tunjangan',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: 'Narasumber C',

    integrasi: {
      meetingId: '9988776655',
      linkZoom: 'https://zoom.us/j/9988776655',
      passcode: 'asn2026',
      createdAt: '2026-01-18T13:00:00Z',
    },

    createdAt: '2026-01-18T12:55:00Z',
  },

  {
    id: 5,
    tanggal: '2026-02-06',
    sesi: 'Sesi 5 (15:00 - 15:45)',

    nip: 197912302005011003,
    nama: 'Rina Kurniasih',
    instansi: 'BKD Kota Bandung',
    topik: 'Penilaian Kinerja',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: null,

    integrasi: {
      meetingId: '9988776655',
      linkZoom: 'https://zoom.us/j/9988776655',
      passcode: 'asn2026',
      createdAt: '2026-01-18T13:00:00Z',
    },

    createdAt: '2026-01-18T12:55:00Z',
  },

    {
    id: 6,
    tanggal: '2026-02-06',
    sesi: 'Sesi 5 (15:00 - 15:45)',

    nip: 197912302005011003,
    nama: 'Rina Kurniasih',
    instansi: 'BKD Kota Bandung',
    topik: 'Pangkat dan Jabatan',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: null,

    integrasi: {
      meetingId: '9988776655',
      linkZoom: 'https://zoom.us/j/9988776655',
      passcode: 'asn2026',
      createdAt: '2026-01-18T13:00:00Z',
    },

    createdAt: '2026-01-18T12:55:00Z',
  },

    {
    id: 6,
    tanggal: '2026-02-06',
    sesi: 'Sesi 4 (14:00 - 14:45)',

    nip: 197912302005011003,
    nama: 'Rina Kurniasih',
    instansi: 'BKD Kota Bandung',
    topik: 'Pengadaan',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: null,

    integrasi: {
      meetingId: '9988776655',
      linkZoom: 'https://zoom.us/j/9988776655',
      passcode: 'asn2026',
      createdAt: '2026-01-18T13:00:00Z',
    },

    createdAt: '2026-01-18T12:55:00Z',
  },

    {
    id: 7,
    tanggal: '2026-02-12',
    sesi: 'Sesi 4 (14:00 - 14:45)',

    nip: 198112122008121001,
    nama: 'Budi',
    instansi: 'Pemprov Jawa Timur',
    topik: 'Promosi',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: 'Narasumber A',

    integrasi: {
      meetingId: null,
      linkZoom: null,
      passcode: null,
      createdAt: null,
    },

    createdAt: '2026-02-05T08:10:00Z',
  },

  {
    id: 8,
    tanggal: '2026-02-20',
    sesi: 'Sesi 3 (11:00 - 11:45)',

    nip: 198112122008121001,
    nama: 'Budi Santoso',
    instansi: 'Pemprov Jawa Timur',
    topik: 'Mutasi',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: 'Narasumber A',

    integrasi: {
      meetingId: null,
      linkZoom: null,
      passcode: null,
      createdAt: null,
    },

    createdAt: '2026-02-05T08:10:00Z',
  },

    {
    id: 9,
    tanggal: '2026-02-12',
    sesi: 'Sesi 5 (15:00 - 16:45)',

    nip: 198112122008121001,
    nama: 'Santoso',
    instansi: 'Pemprov Jawa Timur',
    topik: 'Pengembangan Karir',

    deskripsiMasalah: 'Konsultasi terkait proses kenaikan pangkat dan penyesuaian jabatan struktural.',

    petugas: 'Narasumber A',

    integrasi: {
      meetingId: null,
      linkZoom: null,
      passcode: null,
      createdAt: null,
    },

    createdAt: '2026-02-05T08:10:00Z',
  },
]
