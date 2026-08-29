# ⚡ Quick Start - Manual Testing

Panduan cepat untuk mulai uji manual backend LMS dalam 5 langkah.

---

## 🚀 Start Here (2 menit)

### Step 1: Terminal 1 - Start Server

```bash
cd c:/laragon/www/lms-ft-unsur
php artisan serve
```

→ Server ready di `http://localhost:8000`

### Step 2: Terminal 2 - Fresh Database

```bash
cd c:/laragon/www/lms-ft-unsur
php artisan migrate:fresh --seed
```

→ Database clean, akun default sudah siap

### Step 3: Open Browser

```
http://localhost:8000/login
```

### Step 4: Choose Your Test Path

Pick one of these (15-20 min each):

#### 🔐 Test Authentication (15 min)

1. Login as `admin@unsur.ac.id` / `password123`
2. Verify dashboard muncul
3. Logout, login as `dosen@unsur.ac.id` / `password123`
4. Logout, login as `mahasiswa@unsur.ac.id` / `password123`
5. Try access admin route (should fail with 403)

**Expected**: Each role shows different dashboard & menus

---

#### 👨‍💼 Test Admin Module (25 min)

**Login**: `admin@unsur.ac.id` / `password123`

**Tests**:

```
✓ View courses list
✓ Create new course (kode: TIF501, nama: Test)
✓ Enroll mahasiswa to course
✓ Create schedule (Senin 08:00-10:00, Ruangan B201)
✓ Try create overlapping schedule (should fail)
✓ View reports (dashboard summary)
```

**Quickest Commands**:

```bash
# List admin routes
php artisan route:list --name=admin

# Quick curl test
curl -X GET http://localhost:8000/admin/dashboard \
  -H "Accept: application/json"
```

---

#### 👨‍🏫 Test Dosen Module (20 min)

**Login**: `dosen@unsur.ac.id` / `password123`

**Pre-requisite**: Pastikan dosen punya minimal 1 course

- Login admin
- Assign course to dosen_id=2
- Logout & login dosen

**Tests**:

```
✓ View only own courses
✓ Create assignment (judul: Quiz 1, tenggat: 2026-09-15)
✓ Upload material (Slide 1, upload PDF)
✓ View material sudah stored di /private
✓ Try access course dari dosen lain (should fail)
```

**File Verification**:

```bash
# Check material stored di private, bukan public
ls -la storage/app/private/materials/
```

---

#### 👨‍🎓 Test Mahasiswa Module (25 min)

**Login**: `mahasiswa@unsur.ac.id` / `password123`

**Pre-requisite**: Pastikan mahasiswa terdaftar di course

- Login admin
- Enroll mahasiswa ke course: `POST /admin/mahasiswa/3/courses` dengan `course_id=1`
- Logout & login mahasiswa

**Tests**:

```
✓ View dashboard (courses, assignments, grades)
✓ View only enrolled courses
✓ Download material (from enrolled course)
✓ Submit assignment (upload file before deadline)
✓ View submission history (/mahasiswa/submissions)
✓ Try update nomor_induk (should not change)
✓ Try access admin (should fail)
```

**Key Tests**:

```bash
# Dashboard
curl -X GET http://localhost:8000/mahasiswa/dashboard \
  -H "Cookie: laravel_session=..." \
  -H "Accept: application/json"

# My submissions
curl -X GET http://localhost:8000/mahasiswa/submissions \
  -H "Cookie: laravel_session=..." \
  -H "Accept: application/json"
```

---

#### 🔒 Test Security (10 min)

**Tests**:

```
✓ Try access API tanpa token (should 401)
✓ Try submit assignment setelah deadline (should fail)
✓ Try change nomor_induk (should not change)
✓ Try access other user's submission (should fail)
✓ Try upload large file (if limit set)
```

**Quick Commands**:

```bash
# No auth
curl -X GET http://localhost:8000/api/courses

# Invalid token
curl -X GET http://localhost:8000/api/courses \
  -H "Authorization: Bearer INVALID"

# Try change nomor_induk
curl -X PATCH http://localhost:8000/profile \
  -H "Cookie: ..." \
  -H "Content-Type: application/json" \
  -d '{"nomor_induk":"9999999999"}'
```

---

### Step 5: Mark Your Progress

✓ Completed tests:

- [ ] Authentication
- [ ] Admin Module
- [ ] Dosen Module
- [ ] Mahasiswa Module
- [ ] Security Tests

---

## 📊 Test Accounts Reference

| Role      | Email                 | Password    | Purpose           |
| --------- | --------------------- | ----------- | ----------------- |
| Admin     | admin@unsur.ac.id     | password123 | Full control      |
| Dosen     | dosen@unsur.ac.id     | password123 | Course management |
| Mahasiswa | mahasiswa@unsur.ac.id | password123 | Student access    |

---

## 🔧 Useful Commands During Testing

### View All Routes

```bash
php artisan route:list
```

### View Specific Routes

```bash
# Admin routes
php artisan route:list --name=admin

# Dosen routes
php artisan route:list --path=dosen

# Mahasiswa routes
php artisan route:list --path=mahasiswa
```

