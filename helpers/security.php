<?php
/**
 * security.php — Legacy helper (sudah tidak digunakan aktif)
 *
 * Fungsi-fungsi di file ini SUDAH DIGANTIKAN oleh:
 *   - DetailPresensi::verifikasiIntegritas(int $id): bool
 *   - AuditController → menggunakan FormatHelper::generateHash() secara langsung
 *
 * File ini dipertahankan hanya untuk kompatibilitas mundur.
 * Jangan tambahkan logika baru di sini.
 */

/**
 * @deprecated Gunakan DetailPresensi::verifikasiIntegritas() sebagai gantinya.
 */
function verifikasiIntegritas(int $id, PDO $conn): array {
    $sql = "SELECT m.nama, m.nim, mk.kode AS kode_mk, p.tanggal, dp.status, dp.hash_integrity, j.jam_mulai, j.hari
            FROM detail_presensi dp
            JOIN mahasiswa m ON dp.mahasiswa_id = m.id
            JOIN presensi p ON dp.presensi_id = p.id
            JOIN jadwal j ON p.jadwal_id = j.id
            JOIN kelas k ON j.kelas_id = k.id
            JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
            WHERE dp.id = ?
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return ['status' => false, 'pesan' => 'Data Termanipulasi'];
    }

    $data_mentah = trim((string)$row['nama']) . '|' . trim((string)$row['nim']) . '|' . trim((string)$row['tanggal']) . '|' . trim((string)$row['kode_mk']) . '|' . trim((string)$row['jam_mulai']) . '|' . trim((string)$row['hari']) . '|' . trim((string)$row['status']);
    $hash_baru = hash('sha256', $data_mentah);
    $hash_lama = trim((string)$row['hash_integrity']);

    if (hash_equals($hash_lama, $hash_baru)) {
        return ['status' => true, 'pesan' => 'Data Valid'];
    }

    return ['status' => false, 'pesan' => 'Data Termanipulasi'];
}

/**
 * @deprecated Gunakan AuditController secara langsung sebagai gantinya.
 */
function verifikasiIntegritasBatch(?string $detailIds, PDO $conn): array {
    $ids = array_filter(array_map('trim', explode(',', (string)$detailIds)), 'strlen');
    if (empty($ids)) {
        return ['status' => false, 'pesan' => 'Tidak ada data'];
    }

    $ids = array_values(array_unique($ids));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT dp.id, m.nama, m.nim, mk.kode AS kode_mk, p.tanggal, dp.status, dp.hash_integrity, j.jam_mulai, j.hari
            FROM detail_presensi dp
            JOIN mahasiswa m ON dp.mahasiswa_id = m.id
            JOIN presensi p ON dp.presensi_id = p.id
            JOIN jadwal j ON p.jadwal_id = j.id
            JOIN kelas k ON j.kelas_id = k.id
            JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
            WHERE dp.id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($ids);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) !== count($ids)) {
        return ['status' => false, 'pesan' => 'Data Termanipulasi'];
    }

    foreach ($rows as $row) {
        $data_mentah = trim((string)$row['nama']) . '|' . trim((string)$row['nim']) . '|' . trim((string)$row['tanggal']) . '|' . trim((string)$row['kode_mk']) . '|' . trim((string)$row['jam_mulai']) . '|' . trim((string)$row['hari']) . '|' . trim((string)$row['status']);
        $hash_baru = hash('sha256', $data_mentah);
        $hash_lama = trim((string)$row['hash_integrity']);

        if (!hash_equals($hash_lama, $hash_baru)) {
            return ['status' => false, 'pesan' => 'Data Termanipulasi'];
        }
    }

    return ['status' => true, 'pesan' => 'Data Valid'];
}
