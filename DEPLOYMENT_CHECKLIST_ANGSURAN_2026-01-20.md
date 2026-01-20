# ✅ DEPLOYMENT CHECKLIST - PERBAIKAN LOGIKA ANGSURAN

**Tanggal:** 20 Januari 2026  
**Dibuat untuk:** Team Development & QA  
**Estimasi Waktu:** 30-45 menit  

---

## 📋 PRE-DEPLOYMENT

### **Code Review**
- [ ] Review `app/Http/Controllers/PinjamanController.php` line 195-210
- [ ] Review `app/Http/Controllers/PinjamanController.php` line 327-368
- [ ] Review `app/Models/Pinjaman.php` line 221-285
- [ ] Review `app/Models/PinjamanCicilan.php` line 113-165
- [ ] Pastikan tidak ada syntax error: `php artisan tinker` → test simple query

### **Database Backup**
- [ ] Backup database production: `mysqldump -u root -p bumisultan_db > backup_2026_01_20.sql`
- [ ] Pastikan backup file ada & besar
- [ ] Store backup di safe location

### **Git Preparation**
- [ ] Commit semua changes: `git add -A && git commit -m "feat: perbaikan logika angsuran nominal ganjil akurat"`
- [ ] Push ke repository: `git push origin [branch-name]`
- [ ] Verify changes di GitHub/GitLab

---

## 🧪 TESTING DI LOCAL/STAGING

### **Unit Test - Calculation**
```bash
# Test 1: Nominal Pas (habis dibagi)
Pinjaman: Rp 2.250.000, Tenor: 10
Expected: 225.000 × 10 = 2.250.000
Verify: SELECT SUM(jumlah_cicilan) FROM pinjaman_cicilan WHERE pinjaman_id = [ID]
Result: [✓] 2.250.000

# Test 2: Nominal Ganjil (sisa kecil)
Pinjaman: Rp 1.000.000, Tenor: 3
Expected: 333.333 + 333.333 + 333.334 = 1.000.000
Verify: SELECT SUM(jumlah_cicilan) FROM pinjaman_cicilan WHERE pinjaman_id = [ID]
Result: [✓] 1.000.000

# Test 3: Nominal Ganjil (sisa besar)
Pinjaman: Rp 500.000, Tenor: 7
Expected: floor(500.000/7) × 6 + remainder = 500.000
Verify: 71.428 × 6 + 71.432 = 500.000
Result: [✓] 500.000
```

### **Feature Test - UI/UX**
- [ ] Buat pinjaman baru dengan nominal ganjil
  - Input: Rp 1.000.000, tenor 3 bulan
  - Verify: Jadwal cicilan otomatis terbuat (3 baris)
  - Check: Cicilan 1-2 = 333.333, Cicilan 3 = 333.334
  
- [ ] Edit pinjaman (ubah nominal)
  - Update nominal ke Rp 1.500.000
  - Verify: Jadwal cicilan ter-regenerate otomatis
  - Check: Semua cicilan = 500.000
  - Check: sisa_pinjaman = 1.500.000

- [ ] Pencairan pinjaman
  - Cairkan pinjaman
  - Verify: generateJadwalCicilan() dipanggil
  - Check: jadwal cicilan lengkap dengan tanggal jatuh tempo

- [ ] Pembayaran cicilan
  - Bayar cicilan ke-1 (full) → Rp 333.333
  - Verify: cicilan 1 status = 'lunas', sisa_cicilan = 0
  - Check: pinjaman.sisa_pinjaman = 666.667 ✓
  - Check: pinjaman.total_terbayar = 333.333 ✓

- [ ] Pembayaran partial
  - Bayar cicilan ke-2 (partial) → Rp 100.000
  - Verify: cicilan 2 status = 'sebagian', sisa_cicilan = 233.333
  - Check: pinjaman.sisa_pinjaman = 565.667 ✓ (666.667 - 100.000)
  - Check: pinjaman.total_terbayar = 433.333 ✓