### Debug Database

```bash
# Open tinker shell
php artisan tinker

# Inside tinker:
User::all();           # All users
Course::all();         # All courses
Submission::all();     # All submissions
User::find(3);         # Specific user
```

### Check File Storage

```bash
# Materials & submissions in private disk
ls -la storage/app/private/materials/
ls -la storage/app/private/submissions/

# Should NOT be in public
ls -la public/storage/  # Should be empty for these
```

### View Logs

```bash
# Real-time logs
tail -f storage/logs/laravel.log

# Or open in editor
cat storage/logs/laravel.log
```

### Reset Database

```bash
# Clean reset
php artisan migrate:fresh --seed

# Specific migration
php artisan migrate:refresh
```

---

## 🎯 Common Test Scenarios

### Scenario 1: Create Course and Enroll Students (5 min)

**As Admin**:

1. Go to `/admin/courses`
2. Create: kode=TIF502, nama=New Course, dosen=2
3. Go to `/admin/mahasiswa`
4. Click mahasiswa, Enroll to TIF502
5. Verify course now visible to mahasiswa

### Scenario 2: Create Assignment and Submit (10 min)

**As Dosen**:

1. Go to course
2. Create assignment: judul=Quiz, tenggat=2026-09-15
3. Logout

**As Mahasiswa**:

1. Login
2. Go to `/mahasiswa/dashboard`
3. See assignment in list
4. Click assignment
5. Upload file
6. Check `/mahasiswa/submissions` - should see submission

### Scenario 3: Grade and Download (5 min)

**As Dosen**:

1. Go to assignment
2. View submissions
3. Click submission
4. Enter nilai=85, save
5. Download submission file

---

## ❌ Troubleshooting

### Error: "Unauthenticated"

**Cause**: Not logged in or session expired  
**Fix**: Login again, ensure cookie is sent in requests

### Error: "Unauthorized" (403)

**Cause**: Logged in but don't have permission  
**Fix**: Check role and authorization policy

### Error: "File not found"

**Cause**: Material/submission file missing  
**Fix**: Verify in `storage/app/private/`

```bash
ls -la storage/app/private/materials/
ls -la storage/app/private/submissions/
```

### Error: "UNIQUE constraint violation"

**Cause**: Trying to create duplicate enrollment/submission  
**Fix**: This is expected! It means unique constraint works

### Error: "Deadline passed"

**Cause**: Trying to submit assignment after tenggat_waktu  
**Fix**: Create assignment with future date or use admin to set past date for testing

### Error: "Table not found"

**Cause**: Database not migrated  
**Fix**: Run `php artisan migrate:fresh --seed`

---

## 📈 Expected Results Summary

### Authentication

✓ Login works for all roles  
✓ Each role sees different menu  
✓ Invalid password rejected

### Admin

✓ Can CRUD courses, dosen, mahasiswa, schedules  
✓ Cannot delete dosen with active courses  
✓ Schedule conflict detected  
✓ Reports show correct data

### Dosen

✓ Sees only own courses  
✓ Can create assignments, upload materials  
✓ Can grade submissions  
✓ Cannot access other dosen's courses

### Mahasiswa

✓ Sees only enrolled courses  
✓ Can download materials  
✓ Can submit assignments before deadline  
✓ Cannot submit after deadline  
✓ Cannot change nomor_induk  
✓ Cannot access admin or grade submissions

### Security

✓ No auth = 401  
✓ Wrong role = 403  
✓ Deadline check works  
✓ Files in private storage, not public

---

## 📝 Manual Test Checklist

```
AUTHENTICATION
[ ] Admin login works
[ ] Dosen login works
[ ] Mahasiswa login works
[ ] Invalid password rejected
[ ] Access control works (403 for unauthorized)

ADMIN
[ ] Create course
[ ] Update course
[ ] Delete course (if no assignments)
[ ] Create dosen
[ ] Create mahasiswa
[ ] Enroll mahasiswa
[ ] Create schedule
[ ] Detect schedule conflict
[ ] View reports

DOSEN
[ ] View only own courses
[ ] Create assignment
[ ] Upload material
[ ] View submissions
[ ] Grade submission

MAHASISWA
[ ] View enrolled courses only
[ ] Download material
[ ] View assignments with status
[ ] Submit assignment
[ ] View submission history
[ ] Cannot change nomor_induk
[ ] Cannot access admin

SECURITY
[ ] No token = 401
[ ] Wrong role = 403
[ ] Late submission = 422
[ ] Files in private disk
```

---

## 🎓 Next Steps (After Manual Testing)

1. **If tests pass**: ✓ Backend is production-ready
2. **Build frontend**: Use JSON responses already created
3. **Add more tests**: E2E tests for critical flows
4. **Performance test**: Load test with many users

---

**Status**: ✓ Ready for Manual Testing  
**Database**: ✓ Fresh seed (3 users: admin, dosen, mahasiswa)  
**All Tests**: ✓ 32 passed (84 assertions)  
**Duration**: 60-90 minutes for all tests

**Good luck!** 🚀
