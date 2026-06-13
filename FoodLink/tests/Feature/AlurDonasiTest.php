<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile; // Tambahan untuk memalsukan file gambar
use Illuminate\Support\Facades\Storage; // Tambahan untuk mencegah gambar tersimpan beneran
use Tests\TestCase;
use App\Models\User;
use App\Models\KegiatanDonasi;
use App\Models\DonasiMakanan;

class AlurDonasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_alur_pendaftaran_donasi_dari_beranda_hingga_sukses()
    {
        // Setup Storage palsu agar gambar test tidak numpuk di folder komputer Anda
        Storage::fake('public');

        // 1. Setup User dan Data Kegiatan Donasi di Database
        $user = User::factory()->create();
        
        $kegiatan = KegiatanDonasi::factory()->create([
            'judul_donasi' => 'Donasi ke Panti Jompo', 
        ]);

        // 2. User masuk ke halaman beranda dan melihat kegiatan tersebut
        $responseBeranda = $this->actingAs($user)->get('/dashboard');
        $responseBeranda->assertStatus(200);
        $responseBeranda->assertSee($kegiatan->judul_donasi); 

        // 3. User klik "Daftar Donasi" dan masuk ke halaman detail kegiatan
        $responseDetail = $this->get('/donasi/detail/' . $kegiatan->id);
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee($kegiatan->judul_donasi); 
        $responseDetail->assertSee('Daftar Donasi'); 

        // 4. User mengisi form dan klik "Simpan Donasi"
        // ---> SEMUA DATA WAJIB SESUAI ERROR TERMINAL TELAH DILENGKAPI <---
        $formData = [
            'kegiatan_donasi_id' => $kegiatan->id,
            'nama_donatur'       => 'Febrian',
            'no_telp'            => '081234567890',
            'email'              => 'febrian@example.com',
            'kategori_penerima'  => 'Panti Asuhan',
            'kategori_wilayah'   => 'Jakarta Selatan',
            'lokasi_dropbox'     => 'Dropbox Sudirman',
            'kategori_makanan'   => 'Makanan Siap Saji',
            'waktu_layak'        => '12 Jam',
            'nama_makanan'       => 'Nasi Kotak',
            // Membuat file gambar bohongan khusus untuk test
            'foto_makanan'       => UploadedFile::fake()->image('makanan.jpg'),
        ];

        $responseSubmit = $this->post('/donasi/simpan', $formData);

        // 5. Diarahkan (redirect) kembali ke dashboard dengan notifikasi sukses
        // Jika kode controller Anda mengarah ke halaman lain seperti /riwayat-donasi,
        // silakan ubah '/dashboard' di bawah ini menjadi '/riwayat-donasi'
        $responseSubmit->assertRedirect('/dashboard');
        $responseSubmit->assertSessionHas('success'); 

        // 6. Memastikan data benar-benar tersimpan di tabel database donasi_makanans
        $this->assertDatabaseHas('donasi_makanans', [
            'user_id'          => $user->id,
            'nama_donatur'     => 'Febrian',
            'kategori_makanan' => 'Makanan Siap Saji',
            'lokasi_dropbox'   => 'Dropbox Sudirman',
        ]);
    }

    public function test_pendaftaran_donasi_gagal_jika_form_kosong()
    {
        // 1. Setup User dan Data Kegiatan Donasi
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create([
            'judul_donasi' => 'Donasi ke Panti Jompo', 
        ]);

        // 2. User melakukan POST request dengan data kosong (tidak mengisi form apapun)
        $formDataKosong = []; 

        $response = $this->actingAs($user)->post('/donasi/simpan', $formDataKosong);

        // 3. Memastikan sistem menolak dan melempar (redirect) user kembali ke form (Status 302)
        $response->assertStatus(302);

        // 4. Memastikan muncul pesan error validasi pada kolom-kolom yang diwajibkan
        // Sesuai dengan pesan error merah di gambar dan aturan di DonasiMakananController
        $response->assertInvalid([
            'nama_donatur',
            'no_telp',
            'email',
            'kategori_penerima',
            'kategori_wilayah',
            'lokasi_dropbox',
            'kategori_makanan',
            'waktu_layak',
            'foto_makanan',
        ]);
    }
    public function test_user_bisa_mengedit_data_donasi_di_riwayat()
    {
        // 1. Setup User dan Data Kegiatan Donasi
        $user = User::factory()->create();
        
        // Sesuaikan nama field dengan migration
        $kegiatan = KegiatanDonasi::factory()->create([
            'judul_donasi' => 'Donasi ke Panti Jompo', 
        ]);

        // 2. Buat data donasi awal secara manual dengan MENGISI foto_makanan
        $donasi = DonasiMakanan::create([
            'user_id'            => $user->id,
            'kegiatan_donasi_id' => $kegiatan->id,
            'nama_donatur'       => 'Febrian',
            'no_telp'            => '081234567890',
            'email'              => 'febrian@example.com',
            'kategori_penerima'  => 'Panti Asuhan',
            'kategori_wilayah'   => 'Jakarta Selatan',
            'lokasi_dropbox'     => 'Dropbox Sudirman',
            'kategori_makanan'   => 'Sayuran Mentah',
            'waktu_layak'        => '2 Hari',
            'status'             => 'Pending',
            'foto_makanan'       => 'donasi_foto/default.jpg' // <-- TAMBAHKAN INI
        ]);

        // 3. User mengakses halaman edit dari riwayat donasi
        $responseEdit = $this->actingAs($user)->get('/donasi/' . $donasi->id . '/edit');
        $responseEdit->assertStatus(200);

        // 4. Data baru untuk diupdate 
        $updateData = [
            'kategori_makanan' => 'Makanan Siap Saji',
            'waktu_layak'      => '12 Jam',
            'deskripsi'        => 'Isi deskripsi baru ditambahkan',
        ];

        // 5. User submit form update (menggunakan metode PUT)
        $responseUpdate = $this->put('/donasi/update/' . $donasi->id, $updateData);

        // 6. Memastikan redirect ke riwayat donasi dan ada notifikasi sukses
        $responseUpdate->assertRedirect('/riwayat-donasi');
        $responseUpdate->assertSessionHas('success');
        
        // 7. Memastikan data di database benar-benar berubah
        $this->assertDatabaseHas('donasi_makanans', [
            'id'               => $donasi->id,
            'kategori_makanan' => 'Makanan Siap Saji',
            'waktu_layak'      => '12 Jam',
            'deskripsi'        => 'Isi deskripsi baru ditambahkan',
        ]);
    }
    public function test_edit_data_donasi_gagal_jika_kolom_wajib_kosong()
    {
        // 1. Setup User dan Data Kegiatan
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create([
            'judul_donasi' => 'Donasi ke Panti Jompo', 
        ]);

        // 2. Buat data donasi awal
        $donasi = DonasiMakanan::create([
            'user_id'            => $user->id,
            'kegiatan_donasi_id' => $kegiatan->id,
            'nama_donatur'       => 'Febrian',
            'no_telp'            => '081234567890',
            'email'              => 'febrian@example.com',
            'kategori_penerima'  => 'Panti Asuhan',
            'kategori_wilayah'   => 'Jakarta Selatan',
            'lokasi_dropbox'     => 'Dropbox Sudirman',
            'kategori_makanan'   => 'Sayuran Mentah',
            'waktu_layak'        => '2 Hari',
            'status'             => 'Pending',
            'foto_makanan'       => 'donasi_foto/default.jpg'
        ]);

        // 3. User mengirimkan form edit dengan data kosong pada field yang diwajibkan
        $updateDataKosong = [
            'kategori_makanan' => '', // Dikosongkan
            'waktu_layak'      => '', // Dikosongkan
        ];

        $responseUpdate = $this->actingAs($user)->put('/donasi/update/' . $donasi->id, $updateDataKosong);

        // 4. Memastikan sistem menolak (redirect kembali ke halaman form edit dengan status 302)
        $responseUpdate->assertStatus(302);

        // 5. Memastikan muncul pesan error validasi pada kolom kategori_makanan dan waktu_layak
        $responseUpdate->assertInvalid([
            'kategori_makanan',
            'waktu_layak',
        ]);
    }
    public function test_user_bisa_membatalkan_atau_menghapus_donasi()
    {
        // 1. Setup User dan Data Kegiatan
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create([
            'judul_donasi' => 'Donasi ke Panti Jompo', 
        ]);

        // 2. Buat data donasi awal yang akan dihapus
        $donasi = DonasiMakanan::create([
            'user_id'            => $user->id,
            'kegiatan_donasi_id' => $kegiatan->id,
            'nama_donatur'       => 'Febrian',
            'no_telp'            => '081234567890',
            'email'              => 'febrian@example.com',
            'kategori_penerima'  => 'Panti Asuhan',
            'kategori_wilayah'   => 'Jakarta Selatan',
            'lokasi_dropbox'     => 'Dropbox Sudirman',
            'kategori_makanan'   => 'Makanan Siap Saji',
            'waktu_layak'        => '12 Jam',
            'status'             => 'Pending', // Syarat agar diizinkan dihapus
            'foto_makanan'       => 'donasi_foto/default.jpg'
        ]);

        // 3. User menekan tombol Hapus/Batal (Simulasi metode DELETE)
        $responseDelete = $this->actingAs($user)->delete('/donasi/batal/' . $donasi->id);

        // 4. Memastikan diarahkan kembali ke riwayat donasi dan mendapat pesan sukses
        $responseDelete->assertRedirect('/riwayat-donasi');
        $responseDelete->assertSessionHas('success', 'Donasi berhasil dibatalkan.');

        // 5. Memastikan data tersebut sudah BENAR-BENAR HILANG dari database
        $this->assertDatabaseMissing('donasi_makanans', [
            'id' => $donasi->id,
        ]);
    }
    public function test_user_tidak_bisa_membatalkan_donasi_yang_sudah_diproses()
    {
        // 1. Setup User dan Data Kegiatan
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create([
            'judul_donasi' => 'Donasi ke Panti Jompo', 
        ]);

        // 2. Buat data donasi awal dengan status SUDAH DIPROSES (misal: "Selesai" atau "Disetujui")
        $donasi = DonasiMakanan::create([
            'user_id'            => $user->id,
            'kegiatan_donasi_id' => $kegiatan->id,
            'nama_donatur'       => 'Febrian',
            'no_telp'            => '081234567890',
            'email'              => 'febrian@example.com',
            'kategori_penerima'  => 'Panti Asuhan',
            'kategori_wilayah'   => 'Jakarta Selatan',
            'lokasi_dropbox'     => 'Dropbox Sudirman',
            'kategori_makanan'   => 'Makanan Siap Saji',
            'waktu_layak'        => '12 Jam',
            // STATUS INI YANG MEMBUATNYA SEHARUSNYA DITOLAK UNTUK DIHAPUS
            'status'             => 'Selesai', 
            'foto_makanan'       => 'donasi_foto/default.jpg'
        ]);

        // 3. User (sengaja/tidak sengaja) mengirimkan request DELETE ke donasi tersebut
        $responseDelete = $this->actingAs($user)->delete('/donasi/batal/' . $donasi->id);

        // 4. Memastikan sistem MENOLAK penghapusan dan me-redirect dengan pesan ERROR
        $responseDelete->assertRedirect('/riwayat-donasi');
        
        // Sesuai dengan pesan di controller Anda jika gagal:
        $responseDelete->assertSessionHas('error', 'Donasi sudah diproses dan tidak dapat dibatalkan.');

        // 5. Memastikan data tersebut MASIH ADA (Tidak Terhapus) di database
        $this->assertDatabaseHas('donasi_makanans', [
            'id' => $donasi->id,
            'status' => 'Selesai',
        ]);
    }
}