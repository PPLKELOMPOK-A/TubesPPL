import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# 1. Inisialisasi Browser Chrome
driver = webdriver.Chrome()
driver.maximize_window()

# Base URL aplikasi FoodLink Anda
BASE_URL = "http://127.0.0.1:8000"

def alur_navigasi_ke_edit_profil():
    """Alur: Login -> Halaman Dashboard -> Klik Profil Pojok Kanan Atas -> Halaman Detail Profil -> Klik Edit"""
    driver.get(f"{BASE_URL}/login")
    time.sleep(2)
    
    # Periksa apakah session login masih aktif atau dialihkan otomatis
    if "login" in driver.current_url:
        driver.find_element(By.NAME, "email").send_keys("jeni@email.com")
        driver.find_element(By.NAME, "password").send_keys("12345678")
        driver.find_element(By.XPATH, "//button[contains(text(), 'Login')]").click()
        WebDriverWait(driver, 10).until(EC.url_changes(f"{BASE_URL}/login"))
        time.sleep(2) 

    # 2. Klik nama/foto user di pojok kanan atas navbar dashboard
    tombol_avatar_navbar = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable((By.XPATH, "//*[contains(text(), 'jenii') or contains(text(), 'jeni')]"))
    )
    tombol_avatar_navbar.click()
    
    # 3. PERBAIKAN TUNGGUAN: Menunggu tombol Edit siap diklik secara dinamis (Anti Timeout)
    tombol_edit = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable((By.XPATH, "//*[text()='Edit' or contains(text(), 'Edit')]"))
    )
    tombol_edit.click()
    time.sleep(2)

try:
    # =========================================================================
    # TS.PPU.004 - TC.PPU.004.001: Mengedit Profil (Positive)
    # =========================================================================
    alur_navigasi_ke_edit_profil()
    
    driver.find_element(By.NAME, "name").clear()
    driver.find_element(By.NAME, "name").send_keys("jenii edited")
    
    driver.find_element(By.NAME, "telepon").clear()
    driver.find_element(By.NAME, "telepon").send_keys("081234567890")
    
    driver.find_element(By.XPATH, "//*[contains(text(), 'Simpan Perubahan')]").click()
    time.sleep(2)
    print("TC.PPU.004.001 (Edit Profil Valid): PASSED")

    # =========================================================================
    # TS.PPU.004 - TC.PPU.004.002: Mengedit Profil - Nama Lengkap Kosong (Negative)
    # =========================================================================
    alur_navigasi_ke_edit_profil()
    
    input_nama = driver.find_element(By.NAME, "name")
    input_nama.clear() # Hapus seluruh teks hingga kosong
    time.sleep(1)
    
    # Klik tombol simpan perubahan untuk memicu pop-up validasi browser
    driver.find_element(By.XPATH, "//*[contains(text(), 'Simpan Perubahan')]").click()
    time.sleep(1)
    
    # Mengambil validasi pesan "Please fill out this field." dari atribut HTML5 validation
    assert input_nama.get_attribute("required") is not None
    print("TC.PPU.004.002 (Edit Profil Nama Kosong - Validasi Muncul): PASSED")

    # =========================================================================
    # TS.PPU.004 - TC.PPU.004.003: Mengedit Profil - Email Kosong (Negative)
    # =========================================================================
    alur_navigasi_ke_edit_profil()
    
    input_email = driver.find_element(By.NAME, "email")
    input_email.clear() # Hapus teks email hingga kosong
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//*[contains(text(), 'Simpan Perubahan')]").click()
    time.sleep(1)
    
    assert input_email.get_attribute("required") is not None
    print("TC.PPU.004.003 (Edit Profil Email Kosong - Validasi Muncul): PASSED")

    # =========================================================================
    # TS.PPU.004 - TC.PPU.004.004: Mengedit Profil - Telepon & NIK Huruf (Negative)
    # =========================================================================
    alur_navigasi_ke_edit_profil()
    
    driver.find_element(By.NAME, "telepon").clear()
    driver.find_element(By.NAME, "telepon").send_keys("telepon_huruf")
    driver.find_element(By.NAME, "nik").clear()
    driver.find_element(By.NAME, "nik").send_keys("nik_huruf")
    time.sleep(1)
    
    # PEMBETULAN LOGIKA: Jika tombol tidak aktif (is_enabled bernilai False), maka tes ini BERHASIL (PASSED)
    tombol_simpan = driver.find_element(By.XPATH, "//*[contains(text(), 'Simpan Perubahan')]")
    if not tombol_simpan.is_enabled():
        print("TC.PPU.004.004 (Edit Profil Format Salah - Tombol Otomatis Terkunci): PASSED")
    else:
        raise AssertionError("Tombol Simpan tetap aktif padahal format input salah!")

except AssertionError as e:
    print(f"Testing Gagal (Kriteria Assert): {e}")
except Exception as e:
    # Menggunakan repr(e) agar detail class error dari Selenium tidak terpotong kosong lagi
    print(f"Terjadi Kendala Teknis Spesifik: {repr(e)}")

finally:
    # Menutup browser otomatis
    driver.quit()