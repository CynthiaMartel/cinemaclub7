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
    

    <!-- Post funciones JS -->
    <script src='../post/js/functions.post.js'></script>

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
<main class="main pt-4">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-9">
        <div class="row">
          <?php
          // Llamada a la función para obtener los datos de los posts
          $posts = Post::postList();

          if ($posts !== false) {
            $totalPosts = count($posts);
            $postsPerColumn = ceil($totalPosts / 3);

            // Dividir los posts en columnas de 2 en 2
            for ($i = 0; $i < 3; $i++) {
              echo '<div class="col-md-4">';
              for ($j = 0; $j < $postsPerColumn; $j++) {
                $index = $i * $postsPerColumn + $j;
                if ($index >= $totalPosts) break;
                $post = $posts[$index];
          ?>
                <article class="card mb-4">
                  <header class="card-header">
                    <!-- 
                    <div class="card-meta">
                      <a href="#"><time class="timeago" datetime="2021-09-26 20:00" timeago-id="<?php echo $index; ?>"></time></a> en 
                      <a href="page-category.html"><?php // echo sanitizarString($post['category']); ?></a> 
                    </div> 
                    -->
                    <a href="post-image.html">
                      <h4 class="card-title"><?php echo sanitizarString($post['title']); ?></h4>
                    </a>
                  </header>
                  <!-- 
                  <a href="post-image.html">
                    <img class="card-img" src="<?php // echo sanitizarString($post['imagePost']); ?>" alt="">
                  </a> 
                  -->
                  <div class="card-body">
                    <p class="card-text"><?php echo sanitizarString($post['content']); ?></p>
                  </div>
                </article>
          <?php
              } // Fin del inner loop
              echo '</div>'; // Fin de col-md-4
            } // Fin del outer loop
          } else {
            echo '<p>No hay posts disponibles.</p>';
          }
          ?>
        </div>
      </div>

      <!-- Aquí no se incluye la columna lateral derecha (col-md-3) -->
      
    </div>
  </div>
</main>




</body>
</html>




