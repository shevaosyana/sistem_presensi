<?php
class MataKuliah {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(string $q='', string $prodi='', int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp; $like="%$q%";
        $w=["(mk.kode LIKE ? OR mk.nama LIKE ?)"]; $p=[$like,$like];
        if($prodi){$w[]="mk.prodi_id=?";$p[]=$prodi;}
        $s=$this->db->prepare("SELECT mk.*,ps.nama as prodi_nama FROM mata_kuliah mk JOIN program_studi ps ON mk.prodi_id=ps.id WHERE ".implode(' AND ',$w)." ORDER BY mk.nama LIMIT ? OFFSET ?");
        $p[]=$pp;$p[]=$off; $s->execute($p); return $s->fetchAll();
    }
    public function countAll(string $q='', string $prodi=''): int {
        $like="%$q%"; $w=["(mk.kode LIKE ? OR mk.nama LIKE ?)"]; $p=[$like,$like];
        if($prodi){$w[]="mk.prodi_id=?";$p[]=$prodi;}
        $s=$this->db->prepare("SELECT COUNT(*) FROM mata_kuliah mk WHERE ".implode(' AND ',$w)); $s->execute($p); return (int)$s->fetchColumn();
    }
    public function getAllSimple(): array { return $this->db->query("SELECT * FROM mata_kuliah ORDER BY nama")->fetchAll(); }
    public function getByProdi(int $pid): array {
        $s=$this->db->prepare("SELECT * FROM mata_kuliah WHERE prodi_id=? ORDER BY nama"); $s->execute([$pid]); return $s->fetchAll();
    }
    public function findById(int $id): ?array {
        $s=$this->db->prepare("SELECT mk.*,ps.nama as prodi_nama FROM mata_kuliah mk JOIN program_studi ps ON mk.prodi_id=ps.id WHERE mk.id=?");
        $s->execute([$id]); return $s->fetch()?:null;
    }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO mata_kuliah (prodi_id,kode,nama,sks,jenis) VALUES (?,?,?,?,?)");
        $s->execute([$d['prodi_id'],$d['kode'],$d['nama'],$d['sks'],$d['jenis']]); return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE mata_kuliah SET prodi_id=?,kode=?,nama=?,sks=?,jenis=? WHERE id=?");
        return $s->execute([$d['prodi_id'],$d['kode'],$d['nama'],$d['sks'],$d['jenis'],$id]);
    }
    public function delete(int $id): bool { $s=$this->db->prepare("DELETE FROM mata_kuliah WHERE id=?"); return $s->execute([$id]); }
    public function isKodeExist(string $kode, ?int $excl=null): bool {
        if($excl){$s=$this->db->prepare("SELECT COUNT(*) FROM mata_kuliah WHERE kode=? AND id!=?");$s->execute([$kode,$excl]);}
        else{$s=$this->db->prepare("SELECT COUNT(*) FROM mata_kuliah WHERE kode=?");$s->execute([$kode]);}
        return (int)$s->fetchColumn()>0;
    }
    public function getTotalCount(): int { return (int)$this->db->query("SELECT COUNT(*) FROM mata_kuliah")->fetchColumn(); }
    public function hasRelasi(int $id): bool { $s=$this->db->prepare("SELECT COUNT(*) FROM kelas WHERE mata_kuliah_id=?"); $s->execute([$id]); return (int)$s->fetchColumn()>0; }
}
