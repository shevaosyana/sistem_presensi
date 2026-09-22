<?php
class Presensi {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getByJadwalTanggal(int $jid, string $tgl): ?array {
        $s=$this->db->prepare("SELECT * FROM presensi WHERE jadwal_id=? AND tanggal=? LIMIT 1");
        $s->execute([$jid,$tgl]); return $s->fetch()?:null;
    }
    public function findById(int $id): ?array {
        $s=$this->db->prepare("SELECT p.*,j.hari,j.jam_mulai,j.jam_selesai,j.kelas_id,j.toleransi_menit,k.nama as kelas_nama,mk.nama as mk_nama,mk.kode as mk_kode,d.nama as dosen_nama FROM presensi p JOIN jadwal j ON p.jadwal_id=j.id JOIN kelas k ON j.kelas_id=k.id JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id WHERE p.id=?");
        $s->execute([$id]); return $s->fetch()?:null;
    }
    public function getAktifByJadwal(int $jid, string $tgl): ?array {
        $s=$this->db->prepare("SELECT * FROM presensi WHERE jadwal_id=? AND tanggal=? AND status='AKTIF' LIMIT 1");
        $s->execute([$jid,$tgl]); return $s->fetch()?:null;
    }
    public function getSesiAktif(int $jid, string $tgl): ?array {
        return $this->getAktifByJadwal($jid, $tgl);
    }
    public function getHistoryByJadwal(int $jid): array {
        $s=$this->db->prepare("SELECT * FROM presensi WHERE jadwal_id=? ORDER BY pertemuan_ke ASC");
        $s->execute([$jid]); return $s->fetchAll();
    }
    public function getByKelas(int $kelasId): array {
        $s=$this->db->prepare("SELECT p.*,j.hari,j.jam_mulai,j.jam_selesai FROM presensi p JOIN jadwal j ON p.jadwal_id=j.id WHERE j.kelas_id=? ORDER BY p.tanggal DESC");
        $s->execute([$kelasId]); return $s->fetchAll();
    }
    public function buka(int $jid, string $tgl, string $jamBuka, int $ke): int {
        $s=$this->db->prepare("INSERT INTO presensi (jadwal_id,tanggal,jam_buka,status,pertemuan_ke) VALUES (?,?,?,'AKTIF',?) ON DUPLICATE KEY UPDATE jam_buka=VALUES(jam_buka),status='AKTIF'");
        $s->execute([$jid,$tgl,$jamBuka,$ke]);
        // Gunakan prepared statement untuk menghindari SQL Injection
        $r=$this->db->prepare("SELECT id FROM presensi WHERE jadwal_id=? AND tanggal=? LIMIT 1");
        $r->execute([$jid,$tgl]);
        $row = $r->fetch();
        return (int)($row['id']??$this->db->lastInsertId());
    }
    public function tutup(int $id): bool {
        $s=$this->db->prepare("UPDATE presensi SET status='SELESAI',jam_tutup=? WHERE id=?");
        return $s->execute([date('H:i:s'),$id]);
    }
    public function getAktifKedaluwarsa(): array {
        return $this->db->query("SELECT p.*,j.jam_selesai,j.kelas_id FROM presensi p JOIN jadwal j ON p.jadwal_id=j.id WHERE p.status='AKTIF' AND CONCAT(p.tanggal,' ',j.jam_selesai)<NOW()")->fetchAll();
    }
    public function countHariIni(): int {
        $s=$this->db->prepare("SELECT COUNT(*) FROM presensi WHERE tanggal=CURDATE()"); $s->execute([]); return (int)$s->fetchColumn();
    }
    public function getPertemuanKe(int $jid): int {
        $s=$this->db->prepare("SELECT COUNT(*) FROM presensi WHERE jadwal_id=?"); $s->execute([$jid]); return (int)$s->fetchColumn()+1;
    }
}
