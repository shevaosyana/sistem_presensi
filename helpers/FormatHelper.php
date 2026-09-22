<?php
/** Format & Utility Helper */
class FormatHelper {
    public static function tanggalIndo(string $date, bool $withDay=false): string {
        $bulan=[1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        $hari=['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
               'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $ts=$date?strtotime($date):time();
        $str=(int)date('j',$ts).' '.($bulan[(int)date('n',$ts)]??'').' '.date('Y',$ts);
        return $withDay ? ($hari[date('l',$ts)]??'').', '.$str : $str;
    }
    public static function jam(string $t): string { return $t ? substr($t,0,5) : '-'; }
    public static function jamKuliah(string $a, string $b): string { return self::jam($a).' – '.self::jam($b); }
    public static function hariIni(): string {
        return ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][(int)date('w')];
    }
    public static function persen(float $v, int $d=1): string { return number_format($v,$d).'%'; }
    public static function badgeStatus(string $s): string {
        $m=['HADIR'=>['badge-success','Hadir'],'TERLAMBAT'=>['badge-warning','Terlambat'],
            'IZIN'=>['badge-info','Izin'],'SAKIT'=>['badge-secondary','Sakit'],'ALPHA'=>['badge-danger','Alpha']];
        [$cls,$lbl]=$m[$s]??['badge-secondary',$s];
        return "<span class=\"badge $cls\">$lbl</span>";
    }
    public static function persenClass(float $p): string {
        return $p>=80?'text-success':($p>=75?'text-warning':'text-danger');
    }
    public static function e(mixed $v): string { return htmlspecialchars((string)($v??''),ENT_QUOTES,'UTF-8'); }
}

function redirect(string $url): never {
    header('Location: ' . rtrim(BASE_URL, '/') . '/' . ltrim($url, '/'));
    exit;
}
function e(mixed $v): string { return FormatHelper::e($v); }
