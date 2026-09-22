<?php
class DashboardController {
    public function index(): void {
        SessionHelper::requireLogin();
        $role = SessionHelper::getRole();

        if ($role === 'admin') {
            $mhsM  = new Mahasiswa();
            $dsnM  = new Dosen();
            $mkM   = new MataKuliah();
            $jdwM  = new Jadwal();
            $presM = new Presensi();

            $data = [
                'total_mahasiswa' => $mhsM->getTotalCount(),
                'total_dosen'     => $dsnM->getTotalCount(),
                'total_mk'        => $mkM->getTotalCount(),
                'total_jadwal'    => $jdwM->getTotalCount(),
                'presensi_hari_ini' => $presM->countHariIni(),
            ];
            require_once ROOT_PATH.'/views/dashboard/admin.php';

        } elseif ($role === 'dosen') {
            $dosenId  = SessionHelper::getProfilId();
            $jadwalM  = new Jadwal();
            $presM    = new Presensi();
            $hariIni  = FormatHelper::hariIni();

            $jadwalHariIni = $jadwalM->getByDosen($dosenId, $hariIni);
            $presensiAktif = [];
            foreach ($jadwalHariIni as $j) {
                $p = $presM->getAktifByJadwal($j['id'], date('Y-m-d'));
                if ($p) $presensiAktif[] = array_merge($j, $p);
            }
            $semAktif = (new Semester())->getAktif();
            $kelasSaya = (new Kelas())->getByDosen($dosenId, $semAktif ? $semAktif['id'] : null);

            require_once ROOT_PATH.'/views/dashboard/dosen.php';

        } elseif ($role === 'mahasiswa') {
            $mhsId    = SessionHelper::getProfilId();
            if (!$mhsId) {
                die("<h1>Error</h1><p>Akun Mahasiswa Anda belum tertaut dengan Data Profil Mahasiswa. Silakan Logout dan hubungi Administrator.</p><a href='?page=auth&action=logout'>Logout</a>");
            }
            $jadwalM  = new Jadwal();
            $hariIni  = FormatHelper::hariIni();

            $jadwalHariIni = $jadwalM->getByMahasiswa($mhsId, $hariIni);
            $semAktif      = (new Semester())->getAktif();
            $rekap         = [];
            if ($semAktif) {
                $rekap = (new DetailPresensi())->getRekapBySemester($mhsId, $semAktif['id']);
            }
            require_once ROOT_PATH.'/views/dashboard/mahasiswa.php';
        }
    }
}
