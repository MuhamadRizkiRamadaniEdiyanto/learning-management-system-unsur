# Checklist Uji Manual Backend LMS - Per Role

**Status**: Database fresh & seeded dengan akun default  
**Tanggal**: 2026-08-29

---

## Test Accounts

| Role      | Email                 | Password    | Status  |
| --------- | --------------------- | ----------- | ------- |
| Admin     | admin@unsur.ac.id     | password123 | ✓ Ready |
| Dosen     | dosen@unsur.ac.id     | password123 | ✓ Ready |
| Mahasiswa | mahasiswa@unsur.ac.id | password123 | ✓ Ready |

---

## A. AUTHENTICATION & LOGIN TEST

### A.1 Login Admin

- [ ] Buka `http://localhost/login` di browser
- [ ] Masukkan email: `admin@unsur.ac.id`
- [ ] Masukkan password: `password123`
- [ ] Klik "Sign in"
- **Expected**: Redirect ke dashboard, user role admin

**Command curl** (setelah login, ambil cookie):

```bash
curl -X POST http://localhost/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@unsur.ac.id","password":"password123"}' \
  -c cookies.txt
```

### A.2 Login Dosen

- [ ] Logout dari session admin
- [ ] Login dengan email: `dosen@unsur.ac.id`
- [ ] Masukkan password: `password123`
- **Expected**: Redirect ke dashboard, user role dosen

### A.3 Login Mahasiswa

- [ ] Logout dari session dosen
- [ ] Login dengan email: `mahasiswa@unsur.ac.id`
- [ ] Masukkan password: `password123`
- **Expected**: Redirect ke dashboard, user role mahasiswa

### A.4 Invalid Password

- [ ] Login dengan email yang benar tapi password salah
- **Expected**: Error message "invalid credentials"

---

## B. ADMIN ROLE TEST

**Setup**: Login sebagai admin

### B.1 Admin Dashboard

- [ ] Akses `http://localhost/admin/dashboard`
- [ ] Verify response JSON berisi:
    - `total_courses`
    - `total_dosen`
    - `total_mahasiswa`
    - `total_submissions`

**Command**:

```bash
curl -X GET http://localhost/admin/dashboard \
  -H "Authorization: Bearer {ADMIN_TOKEN}" \
  -H "Accept: application/json"
```

### B.2 Course Management - List

- [ ] Akses `http://localhost/admin/courses`
- [ ] Verify response berisi daftar courses dengan pagination

**Command**:

```bash
curl -X GET http://localhost/admin/courses \
  -H "Authorization: Bearer {ADMIN_TOKEN}" \
  -H "Accept: application/json"
```

- [ ] Test pagination dengan `?page=1`
- [ ] Test search dengan `?search=TIF`

### B.3 Course Management - Create

- [ ] POST ke `http://localhost/admin/courses` dengan payload:

```json
{
    "kode_matkul": "TIF999",
    "nama": "Test Course",
    "deskripsi": "Testing API",
    "dosen_id": 2
}
```

- **Expected**: Status 201, course created

**Command**:

```bash
curl -X POST http://localhost/admin/courses \
  -H "Authorization: Bearer {ADMIN_TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "kode_matkul":"TIF999",
    "nama":"Test Course",
    "deskripsi":"Testing API",
    "dosen_id":2
  }'
```

### B.4 Course Management - Update

- [ ] Update course yang baru dibuat:

```bash
curl -X PUT http://localhost/admin/courses/{COURSE_ID} \
  -H "Authorization: Bearer {ADMIN_TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "nama":"Test Course Updated",
    "deskripsi":"Updated description"
  }'
```

- **Expected**: Status 200, course updated

### B.5 Course Management - Assign Dosen

- [ ] POST ke `http://localhost/admin/courses/{COURSE_ID}/assign-dosen`:

```json
{
    "dosen_id": 2
}
```

- **Expected**: Status 200, dosen assigned

### B.6 Dosen Management - List

- [ ] GET `http://localhost/admin/dosen`
- **Expected**: List semua dosen dengan role dosen