### **Integration Test**
- [ ] Workflow lengkap: pengajuan → review → approve → cairkan → bayar → lunas
  - Pinjaman: Rp 1.000.000, tenor 3
  - Progress: Dari status 'pengajuan' hingga 'lunas'
  - Verify: sisa_pinjaman = 0 saat lunas

### **Data Integrity Test**
```sql
-- Query Verification (harus empty result = semua akurat)
SELECT 
    p.id, 
    p.nomor_pinjaman,
    p.total_pinjaman,
    SUM(pc.jumlah_cicilan) as total_cicilan,
    (p.total_pinjaman - SUM(pc.jumlah_cicilan)) as selisih
FROM pinjaman p
LEFT JOIN pinjaman_cicilan pc ON p.id = pc.pinjaman_id
GROUP BY p.id
HAVING selisih != 0 AND p.deleted_at IS NULL;

Expected Result: [✓] EMPTY (tidak ada selisih)
```

### **Performance Test**
- [ ] Generate jadwal cicilan 100 pinjaman
  - Time: < 5 detik
  - No database lock
  - No memory issue

---

## 🚀 DEPLOYMENT KE PRODUCTION

### **Pre-Deployment**
- [ ] Notify team: "Deployment dimulai jam XX:XX"
- [ ] Set maintenance mode: `php artisan down`
- [ ] Verify: Tidak ada user yang login (atau warning terlihat)

### **Code Deployment**
- [ ] Pull latest code: `git pull origin [branch-name]`
- [ ] Install dependencies (jika ada): `composer install`
- [ ] Run migrations (jika ada): `php artisan migrate`

### **Cache Clear**
- [ ] Clear application cache: `php artisan cache:clear`
- [ ] Clear config cache: `php artisan config:clear`
- [ ] Clear route cache: `php artisan route:clear`
- [ ] Clear view cache: `php artisan view:clear`
- [ ] Clear compiled classes: `php artisan clear-compiled`

### **Post-Deployment**
- [ ] Exit maintenance mode: `php artisan up`
- [ ] Verify: Website accessible di production
- [ ] Check log: `tail -f storage/logs/laravel.log`
- [ ] Monitor: Tidak ada error 500 atau warning

---

## 🔍 PRODUCTION VERIFICATION

### **Immediate Checks (5 menit setelah deploy)**
- [ ] Login ke production
- [ ] Cek halaman pinjaman loading normal
- [ ] Create test pinjaman dengan nominal ganjil
- [ ] Verify: Jadwal cicilan terbuat dengan akurat
- [ ] Monitor application performance di New Relic/DataDog (jika ada)

### **Data Validation (1-2 jam)**
```sql
-- Check 1: Semua pinjaman dengan cicilan
SELECT COUNT(*) as total_pinjaman FROM pinjaman WHERE status IN ('berjalan', 'dicairkan');

-- Check 2: Verifikasi akurasi (harus 0 record)
SELECT COUNT(*) as errors FROM (
    SELECT p.id FROM pinjaman p
    LEFT JOIN pinjaman_cicilan pc ON p.id = pc.pinjaman_id
    GROUP BY p.id
    HAVING SUM(pc.jumlah_cicilan) != p.total_pinjaman
) as temp;

-- Check 3: Sisa pinjaman akurat
SELECT COUNT(*) as errors FROM pinjaman p
WHERE (p.total_pinjaman - p.total_terbayar) != p.sisa_pinjaman
AND p.status != 'lunas';
```

### **User Testing (next 24 jam)**
- [ ] Minta team melakukan test pembayaran
- [ ] Monitor: Tidak ada complaint tentang nominal tidak akurat
- [ ] Check: Laporan keuangan sesuai ekspektasi

---

## 📊 ROLLBACK PLAN (Jika Ada Masalah)

### **Jika Error Terjadi Dalam 1 Jam:**
1. Set maintenance mode: `php artisan down`
2. Revert code: `git revert HEAD` atau `git checkout [previous-commit]`
3. Clear cache: `php artisan cache:clear`
4. Exit maintenance mode: `php artisan up`
5. Restore database (jika data corrupt): `mysql -u root -p bumisultan_db < backup_2026_01_20.sql`

