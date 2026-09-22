<?php
class TahunAkademik {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp;
        $s=$this->db->prepare("SELECT * FROM tahun_akademik ORDER BY tahun_mulai DESC LIMIT ? OFFSET ?");
        $s->execute([$pp,$off]); return $s->fetchAll();
    }
    public function countAll(): int { return (int)$this->db->query("SELECT COUNT(*) FROM tahun_akademik")->fetchColumn(); }
    public function getAllSimple(): array { return $this->db->query("SELECT * FROM tahun_akademik ORDER BY tahun_mulai DESC")->fetchAll(); }
    public function findById(int $id): ?array { $s=$this->db->prepare("SELECT * FROM tahun_akademik WHERE id=?"); $s->execute([$id]); return $s->fetch()?:null; }
    public function getAktif(): ?array { $s=$this->db->prepare("SELECT * FROM tahun_akademik WHERE status='aktif' LIMIT 1"); $s->execute([]); return $s->fetch()?:null; }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO tahun_akademik (nama,tahun_mulai,tahun_selesai,status) VALUES (?,?,?,'nonaktif')");
        $s->execute([$d['nama'],$d['tahun_mulai'],$d['tahun_selesai']]); return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE tahun_akademik SET nama=?,tahun_mulai=?,tahun_selesai=? WHERE id=?");
        return $s->execute([$d['nama'],$d['tahun_mulai'],$d['tahun_selesai'],$id]);
    }
    public function setAktif(int $id): bool {
        $this->db->exec("UPDATE tahun_akademik SET status='nonaktif'");
        $s=$this->db->prepare("UPDATE tahun_akademik SET status='aktif' WHERE id=?"); return $s->execute([$id]);
    }
    public function delete(int $id): bool { $s=$this->db->prepare("DELETE FROM tahun_akademik WHERE id=?"); return $s->execute([$id]); }
    public function isNamaExist(string $n, ?int $excl=null): bool {
        if($excl){$s=$this->db->prepare("SELECT COUNT(*) FROM tahun_akademik WHERE nama=? AND id!=?");$s->execute([$n,$excl]);}
        else{$s=$this->db->prepare("SELECT COUNT(*) FROM tahun_akademik WHERE nama=?");$s->execute([$n]);}
        return (int)$s->fetchColumn()>0;
    }
    public function hasRelasi(int $id): bool { $s=$this->db->prepare("SELECT COUNT(*) FROM semester WHERE tahun_akademik_id=?"); $s->execute([$id]); return (int)$s->fetchColumn()>0; }
}

class Semester {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(string $taId='', int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp; $w=[]; $p=[];
        if($taId){$w[]="s.tahun_akademik_id=?";$p[]=$taId;}
        $wstr=$w?"WHERE ".implode(' AND ',$w):"";
        $s=$this->db->prepare("SELECT s.*,ta.nama as ta_nama FROM semester s JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id $wstr ORDER BY ta.tahun_mulai DESC,s.id LIMIT ? OFFSET ?");
        $p[]=$pp;$p[]=$off; $s->execute($p); return $s->fetchAll();
    }
    public function countAll(string $taId=''): int {
        $w=[]; $p=[]; if($taId){$w[]="tahun_akademik_id=?";$p[]=$taId;}
        $wstr=$w?"WHERE ".implode(' AND ',$w):"";
        $s=$this->db->prepare("SELECT COUNT(*) FROM semester $wstr"); $s->execute($p); return (int)$s->fetchColumn();
    }
    public function getAllSimple(): array {
        return $this->db->query("SELECT s.*,ta.nama as ta_nama FROM semester s JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id ORDER BY ta.tahun_mulai DESC,s.id")->fetchAll();
    }
    public function findById(int $id): ?array {
        $s=$this->db->prepare("SELECT s.*,ta.nama as ta_nama FROM semester s JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id WHERE s.id=?");
        $s->execute([$id]); return $s->fetch()?:null;
    }
    public function getAktif(): ?array {
        $s=$this->db->prepare("SELECT s.*,ta.nama as ta_nama FROM semester s JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id WHERE s.is_aktif=1 LIMIT 1");
        $s->execute([]); return $s->fetch()?:null;
    }
    public function getByTA(int $taId): array { $s=$this->db->prepare("SELECT * FROM semester WHERE tahun_akademik_id=? ORDER BY id"); $s->execute([$taId]); return $s->fetchAll(); }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO semester (tahun_akademik_id,nama_semester,is_aktif) VALUES (?,?,0)");
        $s->execute([$d['tahun_akademik_id'],$d['nama_semester']]); return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE semester SET tahun_akademik_id=?,nama_semester=? WHERE id=?");
        return $s->execute([$d['tahun_akademik_id'],$d['nama_semester'],$id]);
    }
    public function setAktif(int $id): bool {
        $this->db->exec("UPDATE semester SET is_aktif=0");
        $s=$this->db->prepare("UPDATE semester SET is_aktif=1 WHERE id=?"); return $s->execute([$id]);
    }
    public function delete(int $id): bool { $s=$this->db->prepare("DELETE FROM semester WHERE id=?"); return $s->execute([$id]); }
    public function hasRelasi(int $id): bool { $s=$this->db->prepare("SELECT COUNT(*) FROM kelas WHERE semester_id=?"); $s->execute([$id]); return (int)$s->fetchColumn()>0; }
    public function isExist(int $taId, string $nm, ?int $excl=null): bool {
        if($excl){$s=$this->db->prepare("SELECT COUNT(*) FROM semester WHERE tahun_akademik_id=? AND nama_semester=? AND id!=?");$s->execute([$taId,$nm,$excl]);}
        else{$s=$this->db->prepare("SELECT COUNT(*) FROM semester WHERE tahun_akademik_id=? AND nama_semester=?");$s->execute([$taId,$nm]);}
        return (int)$s->fetchColumn()>0;
    }
}
