<?php
class Ruangan {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(string $q='', int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp; $like="%$q%";
        $s=$this->db->prepare("SELECT * FROM ruangan WHERE kode LIKE ? OR nama LIKE ? OR IFNULL(gedung,'') LIKE ? ORDER BY kode LIMIT ? OFFSET ?");
        $s->execute([$like,$like,$like,$pp,$off]); return $s->fetchAll();
    }
    public function countAll(string $q=''): int {
        $like="%$q%"; $s=$this->db->prepare("SELECT COUNT(*) FROM ruangan WHERE kode LIKE ? OR nama LIKE ? OR IFNULL(gedung,'') LIKE ?");
        $s->execute([$like,$like,$like]); return (int)$s->fetchColumn();
    }
    public function getAllSimple(): array { return $this->db->query("SELECT * FROM ruangan ORDER BY kode")->fetchAll(); }
    public function findById(int $id): ?array { $s=$this->db->prepare("SELECT * FROM ruangan WHERE id=?"); $s->execute([$id]); return $s->fetch()?:null; }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO ruangan (kode,nama,kapasitas,gedung) VALUES (?,?,?,?)");
        $s->execute([$d['kode'],$d['nama'],$d['kapasitas'],$d['gedung']??null]); return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE ruangan SET kode=?,nama=?,kapasitas=?,gedung=? WHERE id=?");
        return $s->execute([$d['kode'],$d['nama'],$d['kapasitas'],$d['gedung']??null,$id]);
    }
    public function delete(int $id): bool { $s=$this->db->prepare("DELETE FROM ruangan WHERE id=?"); return $s->execute([$id]); }
    public function isKodeExist(string $k, ?int $excl=null): bool {
        if($excl){$s=$this->db->prepare("SELECT COUNT(*) FROM ruangan WHERE kode=? AND id!=?");$s->execute([$k,$excl]);}
        else{$s=$this->db->prepare("SELECT COUNT(*) FROM ruangan WHERE kode=?");$s->execute([$k]);}
        return (int)$s->fetchColumn()>0;
    }
    public function hasRelasi(int $id): bool { $s=$this->db->prepare("SELECT COUNT(*) FROM jadwal WHERE ruangan_id=?"); $s->execute([$id]); return (int)$s->fetchColumn()>0; }
}
