<?php
class Dosen {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(string $q='', string $prodi='', int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp; $like="%$q%";
        $w=["(d.nidn LIKE ? OR d.nama LIKE ?)"]; $p=[$like,$like];
        if($prodi){$w[]="d.prodi_id=?";$p[]=$prodi;}
        $s=$this->db->prepare("SELECT d.*,ps.nama as prodi_nama,ps.kode as prodi_kode FROM dosen d JOIN program_studi ps ON d.prodi_id=ps.id WHERE ".implode(' AND ',$w)." ORDER BY d.nama LIMIT ? OFFSET ?");
        $p[]=$pp;$p[]=$off; $s->execute($p); return $s->fetchAll();
    }
    public function countAll(string $q='', string $prodi=''): int {
        $like="%$q%"; $w=["(d.nidn LIKE ? OR d.nama LIKE ?)"]; $p=[$like,$like];
        if($prodi){$w[]="d.prodi_id=?";$p[]=$prodi;}
        $s=$this->db->prepare("SELECT COUNT(*) FROM dosen d WHERE ".implode(' AND ',$w)); $s->execute($p); return (int)$s->fetchColumn();
    }
    public function findById(int $id): ?array {
        $s=$this->db->prepare("SELECT d.*,ps.nama as prodi_nama FROM dosen d JOIN program_studi ps ON d.prodi_id=ps.id WHERE d.id=?");
        $s->execute([$id]); return $s->fetch()?:null;
    }
    public function findByUserId(int $uid): ?array {
        $s=$this->db->prepare("SELECT d.*,ps.nama as prodi_nama FROM dosen d JOIN program_studi ps ON d.prodi_id=ps.id WHERE d.user_id=?");
        $s->execute([$uid]); return $s->fetch()?:null;
    }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO dosen (user_id,prodi_id,nidn,nama,jenis_kelamin,foto) VALUES (?,?,?,?,?,?)");
        $s->execute([$d['user_id'],$d['prodi_id'],$d['nidn'],$d['nama'],$d['jenis_kelamin'],$d['foto']??null]);
        return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE dosen SET prodi_id=?,nidn=?,nama=?,jenis_kelamin=? WHERE id=?");
        return $s->execute([$d['prodi_id'],$d['nidn'],$d['nama'],$d['jenis_kelamin'],$id]);
    }
    public function updateFoto(int $id, string $f): bool {
        $s=$this->db->prepare("UPDATE dosen SET foto=? WHERE id=?"); return $s->execute([$f,$id]);
    }
    public function delete(int $id): bool { $s=$this->db->prepare("DELETE FROM dosen WHERE id=?"); return $s->execute([$id]); }
    public function isNidnExist(string $nidn, ?int $excl=null): bool {
        if($excl){$s=$this->db->prepare("SELECT COUNT(*) FROM dosen WHERE nidn=? AND id!=?");$s->execute([$nidn,$excl]);}
        else{$s=$this->db->prepare("SELECT COUNT(*) FROM dosen WHERE nidn=?");$s->execute([$nidn]);}
        return (int)$s->fetchColumn()>0;
    }
    public function getAllSimple(): array { return $this->db->query("SELECT * FROM dosen ORDER BY nama")->fetchAll(); }
    public function getTotalCount(): int { return (int)$this->db->query("SELECT COUNT(*) FROM dosen")->fetchColumn(); }
    public function hasRelasi(int $id): bool {
        $s=$this->db->prepare("SELECT COUNT(*) FROM kelas WHERE dosen_id=?"); $s->execute([$id]); return (int)$s->fetchColumn()>0;
    }
}
