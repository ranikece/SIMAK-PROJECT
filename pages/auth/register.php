<section class="auth-portal">
<div class="auth-hero-panel">
<div class="brand auth-brand">
<span class="brand-logo">
<img src="assets/logo-simak.jpg" alt="Logo SIMAK">
</span>
<span class="brand-copy">
<strong>SIMAK</strong>
<span>Sistem Informasi Manajemen Klinik</span>
</span>
</div>
<h1>Buat akun untuk mengakses SIMAK.</h1>
<div class="auth-feature-list">
</div>
</div>
<div class="card auth-card wide">
<h2>Registrasi Akun</h2>
<p class="muted">Buat akun baru untuk mengakses SIMAK.</p>
<form method="post" action="actions/register.php" class="form-grid auth-form-grid">
<div class="form-row">
<label>Nama Lengkap</label>
<input name="nama" required placeholder="Nama pengguna">
</div>
<div class="form-row">
<label>Username</label>
<input name="username" required autocomplete="username" placeholder="Contoh: gojosatoru">
</div>
<div class="form-row">
<label>Password</label>
<input type="password" name="password" required autocomplete="new-password" minlength="5" placeholder="Minimal 5 karakter">
</div>
<div class="form-row">
<label>Konfirmasi Password</label>
<input type="password" name="password_confirm" required autocomplete="new-password" minlength="5" placeholder="Ulangi password">
</div>
<div class="form-row full">
<label>Role Akun</label>
<select name="role" required>
<option value="user">Pasien/User</option>
<option value="resepsionis">Resepsionis</option>
<option value="admin">Admin</option>
</select>
</div>
<div class="form-row full">
<button class="btn full-btn" type="submit">Daftar Akun</button>
</div>
</form>
<p class="muted small">Sudah punya akun? <a class="text-link" href="index.php?page=login">Login di sini</a>.</p>
</div>
</section>
