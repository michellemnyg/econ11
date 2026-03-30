<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f9fafb; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { text-align: center; border-bottom: 2px solid #ef4444; padding-bottom: 20px; margin-bottom: 20px; }
        .header img { height: 60px; margin-bottom: 10px; }
        .content { padding: 10px 0; }
        .box { background: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e2e8f0; }
        .box p { margin: 8px 0; font-size: 15px; }
        .button-container { text-align: center; margin: 30px 0; }
        .button { display: inline-block; padding: 12px 24px; background: #2563eb; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; }
        .footer { font-size: 12px; color: #64748b; text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    @php
        // Konversi kode sesi ke jam agar klien tahu jam berapa
        $waktuSesi = [
            'sesi-1' => '09:00 - 09:45 WITA',
            'sesi-2' => '10:00 - 10:45 WITA',
            'sesi-3' => '11:00 - 11:45 WITA',
            'sesi-4' => '14:00 - 14:45 WITA',
            'sesi-5' => '15:00 - 15:45 WITA',
        ];
        $jamKonsultasi = $waktuSesi[$consultation->sesi] ?? 'Menunggu Konfirmasi';
    @endphp

    <div class="container">
        <div class="header">
            <img src="https://asndigital.bkn.go.id/images/logo-bkn.png" alt="Logo BKN">
            <h2 style="color: #0f172a; margin: 0; font-size: 20px;">e-con | Kanreg XI BKN</h2>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $consultation->nama }}</strong>,</p>
            <p>Permintaan konsultasi online Anda telah kami terima dan dijadwalkan. Berikut rinciannya:</p>

            <div class="box">
                <p><strong>Topik:</strong> {{ $consultation->topik->nama_topik ?? 'Konsultasi Kepegawaian' }}</p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($consultation->tanggal)->translatedFormat('l, d F Y') }}</p>
                <p><strong>Sesi:</strong> {{ strtoupper(str_replace('-', ' ', $consultation->sesi)) }}</p>
                <p><strong>Waktu:</strong> <span style="color: #ef4444; font-weight: bold;">{{ $jamKonsultasi }}</span></p>
            </div>

            <p>Silakan bergabung ke ruang Zoom pada waktu yang telah ditentukan. Mohon hadir 5 menit sebelum sesi dimulai.</p>

            <div class="button-container">
                <a href="{{ $consultation->zoom_link }}" class="button">Bergabung ke Zoom Meeting</a>
            </div>

            <div style="text-align: center; background: #fef2f2; border: 1px dashed #f87171; padding: 10px; border-radius: 6px; width: max-content; margin: 0 auto;">
                <p style="margin: 0; font-size: 14px; color: #991b1b;">
                    Passcode: <strong style="font-family: monospace; font-size: 18px; letter-spacing: 2px;">{{ $consultation->zoom_passcode ?? 'econ11' }}</strong>
                </p>
            </div>
        </div>

        <div class="footer">
            © {{ date('Y') }} Kantor Regional XI Badan Kepegawaian Negara.<br>
            Jl. A.A. Maramis No.Km. 8, Paniki Bawah, Kec. Mapanget, Kota Manado, Sulawesi Utara 95258.<br>
            <em>Email ini dibuat otomatis oleh sistem. Mohon tidak membalas email ini.</em>
        </div>
    </div>
</body>
</html>
