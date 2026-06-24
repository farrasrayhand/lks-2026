<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ServiceType;
use App\Models\ServiceRequest;
use App\Models\Report;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Kaltim',
            'email' => 'admin@kaltim.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Kantor Gubernur Kaltim',
        ]);

        $citizen1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@email.com',
            'password' => Hash::make('password'),
            'role' => 'citizen',
            'phone' => '081234567891',
            'address' => 'Jl. Mulawarman No. 10, Samarinda',
        ]);

        $citizen2 = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti@email.com',
            'password' => Hash::make('password'),
            'role' => 'citizen',
            'phone' => '081234567892',
            'address' => 'Jl. Pahlawan No. 5, Balikpapan',
        ]);

        $services = [
            ['name' => 'Pembuatan KTP', 'description' => 'Pelayanan pembuatan Kartu Tanda Penduduk elektronik', 'estimated_days' => 14],
            ['name' => 'Pembuatan KK', 'description' => 'Pelayanan pembuatan Kartu Keluarga', 'estimated_days' => 7],
            ['name' => 'Akta Kelahiran', 'description' => 'Pelayanan pembuatan Akta Kelahiran', 'estimated_days' => 7],
            ['name' => 'Izin Usaha', 'description' => 'Pelayanan perizinan usaha mikro dan kecil', 'estimated_days' => 21],
            ['name' => 'Surat Pindah', 'description' => 'Pelayanan surat keterangan pindah domisili', 'estimated_days' => 5],
        ];

        foreach ($services as $svc) {
            ServiceType::create($svc);
        }

        $serviceRequest1 = ServiceRequest::create([
            'user_id' => $citizen1->id,
            'service_type_id' => 1,
            'status' => 'pending',
            'description' => 'Membutuhkan KTP baru karena hilang',
        ]);

        $serviceRequest2 = ServiceRequest::create([
            'user_id' => $citizen2->id,
            'service_type_id' => 3,
            'status' => 'processing',
            'description' => 'Akta kelahiran untuk anak pertama',
        ]);

        Report::create([
            'user_id' => $citizen1->id,
            'category' => 'infrastructure',
            'title' => 'Jalan Berlubang di Depan Sekolah',
            'description' => 'Terdapat lubang besar di Jl. Merdeka depan SDN 1 Samarinda yang membahayakan pengguna jalan.',
            'location' => 'Jl. Merdeka, Samarinda',
            'status' => 'open',
        ]);

        Report::create([
            'user_id' => $citizen2->id,
            'category' => 'environment',
            'title' => 'Tumpukan Sampah di Pasar Induk',
            'description' => 'Sampah menumpuk di area Pasar Induk Balikpapan selama 3 hari tidak diangkut.',
            'location' => 'Pasar Induk, Balikpapan',
            'status' => 'in_progress',
        ]);

        Report::create([
            'user_id' => $citizen1->id,
            'category' => 'social',
            'title' => 'Bantuan untuk Warga Kurang Mampu',
            'description' => 'Mohon bantuan sembako untuk warga RT 05 yang terkena dampak banjir.',
            'location' => 'RT 05, Kelurahan Sungai Kunjang, Samarinda',
            'status' => 'open',
        ]);

        Notification::create([
            'user_id' => $citizen1->id,
            'message' => 'Status layanan Pembuatan KTP Anda: Pending',
            'type' => 'service_status',
            'reference_id' => $serviceRequest1->id,
            'reference_type' => 'service_request',
        ]);

        Notification::create([
            'user_id' => $citizen2->id,
            'message' => 'Status layanan Akta Kelahiran Anda: Sedang Diproses',
            'type' => 'service_status',
            'reference_id' => $serviceRequest2->id,
            'reference_type' => 'service_request',
        ]);
    }
}
