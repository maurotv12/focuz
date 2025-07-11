<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top shadow-sm">
  <div class="container-fluid d-flex justify-content-between align-items-center">

    <?php
    // Detectar si la URL es un perfil tipo /@username
    $isProfileUrl = false;
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    if (preg_match('#^/@[a-zA-Z0-9_.-]+$#', $requestUri)) {
      $isProfileUrl = true;
    }
    ?>

    <?php if (!isset($_SESSION['user']) && $isProfileUrl): ?>
      <div>
        <a href="/login" class="btn btn1 btn-outline-primary me-2">Iniciar sesión</a>
        <a href="/register" class="btn btn1 btn-primary">Registrarse</a>
      </div>
    <?php else: ?>

      <!-- Botón del Side Panel  y logo-->
      <?php if (isset($_SESSION['user'])): ?>
        <div class="d-flex align-items-center flex-shrink-0">
          <button class="btn btn1 me-3 d-none d-lg-flex align-items-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            ☰ <span class="ms-2">Menú</span>
          </button>
          <button class="btn btn1 me-3 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            ☰
          </button>
          <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="/assets/images/logo.png" alt="Logo" class="logo-img">
          </a>
        </div>
      <?php endif; ?>

      <!-- Buscador centrado -->
      <?php if (isset($_SESSION['user'])): ?>
        <div class="flex-grow-1 d-flex justify-content-center align-items-center">
          <div class="position-relative" style="width: 100%; max-width: 350px;">
            <form class="d-flex w-100" role="search" onsubmit="return false;">
              <input id="search-input" class="form-control text-center rounded-pill" type="search" placeholder="Buscar usuarios..." aria-label="Search" oninput="searchUsers(this.value)">

            </form>
            <ul id="search-results" class="list-group position-absolute d-none border-0 search-result-item" style="z-index: 1050; width:100%;">
              <!-- Resultados de búsqueda aparecerán aquí en Js-->
            </ul>
          </div>
        </div>
      <?php endif; ?>

      <!-- Dropdown de Notificaciones y Foto de Perfil -->
      <?php if (isset($_SESSION['user'])): ?>
        <div class="d-flex align-items-center flex-shrink-0">
          <!-- Notificaciones -->
          <div class="dropdown me-3">
            <button class="btn btn1 position-relative" style="width: 40px; height: 40px; object-fit: cover;" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-bell"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount">
                0
              </span>
            </button>

            <ul
              class="dropdown-menu dropdown-menu-end notifications-dropdown"
              aria-labelledby="notificationDropdown"
              style="max-height: 300px; overflow-y: auto;">
              <li
                class="dropdown-item text-center text-000000muted"
                id="noNotifications">No hay notificaciones
              </li>
            </ul>
          </div>
          <!-- Foto de perfil -->
          <a class="nav-link p-0 d-flex align-items-center" href="/@<?= $_SESSION['user']['username'] ?>">
            <img
              src="<?= file_exists(__DIR__ . '/../../../public/assets/images/profiles/' . $_SESSION['user']['id'] . '.' . $_SESSION['user']['profile_photo_type'])
                      ? '/assets/images/profiles/' . $_SESSION['user']['id'] . '.' . $_SESSION['user']['profile_photo_type']
                      : '/assets/images/user-default.png' ?>"
              alt="Perfil"
              class="rounded-circle profile-photo"
              style="width: 40px; height: 40px; object-fit: cover;">
            <!--mostrar el nombre del usuario opcional -->
            <!-- <span class="ms-2"><?= htmlspecialchars($_SESSION['user']['username']) ?></span> -->
          </a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</nav>

<!-- Incluir el Side Panel -->
<?php include __DIR__ . '/sidePanel.php'; ?>
<script src="/assets/js/search.js"></script>
<?php if (isset($_SESSION['user'])): ?>
  <script src="/assets/js/notifications.js"></script>
<?php endif; ?>