### B.7 Dosen Management - Create

- [ ] POST ke `http://localhost/admin/dosen`:

```json
{
    "name": "Dr. Budi Santoso",
    "email": "budi.santoso@unsur.ac.id",
    "nomor_induk": "0512345679",
    "password": "password123"
}
```

- **Expected**: Status 201, dosen created dengan role forced to "dosen"

### B.8 Dosen Management - Update

- [ ] PUT ke `http://localhost/admin/dosen/{DOSEN_ID}`:

```json
{
    "name": "Dr. Budi Santoso, M.Kom."
}
```

- **Expected**: Status 200, dosen updated

### B.9 Dosen Management - Delete (Should Fail if has active courses)

- [ ] DELETE ke `http://localhost/admin/dosen/{DOSEN_ID}` dimana dosen punya courses
- **Expected**: Status 422, error "Dosen tidak bisa dihapus karena memiliki mata kuliah aktif"

### B.10 Mahasiswa Management - List

- [ ] GET `http://localhost/admin/mahasiswa`
- **Expected**: List semua mahasiswa

### B.11 Mahasiswa Management - Create

- [ ] POST ke `http://localhost/admin/mahasiswa`:

```json
{
    "name": "Andi Wijaya",
    "email": "andi.wijaya@student.unsur.ac.id",
    "nomor_induk": "5520119099",
    "password": "password123"
}
```

- **Expected**: Status 201, mahasiswa created dengan role forced to "mahasiswa"

### B.12 Mahasiswa Management - Enroll to Course

- [ ] POST ke `http://localhost/admin/mahasiswa/{MAHASISWA_ID}/courses`:

```json
{
    "course_id": 1
}
```

- **Expected**: Status 200, mahasiswa enrolled to course

### B.13 Schedule Management - List

- [ ] GET `http://localhost/admin/schedules`
- **Expected**: List semua schedules

### B.14 Schedule Management - Create

- [ ] POST ke `http://localhost/admin/schedules`:

```json
{
    "course_id": 1,
    "hari": "Senin",
    "jam_mulai": "08:00",
    "jam_selesai": "10:00",
    "ruangan": "B101"
}
```

- **Expected**: Status 201, schedule created

### B.15 Schedule Conflict Detection

- [ ] Coba create schedule overlap di ruangan dan waktu yang sama
- **Expected**: Status 422, error "Ruangan sudah terpakai di jam tersebut"

### B.16 Reports - Jumlah Mahasiswa Per Matkul

- [ ] GET `http://localhost/admin/reports/jumlah-mahasiswa-per-matkul`
- **Expected**: JSON dengan breakdown per course

### B.17 Reports - Rekap Nilai Per Matkul

- [ ] GET `http://localhost/admin/reports/rekap-nilai-per-matkul`
- **Expected**: JSON dengan grade distribution per course

### B.18 Reports - Rekap Pengumpulan Tugas

- [ ] GET `http://localhost/admin/reports/rekap-pengumpulan-tugas`
- **Expected**: JSON dengan submission stats

### B.19 Authorization Test - Dosen Cannot Access Admin Routes

- [ ] Logout admin, login dosen
- [ ] Akses `http://localhost/admin/courses`
- **Expected**: Status 403 Forbidden

### B.20 Authorization Test - Mahasiswa Cannot Access Admin Routes

- [ ] Logout dosen, login mahasiswa
- [ ] Akses `http://localhost/admin/courses`
- **Expected**: Status 403 Forbidden

---

## C. DOSEN ROLE TEST

**Setup**: Login sebagai dosen

### C.1 Dosen Dashboard

- [ ] Akses `http://localhost/dosen/dashboard`
- [ ] Verify response berisi:
    - `courses_taught` (courses yang dosen ajar)
    - `total_students`
    - `pending_submissions`

### C.2 View My Courses

- [ ] GET `http://localhost/api/courses`
- **Expected**: Response hanya courses yang dosen ajar (filter by dosen_id = 2)

**Command**:

