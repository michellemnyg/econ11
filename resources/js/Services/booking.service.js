import axios from 'axios';

export async function getBookedSessionsByDate(date) {
    try {
        const response = await axios.get(`/api/consultations/booked?date=${date}`);
        return response.data;
    } catch (error) {
        console.error("Gagal mengambil data booking:", error);
        return [];
    }
}
