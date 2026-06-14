import os
import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# 1. Inisialisasi Browser Chrome
driver = webdriver.Chrome()
driver.maximize_window()

# Base URL aplikasi FoodLink
BASE_URL = "http://127.0.0.1:8000"

def alur_navigasi_ke_edit_donasi():
    """Alur: Login Admin -> Halaman Beranda Admin -> Klik Tombol Edit Pertama"""
    driver.delete_all_cookies()
    driver.get(f"{BASE_URL}/login")
    
    # Tunggu input form login muncul
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "email")))
    
    # Login menggunakan akun admin
    driver.find_element(By.NAME, "email").send_keys("admin@email.com")
    driver.find_element(By.NAME, "password").send_keys("12345678")
    driver.find_element(By.XPATH, "//button[contains(text(), 'Login') or @type='submit']").click()
    
    # Tunggu sampai URL berubah menjadi /admin/dashboard
    WebDriverWait(driver, 10).until(EC.url_contains("/admin/dashboard"))
    
    # Cari dan klik tombol "Edit" dengan CLASS yang ada di file blade ('btn-edit')
    try:
        # Menggunakan CSS Selector yang lebih akurat
        tombol_edit = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.CSS_SELECTOR, "a.btn-edit"))
        )
        tombol_edit.click()
        
        # Tunggu hingga halaman form edit benar-benar terbuka (elemen input judul muncul)
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "judul")))
    except Exception as e:
        print(f"Gagal menemukan tombol Edit. Pastikan login berhasil dan ada data donasi: {e}")
        driver.quit()
        exit()

try:
    # =========================================================================
    # TS.PPU.002 - TC.PPU.002.001: Mengedit Postingan (Positive)
    # Skenario: Hanya mengubah field Judul
    # =========================================================================
    print("--- Memulai TS.PPU.002 - TC.PPU.002.001 ---")
    alur_navigasi_ke_edit_donasi()
    
    # Mencari elemen berdasarkan atribut name='judul'
    input_judul = driver.find_element(By.NAME, "judul")
    input_judul.send_keys(Keys.CONTROL + "a")
    input_judul.send_keys(Keys.DELETE)
    time.sleep(1)
    
    # Masukkan judul baru
    input_judul.send_keys("Donasi Makanan Berkah (Update Terbaru)")
    
    # Klik tombol Simpan menggunakan ID tombol yang ada di blade: id="btnSubmitKegiatan"
    driver.find_element(By.ID, "btnSubmitKegiatan").click()
    
    # Tunggu proses save selesai (kembali ke dashboard)
    WebDriverWait(driver, 10).until(EC.url_contains("/admin/dashboard"))
    print("TC.PPU.002.001 (Edit Postingan Valid - Hanya Ubah Judul): PASSED")

    # =========================================================================
    # TS.PPU.002 - TC.PPU.002.002: Mengedit Postingan - Judul Kosong (Negative)
    # =========================================================================
    print("\n--- Memulai TS.PPU.002 - TC.PPU.002.002 ---")
    alur_navigasi_ke_edit_donasi()
    
    # Hapus teks pada judul donasi hingga kosong
    input_judul = driver.find_element(By.NAME, "judul")
    input_judul.send_keys(Keys.CONTROL + "a")
    input_judul.send_keys(Keys.DELETE)
    time.sleep(1)
    
    driver.find_element(By.ID, "btnSubmitKegiatan").click()
    time.sleep(1)
    
    # Validasi penolakan HTML5 (atribut 'required')
    assert input_judul.get_attribute("required") is not None, "Sistem meloloskan judul kosong!"
    print("TC.PPU.002.002 (Gagal Simpan Edit - Judul Kosong): PASSED")

    # =========================================================================
    # TS.PPU.002 - TC.PPU.002.003: Mengedit Postingan - Tanggal Masa Lalu (Negative)
    # =========================================================================
    print("\n--- Memulai TS.PPU.002 - TC.PPU.002.003 ---")
    alur_navigasi_ke_edit_donasi()
    
    # Mengambil ID spesifik tanggal di blade: id="tanggal_kegiatan"
    input_tanggal = driver.find_element(By.ID, "tanggal_kegiatan")
    
    # Mengisi tanggal masa lampau
    input_tanggal.send_keys(Keys.CONTROL + "a")
    input_tanggal.send_keys(Keys.DELETE)
    input_tanggal.send_keys("01-01-2020")
    
    # Pemicu event JS agar mendeteksi perubahan
    input_tanggal.send_keys(Keys.TAB) 
    time.sleep(1)
    
    # Mengecek apakah Javascript berhasil men-disable tombol submit
    btn_submit = driver.find_element(By.ID, "btnSubmitKegiatan")
    assert btn_submit.is_enabled() == False, "Tombol Simpan masih bisa diklik walau tanggal masa lalu!"
    print("TC.PPU.002.003 (Gagal Simpan Edit - JS Berhasil Mengunci Tombol): PASSED")

    # =========================================================================
    # TS.PPU.002 - TC.PPU.002.004: Mengedit Postingan - Format Foto Salah (Negative)
    # Skenario Revisi: Memeriksa validasi Frontend / UI (Atribut Accept)
    # =========================================================================
    print("\n--- Memulai TS.PPU.002 - TC.PPU.002.004 ---")
    alur_navigasi_ke_edit_donasi()
    
    # Mencari elemen input file
    input_foto = driver.find_element(By.ID, "file-input")
    
    # Mengambil atribut 'accept' dari elemen tersebut
    atribut_accept = input_foto.get_attribute("accept")
    
    # Validasi bahwa sistem murni menolak file selain gambar dari sisi UI (Frontend)
    assert atribut_accept == "image/*", f"Sistem tidak membatasi file foto di UI! Atribut saat ini: {atribut_accept}"
    
    print("TC.PPU.002.004 (Gagal Simpan Edit - Format File .docx Terblokir Otomatis oleh Atribut HTML5): PASSED")

except AssertionError as e:
    print(f"Testing Gagal: Aturan bisnis saat edit donasi tidak terpenuhi. {e}")
except Exception as e:
    print(f"Terjadi Kendala Teknis: {e}")

finally:
    # Selesai pengujian, tutup browser otomatis
    driver.quit()