# 🚀 Panduan Eksekusi Uji Manual - Step by Step

Ikuti panduan ini untuk menjalankan uji manual backend secara terstruktur.

---

## FASE 1: SETUP (5 menit)

### Step 1.1: Start Development Server

```bash
cd c:/laragon/www/lms-ft-unsur
php artisan serve
```

**Expected Output**:

```
Laravel development server started: http://127.0.0.1:8000
```

### Step 1.2: Verify Database is Fresh

```bash
# Di terminal baru, cek status
php artisan migrate:fresh --seed
```

**Expected Output**:

```
Seeding database.
Database seeded successfully.
```

### Step 1.3: List Current Routes

```bash
php artisan route:list | grep -E "admin|mahasiswa|dosen"
```

---

## FASE 2: LOGIN & AUTHENTICATION TEST (10 menit)

### Test 2.1: Admin Login

1. Buka browser: **http://localhost:8000/login**
2. Masukkan:
    - Email: `admin@unsur.ac.id`
    - Password: `password123`
3. Klik "Sign in"

**Checkpoint**:

- [ ] Redirect ke dashboard
- [ ] Tampil akses menu admin
- [ ] User role di session adalah "admin"

### Test 2.2: Dosen Login

1. Logout (jika masih login admin)
2. Login dengan:
    - Email: `dosen@unsur.ac.id`
    - Password: `password123`

**Checkpoint**:

- [ ] Redirect ke dashboard dosen
- [ ] Menu berbeda dari admin
- [ ] User role di session adalah "dosen"

### Test 2.3: Mahasiswa Login

1. Logout
2. Login dengan:
    - Email: `mahasiswa@unsur.ac.id`
    - Password: `password123`

**Checkpoint**:

- [ ] Redirect ke dashboard mahasiswa
- [ ] Menu minimal
- [ ] User role di session adalah "mahasiswa"

### Test 2.4: Akses Admin sebagai Mahasiswa (Should Fail)

1. Tetap login sebagai mahasiswa
2. Coba akses: **http://localhost:8000/admin/courses**

**Checkpoint**:

- [ ] Error 403 Forbidden
- [ ] Pesan: "This action is unauthorized"

---

## FASE 3: ADMIN ROLE TESTING (20 menit)

**Setup**: Login sebagai admin

### Test 3.1: Admin Dashboard

```
URL: http://localhost:8000/admin/dashboard
```

**Verification via Browser**:

- [ ] Halaman load tanpa error
- [ ] Tampil summary: Total Courses, Total Dosen, Total Mahasiswa

**Verification via API** (Terminal):

```bash
curl -X GET http://localhost:8000/admin/dashboard \
  -H "Cookie: XSRF-TOKEN=...; laravel_session=..." \
  -H "Accept: application/json"
```

### Test 3.2: View Courses List

```
URL: http://localhost:8000/admin/courses
```

**Expected**:

- [ ] List table/json dengan courses
- [ ] Pagination jika ada > 15 courses
- [ ] Action buttons: Edit, Delete, Assign Dosen

### Test 3.3: Create New Course

1. Click "Create Course" atau POST ke `/admin/courses`
2. Isi form:
    - Kode Matkul: `TIF501`
    - Nama: `Test Course Teknik Perangkat Lunak`
    - Deskripsi: `Mata kuliah testing`
    - Pilih Dosen: (Dosen yang ada)
3. Submit

**Expected**:

- [ ] Course berhasil dibuat
- [ ] Redirect ke course list
- [ ] Course baru ada di list

**Via curl**:

```bash
curl -X POST http://localhost:8000/admin/courses \
  -H "Cookie: ..." \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "kode_matkul=TIF501&nama=Test+Course&deskripsi=Testing&dosen_id=2&_token=..."
```

### Test 3.4: Assign Dosen ke Course

1. Buka course yang sudah dibuat
2. Click "Assign Dosen" atau gunakan API
3. Pilih dosen berbeda (jika ada)
4. Submit

**Expected**:

- [ ] Dosen berhasil di-assign
- [ ] Course sekarang diajar oleh dosen baru

### Test 3.5: Create Dosen (Non-Admin)

1. Go to Admin > Dosen Management
2. Click "Create Dosen"
3. Isi form:
    - Name: `Dr. Siti Nurjanah`
    - Email: `siti@unsur.ac.id`
    - Nomor Induk: `0612345680`
    - Password: `password123`
4. Submit

**Expected**:

