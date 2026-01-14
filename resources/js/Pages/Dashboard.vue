<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'

const page = usePage()

const userName = page.props.auth?.user?.name ?? 'Administrator'
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout>
    <!-- WELCOME -->
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-slate-800">
        Selamat datang, {{ userName }}
      </h2>
      <p class="text-sm text-slate-500 mt-1">
        Berikut ringkasan aktivitas konsultasi hari ini.
      </p>
    </div>

    <!-- DUMMY CONTENT -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">Total Klien</p>
        <p class="text-2xl font-bold text-slate-800">24</p>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">Konsultasi Aktif</p>
        <p class="text-2xl font-bold text-slate-800">7</p>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">Selesai</p>
        <p class="text-2xl font-bold text-slate-800">15</p>
      </div>
    </div>
  </AdminLayout>
</template>
