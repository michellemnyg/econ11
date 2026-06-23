<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pns', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('nip_lama', 9)->nullable();
            $table->string('nip_baru', 20)->nullable()->index();
            $table->string('nama', 55)->nullable();
            $table->string('gelar_depan', 25)->nullable();
            $table->string('gelar_blk', 50)->nullable();
            $table->string('tempatlahir_lokasi_id', 36)->nullable();
            $table->datetime('tgl_lhr')->nullable();
            $table->integer('jenis_kelamin')->nullable();
            $table->string('tk_pendidikan_id', 2)->nullable();
            $table->string('golongan_awal_id', 2)->nullable();
            $table->datetime('tmt_cpns')->nullable();
            $table->datetime('tmt_pns')->nullable();
            $table->string('jenis_pegawai_id', 2)->nullable();
            $table->string('instansi_induk_id', 36)->nullable();
            $table->string('insduk_cepatkode', 5)->nullable();
            $table->string('instansi_kerja_id', 36)->nullable();
            $table->string('insker_cepatkode', 5)->nullable();
            $table->string('unor_id', 36)->nullable();
            $table->string('unor_cepatkode', 30)->nullable();
            $table->integer('jenis_jabatan_id')->nullable();
            $table->string('eselon_id', 2)->nullable();
            $table->longText('struktural_nama_jabatan')->nullable();
            $table->string('jabatan_fungsional_id', 36)->nullable();
            $table->string('jabatan_fungsional_umum_id', 36)->nullable();
            $table->datetime('tmt_jabatan')->nullable();
            $table->string('golongan_id', 2)->nullable();
            $table->datetime('tmt_golongan')->nullable();
            $table->integer('mk_tahun')->nullable();
            $table->integer('mk_bulan')->nullable();
            $table->string('tempatkerja_lokasi_id', 36)->nullable();
            $table->string('jenis_kawin_id', 1)->nullable();
            $table->string('anak_tanggungan', 1)->nullable();
            $table->string('kedudukan_hukum_id', 2)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kode_pos', 15)->nullable();
            $table->string('agama_id', 1)->nullable();
            $table->string('jenis_id_dokumen_id', 1)->nullable();
            $table->string('nik', 30)->nullable();
            $table->string('status_cpns_pns', 1)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('nomor_hp', 140)->nullable();
            $table->string('nomor_telpon', 140)->nullable();
            $table->string('kartu_asn', 13)->nullable();
            $table->string('sk_konv_nomor', 90)->nullable();
            $table->datetime('sk_konv_tanggal')->nullable();
            $table->integer('sk_konv_urut')->nullable();
            $table->string('gunrum', 1)->nullable();
            $table->longText('jenis_asn')->nullable();
            $table->string('pendidikan_id', 36)->nullable();
            $table->string('nama_unor', 255)->nullable();
            $table->string('nama_jabatan', 255)->nullable();
            $table->string('unor_induk_nama', 255)->nullable();
            $table->string('pendidikan_nama', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pns');
    }
};
