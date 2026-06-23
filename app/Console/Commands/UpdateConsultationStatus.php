<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateConsultationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consultation:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbarui status konsultasi yang sudah lewat tanggalnya menjadi selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pembaruan status konsultasi...');

        $updatedCount = \App\Models\Consultation::where('status', 'akan_datang')
            ->where('tanggal', '<', \Carbon\Carbon::today()->toDateString())
            ->update(['status' => 'selesai']);

        $this->info("Berhasil memperbarui {$updatedCount} konsultasi menjadi selesai.");
    }
}
