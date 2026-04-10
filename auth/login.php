<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — E-Parkir</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .login-wrap {
    display: flex;
    width: 820px;
    max-width: 95vw;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,.10);
  }

  .login-left {
    flex: 1;
    background: linear-gradient(145deg, #1d4ed8, #3b82f6);
    padding: 48px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    color: #fff;
  }
  .login-left .brand {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 32px;
  }
  .login-left h2 { font-size: 26px; font-weight: 700; line-height: 1.3; }
  .login-left p  { font-size: 14px; opacity: .75; margin-top: 10px; line-height: 1.6; }
  .feature { display: flex; align-items: center; gap: 10px; margin-top: 20px; font-size: 13.5px; opacity: .85; }
  .feature i { width: 18px; }

  .login-right {
    width: 360px;
    padding: 48px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .login-right h3 { font-size: 20px; font-weight: 700; color: #0f172a; }
  .login-right p  { font-size: 13px; color: #94a3b8; margin-top: 4px; margin-bottom: 28px; }

  .form-group { margin-bottom: 16px; }
  label { font-size: 13px; font-weight: 500; color: #475569; display: block; margin-bottom: 6px; }

  .input-wrap { position: relative; }
  .input-wrap i.icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 14px;
  }
  input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 13.5px;
    font-family: 'Inter', sans-serif;
    color: #1e293b;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
  }
  input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); }
  input.invalid { border-color: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239,68,68,.12) !important; }

  .toggle-pw {
    position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-size: 13px; cursor: pointer; background: none; border: none;
  }

  .field-error {
    display: none;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #ef4444;
    margin-top: 6px;
  }
  .field-error.show { display: flex; }

  .alert-error {
    background: #fef2f2;
    color: #dc2626;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .btn-login {
    width: 100%;
    padding: 11px;
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    margin-top: 8px;
    transition: background .15s;
  }
  .btn-login:hover { background: #2563eb; }

  @media (max-width: 640px) {
    .login-left { display: none; }
    .login-right { width: 100%; }
  }
</style>
</head>
<body>

<div class="login-wrap">
  <div class="login-left">
    <div class="brand">
      <i class="fa-solid fa-square-parking"></i> E-Parkir
    </div>
    <h2>Sistem Manajemen Parkir Digital</h2>
    <p>Kelola area parkir, transaksi, dan laporan dengan mudah dalam satu platform.</p>
    <div class="feature"><i class="fa-solid fa-check-circle"></i> Monitoring real-time</div>
    <div class="feature"><i class="fa-solid fa-check-circle"></i> Laporan otomatis</div>
    <div class="feature"><i class="fa-solid fa-check-circle"></i> Multi-role pengguna</div>
  </div>

  <div class="login-right">
    <h3>Selamat Datang</h3>
    <p>Masuk ke akun Anda untuk melanjutkan</p>

    <?php if(isset($_SESSION['error'])): ?>
    <div class="alert-error">
      <i class="fa-solid fa-circle-exclamation"></i>
      <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="proses_login.php" id="loginForm">
      <div class="form-group">
        <label>Username</label>
        <div class="input-wrap">
          <i class="fa-solid fa-user icon"></i>
          <input type="text" name="username" id="username" placeholder="Masukkan username" required>
        </div>
        <div class="field-error" id="username-error">
          <i class="fa-solid fa-circle-exclamation"></i> Username tidak boleh kosong.
        </div>
      </div>
      <div class="form-group">
        <label>Password</label>
        <div class="input-wrap">
          <i class="fa-solid fa-lock icon"></i>
          <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required>
          <button type="button" class="toggle-pw" onclick="togglePw(this)">
            <i class="fa-solid fa-eye"></i>
          </button>
        </div>
        <div class="field-error" id="pw-error">
          <i class="fa-solid fa-circle-exclamation"></i> Password minimal 8 karakter.
        </div>
      </div>
      <button type="submit" class="btn-login">Masuk</button>
    </form>
  </div>
</div>

<script>
function togglePw(btn) {
  const input = document.getElementById('password');
  const icon  = btn.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'fa-solid fa-eye-slash';
  } else {
    input.type = 'password';
    icon.className = 'fa-solid fa-eye';
  }
}

document.getElementById('loginForm').addEventListener('submit', function(e) {
  let valid = true;

  const username = document.getElementById('username');
  const pw       = document.getElementById('password');
  const unErr    = document.getElementById('username-error');
  const pwErr    = document.getElementById('pw-error');

  // reset
  username.classList.remove('invalid');
  pw.classList.remove('invalid');
  unErr.classList.remove('show');
  pwErr.classList.remove('show');

  if (username.value.trim() === '') {
    username.classList.add('invalid');
    unErr.classList.add('show');
    valid = false;
  }

  if (pw.value.length < 8) {
    pw.classList.add('invalid');
    pwErr.classList.add('show');
    valid = false;
  }

  if (!valid) e.preventDefault();
});

// hapus error saat user mulai mengetik
document.getElementById('username').addEventListener('input', function() {
  this.classList.remove('invalid');
  document.getElementById('username-error').classList.remove('show');
});
document.getElementById('password').addEventListener('input', function() {
  this.classList.remove('invalid');
  document.getElementById('pw-error').classList.remove('show');
});
</script>

</body>
</html>
