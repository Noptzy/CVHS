### **1. Rumah Dalam**
- **Lampu indikator (merah) menggunakan sensor ultrasonik**:
  - Tambahkan logika untuk mengatur waktu berkedip agar responsif dan tidak mengganggu.
  - Pastikan sensor ultrasonik memiliki toleransi noise yang baik (misalnya, menggunakan library atau algoritme untuk mengurangi error pembacaan).

- **Lampu primer (kuning) menggunakan sensor kamera**:
  - Tentukan metode komunikasi antara kamera dan lampu (misalnya, API atau protokol khusus).
  - Pertimbangkan mode manual override untuk menghidupkan/mematikan lampu melalui UI.

---

### **2. Taman Depan**
- **Lampu menggunakan jari (gesture control)**:
   - Dokumentasikan gestur yang digunakan agar pengguna memahami cara pengoperasian.

---

### **3. Fitur**
#### **Lampu Pakai Jari**
- Perlu pengujian intensif untuk memastikan keakuratan pengenalan gesture dalam berbagai kondisi pencahayaan.
- Sediakan fallback untuk mengontrol lampu melalui aplikasi jika gesture gagal dikenali.

#### **Pengenalan Pakai Muka**
- Sediakan fitur untuk memperbarui foto wajah (misalnya, jika penampilan berubah).
- Tambahkan log untuk mencatat hasil pengenalan wajah (misalnya, berhasil/gagal).

#### **Sensor Ultrasonic**
- Pastikan jarak baca sensor sesuai kebutuhan dan lingkungan (kalibrasi awal mungkin diperlukan).

---

### **4. Backend**
#### **Tabel User**
- **Tambah kolom**:
  - `Email`: Untuk mengidentifikasi pengguna (opsional).
  - `Role`: Jika sistem mendukung user roles (misalnya, admin, pengguna biasa).
  - `Created_At` dan `Updated_At`: Untuk mencatat waktu pembuatan dan pembaruan data.

#### **Tabel History Berkunjung**
- **Tambah kolom**:
  - `User_Id`: Menghubungkan kunjungan dengan pengguna tertentu.
  - `Foto_Kunjungan`: Foto pengguna yang terdeteksi saat kunjungan.
  - `Status_Kunjungan`: Menandai apakah kunjungan berhasil dikenali atau tidak.

---

### **5. Frontend**
#### **Halaman CRUD User**
- Tambahkan fitur berikut:
  - **Preview Gambar**: Saat mengunggah foto wajah, tampilkan pratinjau untuk verifikasi.
  - **Notifikasi Status**: Informasi apakah data berhasil diunggah atau gagal.
  - **Pencarian User**: Untuk memudahkan pencarian pengguna dalam daftar.

#### **Halaman Dashboard**
- Tampilan statistik:
  - Jumlah kunjungan harian/bulanan.
  - Daftar pengguna yang baru ditambahkan.
  - Status lampu (hidup/mati) secara real-time.
  - Status Ultrasonic jika dibawah 10Cm maka akan ada notif ada tamu 

#### **Halaman Monitoring**
- Tampilkan data dari sensor ultrasonik (misalnya, status berkedip).
- Streaming kamera untuk memonitor kondisi rumah dalam waktu nyata.

---

### **6. Integrasi**
- Pastikan Laravel dapat berkomunikasi dengan Python untuk pengolahan gesture dan pengenalan wajah.
- Gunakan MQTT atau REST API untuk komunikasi antar perangkat IoT (sensor, kamera, lampu).

---

### **7. Pengujian**
- Uji sistem dalam kondisi nyata dengan berbagai skenario:
  - Pencahayaan rendah/tinggi.
  - Banyak pengguna berbeda untuk pengenalan wajah.
  - Jarak deteksi sensor ultrasonik.
