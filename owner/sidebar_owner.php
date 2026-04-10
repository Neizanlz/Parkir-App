<?php $current = basename($_SERVER['PHP_SELF']); ?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">

<!-- Hamburger -->
<button class="hamburger" id="hamburger" onclick="toggleDropdown()">
  <i class="fa-solid fa-bars"></i>
</button>

<!-- Dropdown Menu (Mobile) -->
<div class="dropdown-menu" id="dropdownMenu">
  <a href="dashboard.php" class="<?= $current=='dashboard.php' ?'active':'' ?>">
    <i class="fa-solid fa-gauge-high"></i> Dashboard
  </a>
  <a href="rekap.php" class="<?= $current=='rekap.php' ?'active':'' ?>">
    <i class="fa-solid fa-file-lines"></i> Rekap Data
  </a>
  <a href="../auth/logout.php" class="logout">
    <i class="fa-solid fa-right-from-bracket"></i> Logout
  </a>
</div>

<!-- Desktop Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <i class="fa-solid fa-square-parking" style="color:#3b82f6"></i>
    E-Parkir <span>Owner</span>
  </div>

  <a href="dashboard.php" class="<?= $current=='dashboard.php' ?'active':'' ?>">
    <i class="fa-solid fa-gauge-high"></i> Dashboard
  </a>
  <a href="rekap.php" class="<?= $current=='rekap.php' ?'active':'' ?>">
    <i class="fa-solid fa-file-lines"></i> Rekap Data
  </a>

  <a href="../auth/logout.php" class="logout" style="margin-top:auto">
    <i class="fa-solid fa-right-from-bracket"></i> Logout
  </a>
</div>

<script>
function toggleDropdown() {
  const menu = document.getElementById('dropdownMenu');
  menu.classList.toggle('open');
  // Close ketika click di luar
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.hamburger') && !e.target.closest('.dropdown-menu')) {
      menu.classList.remove('open');
    }
  });
}
</script>
