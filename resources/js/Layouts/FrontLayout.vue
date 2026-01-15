<script setup>
import { ref } from 'vue'
import { ArrowUp } from 'lucide-vue-next'
</script>

<template>
  <div class="min-h-screen flex flex-col bg-slate-50">
    <!-- HEADER / HERO -->
<header
  class="relative min-h-screen bg-cover bg-center"
  style="background-image: url('https://i.ibb.co.com/N2zYq8Zg/kantor1.jpg')"
>
  <!-- OVERLAY -->
  <div class="absolute inset-0 bg-blue-500/80"></div>

  <!-- NAVBAR -->
  <nav
    class="relative z-10 max-w-7xl mx-auto px-6 py-4 flex items-center justify-between"
  >
    <!-- LOGO -->
    <div class="flex items-center gap-2">
      <div
        class="w-10 h-10 rounded-full bg-white flex items-center justify-center font-bold text-blue-600"
      >
        e
      </div>
      <span
        class="text-lg font-semibold text-white tracking-wide hover:text-blue-200 transition"
      >
        con11
      </span>
    </div>

    <!-- MENU -->
    <div class="flex gap-6 text-sm font-medium text-white">
      <a href="#" class="hover:text-blue-200 transition">
        Home
      </a>
      <a href="#" class="hover:text-blue-200 transition">
        Informasi
      </a>
    </div>
  </nav>

  <!-- HERO CONTENT -->
  <div
    class="relative z-10 max-w-7xl mx-auto px-6 flex flex-col justify-center min-h-[calc(100vh-72px)]"
  >
    <div class="grid lg:grid-cols-2 gap-10 items-center">
      <!-- TITLE -->
      <div class="text-white">
        <h1 class="text-3xl lg:text-4xl font-bold leading-tight">
          Konsultasi Online
        </h1>
        <h2 class="text-xl lg:text-2xl font-semibold mt-2">
          Kantor Regional XI BKN
        </h2>
        <p class="mt-4 text-blue-100 text-sm leading-relaxed max-w-xl">
          Media penunjang fasilitasi konsultasi kepegawaian
        </p>
      </div>

      <!-- FORM WRAPPER -->
      <div
        class="bg-white rounded-2xl shadow-xl p-6 lg:p-8 w-full"
      >
        <!-- SLOT FORM -->
        <slot name="form" />
      </div>
    </div>
  </div>
</header>


    <!-- MAIN CONTENT -->
    <main class="flex-1">
      <div class="max-w-7xl mx-auto px-6 py-16">
        <!-- SLOT CONTENT -->
        <slot />
      </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-800 text-slate-300 text-sm py-6">
      <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
        <p>
          © {{ new Date().getFullYear() }} Kantor Regional XI BKN
        </p>

        <button
          @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
          class="flex items-center gap-2 text-slate-300 hover:text-white transition"
        >
          <ArrowUp class="w-4 h-4" />
          Kembali ke atas
        </button>
      </div>
    </footer>
  </div>
</template>
