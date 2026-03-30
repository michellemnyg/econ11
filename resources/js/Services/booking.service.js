// src/Services/booking.service.js
import axios from 'axios';

export async function getBookedSessionsByDate(date) {
    try {
        // Tembak API asli Laravel
        const response = await axios.get(`/api/consultations/booked?date=${date}`);
        return response.data; // Harus mengembalikan array: [{ session: 'sesi-1' }, ...]
    } catch (error) {
        console.error("Gagal mengambil data booking:", error);
        return [];
    }
}
