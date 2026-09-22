<?php
class KelasAnggota {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getByKelas(int $kid): array {
        $s=$this->db->prepare("SELECT ka.*,m.nim,m.nama,m.angkatan,ps.nama as prodi_nama,ps.kode as prodi_kode FROM kelas_anggota ka JOIN mahasiswa m ON ka.mahasiswa_id=m.id JOIN program_studi ps ON m.prodi_id=ps.id WHERE ka.kelas_id=? ORDER BY m.nama");
        $s->execute([$kid]); return $s->fetchAll();
    }
    public function getMahasiswaIdsByKelas(int $kid): array {
        $s=$this->db->prepare("SELECT mahasiswa_id FROM kelas_anggota WHERE kelas_id=?");
        $s->execute([$kid]); return $s->fetchAll(PDO::FETCH_COLUMN);
    }
    public function getMahasiswaTidakDiKelas(int $kid, int $pid): array {
        $s=$this->db->prepare("SELECT m.* FROM mahasiswa m WHERE m.prodi_id=? AND m.id NOT IN (SELECT mahasiswa_id FROM kelas_anggota WHERE kelas_id=?) ORDER BY m.nama");
        $s->execute([$pid,$kid]); return $s->fetchAll();
    }
    public function tambah(int $kid, int $mid): bool {
        $s=$this->db->prepare("INSERT IGNORE INTO kelas_anggota (kelas_id,mahasiswa_id) VALUES (?,?)");
        return $s->execute([$kid,$mid]);
    }
    public function hapus(int $id): bool { $s=$this->db->prepare("DELETE FROM kelas_anggota WHERE id=?"); return $s->execute([$id]); }
    public function isExist(int $kid, int $mid): bool {
        $s=$this->db->prepare("SELECT COUNT(*) FROM kelas_anggota WHERE kelas_id=? AND mahasiswa_id=?");
        $s->execute([$kid,$mid]); return (int)$s->fetchColumn()>0;
    }
    public function countByKelas(int $kid): int {
        $s=$this->db->prepare("SELECT COUNT(*) FROM kelas_anggota WHERE kelas_id=?"); $s->execute([$kid]); return (int)$s->fetchColumn();
    }
    public function isMhsExistInKelas(int $kid, int $mid): bool {
        return $this->isExist($kid, $mid);
    }
    public function create(array $d): bool {
        return $this->tambah((int)$d['kelas_id'], (int)$d['mahasiswa_id']);
    }
    public function delete(int $id): bool {
        return $this->hapus($id);
    }
}
