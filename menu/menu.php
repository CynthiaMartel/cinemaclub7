<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <!-- Logo (Izquierda) -->
        <a class="navbar-brand ms-4" href="<?php echo CONFIG_GENERAL['RUTA_URL_BASE']?>/index.php" style="position: relative; z-index: 1500;">
            <img src="<?php echo CONFIG_GENERAL['RUTA_URL_BASE']?>/img/logoCineClub7BloodVersion.png" alt="logoCinemaClub7 navbar" style="width: 150px; height:auto; position: relative; z-index: 1500;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse py-2" id="navbarSupportedContent">
            <!-- Menú de navegación -->
            <ul class="navbar-nav mt-4 me-4 mb-2 ms-4 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active fw-bold text-white" aria-current="page" href="<?php echo CONFIG_GENERAL['RUTA_URL_BASE']?>/index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-white" href="<?php echo CONFIG_GENERAL['RUTA_URL_BASE']?>/post/index.php">Noticias</a> <!--onclick="ContentEditorAccess();--->
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-white" href="<?php echo CONFIG_GENERAL['RUTA_URL_BASE']?>/film/index.php?film_id=69">Peliculas</a> <!--onclick="ContentEditorAccess();--->
                </li>
            </ul>

            <!-- Barra de búsqueda -->
            <form id="searchForm" class="d-flex align-items-center" method="GET" action="/cinemaclub7/films/searchResults.php">
                <input id="searchInput" class="form-control mb-1 me-2" type="search" name="query" placeholder="Buscar películas..." aria-label="Search">
                <button class="btn btn-success fw-bold" type="submit">Search</button>
            </form>

            <!-- Avatar del perfil del usuario, con cambiar contraseña y logout dentro -->
             <!-- Si el usuario sí está logueado, muestra el avatar con su perfil -->
            <?php if ($actualSession->read('id') != null) { ?>
                <ul class="navbar-nav mt-4 mb-2 ms-auto me-4 mb-lg-0"> 
                    <li class="nav-item">
                        <a class="nav-link active fw-bold text-white mt-4" aria-current="page" href=#><?php echo ucwords(strtolower($actualUser->getName())); ?></a>
                    </li>
                    <li class="nav-item dropdown mt-2">   
                        <a class="nav-link dropdown-toggle fw-bold text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo CONFIG_GENERAL['RUTA_URL_BASE']?>/img/myAccount-img-default.png" class="rounded-circle shadow-4"
                        style="width: 50px; height: 50px;" alt="Avatar" /></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item text-dark" href="<?php echo CONFIG_GENERAL['RUTA_URL_BASE']?>/index">Mi biblioteca</a></li>
                            <li><a class="dropdown-item text-dark" href="#">Mi info</a></li>
                            <li><a class="dropdown-item text-dark" href="#">Mi actividad reciente</a></li>
                            <li><a class="dropdown-item text-dark" href="<?php echo CONFIG_GENERAL['RUTA_URL_BASE']?>/changePassword.php">Cambiar contraseña</a></li>
                            <li><a class="dropdown-item fw-bold text-dark" href="<?php echo CONFIG_GENERAL['RUTA_URL_BASE']?>/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            <?php } else { ?>
                <!-- Si el usuario no está logueado, solo muestra la opción de Login -->
                <ul class="navbar-nav ms-auto me-4 mt-4">
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-white" href="#" onclick="openModalLogin(); return false;">Login</a>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>
</nav>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
