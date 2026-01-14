<script setup>
import { computed, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import {
  LayoutDashboard,
  Users,
  LogOut,
  Menu,
} from 'lucide-vue-next'

const page = usePage()

/**
 * Sidebar state
 * default: collapsed (desktop & mobile)
 */
const sidebarOpen = ref(false)

/**
 * User info (dummy-safe)
 * nanti otomatis dari auth
 */
const user = computed(() => {
  return page.props.auth?.user ?? {
    name: 'Administrator',
    role: 'Superadmin',
  }
})

/**
 * Page title based on route/component
 */
const pageTitle = computed(() => {
  const component = page.component

  if (component.includes('Dashboard')) return 'Dashboard'
  if (component.includes('Klien')) return 'Klien'
  return 'Admin'
})

/**
 * Active route helper
 */
const isActive = (name) => {
  return page.url.startsWith(name)
}
</script>

<template>
  <div class="min-h-screen bg-slate-100 flex">
    <!-- SIDEBAR -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 transform transition-transform duration-200',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <!-- Sidebar Header -->
      <div class="flex items-center justify-between px-4 h-16 border-b border-slate-200">
        <div class="flex items-center gap-2">
          <img
            src="https://asndigital.bkn.go.id/images/logo-bkn.png"
            alt="BKN"
            class="h-8"
          />
          <span class="font-bold text-slate-800">econ11</span>
        </div>

        <button
          class="text-slate-500 hover:text-blue-500"
          @click="sidebarOpen = false"
          aria-label="Tutup sidebar"
        >
          <Menu class="w-5 h-5" />
        </button>
      </div>

      <!-- Navigation -->
      <nav class="px-3 py-4 space-y-1">
        <Link
          href="/dev/dashboard"
          :class="[
            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
            isActive('/dashboard')
              ? 'bg-blue-50 text-blue-600'
              : 'text-slate-600 hover:bg-slate-100',
          ]"
        >
          <LayoutDashboard class="w-5 h-5" />
          Dashboard
        </Link>

        <Link
          href="/dev/klien"
          :class="[
            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
            isActive('/klien')
              ? 'bg-blue-50 text-blue-600'
              : 'text-slate-600 hover:bg-slate-100',
          ]"
        >
          <Users class="w-5 h-5" />
          Klien
        </Link>
      </nav>

      <!-- Logout -->
      <div class="absolute bottom-0 w-full border-t border-slate-200 p-4">
        <button
          class="flex w-full items-center gap-3 text-sm text-red-500 hover:bg-red-50 rounded-lg px-3 py-2"
        >
          <LogOut class="w-5 h-5" />
          Logout
        </button>
      </div>
    </aside>

    <!-- OVERLAY MOBILE -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 bg-black/30 z-30 lg:hidden"
      @click="sidebarOpen = false"
    />

    <!-- MAIN -->
    <div class="flex-1 flex flex-col">
      <!-- HEADER -->
      <header
        class="sticky top-0 z-20 h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4"
      >
        <div class="flex items-center gap-3">
          <button
            class="text-slate-600 hover:text-blue-500"
            @click="sidebarOpen = true"
            aria-label="Buka sidebar"
          >
            <Menu class="w-6 h-6" />
          </button>

          <h1 class="text-lg font-semibold text-slate-800">
            {{ pageTitle }}
          </h1>
        </div>

        <!-- User Info -->
        <div class="text-right">
          <div class="text-sm font-medium text-slate-800">
            {{ user.name }}
          </div>
          <div class="text-xs text-slate-500">
            {{ user.role }}
          </div>
        </div>
      </header>

      <!-- CONTENT -->
      <main class="flex-1 p-4 sm:p-6">
        <slot />
      </main>
    </div>
  </div>
</template>