- [ ] Dosen berhasil dibuat
- [ ] Role otomatis "dosen" (tidak bisa diubah)
- [ ] Email unik (tidak boleh duplicate)

### Test 3.6: Enroll Mahasiswa ke Course

1. Go to Admin > Mahasiswa
2. Cari mahasiswa
3. Click "Enroll to Course"
4. Pilih course
5. Submit

**Expected**:

- [ ] Mahasiswa berhasil di-enroll
- [ ] Mahasiswa sekarang bisa lihat course itu

### Test 3.7: Create Schedule

1. Go to Admin > Schedules
2. Click "Create Schedule"
3. Isi:
    - Course: Pilih TIF501 yang baru dibuat
    - Hari: Senin
    - Jam Mulai: 08:00
    - Jam Selesai: 10:00
    - Ruangan: B201
4. Submit

**Expected**:

- [ ] Schedule berhasil dibuat
- [ ] Tampil di schedule list

### Test 3.8: Test Schedule Conflict Detection

1. Try create schedule di ruangan & waktu yang sama:
    - Course: TIF501 (atau course lain)
    - Hari: Senin
    - Jam Mulai: 09:00 (overlap dengan 08:00-10:00)
    - Jam Selesai: 11:00
    - Ruangan: B201 (sama)
2. Submit

**Expected**:

- [ ] Error: "Ruangan B201 sudah terpakai pada jam tersebut"
- [ ] Schedule NOT created

### Test 3.9: View Admin Reports

1. Go to Admin > Reports
2. Check setiap report:

**Report 3.9.1**: Jumlah Mahasiswa per Matkul

```
URL: http://localhost:8000/admin/reports/jumlah-mahasiswa-per-matkul
```

- [ ] Tampil breakdown per course
- [ ] Format: `[{course: "TIF101", count: 5}, ...]`

**Report 3.9.2**: Rekap Nilai per Matkul

```
URL: http://localhost:8000/admin/reports/rekap-nilai-per-matkul
```

- [ ] Tampil grade stats (avg, min, max) per course

**Report 3.9.3**: Rekap Pengumpulan Tugas

```
URL: http://localhost:8000/admin/reports/rekap-pengumpulan-tugas
```

- [ ] Tampil submission stats

---

## FASE 4: DOSEN ROLE TESTING (20 menit)

**Setup**: Logout, login sebagai dosen

### Test 4.1: Dosen Dashboard

```
URL: http://localhost:8000/dosen/dashboard
```

**Expected**:

- [ ] Tampil courses yang dosen ajar
- [ ] Tampil total mahasiswa
- [ ] Tampil pending submissions

### Test 4.2: View Own Courses Only

```
URL: http://localhost:8000/api/courses
```

**Verification via API**:

```bash
curl -X GET http://localhost:8000/api/courses \
  -H "Cookie: ..." \
  -H "Accept: application/json"
```

**Expected**:

- [ ] Response JSON berisi hanya courses yang dosen ajar
- [ ] Tidak ada courses dari dosen lain

### Test 4.3: Try View Other Dosen's Course (Should Fail)

1. Get course ID dari course yang diajar dosen lain
2. Access: `http://localhost:8000/api/courses/{OTHER_COURSE_ID}`

**Expected**:

- [ ] Error 403 Forbidden

### Test 4.4: Create Assignment di Own Course

1. Get course_id dari course yang dosen ajar
2. Click "Create Assignment" di course tersebut
3. Isi:
    - Judul: `Quiz 1 - Basis Data`
    - Deskripsi: `Kuis pertama`
    - Tenggat Waktu: `2026-09-15 23:59:59` (future date)
4. Submit

**Via API**:

```bash
curl -X POST http://localhost:8000/api/courses/1/assignments \
  -H "Cookie: ..." \
  -H "Content-Type: application/json" \
  -d '{
    "judul":"Quiz 1",
    "deskripsi":"Kuis pertama",
    "tenggat_waktu":"2026-09-15T23:59:59"
  }'
```

**Expected**:

- [ ] Assignment berhasil dibuat
- [ ] Status 201 Created

### Test 4.5: Try Create Assignment di Course Lain (Should Fail)

1. Get course_id dari course yang dosen TIDAK ajar
2. Try POST assignment ke course itu

**Expected**:

- [ ] Error 403 Forbidden

### Test 4.6: Upload Material

1. Go to Course Detail
2. Click "Upload Material"
3. Isi:
    - Judul: `Slide Pertemuan 1`
    - Deskripsi: `Pengenalan Database`
    - File: [upload file PDF atau image]
