<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation; 
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\KegiatanDonasi;
use App\Models\DonasiMakanan;

class AlurDonasiBrowserTest extends DuskTestCase
{
    // Gunakan DatabaseMigrations agar database di-reset (fresh) setiap kali test berjalan
    use DatabaseTruncation; 

    public function test_alur_pendaftaran_donasi_dari_beranda_hingga_sukses()
    {
        // 1. Setup Data
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create([
            'judul_donasi' => 'Donasi ke Panti Jompo', 
        ]);

        $this->browse(function (Browser $browser) use ($user, $kegiatan) {
            $browser->loginAs($user) // Login otomatis
                    ->visit('/dashboard') // 2. Buka halaman beranda
                    ->assertSee($kegiatan->judul_donasi) // Cek ada judul kegiatan
                    ->visit('/donasi/detail/' . $kegiatan->id) // 3. Masuk ke halaman form
                    ->assertSee('Daftar Donasi')
                    // 4. Mulai isi form sesuai atribut name="nama_kolom" di Blade
                    ->type('nama_donatur', 'Febrian')
                    ->type('no_telp', '081234567890')
                    ->type('email', 'febrian@example.com')
                    ->select('kategori_penerima', 'Panti Asuhan') // Untuk elemen <select>
                    ->select('kategori_wilayah', 'Jakarta Selatan')
                    ->select('lokasi_dropbox', 'Dropbox Sudirman')
                    ->select('kategori_makanan', 'Makanan Siap Saji')
                    ->type('waktu_layak', '12 Jam')
                    ->type('nama_makanan', 'Nasi Kotak')
                    // Upload file fisik yang ada di direktori test (pastikan ada file dummy.jpg)
                    ->attach('foto_makanan', __DIR__ . '/dummy.jpg') 
                    ->press('Simpan Donasi') // Klik tombol submit (sesuaikan teks tombolnya)
                    // 5. Pastikan diarahkan ke dashboard dan melihat indikasi sukses
                    ->assertPathIs('/dashboard');
                    // Tambahkan ->assertSee('berhasil') jika ada alert sukses di UI
        });
    }

    public function test_pendaftaran_donasi_gagal_jika_form_kosong()
    {
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create([
            'judul_donasi' => 'Donasi ke Panti Jompo', 
        ]);

        $this->browse(function (Browser $browser) use ($user, $kegiatan) {
            $browser->loginAs($user)
                    ->visit('/donasi/detail/' . $kegiatan->id)
                    // Langsung klik simpan tanpa isi apa-apa
                    ->press('Simpan Donasi')
                    // Memastikan tetap di halaman form dan melihat pesan error dari validasi Laravel
                    ->assertPathIs('/donasi/detail/' . $kegiatan->id)
                    ->assertSee('required') // Memastikan muncul kata 'required' atau sesuaikan dengan pesan error bahasa Indonesia Anda
                    ->assertSee('nama donatur')
                    ->assertSee('foto makanan');
        });
    }

    public function test_user_bisa_mengedit_data_donasi_di_riwayat()
    {
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create();

        // Buat data donasi awal
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

        $this->browse(function (Browser $browser) use ($user, $donasi) {
            $browser->loginAs($user)
                    ->visit('/donasi/' . $donasi->id . '/edit') // Buka form edit
                    // Timpa data lama
                    ->clear('waktu_layak')
                    ->type('waktu_layak', '12 Jam')
                    ->clear('deskripsi')
                    ->type('deskripsi', 'Isi deskripsi baru ditambahkan')
                    ->press('Update') // Sesuaikan teks tombol update Anda (misal: 'Simpan', 'Update', dll)
                    ->assertPathIs('/riwayat-donasi');
        });
    }

    public function test_edit_data_donasi_gagal_jika_kolom_wajib_kosong()
    {
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create();
        
        $donasi = DonasiMakanan::create([
            'user_id' => $user->id, 'kegiatan_donasi_id' => $kegiatan->id,
            'nama_donatur' => 'Febrian', 'no_telp' => '0812', 'email' => 'a@b.com',
            'kategori_penerima' => 'A', 'kategori_wilayah' => 'B', 'lokasi_dropbox' => 'C',
            'kategori_makanan' => 'Sayuran', 'waktu_layak' => '2 Hari', 'status' => 'Pending',
            'foto_makanan' => 'default.jpg'
        ]);

        $this->browse(function (Browser $browser) use ($user, $donasi) {
            $browser->loginAs($user)
                    ->visit('/donasi/' . $donasi->id . '/edit')
                    ->clear('waktu_layak') // Kosongkan field wajib
                    ->press('Update') // Sesuaikan teks tombol
                    ->assertPathIs('/donasi/' . $donasi->id . '/edit')
                    ->assertSee('required'); // Cek error muncul
        });
    }

    public function test_user_bisa_membatalkan_atau_menghapus_donasi()
    {
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create();

        $donasi = DonasiMakanan::create([
            'user_id' => $user->id, 'kegiatan_donasi_id' => $kegiatan->id,
            'nama_donatur' => 'Febrian', 'no_telp' => '0812', 'email' => 'a@b.com',
            'kategori_penerima' => 'A', 'kategori_wilayah' => 'B', 'lokasi_dropbox' => 'C',
            'kategori_makanan' => 'Sayuran', 'waktu_layak' => '2 Hari', 
            'status' => 'Pending', // Syarat bisa dihapus
            'foto_makanan' => 'default.jpg'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/riwayat-donasi')
                    ->press('Batal') // Sesuaikan teks tombol di blade (misal: 'Hapus', 'Batal')
                    // HILANGKAN KOMENTAR DI BAWAH INI jika tombol hapus Anda memunculkan pop-up konfirmasi (alert)
                    // ->acceptDialog() 
                    ->assertPathIs('/riwayat-donasi')
                    ->assertSee('Donasi berhasil dibatalkan'); // Sesuaikan dengan pesan session success Anda
        });
    }

    public function test_user_tidak_bisa_membatalkan_donasi_yang_sudah_diproses()
    {
        $user = User::factory()->create();
        $kegiatan = KegiatanDonasi::factory()->create();

        $donasi = DonasiMakanan::create([
            'user_id' => $user->id, 'kegiatan_donasi_id' => $kegiatan->id,
            'nama_donatur' => 'Febrian', 'no_telp' => '0812', 'email' => 'a@b.com',
            'kategori_penerima' => 'A', 'kategori_wilayah' => 'B', 'lokasi_dropbox' => 'C',
            'kategori_makanan' => 'Sayuran', 'waktu_layak' => '2 Hari', 
            'status' => 'Selesai', // Sudah diproses
            'foto_makanan' => 'default.jpg'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            // Dalam UI Testing, jika status 'Selesai', tombol 'Batal' biasanya disembunyikan
            $browser->loginAs($user)
                    ->visit('/riwayat-donasi')
                    ->assertDontSee('Batal'); // Pastikan tombol Batal/Hapus tidak dirender
        });
    }
}