```bash
curl -X GET http://localhost/api/courses \
  -H "Authorization: Bearer {DOSEN_TOKEN}" \
  -H "Accept: application/json"
```

### C.3 Cannot View Courses Teaching by Other Dosen

- [ ] Query courses yang diajar dosen lain
- **Expected**: Not in the list atau error 403

### C.4 Assignment Management - List (for own course)

- [ ] GET `http://localhost/api/courses/{COURSE_ID}/assignments`
- **Expected**: List semua assignments untuk course tersebut

### C.5 Assignment Management - Create

- [ ] POST ke `http://localhost/api/courses/{COURSE_ID}/assignments`:

```json
{
    "judul": "UTS - Basis Data",
    "deskripsi": "Ujian Tengah Semester",
    "tenggat_waktu": "2026-09-15T23:59:59"
}
```

- **Expected**: Status 201, assignment created

### C.6 Assignment Management - Update

- [ ] PUT ke `http://localhost/api/courses/{COURSE_ID}/assignments/{ASSIGNMENT_ID}`:

```json
{
    "judul": "UTS - Basis Data (Update)"
}
```

- **Expected**: Status 200, assignment updated

### C.7 Submission Management - List Submissions for Assignment

- [ ] GET `http://localhost/api/courses/{COURSE_ID}/assignments/{ASSIGNMENT_ID}/submissions`
- **Expected**: List semua submissions untuk assignment tersebut

### C.8 Submission Grading - Grade a Submission

- [ ] PUT ke `http://localhost/api/submissions/{SUBMISSION_ID}`:

```json
{
    "nilai": 85,
    "feedback": "Bagus, tapi masih ada yang kurang sempurna"
}
```

- **Expected**: Status 200, submission graded

### C.9 Cannot Access Submissions from Other Courses

- [ ] GET `http://localhost/api/courses/{OTHER_COURSE_ID}/assignments/{OTHER_ASSIGNMENT_ID}/submissions`
- **Expected**: Status 403 Forbidden (karena course bukan milik dosen)

### C.10 Material Upload

- [ ] POST ke `http://localhost/api/courses/{COURSE_ID}/materials`:

```
Content-Type: multipart/form-data
- judul: "Slide Pertemuan 1"
- deskripsi: "Materi dasar"
- file: [binary file upload]
```

- **Expected**: Status 201, file stored in private disk

### C.11 Material Access - Only Enrolled Mahasiswa Can Download

- [ ] Login sebagai mahasiswa yang terdaftar
- [ ] Download material dari course yang terdaftar
- **Expected**: Status 200, file downloaded

### C.12 Material Access - Unauthorized Mahasiswa Cannot Download

- [ ] Login sebagai mahasiswa yang NOT terdaftar di course
- [ ] Coba download material dari course tersebut
- **Expected**: Status 403 Forbidden

---

## D. MAHASISWA ROLE TEST

**Setup**: Login sebagai mahasiswa yang sudah terdaftar di minimal 1 course

**Pre-requisite**:

```bash
# Ensure mahasiswa enrolled to a course via admin
curl -X POST http://localhost/admin/mahasiswa/3/courses \
  -H "Authorization: Bearer {ADMIN_TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{"course_id":1}'
```

### D.1 Mahasiswa Dashboard

- [ ] GET `http://localhost/mahasiswa/dashboard`
- [ ] Verify response JSON berisi:
    - `courses`: Array of enrolled courses
    - `assignments`: Recent assignments
    - `nilai_terakhir`: Latest grades

**Command**:

```bash
curl -X GET http://localhost/mahasiswa/dashboard \
  -H "Authorization: Bearer {MAHASISWA_TOKEN}" \
  -H "Accept: application/json"
```

**Expected Response Structure**:

```json
{
    "data": {
        "courses": [
            {
                "id": 1,
                "kode_matkul": "TIF101",
                "nama": "Course Name"
            }
        ],
        "assignments": [
            {
                "id": 1,
                "judul": "Assignment Name",
                "submission_status": "belum",
                "submission_nilai": null
            }
        ],
        "nilai_terakhir": [
            {
                "course": "TIF101",
                "nilai": 85
            }
        ]
    }
}
```

