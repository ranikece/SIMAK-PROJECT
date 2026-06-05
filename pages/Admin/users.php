<?php
$rows = queryAll('SELECT * FROM users ORDER BY id_user DESC');
?>
<div class="page-head">
<div>
<h2>User Sistem</h2>
<p>Admin mengelola akun login untuk tiga role: Pasien/User, Resepsionis, dan Admin. Semua tetap memakai satu tabel <code>users</code> dalam satu database.</p>
</div>
</div>
<section class="card">
<h3>Tambah User</h3>
<form method="post" action="actions/save_user.php" class="form-grid">
<div class="form-row">
<label>Nama</label>
<input name="nama" required>
</div>
<div class="form-row">
<label>Username</label>
<input name="username" required>
</div>
<div class="form-row">
<label>Password</label>
<input type="password" name="password" required>
</div>
<div class="form-row">
<label>Role</label>
<select name="role">
<option value="user">Pasien/User</option>
<option value="resepsionis">Resepsionis</option>
<option value="admin">Admin</option>
</select>
</div>
<div class="form-row">
<label>Status</label>
<select name="status">
<option value="aktif">Aktif</option>
<option value="nonaktif">Nonaktif</option>
</select>
</div>
<div class="form-row full">
<button type="submit">Simpan User</button>
</div>
</form>
</section>
<section class="card">
<h3>Daftar User</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Nama</th>
<th>Username</th>
<th>Role</th>
<th>Status</th>
<th>Dibuat</th>
</tr>
</thead>
<tbody>
<?php
foreach ($rows as $r):
?>
<tr>
<td>
<?= e($r['nama']) ?>
</td>
<td>
<b>
<?= e($r['username']) ?>
</b>
</td>
<td>
<span class="badge dark">
<?= e(roleLabel($r['role'])) ?>
</span>
</td>
<td>
<span class="badge
<?= $r['status'] === 'aktif' ? 'success' : 'danger' ?>
">
<?= e($r['status']) ?>
</span>
</td>
<td>
<?= e($r['created_at']) ?>
</td>
</tr>
<?php
endforeach;
?>
<?php
if (!$rows):
?>
<tr>
<td colspan="5">Belum ada user.</td>
</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
</section>
