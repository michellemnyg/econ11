<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Eye, EyeOff } from 'lucide-vue-next'

defineProps({
  canResetPassword: Boolean,
  status: String,
})

const form = useForm({
  nip: '',
  password: '',
  remember: false,
})

const showPassword = ref(false)

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head title="Login | econ11" />

  <div class="min-h-screen flex w-full bg-slate-50 overflow-hidden">
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-700 via-blue-800 to-slate-900 relative p-12 items-center justify-center">
      <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20 pointer-events-none">
        <div class="absolute -top-[20%] -right-[10%] w-[70%] h-[70%] rounded-full bg-blue-500 blur-3xl"></div>
        <div class="absolute bottom-[10%] -left-[20%] w-[60%] h-[60%] rounded-full bg-red-500 blur-3xl"></div>
      </div>

      <div class="relative z-10 max-w-lg text-white">
        <img src="https://asndigital.bkn.go.id/images/logo-bkn.png" alt="Logo BKN" class="h-20 mb-8 drop-shadow-lg bg-white/10 p-2 rounded-xl backdrop-blur-sm" />
        <h1 class="text-5xl font-extrabold tracking-tight mb-4 leading-tight">
          Sistem <span class="text-blue-300">Konsultasi</span><br>Kepegawaian Online
        </h1>
        <p class="text-lg text-blue-100 font-medium opacity-90 leading-relaxed border-l-4 border-red-400 pl-4">
          Platform administrasi terpadu untuk pengelolaan jadwal, penugasan narasumber, dan manajemen layanan konsultasi Aparatur Sipil Negara.
        </p>
      </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white relative">
      <div class="w-full max-w-md">

        <div class="lg:hidden flex flex-col items-center mb-10">
          <img src="https://asndigital.bkn.go.id/images/logo-bkn.png" alt="Logo BKN" class="h-16 mb-4" />
          <h2 class="text-2xl font-bold text-slate-800 tracking-tight">E-Consultation BKN</h2>
        </div>

        <div class="mb-8">
          <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-2">Selamat Datang</h2>
          <p class="text-sm text-slate-500 font-medium">Silakan masuk menggunakan NIP dan Password Anda.</p>
        </div>

        <div v-if="status" class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-start gap-3">
          <p class="text-sm font-medium text-emerald-800">{{ status }}</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
          <div class="space-y-1.5">
            <p v-if="form.errors.nip" class="text-sm font-semibold text-red-500">{{ form.errors.nip }}</p>
            <label class="block text-sm font-bold text-slate-700">Nomor Induk Pegawai (NIP)</label>
            <input
              type="text"
              v-model="form.nip"
              placeholder="Masukkan NIP Anda"
              class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 bg-slate-50 font-mono transition-colors shadow-sm"
              required
              autofocus
            />
          </div>

          <div class="space-y-1.5">
            <label class="block text-sm font-bold text-slate-700">Password</label>
            <div class="relative">
              <input
                :type="showPassword ? 'text' : 'password'"
                v-model="form.password"
                placeholder="Masukkan Password Anda"
                class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 bg-slate-50 transition-colors shadow-sm pr-11"
                required
              />
              <button 
                type="button" 
                @click="showPassword = !showPassword" 
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 focus:outline-none p-1 rounded-md transition-colors"
                title="Toggle Show Password"
              >
                <Eye v-if="!showPassword" class="w-5 h-5" />
                <EyeOff v-else class="w-5 h-5" />
              </button>
            </div>
            <p v-if="form.errors.password" class="text-xs font-semibold text-red-500 mt-1">{{ form.errors.password }}</p>
          </div>

          <div class="flex items-center justify-between pt-2">
            <label class="flex items-center gap-2 text-sm text-slate-600 font-medium cursor-pointer">
              <input type="checkbox" v-model="form.remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" />
              Ingat Saya
            </label>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-3.5 mt-4 transition-all disabled:opacity-70 disabled:cursor-not-allowed shadow-md shadow-blue-600/20"
          >
            <span v-if="form.processing" class="flex items-center justify-center gap-2">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              Memproses...
            </span>
            <span v-else>Masuk ke Sistem</span>
          </button>
        </form>

        <div class="mt-12 text-center">
          <p class="text-xs font-medium text-slate-400">
            &copy; {{ new Date().getFullYear() }} Direktorat Infrastruktur Teknologi Informasi<br>Badan Kepegawaian Negara
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