### D.2 View Enrolled Courses Only

- [ ] GET `http://localhost/api/courses`
- **Expected**: Response hanya courses yang mahasiswa terdaftar

**Command**:

```bash
curl -X GET http://localhost/api/courses \
  -H "Authorization: Bearer {MAHASISWA_TOKEN}" \
  -H "Accept: application/json"
```

### D.3 Cannot View Non-Enrolled Courses

- [ ] Try GET courses yang mahasiswa NOT enrolled
- **Expected**: Not in list, status 403 if direct access to course detail

### D.4 View Course Materials

- [ ] GET `http://localhost/api/courses/{ENROLLED_COURSE_ID}/materials`
- **Expected**: List materials untuk course yang diikuti

### D.5 Download Material File

- [ ] GET `/materials/{MATERIAL_ID}/download`
- **Expected**: File downloaded (private disk storage)

### D.6 Cannot Download Material from Non-Enrolled Course

- [ ] Try download material dari course yang tidak diikuti
- **Expected**: Status 403 Forbidden

### D.7 View Assignments for Enrolled Course

- [ ] GET `http://localhost/api/courses/{COURSE_ID}/assignments`
- **Expected**: List assignments dengan submission_status per assignment

**Expected Response**:

```json
{
    "data": [
        {
            "id": 1,
            "judul": "Assignment 1",
            "tenggat_waktu": "2026-09-15T23:59:59",
            "submission_status": "belum|terlambat|tepat",
            "submission_nilai": null
        }
    ]
}
```

### D.8 Submit Assignment

- [ ] POST ke `http://localhost/api/assignments/{ASSIGNMENT_ID}/submit`:

```
Content-Type: multipart/form-data
- file: [binary file upload]
```

- **Expected**: Status 201, submission stored in private disk

### D.9 Cannot Submit After Deadline

- [ ] Create assignment dengan tenggat_waktu di masa lalu
- [ ] Coba submit
- **Expected**: Status 422, error "Sudah melewati tenggat waktu"

### D.10 Cannot Submit Same Assignment Twice (must update)

- [ ] Submit assignment (first time)
- [ ] Submit assignment again (same mahasiswa, same assignment)
- **Expected**: Status 422 atau update yang ada, jangan duplikat

### D.11 View Submission History (My Submissions)

- [ ] GET `http://localhost/mahasiswa/submissions`
- [ ] Verify response list semua submissions dari mahasiswa ini across all courses

**Command**:

```bash
curl -X GET http://localhost/mahasiswa/submissions \
  -H "Authorization: Bearer {MAHASISWA_TOKEN}" \
  -H "Accept: application/json"
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
            "nilai": 85,
            "assignment": {
                "judul": "UTS"
            }
        }
    ]
}
```

### D.12 Cannot View Other Mahasiswa's Submissions

- [ ] Login as different mahasiswa
- [ ] Verify endpoint `/mahasiswa/submissions` returns only their own

### D.13 Download Own Submission

- [ ] GET `/submissions/{SUBMISSION_ID}/download`
- **Expected**: File downloaded

### D.14 Update Profile (Name & Email Only)

- [ ] PATCH `/profile`:

```json
{
    "name": "New Name",
    "email": "newemail@student.unsur.ac.id"
}
```

- **Expected**: Status 200, profile updated

### D.15 Cannot Change nomor_induk via Profile Update

- [ ] Try PATCH `/profile` dengan field `nomor_induk`
- **Expected**: Status 200 tapi nomor_induk tetap tidak berubah (atau 422)

**Command**:

```bash
curl -X PATCH http://localhost/profile \
  -H "Authorization: Bearer {MAHASISWA_TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "name":"New Name",
    "nomor_induk":"9999999999"
  }'
```

### D.16 Authorization Test - Cannot Access Admin Routes

- [ ] Try GET `http://localhost/admin/courses`
- **Expected**: Status 403 Forbidden

