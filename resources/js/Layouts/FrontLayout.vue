<script setup>
import { ref } from 'vue'
import { ArrowUp, FileText } from 'lucide-vue-next'

const topics = [
  {title: 'Penyusunan dan Penetapan Kebutuhan', desc: 'Penyusunan kebutuhan pegawai berdasarkan Analisis Jabatan (Anjab) dan Analisis Beban Kerja (ABK)'},
  {title: 'Pengadaan', desc: 'Pengadaan Pegawai Negeri Sipil untuk mengisi formasi yang lowong'},
  {title: 'Pangkat dan Jabatan', desc: 'Tingkat atau jenjang kedudukan seorang ASN dalam sistem kepegawaian'},
  {title: 'Pengembangan Karir', desc: 'Perjalanan kemajuan ASN/PNS secara individual dalam jenjang jabatan/kepangkatan'},
  {title: 'Pola Karir', desc: 'Urutan penempatan dan perpindahan PNS dalam dan antar jabatan secara berkesinambungan'},
  {title: 'Promosi', desc: 'Promosi berdasarkan prestasi kerja dan senioritas'},
  {title: 'Mutasi', desc: 'Perubahan status PNS seperti pengangkatan, pemindahan, pemberhentian, dll.'},
  {title: 'Penilaian Kinerja', desc: 'Perbandingan realisasi kinerja dengan target yang telah ditetapkan'},
  {title: 'Penggajian dan Tunjangan', desc: 'Informasi hak gaji dan tunjangan ASN'},
  {title: 'Penghargaan', desc: 'Bentuk penghargaan dan tanda kehormatan ASN'},
  {title: 'Disiplin', desc: 'Kewajiban dan larangan ASN sesuai peraturan perundang-undangan'},
  {title: 'Pemberhentian', desc: 'Pemberhentian sebagai ASN atau dari jabatan'},
  {title: 'Jaminan Pensiun dan Hari Tua', desc: 'Hak pensiun dan jaminan hari tua ASN'},
  {title: 'Perlindungan', desc: 'Jaminan dan bantuan hukum bagi ASN'},
  {title: 'Manajemen PPPK', desc: 'Pengelolaan Pegawai Pemerintah dengan Perjanjian Kerja'},
]

const scrollToForm = () => {
  const form = document.querySelector('[data-form]')
  form?.scrollIntoView({ behavior: 'smooth' })
}

const showTopics = ref(false)
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
      <div class="relative z-20">
        <nav class="max-w-7xl mx-auto px-6 py-6 flex justify-center">
          <div class="flex items-center gap-8">
            <div
              class="flex items-center gap-3 cursor-pointer
                     transition-transform duration-300 hover:scale-[1.03]"
            >
              <div
                class="w-12 h-12 rounded-full bg-blue-600
                       flex items-center justify-center
                       font-bold text-white text-xl shadow-lg border border-white/10"
              >
                e
              </div>
              <span
                class="text-3xl font-bold text-white tracking-tight
                       transition-colors duration-300
                       hover:text-red-500"
              >
                con11
              </span>
            </div>

            <div class="h-10 w-[2px] bg-white/30"></div>

            <div class="leading-tight text-left">
            <p class="text-sm sm:text-xl font-bold text-white">
                Konsultasi Online
            </p>
            <p class="text-xs sm:text-base font-semibold text-white/90">
                Kantor Regional XI BKN
            </p>
            </div>
          </div>
        </nav>
      </div>

      <!-- HERO -->
      <div
        class="relative z-10 max-w-7xl mx-auto px-6
               min-h-[calc(100vh-120px)]
               flex items-center"
      >
        <div
          class="grid w-full items-center transition-all duration-500"
          :class="showTopics ? 'lg:grid-cols-2 gap-12' : 'lg:grid-cols-1 justify-items-center'"
        >
          <!-- LEFT -->
          <div
            class="text-white text-left transition-all duration-500"
            :class="showTopics ? 'justify-self-start' : 'max-w-xl'"
          >
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight">
              Konsultasi Kepegawaian
            </h1>
            <p class="mt-6 text-blue-100 text-base leading-relaxed">
              Media penunjang fasilitasi konsultasi kepegawaian bagi ASN
              dalam rangka peningkatan kualitas layanan manajemen ASN
            </p>

            <div class="mt-10 flex items-center gap-6">
              <button
                @click="showTopics = true"
                class="flex items-center gap-2 text-white
                       hover:text-red-300 transition"
              >
                <FileText class="w-6 h-6" />
                <span class="text-sm font-medium">
                  Topik Konsultasi
                </span>
              </button>

              <button
                @click="scrollToForm"
                class="flex items-center gap-2
                       text-red-400 hover:text-red-300 transition"
              >
                <FileText class="w-6 h-6" />
                <span class="text-sm font-medium">
                  Isi Form
                </span>
              </button>
            </div>
          </div>

          <!-- MODAL TOPIK -->
        <div
        v-if="showTopics"
        class="relative transition-all duration-500 pt-10"
        >
        <!-- CLOSE BUTTON (CENTER TOP) -->
        <button
            @click="showTopics = false"
            aria-label="Tutup"
            class="absolute top-0 left-1/2
                -translate-x-1/2 -translate-y-1/2
                w-9 h-9
                flex items-center justify-center
                rounded-lg
                bg-white
                text-black
                shadow
                hover:text-red-500 transition"
        >
            ✕
        </button>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
            <div
            v-for="topic in topics"
            :key="topic.title"
            class="group cursor-pointer
                    bg-white text-blue-600
                    rounded-xl
                    px-4 py-3
                    flex items-center justify-center text-center
                    transition-all duration-300
                    hover:scale-110 hover:bg-blue-500 hover:text-white"
            >
            <div>
                <p class="font-semibold text-sm leading-snug">
                {{ topic.title }}
                </p>

                <p
                class="mt-2 text-xs text-blue-100
                        opacity-0 max-h-0 overflow-hidden
                        group-hover:opacity-100 group-hover:max-h-32
                        transition-all duration-300"
                >
                {{ topic.desc }}
                </p>
            </div>
            </div>
        </div>
        </div>
        </div>
      </div>
    </header>

    <!-- MAIN -->
    <main class="flex-1">
      <div class="max-w-7xl mx-auto px-6 py-16">
        <slot name="form"/>
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
