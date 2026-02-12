import { ref } from 'vue'

export function useClipboardToast() {
  const toastMessage = ref('')
  const showToast = ref(false)

  const copy = async (text?: string | null, message = 'Berhasil disalin') => {
    if (!text) return
    await navigator.clipboard.writeText(text)
    toastMessage.value = message
    showToast.value = true

    setTimeout(() => {
      showToast.value = false
    }, 2000)
  }

  return {
    copy,
    toastMessage,
    showToast,
  }
}
