<?php
class Jadwal {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(string $q='', string $kid='', int $pg=1, int $pp=10): array {
        $off=($pg-1)*$pp; $like="%$q%";
        $w=["(mk.nama LIKE ? OR d.nama LIKE ? OR r.nama LIKE ?)"]; $p=[$like,$like,$like];
        if($kid){$w[]="j.kelas_id=?";$p[]=$kid;}
        $wstr=implode(' AND ',$w);
        $s=$this->db->prepare("SELECT j.*,k.nama as kelas_nama,mk.nama as mk_nama,mk.kode as mk_kode,d.nama as dosen_nama,r.nama as ruangan_nama,r.kode as ruangan_kode,s.nama_semester,ta.nama as ta_nama FROM jadwal j JOIN kelas k ON j.kelas_id=k.id JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id JOIN ruangan r ON j.ruangan_id=r.id JOIN semester s ON k.semester_id=s.id JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id WHERE $wstr ORDER BY FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),j.jam_mulai LIMIT ? OFFSET ?");
        $p[]=$pp;$p[]=$off; $s->execute($p); return $s->fetchAll();
    }
    public function countAll(string $q='', string $kid=''): int {
        $like="%$q%"; $w=["(mk.nama LIKE ? OR d.nama LIKE ? OR r.nama LIKE ?)"]; $p=[$like,$like,$like];
        if($kid){$w[]="j.kelas_id=?";$p[]=$kid;}
        $s=$this->db->prepare("SELECT COUNT(*) FROM jadwal j JOIN kelas k ON j.kelas_id=k.id JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id JOIN ruangan r ON j.ruangan_id=r.id WHERE ".implode(' AND ',$w));
        $s->execute($p); return (int)$s->fetchColumn();
    }
    public function findById(int $id): ?array {
        $s=$this->db->prepare("SELECT j.*,k.nama as kelas_nama,mk.nama as mk_nama,mk.kode as mk_kode,mk.sks,mk.prodi_id,d.nama as dosen_nama,d.id as dosen_id,r.nama as ruangan_nama,r.kode as ruangan_kode,s.nama_semester,ta.nama as ta_nama,k.mata_kuliah_id,k.semester_id FROM jadwal j JOIN kelas k ON j.kelas_id=k.id JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id JOIN ruangan r ON j.ruangan_id=r.id JOIN semester s ON k.semester_id=s.id JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id WHERE j.id=?");
        $s->execute([$id]); return $s->fetch()?:null;
    }
    public function getByDosen(int $did, string $hari=''): array {
        $p=[$did]; $w="k.dosen_id=?";
        if($hari){$w.=" AND j.hari=?";$p[]=$hari;}
        $s=$this->db->prepare("SELECT j.*,k.nama as kelas_nama,mk.nama as mk_nama,mk.kode as mk_kode,mk.sks,r.nama as ruangan_nama,r.kode as ruangan_kode,s.nama_semester FROM jadwal j JOIN kelas k ON j.kelas_id=k.id JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN ruangan r ON j.ruangan_id=r.id JOIN semester s ON k.semester_id=s.id WHERE $w ORDER BY FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),j.jam_mulai");
        $s->execute($p); return $s->fetchAll();
    }
    public function getByMahasiswa(int $mid, string $hari=''): array {
        $p=[$mid]; $w="ka.mahasiswa_id=?";
        if($hari){$w.=" AND j.hari=?";$p[]=$hari;}
        $s=$this->db->prepare("SELECT j.*,k.nama as kelas_nama,mk.nama as mk_nama,mk.kode as mk_kode,mk.sks,d.nama as dosen_nama,r.nama as ruangan_nama,r.kode as ruangan_kode FROM jadwal j JOIN kelas k ON j.kelas_id=k.id JOIN kelas_anggota ka ON k.id=ka.kelas_id JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN dosen d ON k.dosen_id=d.id JOIN ruangan r ON j.ruangan_id=r.id WHERE $w ORDER BY FIELD(j.hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),j.jam_mulai");
        $s->execute($p); return $s->fetchAll();
    }
    public function create(array $d): int {
        $s=$this->db->prepare("INSERT INTO jadwal (kelas_id,ruangan_id,hari,jam_mulai,jam_selesai,toleransi_menit) VALUES (?,?,?,?,?,?)");
        $s->execute([$d['kelas_id'],$d['ruangan_id'],$d['hari'],$d['jam_mulai'],$d['jam_selesai'],$d['toleransi_menit']??15]);
        return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $s=$this->db->prepare("UPDATE jadwal SET kelas_id=?,ruangan_id=?,hari=?,jam_mulai=?,jam_selesai=?,toleransi_menit=? WHERE id=?");
        return $s->execute([$d['kelas_id'],$d['ruangan_id'],$d['hari'],$d['jam_mulai'],$d['jam_selesai'],$d['toleransi_menit']??15,$id]);
    }
    public function delete(int $id): bool { $s=$this->db->prepare("DELETE FROM jadwal WHERE id=?"); return $s->execute([$id]); }
    public function getTotalCount(): int { return (int)$this->db->query("SELECT COUNT(*) FROM jadwal")->fetchColumn(); }
}
