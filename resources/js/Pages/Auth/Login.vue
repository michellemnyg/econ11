<script setup>
import { Head, useForm } from '@inertiajs/vue3'

defineProps({
  canResetPassword: Boolean,
  status: String,
})

const form = useForm({
  email: '',     // NANTI akan diganti ke NIP di backend
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head title="Login | econ11" />

  <div class="min-h-screen flex items-center justify-center bg-slate-100 px-4">
    <div
      class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-slate-200 p-6 sm:p-8"
    >
      <!-- HEADER -->
      <div class="flex flex-col items-center mb-6">
        <img
          src="https://asndigital.bkn.go.id/images/logo-bkn.png"
          alt="Logo BKN"
          class="h-16 mb-4"
        />

        <h1 class="text-2xl font-bold text-slate-900">
          econ11
        </h1>

        <p class="text-sm text-slate-500 text-center mt-1">
          Sistem Admin Konsultasi ASN
        </p>
      </div>

      <!-- STATUS MESSAGE -->
      <div
        v-if="status"
        class="mb-4 rounded-lg bg-blue-50 border border-blue-200 px-4 py-2 text-sm text-blue-700"
      >
        {{ status }}
      </div>

      <!-- FORM -->
      <form @submit.prevent="submit" class="space-y-4">
        <!-- NIP -->
        <div>
          <label class="block text-sm font-medium text-slate-700">
            NIP
          </label>
          <input
            type="text"
            v-model="form.email"
            placeholder="Masukkan NIP"
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
            required
            autofocus
          />
          <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">
            {{ form.errors.email }}
          </p>
        </div>

        <!-- PASSWORD -->
        <div>
          <label class="block text-sm font-medium text-slate-700">
            Password
          </label>
          <input
            type="password"
            v-model="form.password"
            placeholder="Masukkan password"
            class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
            required
          />
          <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">
            {{ form.errors.password }}
          </p>
        </div>

        <!-- REMEMBER -->
        <div class="flex items-center justify-between">
          <label class="flex items-center gap-2 text-sm text-slate-600">
            <input
              type="checkbox"
              v-model="form.remember"
              class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
            />
            Ingat saya
          </label>
        </div>

        <!-- BUTTON -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 transition disabled:opacity-50"
        >
          Masuk
        </button>
      </form>

      <!-- FOOTER -->
      <p class="mt-6 text-center text-xs text-slate-400">
        © {{ new Date().getFullYear() }} Badan Kepegawaian Negara
      </p>
    </div>
  </div>
</template>
