<?php
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('admin');

function logAktivitas($conn, $id_user, $aktivitas) {
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_user FROM user WHERE id_user='$id_user'"));
    if ($cek) {
        mysqli_query($conn, "INSERT INTO log_aktivitas (id_user, aktivitas, waktu_aktivitas) VALUES ('$id_user', '$aktivitas', NOW())");
    }
}

$error = null;

if (isset($_POST['simpan'])) {
    $id       = $_POST['id_user'];
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = $_POST['role'];

    $cekUsername = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT id_user FROM user WHERE username='$username' AND id_user!='$id'"));

    if ($cekUsername) {
        $error = "Username <strong>$username</strong> sudah digunakan, pilih username lain.";
    } elseif (strlen($_POST['password']) < 8) {
        $error = "Password minimal 8 karakter.";
    } elseif ($id == "") {
        mysqli_query($conn, "INSERT INTO user (nama_lengkap, username, password, role, status_aktif) VALUES ('$nama','$username','$password','$role',1)");
        logAktivitas($conn, $_SESSION['id_user'], "Menambah user $username");
        header("Location: user.php?sukses=tambah");
        exit;
    } else {
        mysqli_query($conn, "UPDATE user SET nama_lengkap='$nama', username='$username', password='$password', role='$role' WHERE id_user='$id'");
        logAktivitas($conn, $_SESSION['id_user'], "Mengubah user $username");
        header("Location: user.php?sukses=edit");
        exit;
    }
}
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM user WHERE id_user=$id");
    logAktivitas($conn, $_SESSION['id_user'], "Menghapus user ID $id");
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user=".(int)$_GET['edit']));
}

$data = mysqli_query($conn, "SELECT * FROM user ORDER BY id_user DESC");
$roleBadge = ['admin' => 'badge-red', 'petugas' => 'badge-blue', 'owner' => 'badge-green'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola User</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
</head>
<body>

<?php include "sidebar_admin.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Kelola User</h4>
    <p>Tambah, edit, dan hapus akun pengguna sistem</p>
  </div>

  <div class="card mb-4">
    <div class="card-title">
      <i class="fa-solid fa-<?= $edit ? 'pen' : 'plus' ?>"></i>
      <?= $edit ? 'Edit User' : 'Tambah User' ?>
    </div>
    <form method="POST">
      <?php if(isset($_GET['sukses'])): ?>
      <div class="alert-success"><i class="fa-solid fa-circle-check"></i>
        <?= $_GET['sukses'] == 'edit' ? 'User berhasil diperbarui.' : 'User berhasil ditambahkan.' ?>
      </div>
      <?php endif; ?>
      <?php if($error): ?>
      <div class="alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?= $error ?></div>
      <?php endif; ?>
      <input type="hidden" name="id_user" value="<?= $edit['id_user'] ?? '' ?>">
      <div class="row g-3">
        <div class="col-12 col-md-6">
          <label>Nama Lengkap</label>
          <input type="text" name="nama_lengkap" class="form-control" value="<?= $edit['nama_lengkap'] ?? '' ?>" required>
        </div>
        <div class="col-12 col-md-6">
          <label>Username</label>
          <input type="text" name="username" class="form-control" value="<?= $edit['username'] ?? '' ?>" required>
        </div>
        <div class="col-12 col-md-6">
          <label>Password</label>
          <input type="text" name="password" class="form-control" value="<?= $edit['password'] ?? '' ?>" required minlength="8" placeholder="Minimal 8 karakter">
        </div>
        <div class="col-12 col-md-6">
          <label>Role</label>
          <select name="role" class="form-select">
            <option value="admin"   <?= ($edit['role'] ?? '') == 'admin'   ? 'selected' : '' ?>>Admin</option>
            <option value="petugas" <?= ($edit['role'] ?? '') == 'petugas' ? 'selected' : '' ?>>Petugas</option>
            <option value="owner"   <?= ($edit['role'] ?? '') == 'owner'   ? 'selected' : '' ?>>Owner</option>
          </select>
        </div>
        <div class="col-12 d-flex gap-2">
          <button name="simpan" class="btn-primary-custom">Simpan</button>
          <?php if($edit): ?>
          <a href="user.php" class="btn-outline-custom">Batal</a>
          <?php endif; ?>
        </div>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="card-title"><i class="fa-solid fa-users"></i> Daftar User</div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Nama</th><th>Username</th><th>Role</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($data) == 0): ?>
          <tr>
            <td colspan="5" class="text-center" style="padding:32px;color:#94a3b8">
              <i class="fa-solid fa-users" style="font-size:24px;display:block;margin-bottom:8px"></i>
              Tidak ada data user
            </td>
          </tr>
          <?php else: while($d = mysqli_fetch_assoc($data)): $cls = $roleBadge[$d['role']] ?? 'badge-gray'; ?>
          <tr>
            <td><?= $d['id_user'] ?></td>
            <td><?= $d['nama_lengkap'] ?></td>
            <td><?= $d['username'] ?></td>
            <td><span class="badge <?= $cls ?>"><?= ucfirst($d['role']) ?></span></td>
            <td class="d-flex gap-2">
              <a href="?edit=<?= $d['id_user'] ?>" class="btn-warning-custom">
                <i class="fa-solid fa-pen"></i> Edit
              </a>
              <a href="?hapus=<?= $d['id_user'] ?>" class="btn-danger-custom"
                 onclick="return confirm('Hapus user ini?')">
                <i class="fa-solid fa-trash-can"></i> Hapus
              </a>
            </td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
