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

def alur_navigasi_ke_dashboard_admin():
    """Alur: Login Admin -> Berada di Halaman Beranda Admin"""
    driver.delete_all_cookies()
    driver.get(f"{BASE_URL}/login")
    
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "email")))
    driver.find_element(By.NAME, "email").send_keys("admin@email.com")
    driver.find_element(By.NAME, "password").send_keys("12345678")
    driver.find_element(By.XPATH, "//button[contains(text(), 'Login') or @type='submit']").click()
    
    WebDriverWait(driver, 10).until(EC.url_contains("/admin/dashboard"))
    time.sleep(2)

try:
    print("Mempersiapkan pengujian Search & Filter...")
    alur_navigasi_ke_dashboard_admin()

    # =========================================================================
    # TS.PPU.001 - TC.PPU.001.001: Mencari Postingan Donasi (Positive)
    # Skenario: Sukses mencari donasi dengan kata kunci yang valid
    # =========================================================================
    print("\n--- Memulai TS.PPU.001 - TC.PPU.001.001 ---")
    
    # 1. Ketik kata kunci donasi
    input_search = driver.find_element(By.NAME, "search")
    kata_kunci_valid = "hghgjghg" 
    input_search.send_keys(kata_kunci_valid)
    
    # Tekan ENTER untuk memicu auto-submit
    input_search.send_keys(Keys.ENTER)
    
    # Tunggu halaman memuat ulang dan URL mengandung parameter pencarian
    WebDriverWait(driver, 10).until(EC.url_contains(f"search={kata_kunci_valid}"))
    time.sleep(1) # Jeda agar UI selesai dirender
    
    # Validasi: Hanya donasi dengan judul yang mengandung kata kunci yang ditampilkan
    hasil_pencarian = driver.find_elements(By.CSS_SELECTOR, ".donasi-info h3")
    assert len(hasil_pencarian) > 0, "Error: Pencarian valid tidak memunculkan data!"
    
    # Mengecek kecocokan teks
    cocok = any(kata_kunci_valid.lower() in elemen.text.lower() for elemen in hasil_pencarian)
    assert cocok == True, "Error: Data yang muncul tidak relevan dengan kata kunci pencarian!"
    
    print("TC.PPU.001.001 (Search Kata Kunci Valid): PASSED")

    # =========================================================================
    # TS.PPU.001 - TC.PPU.001.002: Mencari Postingan Donasi (Negative)
    # Skenario: Gagal menemukan donasi dengan kata kunci yang tidak terdaftar
    # =========================================================================
    print("\n--- Memulai TS.PPU.001 - TC.PPU.001.002 ---")
    
    # Reset navigasi ke dashboard murni
    driver.get(f"{BASE_URL}/admin/dashboard")
    time.sleep(2)
    
    # 1. Ketik kata kunci acak
    input_search = driver.find_element(By.NAME, "search")
    kata_kunci_asal = "DataKosong123"
    input_search.send_keys(kata_kunci_asal)
    input_search.send_keys(Keys.ENTER)
    
    # Tunggu sistem memuat ulang
    WebDriverWait(driver, 10).until(EC.url_contains(f"search={kata_kunci_asal}"))
    time.sleep(1)
    
    # Validasi: Menampilkan pesan daftar kosong
    assert "Belum ada data donasi di database yang sesuai" in driver.page_source, "Error: Pesan data kosong tidak muncul di layar!"
    
    print("TC.PPU.001.002 (Search Data Kosong/Invalid): PASSED")

    # =========================================================================
    # TS.PPU.002 - TC.PPU.002.001: Memfilter Postingan Donasi (Positive)
    # Skenario: Sukses memfilter donasi berdasarkan Kategori Penerima
    # =========================================================================
    print("\n--- Memulai TS.PPU.002 - TC.PPU.002.001 ---")
    
    # Reset navigasi ke dashboard murni
    driver.get(f"{BASE_URL}/admin/dashboard")
    time.sleep(2)
    
    # 1. Klik tombol "Filter"
    btn_filter = driver.find_element(By.CSS_SELECTOR, "button.filter-btn")
    btn_filter.click()
    time.sleep(1) # Tunggu dropdown animasi CSS
    
    # 2. Centang checkbox pada "Organisasi (Yayasan)"
    checkbox_yayasan = driver.find_element(By.XPATH, "//input[@type='checkbox' and @value='Organisasi (Yayasan)']")
    checkbox_yayasan.click()
    
    # Tunggu sistem melakukan auto-submit (URL terupdate dengan parameter kategori)
    WebDriverWait(driver, 10).until(EC.url_contains("kategori"))
    time.sleep(1)
    
    print("TC.PPU.002.001 (Filter Kategori Penerima): PASSED")

    # =========================================================================
    # TS.PPU.002 - TC.PPU.002.002: Memfilter Postingan Donasi (Positive)
    # Skenario: Sukses mengurutkan donasi berdasarkan Urutan Waktu
    # =========================================================================
    print("\n--- Memulai TS.PPU.002 - TC.PPU.002.002 ---")
    
    # Reset navigasi ke dashboard murni
    driver.get(f"{BASE_URL}/admin/dashboard")
    time.sleep(2)
    
    # 1. Klik tombol "Filter"
    btn_filter = driver.find_element(By.CSS_SELECTOR, "button.filter-btn")
    btn_filter.click()
    time.sleep(1)
    
    # 2. Pilih radio button "Terlama"
    radio_terlama = driver.find_element(By.XPATH, "//input[@type='radio' and @value='terlama']")
    
    # Memastikan elemen bisa di-klik jika tertutup elemen lain
    driver.execute_script("arguments[0].scrollIntoView(true);", radio_terlama)
    time.sleep(0.5)
    radio_terlama.click()
    
    # Tunggu sistem melakukan auto-submit (URL terupdate dengan parameter waktu)
    WebDriverWait(driver, 10).until(EC.url_contains("waktu=terlama"))
    time.sleep(1)
    
    print("TC.PPU.002.002 (Filter Urutan Waktu): PASSED")

except AssertionError as e:
    print(f"Testing Gagal: Validasi tidak terpenuhi. {e}")
except Exception as e:
    print(f"Terjadi Kendala Teknis: {e}")

finally:
    # Tutup browser otomatis
    driver.quit()