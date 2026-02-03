import { CALENDAR_MOCK } from '@/Mocks/calendar.mock'

export const getCalendarSessions = async () => {
  // nanti ganti:
  // return axios.get('/api/consultations/calendar')

  return new Promise((resolve) => {
    setTimeout(() => {
      resolve(CALENDAR_MOCK)
    }, 500)
  })
}
