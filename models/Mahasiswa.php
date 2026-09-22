<?php
class Mahasiswa {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(string $q='', string $prodi='', string $angk='', int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp; $like="%$q%";
        $w=["(m.nim LIKE ? OR m.nama LIKE ?)"]; $p=[$like,$like];
        if($prodi){$w[]="m.prodi_id=?";$p[]=$prodi;}
        if($angk){$w[]="m.angkatan=?";$p[]=$angk;}
        $s=$this->db->prepare("SELECT m.*,ps.nama as prodi_nama,ps.kode as prodi_kode FROM mahasiswa m JOIN program_studi ps ON m.prodi_id=ps.id WHERE ".implode(' AND ',$w)." ORDER BY m.nama LIMIT ? OFFSET ?");
        $p[]=$pp;$p[]=$off; $s->execute($p); return $s->fetchAll();
    }
    public function countAll(string $q='', string $prodi='', string $angk=''): int {
        $like="%$q%"; $w=["(m.nim LIKE ? OR m.nama LIKE ?)"]; $p=[$like,$like];
        if($prodi){$w[]="m.prodi_id=?";$p[]=$prodi;}
        if($angk){$w[]="m.angkatan=?";$p[]=$angk;}
        $s=$this->db->prepare("SELECT COUNT(*) FROM mahasiswa m WHERE ".implode(' AND ',$w));
        $s->execute($p); return (int)$s->fetchColumn();
    }
    public function findById(int $id): ?array {
        $s=$this->db->prepare("SELECT m.*,ps.nama as prodi_nama FROM mahasiswa m JOIN program_studi ps ON m.prodi_id=ps.id WHERE m.id=?");
        $s->execute([$id]); return $s->fetch()?:null;
    }
    public function findByUserId(int $uid): ?array {
        $s=$this->db->prepare("SELECT m.*,ps.nama as prodi_nama FROM mahasiswa m JOIN program_studi ps ON m.prodi_id=ps.id WHERE m.user_id=?");
        $s->execute([$uid]); return $s->fetch()?:null;
    }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO mahasiswa (user_id,prodi_id,nim,nama,jenis_kelamin,angkatan,foto) VALUES (?,?,?,?,?,?,?)");
        $s->execute([$d['user_id'],$d['prodi_id'],$d['nim'],$d['nama'],$d['jenis_kelamin'],$d['angkatan'],$d['foto']??null]);
        return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE mahasiswa SET prodi_id=?,nim=?,nama=?,jenis_kelamin=?,angkatan=? WHERE id=?");
        return $s->execute([$d['prodi_id'],$d['nim'],$d['nama'],$d['jenis_kelamin'],$d['angkatan'],$id]);
    }
    public function updateFoto(int $id, string $f): bool {
        $s=$this->db->prepare("UPDATE mahasiswa SET foto=? WHERE id=?"); return $s->execute([$f,$id]);
    }
    public function delete(int $id): bool {
        $s=$this->db->prepare("DELETE FROM mahasiswa WHERE id=?"); return $s->execute([$id]);
    }
    public function isNimExist(string $nim, ?int $excl=null): bool {
        if($excl){$s=$this->db->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim=? AND id!=?");$s->execute([$nim,$excl]);}
        else{$s=$this->db->prepare("SELECT COUNT(*) FROM mahasiswa WHERE nim=?");$s->execute([$nim]);}
        return (int)$s->fetchColumn()>0;
    }
    public function getAngkatanList(): array {
        return $this->db->query("SELECT DISTINCT angkatan FROM mahasiswa ORDER BY angkatan DESC")->fetchAll(PDO::FETCH_COLUMN);
    }
    public function getTotalCount(): int { return (int)$this->db->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn(); }
    public function hasRelasi(int $id): bool {
        $s=$this->db->prepare("SELECT COUNT(*) FROM detail_presensi WHERE mahasiswa_id=?"); $s->execute([$id]); return (int)$s->fetchColumn()>0;
    }
    public function getAllSimple(): array {
        return $this->db->query("SELECT id, nim, nama FROM mahasiswa ORDER BY nama")->fetchAll();
    }
}