4. Submit

**Expected**:

- [ ] Material berhasil upload
- [ ] File disimpan di `storage/app/private/materials/`
- [ ] Bukan di public folder

**Verify di terminal**:

```bash
ls -la storage/app/private/materials/
```

### Test 4.7: Grade Submission

1. Go to Assignment
2. Click "View Submissions"
3. Klik submission mahasiswa
4. Input nilai: `85`
5. Input feedback: `Bagus, tapi bisa lebih baik`
6. Submit

**Expected**:

- [ ] Submission berhasil di-grade
- [ ] Nilai tersimpan
- [ ] Feedback tersimpan

### Test 4.8: Download Student Submission

1. Go to Assignment > Submissions
2. Click submission file
3. Download

**Expected**:

- [ ] File berhasil didownload
- [ ] File tersimpan di private disk
- [ ] Nama file sesuai original

---

## FASE 5: MAHASISWA ROLE TESTING (25 menit)

**Setup**: Logout, login sebagai mahasiswa

### Step 5.0: Pre-requisite (Admin Enrollment)

Pastikan mahasiswa sudah terdaftar di minimal 1 course. Jika belum:

**Logout dari mahasiswa, login admin**:

```bash
curl -X POST http://localhost:8000/admin/mahasiswa/3/courses \
  -H "Cookie: ..." \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "course_id=1&_token=..."
```

**Login kembali sebagai mahasiswa**

### Test 5.1: Mahasiswa Dashboard

```
URL: http://localhost:8000/mahasiswa/dashboard
```

**Verification**:

```bash
curl -X GET http://localhost:8000/mahasiswa/dashboard \
  -H "Cookie: ..." \
  -H "Accept: application/json"
```

**Expected Response**:

```json
{
    "data": {
        "courses": [
            {
                "id": 1,
                "kode_matkul": "TIF101",
                "nama": "Basis Data"
            }
        ],
        "assignments": [
            {
                "id": 1,
                "judul": "Quiz 1",
                "submission_status": "belum",
                "submission_nilai": null
            }
        ],
        "nilai_terakhir": [
            {
                "course": "TIF101",
                "nilai": 0
            }
        ]
    }
}
```

**Expected**:

- [ ] Status 200
- [ ] Data courses tidak kosong
- [ ] Data assignments menampilkan submission_status

### Test 5.2: View Enrolled Courses Only

```
URL: http://localhost:8000/api/courses
```

**Expected**:

- [ ] List hanya courses yang mahasiswa terdaftar
- [ ] Tidak ada courses lain

### Test 5.3: Try Access Non-Enrolled Course (Should Fail)

1. Get course_id dari course yang mahasiswa NOT enrolled
2. Access: `http://localhost:8000/api/courses/{OTHER_COURSE_ID}`

**Expected**:

- [ ] Error 403 Forbidden

### Test 5.4: View Course Materials

```
URL: http://localhost:8000/api/courses/1/materials
```

**Expected**:

- [ ] List materials dari course
- [ ] Setiap material: id, judul, deskripsi, file_path

### Test 5.5: Download Material (Allowed)

1. Click download link pada material dari course yang enrolled
2. Browser download file

**Expected**:

- [ ] File berhasil didownload
- [ ] Format original terjaga (PDF/JPG/etc)

### Test 5.6: Try Download Material from Non-Enrolled Course (Should Fail)

1. Get material_id dari material course yang NOT enrolled
2. Try access download endpoint

**Expected**:

- [ ] Error 403 Forbidden

### Test 5.7: View Assignments

```
URL: http://localhost:8000/api/courses/1/assignments
```

**Expected Response**:

```json
{
    "data": [
        {
            "id": 1,
            "judul": "Quiz 1",
            "deskripsi": "Kuis pertama",
            "tenggat_waktu": "2026-09-15T23:59:59",
            "submission_status": "belum",
            "submission_nilai": null
        }
    ]
}
```

**Expected**:

- [ ] Status 200
- [ ] Setiap assignment punya submission_status

### Test 5.8: Submit Assignment (Before Deadline)

1. Go to Assignment
2. Click "Submit"
3. Upload file: test.pdf (atau file apapun)
4. Click Submit

**Via curl**:

```bash
curl -X POST http://localhost:8000/api/assignments/1/submit \
  -H "Cookie: ..." \
  -F "file=@test.pdf"
```

**Expected**:

