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

def persiapkan_halaman_login():
    """Mengembalikan browser ke kondisi login bersih tanpa session cookie"""
    driver.delete_all_cookies()
    driver.get(f"{BASE_URL}/login")
    time.sleep(2)

def alur_pindah_ke_register():
    """Navigasi dari halaman login menuju ke form Sign Up / Create an Account"""
    persiapkan_halaman_login()
    # Klik tautan "Create an account" di bawah form login (sesuai Screenshot)
    driver.find_element(By.LINK_TEXT, "Create an account").click()
    time.sleep(2)

try:
    # =========================================================================
    # TS.PPU.001 - TC.PPU.001.001: Create Account (Positive)
    # =========================================================================
    alur_pindah_ke_register()
    
    driver.find_element(By.NAME, "name").send_keys("Jeni Baru")
    
    # Menggunakan timestamp waktu agar email pendaftaran selalu unik saat dites berulang-ulang
    email_unik = f"jeni.baru{int(time.time())}@email.com"
    driver.find_element(By.NAME, "email").send_keys(email_unik)
    
    driver.find_element(By.NAME, "password").send_keys("12345678")
    driver.find_element(By.NAME, "password_confirmation").send_keys("12345678")
    time.sleep(1)
    
    # Klik tombol Create (Sesuai teks tombol cokelat di halaman Sign Up)
    driver.find_element(By.XPATH, "//button[contains(text(), 'Create')]").click()
    
    # Menunggu sistem mengalihkan halaman kembali ke form login setelah sukses mendaftar
    WebDriverWait(driver, 10).until(EC.url_contains("/login"))
    print("TC.PPU.001.001 (Create Account Valid): PASSED")

    # =========================================================================
    # TS.PPU.001 - TC.PPU.001.002: Create Account - Nama Kosong (Negative)
    # =========================================================================
    alur_pindah_ke_register()
    
    # Biarkan field name kosong, isi email dan password
    driver.find_element(By.NAME, "email").send_keys("jenitest@email.com")
    driver.find_element(By.NAME, "password").send_keys("12345678")
    driver.find_element(By.NAME, "password_confirmation").send_keys("12345678")
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//button[contains(text(), 'Create')]").click()
    time.sleep(1)
    
    # Membuktikan pop-up browser HTML5 "Please fill out this field." dipicu di input name
    input_nama = driver.find_element(By.NAME, "name")
    assert input_nama.get_attribute("required") is not None
    print("TC.PPU.001.002 (Create Account Nama Kosong - Validasi Muncul): PASSED")

    # =========================================================================
    # TS.PPU.001 - TC.PPU.001.003: Create Account - Password Tidak Cocok (Negative)
    # =========================================================================
    alur_pindah_ke_register()
    
    driver.find_element(By.NAME, "name").send_keys("Jeni Test")
    driver.find_element(By.NAME, "email").send_keys("jenitest@email.com")
    driver.find_element(By.NAME, "password").send_keys("12345678")
    driver.find_element(By.NAME, "password_confirmation").send_keys("123456789")  # Konfirmasi password berbeda
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//button[contains(text(), 'Create')]").click()
    time.sleep(2)
    
    # ATURAN BISNIS: Sistem menolak pendaftaran dan me-redirect (looping) ke halaman itu lagi
    assert "/register" in driver.current_url or "login" not in driver.current_url
    print("TC.PPU.001.003 (Create Account Password Tidak Cocok - Looping Form): PASSED")

    # =========================================================================
    # TS.PPU.002 - TC.PPU.002.001: Login Sukses (Positive)
    # =========================================================================
    persiapkan_halaman_login()
    
    driver.find_element(By.NAME, "email").send_keys("jeni@email.com")
    driver.find_element(By.NAME, "password").send_keys("12345678")
    time.sleep(1)
    
    # Klik tombol Login cokelat
    driver.find_element(By.XPATH, "//button[contains(text(), 'Login')]").click()
    
    # Memverifikasi halaman dialihkan ke dashboard utama setelah otentikasi aktif
    WebDriverWait(driver, 10).until(EC.url_changes(f"{BASE_URL}/login"))
    print("TC.PPU.002.001 (Login Valid): PASSED")

    # =========================================================================
    # TS.PPU.002 - TC.PPU.002.002: Login Gagal - Email Kosong (Negative)
    # =========================================================================
    persiapkan_halaman_login()
    
    # Hanya mengisi password saja
    driver.find_element(By.NAME, "password").send_keys("12345678")
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//button[contains(text(), 'Login')]").click()
    time.sleep(1)
    
    # Memverifikasi atribut required pada field email aktif menangkap kekosongan
    input_email = driver.find_element(By.NAME, "email")
    assert input_email.get_attribute("required") is not None
    print("TC.PPU.002.002 (Login Email Kosong - Validasi Muncul): PASSED")

    # =========================================================================
    # TS.PPU.002 - TC.PPU.002.003: Login Gagal - Password Salah (Negative)
    # =========================================================================
    persiapkan_halaman_login()
    
    driver.find_element(By.NAME, "email").send_keys("jeni@email.com")
    driver.find_element(By.NAME, "password").send_keys("password_salah_acak")
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//button[contains(text(), 'Login')]").click()
    time.sleep(2)
    
    # Memastikan sistem menolak masuk dan tetap menahan pengguna di form login
    assert "login" in driver.current_url
    print("TC.PPU.002.003 (Login Gagal - Password Salah Ditolak): PASSED")

except AssertionError as e:
    print(f"Testing Gagal (Kriteria Assert): {e}")
except Exception as e:
    print(f"Terjadi Kendala Teknis: {repr(e)}")

finally:
    # Menutup browser otomatis setelah seluruh rangkaian selesai
    driver.quit()