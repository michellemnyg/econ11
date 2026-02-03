import { CONSULTATION_BOOKINGS_MOCK } from '@/Mocks/consultationBookings.mock'

const USE_MOCK = import.meta.env.VITE_USE_MOCK === 'true'

export async function getBookedSessionsByDate(date) {
  if (USE_MOCK) {
    return CONSULTATION_BOOKINGS_MOCK.filter(
      (b) => b.date === date
    )
  }

  // FUTURE API
  // return axios.get(`/api/consultations/booked?date=${date}`)
}
