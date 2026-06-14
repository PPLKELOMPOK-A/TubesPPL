import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# 1. Inisialisasi Browser Chrome
driver = webdriver.Chrome()
driver.maximize_window()

# Base URL aplikasi FoodLink
BASE_URL = "http://127.0.0.1:8000"

def alur_navigasi_ke_dashboard_admin():
    """Alur: Login Admin -> Berada di Halaman Beranda Admin"""
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
    time.sleep(2) # Jeda sejenak agar tabel donasi termuat sepenuhnya

try:
    # =========================================================================
    # TS.PPU.003 - TC.PPU.003.002: Membatalkan Penghapusan Postingan (Negative)
    # *Dijalankan lebih dulu agar tidak kehabisan data untuk dites*
    # =========================================================================
    print("--- Memulai TS.PPU.003 - TC.PPU.003.002 (Negative Test) ---")
    alur_navigasi_ke_dashboard_admin()
    
    # Menghitung jumlah tombol hapus saat ini untuk validasi nanti
    tombol_hapus_awal = driver.find_elements(By.CSS_SELECTOR, "button.btn-delete")
    jumlah_data_sebelum = len(tombol_hapus_awal)
    
    if jumlah_data_sebelum == 0:
        print("Data donasi kosong. Tidak ada data yang bisa dites hapus.")
    else:
        # Klik tombol Hapus pada data pertama
        tombol_hapus_awal[0].click()
        
        # Tunggu pop-up konfirmasi JS muncul
        alert = WebDriverWait(driver, 5).until(EC.alert_is_present())
        time.sleep(1) # Jeda visual sesaat
        
        # Klik "Cancel" atau "Batal"
        alert.dismiss()
        time.sleep(2)
        
        # Validasi: Hitung ulang jumlah tombol hapus, pastikan tidak berkurang (data tidak terhapus)
        tombol_hapus_akhir = driver.find_elements(By.CSS_SELECTOR, "button.btn-delete")
        assert len(tombol_hapus_akhir) == jumlah_data_sebelum, "Error: Data tetap terhapus padahal sudah klik Batal!"
        print("TC.PPU.003.002 (Membatalkan Penghapusan Postingan): PASSED")

    # =========================================================================
    # TS.PPU.003 - TC.PPU.003.001: Menghapus Postingan (Positive)
    # =========================================================================
    print("\n--- Memulai TS.PPU.003 - TC.PPU.003.001 (Positive Test) ---")
    alur_navigasi_ke_dashboard_admin()
    
    tombol_hapus_awal = driver.find_elements(By.CSS_SELECTOR, "button.btn-delete")
    jumlah_data_sebelum = len(tombol_hapus_awal)
    
    if jumlah_data_sebelum == 0:
        print("Data donasi kosong. Tidak ada data yang bisa dihapus.")
    else:
        # Klik tombol Hapus pada data pertama
        tombol_hapus_awal[0].click()
        
        # Tunggu pop-up konfirmasi JS muncul
        alert = WebDriverWait(driver, 5).until(EC.alert_is_present())
        time.sleep(1) # Jeda visual sesaat
        
        # Klik "OK" atau "Hapus"
        alert.accept()
        
        # Tunggu notifikasi sukses muncul. Anda menggunakan elemen dengan id="success-alert" di file blade
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "success-alert")))
        time.sleep(1)
        
        # Validasi opsional: Pastikan jumlah data berkurang 1
        tombol_hapus_akhir = driver.find_elements(By.CSS_SELECTOR, "button.btn-delete")
        assert len(tombol_hapus_akhir) == (jumlah_data_sebelum - 1), "Error: Data tidak hilang dari daftar setelah dihapus!"
        print("TC.PPU.003.001 (Berhasil Menghapus Postingan): PASSED")

except AssertionError as e:
    print(f"Testing Gagal: Validasi skenario penghapusan tidak terpenuhi. {e}")
except Exception as e:
    print(f"Terjadi Kendala Teknis: {e}")

finally:
    # Tutup browser
    driver.quit()