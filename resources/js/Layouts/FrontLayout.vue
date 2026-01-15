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

  <nav
  class="relative z-10 max-w-7xl mx-auto px-6 py-6 flex items-center justify-between"
>
  <!-- LOGO -->
  <div class="flex items-center gap-3">
    <div
      class="w-12 h-12 rounded-full bg-white flex items-center justify-center font-bold text-blue-600 text-xl"
    >
      e
    </div>
    <span
      class="text-xl font-semibold text-white tracking-wide hover:text-blue-200 transition"
    >
      con11
    </span>
  </div>

  <!-- MENU -->
  <div class="flex gap-8 text-base font-medium text-white">
    <a
      href="#"
      class="relative pb-1 transition after:absolute after:left-0 after:bottom-0 after:h-[2px] after:w-0 after:bg-red-500 after:transition-all after:duration-300 hover:after:w-full"
    >
      HOME
    </a>
    <a
      href="#"
      class="relative pb-1 transition after:absolute after:left-0 after:bottom-0 after:h-[2px] after:w-0 after:bg-red-500 after:transition-all after:duration-300 hover:after:w-full"
    >
      INFORMASI
    </a>
  </div>
</nav>

  <!-- HERO CONTENT -->
  <div
    class="relative z-10 max-w-7xl mx-auto px-6 flex flex-col justify-center min-h-[calc(100vh-72px)]"
  >
    <div class="grid lg:grid-cols-2 gap-10 items-center">
      <!-- TITLE -->
        <div class="text-white pl-6 border-l-4 border-white/80">
        <h1 class="text-4xl lg:text-5xl font-bold leading-tight
                transition-colors duration-300
                hover:text-red-400">
            Konsultasi Online
        </h1>
        <h2 class="text-2xl lg:text-3xl font-semibold mt-3
                transition-colors duration-300
                hover:text-red-300">
            Kantor Regional XI BKN
        </h2>
        <p class="mt-6 text-blue-100 text-base leading-relaxed max-w-xl">
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
