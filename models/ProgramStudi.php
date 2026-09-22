<?php
class ProgramStudi {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(string $q='', int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp; $like="%$q%";
        $s=$this->db->prepare("SELECT p.*,(SELECT COUNT(*) FROM mahasiswa WHERE prodi_id=p.id) as total_mhs,(SELECT COUNT(*) FROM dosen WHERE prodi_id=p.id) as total_dosen FROM program_studi p WHERE p.nama LIKE ? OR p.kode LIKE ? ORDER BY p.nama LIMIT ? OFFSET ?");
        $s->execute([$like,$like,$pp,$off]); return $s->fetchAll();
    }
    public function countAll(string $q=''): int {
        $like="%$q%"; $s=$this->db->prepare("SELECT COUNT(*) FROM program_studi WHERE nama LIKE ? OR kode LIKE ?");
        $s->execute([$like,$like]); return (int)$s->fetchColumn();
    }
    public function getAllSimple(): array { return $this->db->query("SELECT * FROM program_studi ORDER BY nama")->fetchAll(); }
    public function findById(int $id): ?array {
        $s=$this->db->prepare("SELECT * FROM program_studi WHERE id=?"); $s->execute([$id]); return $s->fetch()?:null;
    }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO program_studi (kode,nama,jenjang,fakultas) VALUES (?,?,?,?)");
        $s->execute([$d['kode'],$d['nama'],$d['jenjang'],$d['fakultas']]); return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE program_studi SET kode=?,nama=?,jenjang=?,fakultas=? WHERE id=?");
        return $s->execute([$d['kode'],$d['nama'],$d['jenjang'],$d['fakultas'],$id]);
    }
    public function delete(int $id): bool { $s=$this->db->prepare("DELETE FROM program_studi WHERE id=?"); return $s->execute([$id]); }
    public function isKodeExist(string $kode, ?int $excl=null): bool {
        if($excl){$s=$this->db->prepare("SELECT COUNT(*) FROM program_studi WHERE kode=? AND id!=?");$s->execute([$kode,$excl]);}
        else{$s=$this->db->prepare("SELECT COUNT(*) FROM program_studi WHERE kode=?");$s->execute([$kode]);}
        return (int)$s->fetchColumn()>0;
    }
    public function hasRelasi(int $id): bool {
        foreach(['mahasiswa','dosen','mata_kuliah'] as $t){
            $s=$this->db->prepare("SELECT COUNT(*) FROM $t WHERE prodi_id=?"); $s->execute([$id]);
            if((int)$s->fetchColumn()>0) return true;
        }
        return false;
    }
}
