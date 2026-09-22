<?php
/**
 * Entry Point & Router — Sistem Informasi Presensi Perkuliahan
 */
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/config/database.php';
require_once __DIR__.'/helpers/SessionHelper.php';
require_once __DIR__.'/helpers/ValidationHelper.php';
require_once __DIR__.'/helpers/FormatHelper.php';

// Models
foreach (['User','Mahasiswa','Dosen','ProgramStudi','MataKuliah','Semester',
          'TahunAkademik','Kelas','KelasAnggota','Jadwal','Ruangan','Presensi','DetailPresensi'] as $m)
    require_once __DIR__."/models/$m.php";

// Controllers
foreach (['Auth','Dashboard','Mahasiswa','Dosen','Prodi','MataKuliah','Semester',
          'TahunAkademik','Kelas','Jadwal','Ruangan','Presensi','Rekap','Laporan','User'] as $c) {
    $file = __DIR__."/controllers/{$c}Controller.php";
    if (file_exists($file)) require_once $file;
}

SessionHelper::start();

$page   = $_GET['page']   ?? 'auth';
$action = $_GET['action'] ?? 'login';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Sudah login → redirect dari login page
if ($page==='auth' && $action==='login' && SessionHelper::isLoggedIn()) redirect('?page=dashboard');

switch ($page) {
    case 'auth':
        $c=new AuthController();
        match($action){ 'login'=>$c->login(),'proses'=>$c->proses(),'logout'=>$c->logout(),default=>$c->login() };
        break;
    case 'dashboard':
        (new DashboardController())->index(); break;
    case 'mahasiswa':
        $c=new MahasiswaController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),default=>$c->index()};
        break;
    case 'dosen':
        $c=new DosenController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),default=>$c->index()};
        break;
    case 'prodi':
        $c=new ProdiController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),default=>$c->index()};
        break;
    case 'matakuliah':
        $c=new MataKuliahController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),default=>$c->index()};
        break;
    case 'semester':
        $c=new SemesterController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),
            'set_aktif'=>$c->setAktif($id),default=>$c->index()};
        break;
    case 'tahunakademik':
        $c=new TahunAkademikController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),
            'set_aktif'=>$c->setAktif($id),default=>$c->index()};
        break;
    case 'kelas':
        $c=new KelasController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),
            'anggota'=> $c->anggota($id),
        'tambah_anggota'=> $c->tambah_anggota($id),
        'hapus_anggota'=> $c->hapus_anggota($id),default=>$c->index()};
        break;
    case 'jadwal':
        $c=new JadwalController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),
            'saya'=>$c->jadwalSaya(),default=>$c->index()};
        break;
    case 'ruangan':
        $c=new RuanganController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),default=>$c->index()};
        break;
    case 'presensi':
        $c=new PresensiController();
        match($action){'index'=>$c->index(),'buka'=>$c->buka($id),'tutup'=>$c->tutup($id),
            'submit'=>$c->submit(),'kelola'=>$c->kelola($id),'ubah'=>$c->ubahStatus(),
            'auto'=>$c->autoClose(),default=>$c->index()};
        break;
    case 'rekap':
        $c=new RekapController();
        match($action){'index'=>$c->index(),'detail'=>$c->detail(),default=>$c->index()};
        break;
    case 'laporan':
        $c=new LaporanController();
        match($action){'index'=>$c->index(),'cetak'=>$c->cetak(),'pdf'=>$c->pdf(),default=>$c->index()};
        break;
    case 'user':
        $c=new UserController();
        match($action){'index'=>$c->index(),'create'=>$c->create(),'store'=>$c->store(),
            'edit'=>$c->edit($id),'update'=>$c->update($id),'delete'=>$c->delete($id),
            'toggle'=>$c->toggle($id),'profil'=>$c->profil(),'update_profil'=>$c->updateProfil(),
            default=>$c->index()};
        break;
    default:
        SessionHelper::isLoggedIn() ? redirect('?page=dashboard') : redirect('?page=auth&action=login');
}
