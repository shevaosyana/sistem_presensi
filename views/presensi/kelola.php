<?php 
$pageTitle = 'Kelola Presensi';
require_once ROOT_PATH.'/views/layouts/header.php';
require_once ROOT_PATH.'/views/layouts/sidebar.php';
require_once ROOT_PATH.'/views/layouts/navbar.php';
?>
<div class="page-content">
  <div class="page-header">
    <div>
      <h1 class="page-title">Kelola Presensi</h1>
      <div class="breadcrumb">
        <a href="?page=dashboard">Dashboard</a> / <a href="?page=jadwal&action=saya">Jadwal Saya</a> / <a href="?page=presensi&jadwal_id=<?= $p['jadwal_id'] ?>">Presensi</a> / <span>Detail</span>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">Pertemuan Ke-<?= $p['pertemuan_ke'] ?></h5>
            <span class="text-muted"><?= FormatHelper::tanggalIndo($p['tanggal'], true) ?></span>
          </div>
          <div>
            <?php if($p['status'] === 'AKTIF'): ?>
              <span class="badge badge-success p-2" style="font-size:14px">Sesi Masih Aktif</span>
            <?php else: ?>
              <span class="badge badge-secondary p-2" style="font-size:14px">Sesi Selesai</span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Daftar Kehadiran Mahasiswa</h3>
        </div>
        <div class="table-wrapper">
          <table class="table">
            <thead>
              <tr>
                <th class="table-no">No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Jam Absen</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Ubah Manual</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($detail)): ?>
                <tr><td colspan="7" class="text-center text-muted">Belum ada data absensi. Data lengkap akan muncul setelah sesi DITUTUP.</td></tr>
              <?php else: $no=1; foreach($detail as $d): ?>
                <tr>
                  <td class="table-no"><?= $no++ ?></td>
                  <td class="fw-600"><?= e($d['nim']) ?></td>
                  <td><?= e($d['nama']) ?></td>
                  <td><?= $d['jam_presensi'] ? FormatHelper::jam($d['jam_presensi']) : '-' ?></td>
                  <td><?= FormatHelper::badgeStatus($d['status']) ?></td>
                  <td>
                    <small>
                      <?php 
                        if ($d['status'] === 'TERLAMBAT' && ($d['keterangan'] === 'Tepat Waktu' || empty($d['keterangan']))) {
                            echo 'Terlambat';
                        } elseif ($d['status'] === 'HADIR' && empty($d['keterangan'])) {
                            echo 'Tepat Waktu';
                        } else {
                            echo e($d['keterangan'] ?? '-');
                        }
                      ?>
                    </small>
                  </td>
                  <td>
                    <!-- Tombol Ubah Modal -->
                    <button type="button" class="btn btn-sm btn-warning" onclick="bukaModalUbah(<?= $d['id'] ?>, '<?= $d['status'] ?>', '<?= e($d['keterangan']) ?>')">Ubah</button>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Ubah Status -->
<div id="modalUbah" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
  <div style="background:#fff; max-width:400px; margin: 100px auto; border-radius:8px; overflow:hidden;">
    <div style="padding: 15px 20px; background:#f8f9fa; border-bottom:1px solid #ddd; d-flex; justify-content:space-between">
      <h5 style="margin:0">Ubah Status Kehadiran</h5>
    </div>
    <form action="?page=presensi&action=ubah" method="POST">
      <div style="padding: 20px;">
        <input type="hidden" name="presensi_id" value="<?= $p['id'] ?>">
        <input type="hidden" name="detail_id" id="ubah_detail_id">
        
        <div class="form-group mb-3">
          <label class="form-label">Status</label>
          <select name="status" id="ubah_status" class="form-control" required>
            <option value="HADIR">HADIR</option>
            <option value="TERLAMBAT">TERLAMBAT</option>
            <option value="IZIN">IZIN</option>
            <option value="SAKIT">SAKIT</option>
            <option value="ALPHA">ALPHA</option>
          </select>
        </div>
        
        <div class="form-group mb-0">
          <label class="form-label">Keterangan</label>
          <input type="text" name="keterangan" id="ubah_keterangan" class="form-control" placeholder="Opsional...">
        </div>
      </div>
      <div style="padding: 15px 20px; border-top:1px solid #ddd; text-align:right">
        <button type="button" class="btn btn-light" onclick="tutupModalUbah()">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
function bukaModalUbah(id, status, ket) {
  document.getElementById('ubah_detail_id').value = id;
  document.getElementById('ubah_status').value = status;
  document.getElementById('ubah_keterangan').value = ket;
  document.getElementById('modalUbah').style.display = 'block';
}
function tutupModalUbah() {
  document.getElementById('modalUbah').style.display = 'none';
}
</script>

<?php require_once ROOT_PATH.'/views/layouts/footer.php'; ?>
