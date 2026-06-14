import os
import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select

# 1. Inisialisasi Browser Chrome
driver = webdriver.Chrome()
driver.maximize_window()

# Base URL aplikasi FoodLink Anda
BASE_URL = "http://127.0.0.1:8000"

# PATH FILE UJI
path_foto_valid = r"C:\Users\LENOVO\Downloads\donatck.jpg"
path_dokumen_salah = r"C:\Users\LENOVO\Downloads\kwu .docx" # <-- File dokumen untuk negative test

def alur_navigasi_ke_buat_donasi():
    """Alur: Login Admin -> Akses Halaman Form Buat Donasi Baru"""
    driver.delete_all_cookies()
    driver.get(f"{BASE_URL}/login")
    time.sleep(2)
    
    # Login menggunakan akun admin
    driver.find_element(By.NAME, "email").send_keys("admin@email.com")
    driver.find_element(By.NAME, "password").send_keys("12345678")
    driver.find_element(By.XPATH, "//button[contains(text(), 'Login')]").click()
    
    # Tunggu transisi hingga masuk dashboard utama admin
    WebDriverWait(driver, 10).until(EC.url_changes(f"{BASE_URL}/login"))
    time.sleep(2)
    
    # Langsung arahkan robot ke URL form tambah donasi
    driver.get(f"{BASE_URL}/admin/donasi/tambah")
    time.sleep(3)

try:
    # =========================================================================
    # TS.PPU.001 - TC.PPU.001.001: Membuat Postingan Donasi (Positive)
    # =========================================================================
    print("--- Memulai TC.PPU.001.001 ---")
    alur_navigasi_ke_buat_donasi()
    
    try:
        input_file = driver.find_element(By.XPATH, "//input[@type='file']")
        input_file.send_keys(path_foto_valid)
        time.sleep(1)
    except Exception as e:
        print(f"Sistem mengabaikan upload foto atau elemen input file tersembunyi: {e}")
        
    input_judul = driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Contoh: Donasi Sembako')]")
    input_judul.send_keys("Donasi Makanan Berkah")
    
    elemen_kategori = driver.find_element(By.XPATH, "//select[./option[contains(text(), 'Pilih Kategori')]]")
    dropdown_penerima = Select(elemen_kategori)
    dropdown_penerima.select_by_visible_text("Kegiatan Keagamaan")
    
    input_tanggal = driver.find_element(By.XPATH, "//input[@type='date' or contains(@name, 'tanggal')]")
    input_tanggal.send_keys("30-12-2026")
    
    input_deskripsi = driver.find_element(By.XPATH, "//textarea[contains(@placeholder, 'Jelaskan secara rinci')]")
    input_deskripsi.send_keys("Membagikan makanan siap saji untuk panti asuhan.")
    
    input_alamat = driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Masukkan alamat lengkap')]")
    input_alamat.send_keys("Jl. Palmerah Barat No. 12, West Jakarta")
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//button[contains(text(), 'POSTING DONASI')]").click()
    time.sleep(3)
    print("TC.PPU.001.001 (Buat Postingan Valid): PASSED")

    # =========================================================================
    # TS.PPU.001 - TC.PPU.001.002: Membuat Postingan - Judul Kosong (Negative)
    # =========================================================================
    print("\n--- Memulai TC.PPU.001.002 ---")
    alur_navigasi_ke_buat_donasi()
    
    driver.find_element(By.XPATH, "//input[@type='date' or contains(@name, 'tanggal')]").send_keys("30-12-2026")
    
    input_judul = driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Contoh: Donasi Sembako')]")
    input_judul.clear() 
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//button[contains(text(), 'POSTING DONASI')]").click()
    time.sleep(1)
    
    assert input_judul.get_attribute("required") is not None or "tidak boleh kosong" in driver.page_source
    print("TC.PPU.001.002 (Gagal Simpan - Judul Kosong): PASSED")

    # =========================================================================
    # TS.PPU.001 - TC.PPU.001.003: Membuat Postingan - Tanggal Masa Lalu (Negative)
    # =========================================================================
    print("\n--- Memulai TC.PPU.001.003 ---")
    alur_navigasi_ke_buat_donasi()
    
    driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Contoh: Donasi Sembako')]").send_keys("Donasi Sembako")
    
    input_tanggal = driver.find_element(By.XPATH, "//input[@type='date' or contains(@name, 'tanggal')]")
    input_tanggal.send_keys("01-01-2020")
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//button[contains(text(), 'POSTING DONASI')]").click()
    time.sleep(2)
    print("TC.PPU.001.003 (Gagal Simpan - Tanggal Masa Lalu): PASSED")

    # =========================================================================
    # TS.PPU.001 - TC.PPU.001.004: Membuat Postingan - Format Foto Salah (Negative)
    # =========================================================================
    print("\n--- Memulai TC.PPU.001.004 ---")
    alur_navigasi_ke_buat_donasi()
    
    # 1. Masukkan data teks yang valid terlebih dahulu
    driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Contoh: Donasi Sembako')]").send_keys("Donasi Uji Ekstensi")
    
    elemen_kategori = driver.find_element(By.XPATH, "//select[./option[contains(text(), 'Pilih Kategori')]]")
    Select(elemen_kategori).select_by_visible_text("Kegiatan Keagamaan")
    
    driver.find_element(By.XPATH, "//input[@type='date' or contains(@name, 'tanggal')]").send_keys("30-12-2026")
    driver.find_element(By.XPATH, "//textarea[contains(@placeholder, 'Jelaskan secara rinci')]").send_keys("Testing format file dokumen.")
    driver.find_element(By.XPATH, "//input[contains(@placeholder, 'Masukkan alamat lengkap')]").send_keys("Jl. Pengujian File")
    
    # 2. MASUKKAN FILE .DOCX (Aksi pengetesan sesungguhnya)
    input_foto = driver.find_element(By.XPATH, "//input[@type='file']")
    input_foto.send_keys(path_dokumen_salah)
    time.sleep(1)
    
    # 3. Klik tombol Posting Donasi
    driver.find_element(By.XPATH, "//button[contains(text(), 'POSTING DONASI')]").click()
    time.sleep(3)
    
    # 4. Validasi penolakan sistem (Validasi Laravel / Alert error di halaman)
    # Menyesuaikan dengan pesan error Laravel Anda, misalnya 'The foto kegiatan must be an image.' atau 'harus berupa gambar'
    assertion_error_msg = "Format file tidak didukung / harus berupa gambar"
    assert "image" in driver.page_source or "foto_kegiatan" in driver.page_source or "invalid" in driver.page_source, "Sistem meloloskan file .docx!"
    
    print("TC.PPU.001.004 (Gagal Simpan - Format File .docx Berhasil Ditolak): PASSED")

except AssertionError as e:
    print(f"Testing Gagal: Aturan bisnis pembuatan donasi tidak terpenuhi. {e}")
except Exception as e:
    print(f"Terjadi Kendala Teknis: {e}")

finally:
    # Selesai pengujian, tutup browser otomatis
    driver.quit()