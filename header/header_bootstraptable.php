<?php
require_once(__DIR__.'/../config/config.globales.php');
?>

    <style>
        .tablaListado {
            font-size: 16px;
        }

        .tablaListado tbody tr td {
            height: auto;
            padding: 0.20rem 0.20rem;
        }

        .tabladoListado thead tr th {
            height: auto;
            padding: 0.15rem 0.15rem !important;
        }

        .tablaListado .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
    </style>

    <!-- Bootstrap Table -->
    <link rel="stylesheet" href="<?php echo CONFIG_GENERAL['RUTA_URL_BASE_LIB']?>/bootstraptable/bootstrap-table.min.css">
    <link rel="stylesheet" href="<?php echo CONFIG_GENERAL['RUTA_URL_BASE_LIB']?>/bootstraptable/bootstrap-icons.css">
    