### D.17 Authorization Test - Cannot Access Dosen Endpoints

- [ ] Try POST `http://localhost/api/courses/1/assignments` (create assignment)
- **Expected**: Status 403 Forbidden

### D.18 Cannot Grade Submissions

- [ ] Try PUT `/submissions/{SUBMISSION_ID}` dengan nilai
- **Expected**: Status 403 Forbidden

---

## E. SECURITY & AUTHORIZATION TESTS

### E.1 No Authentication

- [ ] Try access protected endpoint tanpa token
- **Expected**: Status 401 Unauthorized

**Command**:

```bash
curl -X GET http://localhost/api/courses
```

### E.2 Invalid Token

- [ ] Try access dengan invalid/expired token
- **Expected**: Status 401 Unauthorized

### E.3 CSRF Protection

- [ ] Try POST tanpa CSRF token di session
- **Expected**: Status 419 (token mismatch) atau 403

### E.4 SQL Injection Test

- [ ] Try search dengan payload: `'; DROP TABLE users; --`
- **Expected**: Treated as literal string, no SQL execution

### E.5 File Upload Validation

- [ ] Try upload file dengan size > limit
- **Expected**: Status 422, error "File terlalu besar"

### E.6 File Upload - Only Allowed Types

- [ ] Try upload executable file (.exe, .bat)
- **Expected**: Status 422, error "Tipe file tidak diperbolehkan"

---

## F. DATA CONSISTENCY TESTS

### F.1 Verify Course Enrollment Relationship

- [ ] Check database:

```bash
php artisan tinker
# In tinker:
$course = Course::find(1);
$course->mahasiswa()->count(); // should match enrolled count
```

### F.2 Verify Submission Unique Constraint

- [ ] Try create 2 submissions dengan assignment_id & user_id yang sama di DB level
- **Expected**: Error unique constraint violation

### F.3 Verify File Storage Location

```bash
# Files disimpan di storage/app/private, bukan public
ls -la storage/app/private/materials/
ls -la storage/app/private/submissions/
```

- **Expected**: File ada di private folder, tidak di public/

### F.4 Verify File Cleanup on Delete

- [ ] Delete assignment dengan submissions
- [ ] Cek file di disk sudah dihapus
- **Expected**: File tidak ada di storage/

---

## G. PERFORMANCE TESTS (Optional)

### G.1 Large Course List

- [ ] Create 50+ courses
- [ ] Test pagination & search performance
- [ ] **Expected**: Response time < 200ms

### G.2 Large Submissions List

- [ ] Create 100+ submissions
- [ ] Test `/mahasiswa/submissions` response time
- **Expected**: Response time < 500ms

---

## Test Results Summary

| Test Category       | Status | Notes |
| ------------------- | ------ | ----- |
| A. Authentication   | [ ]    |       |
| B. Admin Role       | [ ]    |       |
| C. Dosen Role       | [ ]    |       |
| D. Mahasiswa Role   | [ ]    |       |
| E. Security         | [ ]    |       |
| F. Data Consistency | [ ]    |       |
| G. Performance      | [ ]    |       |

---

## How to Run Tests

### Option 1: Browser Testing

1. Start dev server: `php artisan serve`
2. Open http://localhost in browser
3. Follow checklist step by step
4. Mark each ✓ when passed

### Option 2: Postman/Insomnia

1. Import collection (akan dibuat)
2. Set up environment variables (BASE_URL, TOKENS)
3. Run requests per role

### Option 3: Automated Curl Scripts

```bash
# Run all mahasiswa tests
bash scripts/test-mahasiswa.sh
```

### Option 4: PHP Artisan Test (Unit & Feature Tests)

```bash
php artisan test
```

---

## Notes

- Database reset setiap kali fresh: `php artisan migrate:fresh --seed`
- Jika ada error, check `storage/logs/laravel.log`
- Gunakan `php artisan tinker` untuk debug query
- Token auth sudah dicek di middleware

---

**Last Updated**: 2026-08-29  
**Tested By**: [Your Name]  
**Status**: Ready for Manual Testing
