<?php
require_once dirname(__DIR__) . '/config/config.globales.php';
require_once dirname(__DIR__) . '/api/comprobar.sesion.php';
require_once dirname(__DIR__). '/modal.login.php';
require_once  dirname(__DIR__). '/class/class.User.php';
require_once  dirname(__DIR__). '/class/class.post.php';


global $actualUser;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once dirname(__DIR__) .'/header/header.php'; ?>
        
    </head>

    <body>
    <?php require_once dirname(__DIR__) . '/menu/menu.php'; ?> 
  
    <!-- // Si el usuario está logueado se leerá actualUser, y se ejecuatará isAdminOrEditor. Sino está logueado, no se ejecutará esta función pero se mostrará el contenido de la página igualmente-->
    <!-- // El usuario puede abrir el modal de crear Post si tiene id rol 1 (Admin) o id rol 2 (Editor). Si es usuario regular (id rol = 3, no se mostrará)-->
    <?php if (isset($actualUser) && $actualUser->isAdminOrEditor()) { ?>
       <div class="row justify-content-center" id="add-post-container">
       <div class="col-12 col-md-4 text-center">
         <a href="#" class="btn btn-success mt-3" onclick="openModalPost(); return false;">¡Añadir Post!</a>
       </div>
     </div>

     <?php } ?>
  

<?php require_once dirname(__DIR__) . '/post/modal/create.editate.post.php'; ?>
<?php require_once dirname(__DIR__) . '/post/modal/create.editate.post.php'; ?>

<main class="main pt-4">
  <div class="container-fluid">
    <div class="row">

      <!-- Contenedor de ancho completo -->
      <div class="col-12">
        <div class="row">
          <?php
          // Recuperamos posts
          $posts = Post::postList() ?: [];

          // Preparamos cuatro columnas vacías
          $columns = [[], [], [], []];
          // Repartimos los posts en las cuatro columnas de forma intercalada
          foreach ($posts as $i => $post) {
            $columns[$i % 4][] = $post;
          }

          // Renderizamos cada columna
          for ($col = 0; $col < 4; $col++): ?>
            <div class="col-md-3">
              <?php foreach ($columns[$col] as $post):
                // URL de la imagen (fallback si no hay)
                $urlImg = !empty($post['img'])
                  ? $post['img']
                  : '../img/default_img_post.jpg';
              ?>
                <article class="card mb-4">
                  <header class="card-header">
                    <h4 class="card-title mb-0">
                      <?php echo sanitizarString($post['title']); ?>
                    </h4>
                  </header>
                  <a href="post.php?id=<?php echo $post['id']; ?>">
                    <img
                      class="card-img"
                      src="<?php echo htmlspecialchars($urlImg); ?>"
                      alt="Imagen del post">
                  </a>
                  <div class="card-body">
                    <p class="card-text">
                      <?php echo html_entity_decode(
                        $post['content'],
                        ENT_QUOTES|ENT_HTML5,
                        'UTF-8'
                      ); ?>
                    </p>

                    <?php if (isset($actualUser) && $actualUser->isAdminOrEditor()): ?>
                      <div class="d-flex justify-content-between gap-2 mt-3">
                        <button
                          class="btn btn-sm btn-warning w-50"
                          onclick="openModalPost(<?php echo $post['id']; ?>)">
                          <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <button
                          class="btn btn-sm btn-danger w-50"
                          onclick="confirmDeletePost(<?php echo $post['id']; ?>)">
                          <i class="bi bi-trash3"></i> Eliminar
                        </button>
                      </div>
                    <?php endif; ?>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endfor; ?>
        </div>
      </div>

    </div>
  </div>
</main>




  <!-- Post funciones JS -->
  <script src='../post/js/functions.post.js'></script>

</body>
</html>