### **Jika Data Issue Ditemukan:**
1. Backup current database (untuk analisa)
2. Restore dari backup sebelum deploy
3. Analisa masalah
4. Fix code issue
5. Re-test di staging
6. Re-deploy dengan confidence

---

## 📝 DOCUMENTATION

### **File yang Harus di-Share ke Tim:**
- [ ] SUMMARY_PERBAIKAN_ANGSURAN_2026-01-20.md (ringkasan)
- [ ] ANALISA_PERBAIKAN_LOGIKA_ANGSURAN_NOMINAL_GANJIL.md (analisa detail)
- [ ] IMPLEMENTASI_PERBAIKAN_LOGIKA_ANGSURAN_LENGKAP.md (implementasi detail)
- [ ] QUICK_REFERENCE_LOGIKA_ANGSURAN_AKURAT.md (quick ref)
- [ ] DIAGRAM_ALUR_LOGIKA_ANGSURAN_LENGKAP.md (visual)

### **Training untuk Tim:**
- [ ] Explain perubahan logika
- [ ] Show testing scenarios
- [ ] Discuss rollback plan
- [ ] Q&A session

---

## 🎯 SUCCESS CRITERIA

✅ **Deployment BERHASIL jika:**
- Website berjalan normal (no 500 error)
- Test pinjaman nominal ganjil akurat
- Jadwal cicilan ter-generate dengan benar
- Pembayaran cicilan ter-proses dengan akurat
- sisa_pinjaman selalu = total_pinjaman - total_terbayar
- No user complaint dalam 24 jam pertama

❌ **Deployment GAGAL jika:**
- Website error 500
- Jadwal cicilan tidak ter-generate
- Nominal cicilan tidak akurat
- sisa_pinjaman tidak ter-update
- User report bilang nominal tidak sesuai

---

## 📞 ESCALATION CONTACT

**Jika Ada Masalah:**
1. Developer Lead: [contact]
2. QA Lead: [contact]
3. DBA: [contact]
4. Backup: [contact]

---

## ✅ FINAL CHECKLIST

Sebelum di-deploy, pastikan semua ini di-check:

- [ ] Code review selesai (no issues)
- [ ] Database backup siap
- [ ] Testing di staging berhasil 100%
- [ ] Documentation ready
- [ ] Team notified & ready
- [ ] Rollback plan ready
- [ ] Monitoring tools siap
- [ ] Maintenance window scheduled
- [ ] Stakeholder notified
- [ ] Go/No-Go decision: **[APPROVE]**

---

## 📊 POST-DEPLOYMENT MONITORING

**24 Jam Pertama:**
- [ ] Monitor log setiap jam
- [ ] Check: No error pattern
- [ ] Check: Performance metric normal
- [ ] Check: User tidak report issue

**1-7 Hari:**
- [ ] Monitor daily
- [ ] Collect user feedback
- [ ] Verify: Semua pinjaman nominal akurat
- [ ] Close ticket: "Perbaikan logika angsuran live"

**1 Bulan:**
- [ ] Final verification:
  ```sql
  SELECT COUNT(*) FROM pinjaman WHERE total_pinjaman = 
  (SELECT SUM(jumlah_cicilan) FROM pinjaman_cicilan pc WHERE pc.pinjaman_id = pinjaman.id);
  -- Should return: COUNT = total pinjaman dengan cicilan
  ```
- [ ] Mark implementation as STABLE ✅

---

**Status Deployment: READY**  
**Last Updated:** 2026-01-20  
**Prepared By:** Development Team  
**Approved By:** [___________]  
**Deployment Date:** [___________]  
**Deployed By:** [___________]  

---

## 📋 SIGN-OFF

```
Developer:        ________________  Tanggal: ______
QA Lead:          ________________  Tanggal: ______
System Admin:     ________________  Tanggal: ______
Project Manager:  ________________  Tanggal: ______
```
