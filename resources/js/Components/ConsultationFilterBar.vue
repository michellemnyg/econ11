<template>
  <div class="bg-white border rounded-xl shadow-sm p-4 space-y-3">
    <!-- ROW 1: SEARCH + DESKTOP FILTER -->
    <div class="flex items-center gap-3">
      <!-- SEARCH -->
      <div class="relative w-full md:w-80">
        <Search class="absolute left-3 top-2.5 w-4 h-4 text-slate-400" />
        <input
          type="text"
          :value="search"
          @input="$emit('update:search', $event.target.value)"
          placeholder="Cari nama / NIP / instansi"
          class="w-full pl-9 pr-3 py-2 border rounded-lg text-sm"
        />
      </div>

      <!-- DESKTOP ACTIONS -->
      <div class="hidden md:flex items-center gap-2 ml-auto">
        <!-- PERIODE (PRIMARY FILTER) -->
        <button class="filter-btn" @click="$emit('open-period')">
          <Calendar class="w-4 h-4" />
          <span>Periode</span>
        </button>

        <!-- ADVANCED FILTER (SECONDARY) -->
        <button class="filter-btn" @click="$emit('open-filter')">
          <SlidersHorizontal class="w-4 h-4" />
          <span>Filter</span>
        </button>
      </div>
    </div>

    <!-- ROW 2: MOBILE ACTIONS -->
    <div class="flex md:hidden items-center gap-2">
      <!-- PERIODE -->
      <button
        class="filter-btn flex-1 justify-center"
        @click="$emit('open-period')"
      >
        <Calendar class="w-4 h-4" />
      </button>

      <!-- ADVANCED FILTER -->
      <button
        class="filter-btn flex-1 justify-center"
        @click="$emit('open-filter')"
      >
        <SlidersHorizontal class="w-4 h-4" />
      </button>
    </div>
  </div>
</template>

<script setup>
import {
  Calendar,
  SlidersHorizontal,
  Search,
} from 'lucide-vue-next'

defineProps({
  search: {
    type: String,
    default: '',
  },
})

defineEmits([
  'update:search',
  'open-period',
  'open-filter',
])
</script>

<style scoped>
.filter-btn {
  @apply flex items-center gap-2
         border rounded-lg px-3 py-2
         text-sm text-slate-700
         hover:bg-slate-100;
}
</style>
