-- ============================================
-- FIX FOREIGN KEY CONSTRAINTS - ON DELETE CASCADE
-- ============================================
-- Jalankan SQL ini di Supabase SQL Editor
-- untuk mengatur foreign key constraint agar otomatis hapus data terkait
-- ============================================

-- 1. DROP existing foreign key constraints
ALTER TABLE notifikasi 
DROP CONSTRAINT IF EXISTS notifikasi_id_user_fkey;

ALTER TABLE tagihan 
DROP CONSTRAINT IF EXISTS tagihan_id_user_fkey;

ALTER TABLE laporan 
DROP CONSTRAINT IF EXISTS laporan_id_user_fkey;

ALTER TABLE kamar 
DROP CONSTRAINT IF EXISTS kamar_id_user_fkey;

-- 2. RECREATE foreign key constraints with ON DELETE CASCADE
-- Notifikasi: Hapus otomatis saat user dihapus
ALTER TABLE notifikasi 
ADD CONSTRAINT notifikasi_id_user_fkey 
FOREIGN KEY (id_user) 
REFERENCES "user"(id_user) 
ON DELETE CASCADE;

-- Tagihan: Hapus otomatis saat user dihapus
ALTER TABLE tagihan 
ADD CONSTRAINT tagihan_id_user_fkey 
FOREIGN KEY (id_user) 
REFERENCES "user"(id_user) 
ON DELETE CASCADE;

-- Laporan: Hapus otomatis saat user dihapus
ALTER TABLE laporan 
ADD CONSTRAINT laporan_id_user_fkey 
FOREIGN KEY (id_user) 
REFERENCES "user"(id_user) 
ON DELETE CASCADE;

-- Kamar: Set NULL saat user dihapus (kamar tetap ada tapi jadi kosong)
ALTER TABLE kamar 
ADD CONSTRAINT kamar_id_user_fkey 
FOREIGN KEY (id_user) 
REFERENCES "user"(id_user) 
ON DELETE SET NULL;

-- ============================================
-- VERIFICATION: Cek foreign key constraints
-- ============================================
SELECT
    tc.table_name,
    kcu.column_name,
    ccu.table_name AS foreign_table_name,
    ccu.column_name AS foreign_column_name,
    rc.delete_rule
FROM information_schema.table_constraints AS tc
JOIN information_schema.key_column_usage AS kcu
    ON tc.constraint_name = kcu.constraint_name
JOIN information_schema.constraint_column_usage AS ccu
    ON ccu.constraint_name = tc.constraint_name
JOIN information_schema.referential_constraints AS rc
    ON rc.constraint_name = tc.constraint_name
WHERE tc.constraint_type = 'FOREIGN KEY'
    AND ccu.table_name = 'user'
ORDER BY tc.table_name;

-- ============================================
-- Expected Result:
-- ============================================
-- table_name   | column_name | foreign_table_name | delete_rule
-- -------------|-------------|--------------------|--------------
-- notifikasi   | id_user     | user               | CASCADE
-- tagihan      | id_user     | user               | CASCADE
-- laporan      | id_user     | user               | CASCADE
-- kamar        | id_user     | user               | SET NULL
-- ============================================
