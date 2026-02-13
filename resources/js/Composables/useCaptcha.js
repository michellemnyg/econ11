import { ref } from 'vue'

export function useCaptcha() {
  const captcha = ref(generateCaptcha())

  function generateCaptcha() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'
    let result = ''
    const length = 6

    for (let i = 0; i < length; i++) {
      result += chars.charAt(Math.floor(Math.random() * chars.length))
    }
    return result
  }

  function resetCaptcha() {
    captcha.value = generateCaptcha()
  }

  function validateCaptcha(input) {
    // Validasi case-sensitive
    const isValid = input === captcha.value

    if (!isValid) {
      resetCaptcha()
    }

    return isValid
  }

  return {
    captcha,
    resetCaptcha,
    validateCaptcha,
  }
}
