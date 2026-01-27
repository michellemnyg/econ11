import { ref, computed } from 'vue'

export function useConsultationFormValidation(form) {
  const errors = ref({})

  function clearErrors() {
    errors.value = {}
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
  }

  function isValidPhone(phone) {
    return /^[0-9]{10,15}$/.test(phone)
  }

  function validate() {
    clearErrors()

    if (!form.value.topik) {
      errors.value.topik = 'Topik wajib dipilih'
    }

    if (!form.value.deskripsi) {
      errors.value.deskripsi = 'Deskripsi wajib diisi'
    }

    if (!form.value.tanggal) {
      errors.value.tanggal = 'Tanggal wajib dipilih'
    }

    if (!form.value.sesi) {
      errors.value.sesi = 'Sesi wajib dipilih'
    }

    if (!form.value.email) {
      errors.value.email = 'Email wajib diisi'
    } else if (!isValidEmail(form.value.email)) {
      errors.value.email = 'Format email tidak valid'
    }

    if (!form.value.hp) {
      errors.value.hp = 'Nomor handphone wajib diisi'
    } else if (!isValidPhone(form.value.hp)) {
      errors.value.hp = 'Nomor handphone tidak valid'
    }

    if (!form.value.captchaInput) {
      errors.value.captchaInput = 'Captcha wajib diisi'
    }

    return Object.keys(errors.value).length === 0
  }

  const isFormComplete = computed(() => {
    return (
      form.value.topik &&
      form.value.deskripsi &&
      form.value.tanggal &&
      form.value.sesi &&
      form.value.email &&
      form.value.hp &&
      form.value.captchaInput
    )
  })

  return {
    errors,
    validate,
    clearErrors,
    isFormComplete,
  }
}
