<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallBackend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aplikasi:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai instalasi...');
        $this->info('Pemasangan struktur table pada database...');

        $table_migration = $this->call('migrate:fresh');

        if ($table_migration == 0) {
            $this->info('Pembuatan struktur tabel berhasil.');
        } else {
            $this->error('Gagal membuat struktur tabel');
            return 1;
        }

        $this->info('Pembuatan data awal...');

        $data_seeder = $this->call('db:seed');

        if ($data_seeder == 0) {
            $this->info('Data awal berhasil dibuat.');
        } else {
            $this->error('Gagal membuat data awal.');
            return 1;
        }

        $this->info('Proses instalasi berhasil');
    }
}
