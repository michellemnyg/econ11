<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Klien" />

  <AdminLayout>
    <!-- HEADER -->
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-slate-800">
        Daftar Antrian Klien
      </h2>
      <p class="text-sm text-slate-500 mt-1">
        Daftar klien yang terdaftar untuk konsultasi hari ini
      </p>
    </div>

    <!-- TABLE PLACEHOLDER -->
    <div class="bg-white rounded-xl border border-slate-200 p-4">
      <p class="text-sm text-slate-500">
        (Tabel klien akan ditampilkan di sini)
      </p>
    </div>
  </AdminLayout>
</template>
