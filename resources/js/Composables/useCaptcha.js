import { ref } from 'vue'

export function useCaptcha() {
  const captcha = ref(generateCaptcha())

  function generateCaptcha() {
    return Math.random().toString(36).substring(2, 8).toUpperCase()
  }

  function resetCaptcha() {
    captcha.value = generateCaptcha()
  }

  function validateCaptcha(input) {
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
