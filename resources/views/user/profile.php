<?php
ob_start();
$messages = [];

?>

<div class="container py-5">
  <div class="row align-items-center">
    <div class="col-md-4 text-center">
      <img
        src="<?= file_exists(__DIR__ . '/../../../public/assets/images/profiles/' . $user['id'] . '.' . $user['profile_photo_type'])
                ? '/assets/images/profiles/' . $user['id'] . '.' . $user['profile_photo_type']
                : '/assets/images/user-default.png' ?>"
        alt="Perfil"
        class="rounded-circle img-fluid profile-photo main-profile-photo"
        style="max-width: 200px; height: auto; aspect-ratio: 1 / 1; object-fit: cover;">
    </div>

    <div class="col-md-8">
      <div class="d-flex align-items-center mb-3">
        <h2 class="mb-0 me-3" style="font-size: clamp(1.2rem, 2.5vw, 2rem);"><?= $user['fullname'] ?></h2>
        <span class="username me-3">@<?php echo $user['username'] ?></span>
        <?php if (isset($_SESSION['user']) && $user['id'] == $_SESSION['user']['id'] ?? null): ?>
          <button id="edit-profile-btn" class="btn btn-outline-secondary btn-sm me-2">Editar perfil</button>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <div class="d-inline-block text-start me-4">
          <strong class="d-block"><?= $postCount ?></strong>
          <span>publicaciones</span>
        </div>
        <div class="d-inline-block text-start me-4">
          <strong id="followers-count" class="d-block"> <?= $followersCount ?></strong>
          <span>seguidores</span>
        </div>
        <div class="d-inline-block text-start me-4">
          <strong id="followed-count" class="d-block"><?= $followingCount ?></strong> seguidos</span>
        </div>

      </div>
      <span id="bio-text"><?php echo nl2br(htmlspecialchars($user['bio'] ?? '')); ?></span>

      <?php if (isset($_SESSION['user']) && $user['id'] !== $_SESSION['user']['id']): ?>
        <div class="mt-3">
          <!-- Botón Seguir/Dejar de seguir -->
          <button id="follow-btn"
            class="btn btn-sm me-2 <?= $isFollowing ? 'btn-primary' : 'btn-outline-primary' ?>"
            data-user-id="<?= $user['id'] ?>"
            <?php if ($publicView): ?>disabled<?php else: ?>onclick="toggleFollow(<?= $user['id'] ?>)" <?php endif; ?>>
            <?= $isFollowing ? 'Dejar de seguir' : 'Seguir' ?>
          </button>
          <!-- Botón de Mensaje -->
          <button id="message-btn" class="btn btn-primary btn-sm" data-user-id="<?= $user['id'] ?>" <?php if ($publicView): ?>disabled<?php endif; ?>>Mensaje</button>
        </div>
      <?php endif; ?>


      <?php if (isset($_SESSION['user']) && $user['id'] == ($_SESSION['user']['id'] ?? null)): ?>
        <form class="d-none" id="edit-profile-form" method="POST" action="/user/update-profile/<?= $user['id'] ?>" enctype="multipart/form-data">
          <div class="col-md-6">
            <label class="form-label" for="fullname">Nombre</label>
            <input type="text" id="fullname" class="form-control" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label" for="username">Nombre de Usuario</label>
            <input type="text" id="username" class="form-control" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
            <small class="form-text text-danger"></small>
          </div>
          <div class="col-md-12">
            <label class="form-label" for="profile_photo">Foto de perfil</label>
            <input type="file" id="profile_photo" class="form-control" name="profile_photo" accept="image/*">
          </div>
          <div class="bio mt-3">
            <label for="bio-textarea">Biografía</label>
            <textarea id="bio-textarea" name="bio" class="form-control"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
          </div>
          <button type="submit" id="save-changes-btn" class="btn btn-primary mt-3">Guardar cambios</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>



<?php include __DIR__ . '/../post/list.php'; ?>

<?php
// Guardar el contenido generado en $content
$content = ob_get_clean();

// Incluir el layout principal
include __DIR__ . '/../layout/main.php';
?>

<script src="/assets/js/edit-profile.js"></script>
<script src="/assets/js/likes.js"></script>
<script src="/assets/js/followers.js"></script>
