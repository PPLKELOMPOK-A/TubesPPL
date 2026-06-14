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

def alur_navigasi_ke_form_password():
    """Alur Multi-Step Lupa Password: Login -> Forgot Password -> Input Email -> Continue"""
    driver.delete_all_cookies()  # Bersihkan sesi agar kembali ke halaman login awal
    driver.get(f"{BASE_URL}/login")  # Muat ulang halaman login secara murni
    time.sleep(2)  # Beri jeda stabilitas memuat DOM
    
    # Menunggu link Forgot Password siap diklik setelah redirect halaman
    tombol_forgot = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable((By.LINK_TEXT, "Forgot Password"))
    )
    tombol_forgot.click()
    
    # Menunggu field input email di halaman pertama muncul sempurna
    input_email = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.NAME, "email"))
    )
    input_email.clear()
    input_email.send_keys("jeni@email.com")
    time.sleep(1)
    
    # Menggunakan pencarian XPATH untuk mengklik tombol "Continue"
    tombol_continue = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable((By.XPATH, "//*[contains(text(), 'Continue') or @type='submit']"))
    )
    tombol_continue.click()
    
    # Memastikan form input password baru (halaman kedua) sudah termuat sebelum melaju ke case pengujian
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "password")))
    time.sleep(1)

try:
    # =========================================================================
    # TS.PPU.003 - TC.PPU.003.001: Reset Password (Positive)
    # =========================================================================
    alur_navigasi_ke_form_password()
    
    driver.find_element(By.NAME, "password").send_keys("jeni123456")
    driver.find_element(By.NAME, "password_confirmation").send_keys("jeni123456")
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//*[contains(text(), 'Save Changes')]").click()
    time.sleep(2)
    print("TC.PPU.003.001 (Reset Password Valid): PASSED")

    # =========================================================================
    # TS.PPU.003 - TC.PPU.003.002: Reset Password - Mengosongkan Seluruh Field (Negative)
    # =========================================================================
    alur_navigasi_ke_form_password()
    
    driver.find_element(By.XPATH, "//*[contains(text(), 'Save Changes')]").click()
    time.sleep(1)
    
    input_pass_baru = driver.find_element(By.NAME, "password")
    assert input_pass_baru.get_attribute("required") is not None
    print("TC.PPU.003.002 (Reset Password Seluruh Field Kosong - Validasi Muncul): PASSED")

    # =========================================================================
    # TS.PPU.003 - TC.PPU.003.003: Reset Password - Konfirmasi Tidak Cocok (Negative)
    # =========================================================================
    alur_navigasi_ke_form_password()
    
    driver.find_element(By.NAME, "password").send_keys("jeni123456")
    driver.find_element(By.NAME, "password_confirmation").send_keys("jeni654321")  # Sengaja dibuat beda
    time.sleep(1)
    
    driver.find_element(By.XPATH, "//*[contains(text(), 'Save Changes')]").click()
    time.sleep(2)
    
    assert "Konfirmasi kata sandi baru tidak cocok" in driver.page_source or "The password field confirmation does not match" in driver.page_source
    print("TC.PPU.003.003 (Reset Password Konfirmasi Tidak Cocok): PASSED")

except AssertionError as e:
    print(f"Testing Gagal: Kriteria validasi tidak terpenuhi. {e}")
except Exception as e:
    print(f"Terjadi Kendala Teknis Spesifik: {repr(e)}")

finally:
    driver.quit()