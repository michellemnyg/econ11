<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class ZoomService
{
    private $accountId;
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->accountId = env('ZOOM_ACCOUNT_ID');
        $this->clientId = env('ZOOM_CLIENT_ID');
        $this->clientSecret = env('ZOOM_CLIENT_SECRET');
    }

    /**
     * Mendapatkan Access Token (Berlaku 1 Jam, kita Cache agar tidak hit API terus-menerus)
     */
    private function getAccessToken()
    {
        if (Cache::has('zoom_access_token')) {
            return Cache::get('zoom_access_token');
        }

        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post('https://zoom.us/oauth/token', [
                'grant_type' => 'account_credentials',
                'account_id' => $this->accountId,
            ]);

        if ($response->successful()) {
            $token = $response->json('access_token');
            $expiresIn = $response->json('expires_in'); // Biasanya 3599 detik (1 Jam)

            // Simpan di Cache, dikurangi 60 detik sebagai buffer sebelum expired
            Cache::put('zoom_access_token', $token, $expiresIn - 60);

            return $token;
        }

        throw new Exception('Gagal mendapatkan Zoom Token. Cek Kredensial .env Anda.');
    }

    /**
     * Membuat Zoom Meeting Baru
     */
    public function createMeeting($topic, $startTime, $duration = 45)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post('https://api.zoom.us/v2/users/me/meetings', [
                'topic' => $topic,
                'type' => 2, // 2 = Scheduled meeting
                'start_time' => $startTime, // Format ISO: yyyy-MM-dd'T'HH:mm:ss
                'duration' => $duration,
                'timezone' => 'Asia/Makassar', // Sesuai zona waktu WITA (BKN Regional XI Manado)
                'password' => 'econ11',
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'mute_upon_entry' => true, // Peserta otomatis mute saat masuk
                    'waiting_room' => true,    // Wajib ada ruang tunggu agar aman
                ]
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new Exception('Gagal membuat Zoom Meeting: ' . $response->body());
    }
}
