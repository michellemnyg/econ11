import axios from 'axios'
import { ref } from 'vue'

const holidays = ref([])
const isLoaded = ref(false)

export async function fetchHolidays() {
  if (isLoaded.value) return
  try {
    const response = await axios.get('https://libur.deno.dev/api')
    holidays.value = response.data
    isLoaded.value = true
  } catch (error) {
    console.error('Failed to fetch holidays', error)
  }
}

export function checkIsHoliday(dateString) {
  if (!dateString) return null
  return holidays.value.find(h => h.date === dateString) || null
}
