<?php

namespace App\Mail;

use App\Models\Consultation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConsultationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $consultation;

    public function __construct(Consultation $consultation)
    {
        $this->consultation = $consultation;
    }

    public function build()
    {
        return $this->subject('Konfirmasi Jadwal Konsultasi Online - e-con BKN')
                    ->view('emails.consultation');
    }
}
