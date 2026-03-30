import axios from 'axios'

export const getCalendarSessions = async () => {
  try {
    const response = await axios.get('/api/consultations/calendar')
    return response.data
  } catch (error) {
    console.error("Gagal mengambil data kalender:", error)
    return []
  }
}
