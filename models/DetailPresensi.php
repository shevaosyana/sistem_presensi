<?php
class DetailPresensi {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getByPresensi(int $pid): array {
        $s=$this->db->prepare("SELECT dp.*,m.nim,m.nama,m.foto FROM detail_presensi dp JOIN mahasiswa m ON dp.mahasiswa_id=m.id WHERE dp.presensi_id=? ORDER BY m.nama");
        $s->execute([$pid]); return $s->fetchAll();
    }
    public function getByMahasiswaKelas(int $mid, int $kid): array {
        $s=$this->db->prepare("SELECT dp.*,p.tanggal,p.pertemuan_ke,j.hari,j.jam_mulai FROM detail_presensi dp JOIN presensi p ON dp.presensi_id=p.id JOIN jadwal j ON p.jadwal_id=j.id JOIN kelas k ON j.kelas_id=k.id WHERE dp.mahasiswa_id=? AND k.id=? ORDER BY p.tanggal DESC");
        $s->execute([$mid,$kid]); return $s->fetchAll();
    }
    public function cekSudahPresensi(int $pid, int $mid): ?array {
        $s=$this->db->prepare("SELECT * FROM detail_presensi WHERE presensi_id=? AND mahasiswa_id=? LIMIT 1");
        $s->execute([$pid,$mid]); return $s->fetch()?:null;
    }
    public function insert(int $pid, int $mid, string $status, ?string $jam=null, ?string $ket=null): bool {
        $s=$this->db->prepare("INSERT INTO detail_presensi (presensi_id,mahasiswa_id,status,jam_presensi,keterangan) VALUES (?,?,?,?,?)");
        return $s->execute([$pid,$mid,$status,$jam,$ket]);
    }
    public function insertAlpha(int $pid, int $mid): bool {
        $s=$this->db->prepare("INSERT IGNORE INTO detail_presensi (presensi_id,mahasiswa_id,status) VALUES (?,?,'ALPHA')");
        return $s->execute([$pid,$mid]);
    }
    public function updateStatus(int $id, string $status, ?string $ket=null): bool {
        $s=$this->db->prepare("UPDATE detail_presensi SET status=?,keterangan=? WHERE id=?");
        return $s->execute([$status,$ket,$id]);
    }
    public function getMahasiswaSudahPresensi(int $pid): array {
        $s=$this->db->prepare("SELECT mahasiswa_id FROM detail_presensi WHERE presensi_id=?");
        $s->execute([$pid]); return $s->fetchAll(PDO::FETCH_COLUMN);
    }
    public function getRekapByMahasiswaKelas(int $mid, int $kid): array {
        $s=$this->db->prepare("SELECT SUM(dp.status='HADIR') as hadir,SUM(dp.status='TERLAMBAT') as terlambat,SUM(dp.status='IZIN') as izin,SUM(dp.status='SAKIT') as sakit,SUM(dp.status='ALPHA') as alpha,COUNT(*) as total FROM detail_presensi dp JOIN presensi p ON dp.presensi_id=p.id JOIN jadwal j ON p.jadwal_id=j.id JOIN kelas k ON j.kelas_id=k.id WHERE dp.mahasiswa_id=? AND k.id=?");
        $s->execute([$mid,$kid]); return $s->fetch()?:[];
    }
    public function getRekapByKelas(int $kid): array {
        $s=$this->db->prepare("SELECT m.id as mahasiswa_id,m.nim,m.nama,SUM(dp.status='HADIR') as hadir,SUM(dp.status='TERLAMBAT') as terlambat,SUM(dp.status='IZIN') as izin,SUM(dp.status='SAKIT') as sakit,SUM(dp.status='ALPHA') as alpha,COUNT(dp.id) as total,ROUND((SUM(dp.status='HADIR')+SUM(dp.status='TERLAMBAT'))/NULLIF(COUNT(dp.id),0)*100,1) as persen,GROUP_CONCAT(dp.id ORDER BY dp.id) as detail_ids FROM mahasiswa m JOIN kelas_anggota ka ON m.id=ka.mahasiswa_id LEFT JOIN jadwal j ON j.kelas_id=ka.kelas_id LEFT JOIN presensi pr ON pr.jadwal_id=j.id LEFT JOIN detail_presensi dp ON dp.presensi_id=pr.id AND dp.mahasiswa_id=m.id WHERE ka.kelas_id=? GROUP BY m.id ORDER BY m.nama");
        $s->execute([$kid]); return $s->fetchAll();
    }
    public function getRekapBySemester(int $mid, int $semId): array {
        $s=$this->db->prepare("SELECT mk.kode,mk.nama as mk_nama,k.nama as kelas_nama,k.id as kelas_id,SUM(dp.status='HADIR') as hadir,SUM(dp.status='TERLAMBAT') as terlambat,SUM(dp.status='IZIN') as izin,SUM(dp.status='SAKIT') as sakit,SUM(dp.status='ALPHA') as alpha,COUNT(dp.id) as total,ROUND((SUM(dp.status='HADIR')+SUM(dp.status='TERLAMBAT'))/NULLIF(COUNT(dp.id),0)*100,1) as persen FROM kelas_anggota ka JOIN kelas k ON ka.kelas_id=k.id JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id LEFT JOIN jadwal j ON j.kelas_id=k.id LEFT JOIN presensi pr ON pr.jadwal_id=j.id LEFT JOIN detail_presensi dp ON dp.presensi_id=pr.id AND dp.mahasiswa_id=ka.mahasiswa_id WHERE ka.mahasiswa_id=? AND k.semester_id=? GROUP BY k.id ORDER BY mk.nama");
        $s->execute([$mid,$semId]); return $s->fetchAll();
    }
    public function getLaporanByFilter(array $f): array {
        $w=["1=1"]; $p=[];
        if(!empty($f['mahasiswa_id'])){$w[]="dp.mahasiswa_id=?";$p[]=$f['mahasiswa_id'];}
        if(!empty($f['kelas_id'])){$w[]="k.id=?";$p[]=$f['kelas_id'];}
        if(!empty($f['semester_id'])){$w[]="k.semester_id=?";$p[]=$f['semester_id'];}
        if(!empty($f['mk_id'])){$w[]="k.mata_kuliah_id=?";$p[]=$f['mk_id'];}
        if(!empty($f['tgl_dari'])){$w[]="pr.tanggal>=?";$p[]=$f['tgl_dari'];}
        if(!empty($f['tgl_sampai'])){$w[]="pr.tanggal<=?";$p[]=$f['tgl_sampai'];}
        $wstr=implode(' AND ',$w);
        $s=$this->db->prepare("SELECT m.nim,m.nama as nama_mahasiswa,mk.kode as kode_mk,mk.nama as nama_mk,k.nama as nama_kelas,s.nama_semester,ta.nama as tahun_akademik,SUM(dp.status='HADIR') as hadir,SUM(dp.status='TERLAMBAT') as terlambat,SUM(dp.status='IZIN') as izin,SUM(dp.status='SAKIT') as sakit,SUM(dp.status='ALPHA') as alpha,COUNT(dp.id) as total,ROUND((SUM(dp.status='HADIR')+SUM(dp.status='TERLAMBAT'))/NULLIF(COUNT(dp.id),0)*100,1) as persen FROM detail_presensi dp JOIN mahasiswa m ON dp.mahasiswa_id=m.id JOIN presensi pr ON dp.presensi_id=pr.id JOIN jadwal j ON pr.jadwal_id=j.id JOIN kelas k ON j.kelas_id=k.id JOIN mata_kuliah mk ON k.mata_kuliah_id=mk.id JOIN semester s ON k.semester_id=s.id JOIN tahun_akademik ta ON s.tahun_akademik_id=ta.id WHERE $wstr GROUP BY dp.mahasiswa_id,k.id ORDER BY m.nama,mk.nama");
        $s->execute($p); return $s->fetchAll();
    }
}
