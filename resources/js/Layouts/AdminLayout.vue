<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { LayoutDashboard, Users, LogOut, Menu } from 'lucide-vue-next'

const page = usePage()
const sidebarOpen = ref(false)

// 100% AMBIL DARI DATABASE (Tanpa Mockup Fallback)
const user = computed(() => page.props.auth?.user || { name: 'Loading...', role: '' })

// Normalisasi role menjadi huruf kecil agar styling CSS (merah/biru/hijau) selalu akurat
const userRole = computed(() => (user.value.role || '').toLowerCase())

const pageTitle = computed(() => {
  if (page.component.includes('Dashboard')) return 'Dashboard'
  if (page.component.includes('Klien')) return 'Klien'
  return 'Admin Panel'
})

const isActive = (name) => page.url.startsWith(name)

const sidebarRef = ref(null)
const handleClickOutside = (event) => {
  if (sidebarOpen.value && sidebarRef.value && !sidebarRef.value.contains(event.target)) {
    if (!event.target.closest('button[aria-label="Buka sidebar"]')) {
      sidebarOpen.value = false
    }
  }
}

onMounted(() => document.addEventListener('mousedown', handleClickOutside))
onUnmounted(() => document.removeEventListener('mousedown', handleClickOutside))
</script>

<template>
  <div class="min-h-screen bg-slate-50 flex">
    <aside
      ref="sidebarRef"
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 transform transition-transform duration-300 ease-out shadow-2xl',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <div class="flex items-center justify-between px-5 h-16 border-b border-slate-100">
        <div class="flex items-center gap-3">
          <img src="https://asndigital.bkn.go.id/images/logo-bkn.png" alt="BKN" class="h-8" />
          <span class="font-extrabold text-slate-800 tracking-tight text-lg">econ<span class="text-blue-600">11</span></span>
        </div>
        <button class="text-slate-400 hover:text-blue-600 transition p-1 rounded-md hover:bg-slate-50" @click="sidebarOpen = false" aria-label="Tutup sidebar">
          <Menu class="w-5 h-5" />
        </button>
      </div>

      <nav class="px-4 py-6 space-y-2 flex-1 h-[calc(100vh-140px)] overflow-y-auto">
        <p class="px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Menu Utama</p>

        <Link
          href="/dashboard"
          :class="[
            'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all',
            isActive('/dashboard') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600',
          ]"
        >
          <LayoutDashboard class="w-5 h-5" :class="isActive('/dashboard') ? 'text-blue-600' : 'text-slate-400'" />
          Dashboard
        </Link>

        <Link
          href="/klien"
          :class="[
            'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all',
            isActive('/klien') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600',
          ]"
        >
          <Users class="w-5 h-5" :class="isActive('/klien') ? 'text-blue-600' : 'text-slate-400'" />
          Klien / Konsultasi
        </Link>
      </nav>

      <div class="absolute bottom-0 w-full border-t border-slate-100 p-4 bg-white">
        <Link
          href="/logout"
          method="post"
          as="button"
          class="flex w-full items-center gap-3 text-sm font-bold text-red-500 hover:text-red-600 hover:bg-red-50 rounded-xl px-4 py-2.5 transition-colors"
        >
          <LogOut class="w-5 h-5" />
          Keluar Sistem
        </Link>
      </div>
    </aside>

    <div v-if="sidebarOpen" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 transition-opacity" @click="sidebarOpen = false" />

    <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
      <header class="sticky top-0 z-30 h-16 bg-white/90 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 shadow-sm">
        <div class="flex items-center gap-4">
          <button class="text-slate-500 hover:text-blue-600 transition p-1.5 bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow" @click="sidebarOpen = true" aria-label="Buka sidebar">
            <Menu class="w-5 h-5" />
          </button>
          <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">{{ pageTitle }}</h1>
        </div>

        <div class="flex items-center gap-3 text-right">
          <div class="hidden sm:block">
            <div class="text-sm font-bold text-slate-800">{{ user.name }}</div>
            <div class="text-[10px] font-extrabold uppercase tracking-widest mt-0.5"
                 :class="{
                   'text-red-600': userRole === 'superadmin' || userRole === 'admin',
                   'text-blue-600': userRole === 'operator',
                   'text-emerald-600': userRole === 'ketua tim' || userRole === 'ketua_tim',
                   'text-slate-500': !userRole
                 }">
              {{ user.role || 'Tanpa Role' }}
            </div>
          </div>
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 shadow-md flex items-center justify-center text-white font-bold text-sm border-2 border-white ring-2 ring-slate-100">
            {{ user.name.charAt(0).toUpperCase() }}
          </div>
        </div>
      </header>

      <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
        <slot />
      </main>
    </div>
  </div>
</template>
