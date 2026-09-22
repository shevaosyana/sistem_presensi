<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Presensi - <?= e($kelas['mk_nama']) ?> Kelas <?= e($kelas['nama']) ?></title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; color: #222; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
    .header h1 { font-size: 16px; text-transform: uppercase; }
    .header h2 { font-size: 13px; font-weight: normal; }
    .info-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
    .info-table td { padding: 3px 8px; }
    .info-table td:first-child { font-weight: bold; width: 150px; }
    table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table.data th, table.data td { border: 1px solid #999; padding: 5px 8px; text-align: center; font-size: 11px; }
    table.data th { background: #e8e8e8; font-weight: bold; }
    table.data td:nth-child(3) { text-align: left; }
    .footer { margin-top: 30px; text-align: right; }
    .status-aman { color: #065f46; font-weight: bold; }
    .status-peringatan { color: #92400e; font-weight: bold; }
    .status-bahaya { color: #991b1b; font-weight: bold; }
    @media print {
      body { padding: 5px; }
      .no-print { display: none; }
    }
  </style>
</head>
<body>
  <div class="no-print" style="margin-bottom:15px;">
    <button onclick="window.print()" style="padding:8px 20px; background:#0066cc; color:white; border:none; border-radius:5px; cursor:pointer; font-size:14px;">🖨️ Print / Cetak</button>
    <a href="javascript:window.close()" style="margin-left:10px; padding:8px 20px; background:#6b7280; color:white; border:none; border-radius:5px; cursor:pointer; font-size:14px; text-decoration:none;">✖ Tutup</a>
  </div>

  <div class="header">
    <h1><?= FAKULTAS_NAME ?></h1>
    <h2>LAPORAN REKAPITULASI PRESENSI MAHASISWA</h2>
  </div>

  <table class="info-table">
    <tr><td>Mata Kuliah</td><td>: <?= e($kelas['mk_nama']) ?> (<?= e($kelas['mk_kode']) ?>)</td></tr>
    <tr><td>Kelas</td><td>: <?= e($kelas['nama']) ?></td></tr>
    <tr><td>Dosen Pengampu</td><td>: <?= e($kelas['dosen_nama']) ?></td></tr>
    <tr><td>Semester / TA</td><td>: <?= e($kelas['nama_semester']) ?> / <?= e($kelas['ta_nama']) ?></td></tr>
    <tr><td>Tanggal Cetak</td><td>: <?= FormatHelper::tanggalIndo(date('Y-m-d'), true) ?></td></tr>
  </table>

  <table class="data">
    <thead>
      <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">NIM</th>
        <th rowspan="2">Nama Mahasiswa</th>
        <th colspan="5">Kehadiran</th>
        <th rowspan="2">Total</th>
        <th rowspan="2">%</th>
        <th rowspan="2">Status</th>
      </tr>
      <tr>
        <th>H</th>
        <th>T</th>
        <th>I</th>
        <th>S</th>
        <th>A</th>
      </tr>
    </thead>
    <tbody>
      <?php if(empty($rekap)): ?>
        <tr><td colspan="11" style="text-align:center; padding:15px; color:#888">Belum ada data presensi.</td></tr>
      <?php else: $no=1; foreach($rekap as $r):
        $persen = (float)$r['persen'];
        $statusLbl = ($persen >= 80) ? 'AMAN' : (($persen >= 60) ? 'PERINGATAN' : 'TDK BOLEH UAS');
        $statusClass = ($persen >= 80) ? 'status-aman' : (($persen >= 60) ? 'status-peringatan' : 'status-bahaya');
      ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= e($r['nim']) ?></td>
          <td style="text-align:left"><?= e($r['nama']) ?></td>
          <td><?= $r['hadir'] ?></td>
          <td><?= $r['terlambat'] ?></td>
          <td><?= $r['izin'] ?></td>
          <td><?= $r['sakit'] ?></td>
          <td><?= $r['alpha'] ?></td>
          <td><?= $r['total'] ?></td>
          <td><?= FormatHelper::persen($persen) ?></td>
          <td class="<?= $statusClass ?>"><?= $statusLbl ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>

  <div class="footer">
    <p>Diperiksa oleh,</p>
    <br><br><br>
    <p>( _________________________________ )</p>
    <p><?= e($kelas['dosen_nama']) ?></p>
  </div>
</body>
</html>