- [ ] Status 201 Created
- [ ] File disimpan di `storage/app/private/submissions/`
- [ ] Response: `{"data": {"id": 1, "file_jawaban": "submissions/...", "nilai": null}}`

**Verify file location**:

```bash
ls -la storage/app/private/submissions/
```

### Test 5.9: Try Submit Same Assignment Again (Update Behavior)

1. Submit assignment yang SUDAH submit sebelumnya
2. Upload file berbeda (test2.pdf)

**Expected**:

- [ ] Submission lama dihapus dari disk
- [ ] File baru tersimpan
- [ ] Response: update, bukan duplikat
- [ ] Nilai reset ke null

### Test 5.10: Try Submit After Deadline (Should Fail)

1. Create assignment dengan tenggat_waktu di masa lalu
    - Login admin
    - Create assignment dengan tenggat_waktu: `2026-01-01 23:59:59`
    - Login kembali mahasiswa
2. Try submit ke assignment tersebut

**Expected**:

- [ ] Error 422 Unprocessable Entity
- [ ] Message: "Sudah melewati tenggat waktu"

### Test 5.11: View Own Submission History

```
URL: http://localhost:8000/mahasiswa/submissions
```

**Expected Response**:

```json
{
    "data": [
        {
            "id": 1,
            "assignment_id": 1,
            "user_id": 3,
            "file_jawaban": "submissions/...",
            "nilai": null,
            "assignment": {
                "id": 1,
                "judul": "Quiz 1"
            }
        }
    ]
}
```

**Expected**:

- [ ] Status 200
- [ ] List hanya submissions dari user ini
- [ ] Across all courses
- [ ] Include assignment detail

### Test 5.12: Try View Other Mahasiswa's Submissions (Should Fail)

1. Create user mahasiswa kedua (via admin)
2. Try access `/mahasiswa/submissions` sebagai mahasiswa pertama

**Expected**:

- [ ] Response tetap hanya submissions user pertama
- [ ] Tidak ada submissions user kedua

### Test 5.13: Download Own Submission

1. Go to Submission History
2. Click download submission file
3. File download

**Expected**:

- [ ] File berhasil didownload
- [ ] Nama file sesuai original upload

### Test 5.14: Update Profile (Name & Email)

```
URL: http://localhost:8000/profile (PATCH)
```

**Payload**:

```json
{
    "name": "Mahasiswa Updated Name",
    "email": "mahasiswa.new@student.unsur.ac.id"
}
```

**Via curl**:

```bash
curl -X PATCH http://localhost:8000/profile \
  -H "Cookie: ..." \
  -H "Content-Type: application/json" \
  -d '{
    "name":"Mahasiswa Updated Name",
    "email":"mahasiswa.new@student.unsur.ac.id"
  }'
```

**Expected**:

- [ ] Status 200
- [ ] Name updated
- [ ] Email updated
- [ ] Redirect to profile page

### Test 5.15: Try Change nomor_induk (Should NOT Change)

```json
{
    "name": "Mahasiswa Name",
    "email": "mahasiswa@student.unsur.ac.id",
    "nomor_induk": "9999999999"
}
```

**Expected**:

- [ ] Status 200 (validation pass)
- [ ] Name & email updated
- [ ] **nomor_induk tetap**: `5520119001` (tidak berubah ke 9999999999)

**Verify di database**:

```bash
php artisan tinker
# Inside tinker:
$user = User::find(3);
echo $user->nomor_induk; // Should be: 5520119001
```

### Test 5.16: Try Access Admin Routes (Should Fail)

1. Try access: `http://localhost:8000/admin/courses`

**Expected**:

- [ ] Error 403 Forbidden

### Test 5.17: Try Grade Submissions (Should Fail)

1. Try PUT `/submissions/1` dengan nilai

**Expected**:

- [ ] Error 403 Forbidden

---

## FASE 6: SECURITY TESTS (10 menit)

### Test 6.1: No Authentication Token

```bash
curl -X GET http://localhost:8000/api/courses
```

**Expected**:

- [ ] Error 401 Unauthorized
- [ ] Message: "Unauthenticated"

### Test 6.2: Invalid Token

```bash
curl -X GET http://localhost:8000/api/courses \
  -H "Authorization: Bearer INVALID_TOKEN"
```

**Expected**:

- [ ] Error 401 Unauthorized

### Test 6.3: File Upload - File Too Large

1. Try upload file > 10MB (if limit set)

**Expected**:

- [ ] Error 422
- [ ] Message: "File terlalu besar"

