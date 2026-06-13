<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Laporan; // Pastikan model ini sudah ada
use Carbon\Carbon;

class LaporanDonasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_melihat_dashboard_laporan_default()
    {
        // 1. Setup Admin dan Data Laporan Dummy
        // Pastikan di tabel users ada role 'admin'
        $admin = User::factory()->create(['role' => 'admin']); 
        
        // Buat 3 data laporan sukses dan 1 laporan retur
        Laporan::factory()->count(3)->create(['status' => 'selesai']);
        Laporan::factory()->create(['status' => 'diretur']);

        // 2. Admin mengakses halaman laporan (sesuai prefix route di web.php)
        $response = $this->actingAs($admin)->get('/admin/report');

        // 3. Pastikan halaman sukses dimuat dan mengirimkan data (variabel) ke view
        $response->assertStatus(200);
        $response->assertViewIs('admin.laporan');
        
        // Memastikan variabel yang dihitung oleh ReportController benar-benar dikirim ke View
        $response->assertViewHasAll([
            'totalBerhasil', 
            'penerimaManfaat', 
            'persentaseRetur', 
            'penggunaAktif',
            'chartLabels',
            'chartData',
            'segmentasi',
            'logPenyaluran'
        ]);
        
        // Memastikan perhitungannya benar (3 selesai, maka totalBerhasil harus 3)
        $this->assertEquals(3, $response->viewData('totalBerhasil'));
    }

    public function test_admin_bisa_memfilter_laporan_berdasarkan_rentang_waktu()
    {
        $admin = User::factory()->create(['role' => 'admin']); 

        // 1. Buat data laporan di masa lalu (Bulan Lalu)
        Laporan::factory()->create([
            'status' => 'selesai',
            'created_at' => Carbon::now()->subMonth()
        ]);

        // 2. Buat data laporan HARI INI
        Laporan::factory()->create([
            'status' => 'selesai',
            'created_at' => Carbon::now()
        ]);

        // 3. Admin memfilter laporan HANYA untuk hari ini
        $hariIni = Carbon::now()->toDateString();
        $response = $this->actingAs($admin)->get("/admin/report?start_date={$hariIni}&end_date={$hariIni}");

        $response->assertStatus(200);

        // 4. Pastikan hasil filter hanya menghitung data hari ini (berarti totalnya 1, yang bulan lalu diabaikan)
        $this->assertEquals(1, $response->viewData('totalBerhasil'));
    }

    public function test_admin_bisa_mendownload_laporan_donasi()
    {
        $admin = User::factory()->create(['role' => 'admin']); 
        
        // Buat data laporan agar file tidak kosong
        Laporan::factory()->create(['status' => 'selesai']);

        // Simulasi request download (asumsi route-nya adalah /admin/report/download)
        // Sesuaikan URL dengan route di web.php Anda
        $response = $this->actingAs($admin)->get('/admin/report/download');

        // Pastikan status 200 dan tipe konten adalah unduhan
        $response->assertStatus(200);
        
        // Memastikan sistem mengirimkan header unduhan (biasanya application/pdf atau excel)
        $this->assertTrue(
            $response->headers->get('Content-Type') === 'application/pdf' || 
            $response->headers->get('Content-Type') === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
    }
}