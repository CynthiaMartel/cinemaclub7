<?php
require_once dirname(__DIR__) . '/config/config.globales.php';
require_once dirname(__DIR__) . '/api/comprobar.sesion.php';
require_once dirname(__DIR__). '/modal.login.php';
require_once  dirname(__DIR__). '/class/class.User.php';

global $actualUser;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once dirname(__DIR__) .'/header/header.php'; ?>
        
    </head>

    <body>
    <?php require_once dirname(__DIR__) . '/menu/menu.php'; ?> 
    
    <!-- // Se cargar el modal de crear-editar Post si el usuario tiene id rol 1 (Admin) o id rol 2 (Editor). Si es usuario regular (id rol = 3, no se mostrará)-->
    <?php if ($actualUser->isAdminOrEditor()) : ?>
    <?php require_once dirname(__DIR__) . '/post/modal/create.editate.post.php'; ?>
    <?php endif; ?>

    <!-- Post funciones JS -->
    <script src='../post/js/functions.post.js'></script>

    <!-- // El usuario puede abrir el modal de crear Post si tiene id rol 1 (Admin) o id rol 2 (Editor). Si es usuario regular (id rol = 3, no se mostrará)-->
    <?php if ($actualUser->isAdminOrEditor()) : ?>
    <div class="row justify-content-center" id="add-post-container">
      <div class="col-12 col-md-4 text-center">
        <a href="#" class="btn btn-success mt-3" onclick="openModalPost(); return false;">¡Añadir Post!</a>
      </div>
    </div>
<?php endif; ?>


<div class="container-fluid">
  <div class="row">
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-4">
          <?php
          // Llamada a la función para obtener los datos de los posts
          /* $posts = postTableList(); 

          if ($posts !== false) {
              foreach ($posts['rows'] as $post) { */
          ?>
          
            <article class="card mb-4">
              <header class="card-header">
                <div class="card-meta">
                  <a href="#"><time class="timeago" datetime="2021-09-26 20:00" timeago-id="3">3 años atrás</time></a> en 
                  <a href="page-category.html"><?php echo $post['category']; ?></a> 
                </div>
                <a href="post-image.html">
                  <h4 class="card-title"><?php echo sanitizarString(trim($_POST["title"]));; ?></h4>
                </a>
              </header>
              <a href="post-image.html">
                <img class="card-img" src="<?php echo sanitizarString(trim($_POST["imagePost"]));; ?>" alt="">
              </a>
              <div class="card-body">
                <p class="card-text"><?php echo sanitizarString(trim($_POST["content"]));; ?></p> <!-- Similar con 'content', verifica que lo estés pasando desde la función -->
              </div>
            </article>
          
          <?php
           /*    } // Fin del ciclo foreach
          } else {
              echo '<p>No hay posts disponibles.</p>';
          } */
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once dirname(__DIR__) . '/api/api.post.php'; ?> 
</body>

</html>