### Test 6.4: File Upload - Invalid Type

1. Try upload .exe file

**Expected**:

- [ ] Error 422
- [ ] Message: "Tipe file tidak diperbolehkan"

### Test 6.5: SQL Injection Prevention

```bash
curl -X GET "http://localhost:8000/admin/courses?search='; DROP TABLE courses; --" \
  -H "Cookie: ..."
```

**Expected**:

- [ ] Query treated as literal string
- [ ] No error
- [ ] No SQL execution (table still exists)

---

## FASE 7: DATA CONSISTENCY (5 menit)

### Test 7.1: Unique Submission per Assignment + Mahasiswa

1. Try create 2 submissions dengan:
    - assignment_id = 1
    - user_id = 3
    - Via raw SQL/Tinker

**Expected**:

- [ ] Database error: UNIQUE constraint violation
- [ ] Message: "Integrity constraint violation"

### Test 7.2: File Storage Location

```bash
# Check file locations
ls -la storage/app/private/materials/
ls -la storage/app/private/submissions/
ls -la public/storage/  # Should be empty for these files
```

**Expected**:

- [ ] Files dalam `/storage/app/private/`
- [ ] NOT dalam `/public/storage/`

### Test 7.3: File Cleanup on Delete

1. Note file path dari submission
2. Delete submission via API
3. Check file di disk

**Expected**:

- [ ] File dihapus dari disk
- [ ] Tidak ada orphan files

---

## FINAL VERIFICATION

### Checklist Completion

```
[ ] Fase 1: Setup - PASSED
[ ] Fase 2: Authentication - PASSED
[ ] Fase 3: Admin Role - PASSED
[ ] Fase 4: Dosen Role - PASSED
[ ] Fase 5: Mahasiswa Role - PASSED
[ ] Fase 6: Security - PASSED
[ ] Fase 7: Data Consistency - PASSED
```

### Summary Output

```bash
# Run automated tests
php artisan test

# Check all routes
php artisan route:list

# Verify database
php artisan tinker
>>> User::count()
>>> Course::count()
>>> Submission::count()
```

---

## Troubleshooting

### Error: "SQLSTATE[42S02]: Table or view not found"

**Solution**:

```bash
php artisan migrate:fresh --seed
```

### Error: "Unauthenticated" on protected routes

**Solution**: Ensure you login first, then send cookie in request

### Error: "File not found" on download

**Solution**: Verify file exists in `storage/app/private/`

```bash
ls -la storage/app/private/
```

### File Storage Wrong Location

**Solution**: Check `config/filesystems.php` - local disk harus point ke `storage/app/private/`

---

**Estimated Total Time**: 60-90 minutes  
**Difficulty Level**: Beginner to Intermediate  
**Tools Required**: Browser, Terminal, curl (optional)

---

## FINAL BACKEND RELEASE CHECKLIST

Gunakan checklist ini setelah pengujian manual selesai dan sebelum demo seminar.

- [ ] Database demo berhasil dimigrasikan/di-restore dan akun tiga role tersedia.
- [ ] Login mengarahkan admin, dosen, dan mahasiswa ke dashboard masing-masing.
- [ ] Mahasiswa yang mengakses `/admin/dashboard` dan `/dosen/dashboard` menerima HTTP 403.
- [ ] Dosen hanya dapat mengelola course, materi, tugas, dan submission miliknya.
- [ ] Mahasiswa hanya dapat melihat course yang diikuti dan submission miliknya.
- [ ] Upload PDF/PNG valid, file lebih dari 10 MB, format invalid, serta update/delete file sudah diuji.
- [ ] Submission sebelum deadline berhasil dan create/update setelah deadline ditolak.
- [ ] Dashboard mahasiswa menampilkan jadwal, tugas, materi, dan pesan yang sesuai.
- [ ] Query dashboard diperiksa dan tidak menimbulkan N+1.
- [ ] Laporan admin menampilkan total materi dan rasio pengumpulan yang cocok dengan database.
- [ ] Export laporan berhasil dan endpoint laporan menolak role non-admin.
- [ ] Jalankan `php artisan test tests/Feature/Mahasiswa/MahasiswaSecurityTest.php tests/Feature/Authorization/LmsRoleAuthorizationTest.php`.
- [ ] Jalankan `php artisan test` dan `php artisan view:cache`.
- [ ] Catat kegagalan test, exception log, backup database, URL demo, dan rollback plan.

Good luck! 🎉
