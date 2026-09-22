<?php
class Kelas {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(string $q='', string $semId='', int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp; $like="%$q%";
        $w=["(mk.nama LIKE ? OR k.nama LIKE ? OR d.nama LIKE ?)"]; $p=[$like,$like,$like];
        if($semId){$w[]="k.semester_id=?";$p[]=$semId;}
        $wstr=implode(' AND ',$w);
        $s=$this->db->prepare("SELECT k.*,mk.nama as mk_nama,mk.kode as mk_kode,mk.sks,d.nama as dosen_nama,s.nama_semester,ta.nama as ta_nama,(SELECT COUNT(*) FROM kelas_anggota WHERE kelas_id=k.id) as total_anggota FROM kelas k JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id JOIN semester s ON k.semester_id=s.id JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id WHERE $wstr ORDER BY mk.nama,k.nama LIMIT ? OFFSET ?");
        $p[]=$pp;$p[]=$off; $s->execute($p); return $s->fetchAll();
    }
    public function countAll(string $q='', string $semId=''): int {
        $like="%$q%"; $w=["(mk.nama LIKE ? OR k.nama LIKE ? OR d.nama LIKE ?)"]; $p=[$like,$like,$like];
        if($semId){$w[]="k.semester_id=?";$p[]=$semId;}
        $s=$this->db->prepare("SELECT COUNT(*) FROM kelas k JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id WHERE ".implode(' AND ',$w));
        $s->execute($p); return (int)$s->fetchColumn();
    }
    public function findById(int $id): ?array {
        $s=$this->db->prepare("SELECT k.*,mk.nama as mk_nama,mk.kode as mk_kode,mk.sks,mk.prodi_id,d.nama as dosen_nama,s.nama_semester,ta.nama as ta_nama FROM kelas k JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id JOIN semester s ON k.semester_id=s.id JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id WHERE k.id=?");
        $s->execute([$id]); return $s->fetch()?:null;
    }
    public function getByDosen(int $dosenId, ?int $semId=null): array {
        $p=[$dosenId]; $w="k.dosen_id=?";
        if($semId){$w.=" AND k.semester_id=?";$p[]=$semId;}
        $s=$this->db->prepare("SELECT k.*,mk.nama as mk_nama,mk.kode as mk_kode,mk.sks,s.nama_semester,ta.nama as ta_nama FROM kelas k JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN semester s ON k.semester_id=s.id JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id WHERE $w ORDER BY mk.nama");
        $s->execute($p); return $s->fetchAll();
    }
    public function getByMahasiswa(int $mhsId, ?int $semId=null): array {
        $p=[$mhsId]; $w="ka.mahasiswa_id=?";
        if($semId){$w.=" AND k.semester_id=?";$p[]=$semId;}
        $s=$this->db->prepare("SELECT k.*,mk.nama as mk_nama,mk.kode as mk_kode,mk.sks,d.nama as dosen_nama,s.nama_semester FROM kelas k JOIN kelas_anggota ka ON k.id=ka.kelas_id JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id JOIN semester s ON k.semester_id=s.id WHERE $w ORDER BY mk.nama");
        $s->execute($p); return $s->fetchAll();
    }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO kelas (mata_kuliah_id,dosen_id,semester_id,nama,kapasitas) VALUES (?,?,?,?,?)");
        $s->execute([$d['mata_kuliah_id'],$d['dosen_id'],$d['semester_id'],$d['nama'],$d['kapasitas']]);
        return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE kelas SET mata_kuliah_id=?,dosen_id=?,semester_id=?,nama=?,kapasitas=? WHERE id=?");
        return $s->execute([$d['mata_kuliah_id'],$d['dosen_id'],$d['semester_id'],$d['nama'],$d['kapasitas'],$id]);
    }
    public function delete(int $id): bool { $s=$this->db->prepare("DELETE FROM kelas WHERE id=?"); return $s->execute([$id]); }
    public function getTotalCount(): int { return (int)$this->db->query("SELECT COUNT(*) FROM kelas")->fetchColumn(); }
    public function hasRelasi(int $id): bool { $s=$this->db->prepare("SELECT COUNT(*) FROM jadwal WHERE kelas_id=?"); $s->execute([$id]); return (int)$s->fetchColumn()>0; }
    public function getAllSimple(): array {
        return $this->db->query("SELECT k.id,k.nama,mk.nama as mk_nama,d.nama as dosen_nama FROM kelas k JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id ORDER BY mk.nama,k.nama")->fetchAll();
    }
    public function getAnggota(int $kelasId): array {
        $s=$this->db->prepare("SELECT ka.mahasiswa_id, m.nama, m.nim FROM kelas_anggota ka JOIN mahasiswa m ON ka.mahasiswa_id=m.id WHERE ka.kelas_id=?");
        $s->execute([$kelasId]); return $s->fetchAll();
    }
}
