<?php
session_start();

require 'databse.php';
if (isset($_SESSION['user_id'])) {
  $records = $conn->prepare('SELECT idusers, name, email, password, user_ids, saldo FROM users WHERE idusers = :id');
  $records->bindParam(':id', $_SESSION['user_id']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  $user = null;

  if (count($results) > 0) {
    $user = $results;
  }
}





?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Dashboard</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">
    <script src="JsBarcode.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    


</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="has-sub">
                            <a class="js-arrow" href="index3.php">
                                <i class="fas fa-tachometer-alt"></i>Saldo</a>
                        </li>
                        <li>
                            <a href="codigoBarras.php">
                                <i class="fas fa-chart-bar"></i>Codigo de Barras</a>
                        </li>
                        <li>
                            <a href="historialPagos.php">
                                <i class="fas fa-table"></i>Historial de pagos</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li class="active has-sub">
                            <a class="js-arrow" href="index3.php">
                                <i class="fas fa-tachometer-alt"></i>Saldo</a>
                        </li>
                        <li>
                            <a href="codigoBarras.php">
                                <i class="fas fa-chart-bar"></i>Código de Barras</a>
                        </li>
                        <li>
                            <a href="historialPagos.php">
                                <i class="fas fa-table"></i>Historial de Pagos</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            
                            <div class="header-button">
                                
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="images/usuario.png" alt="John Doe" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#"><?= $user['email'] ?></a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="images/usuario.png" alt="John Doe" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <span class="email"><?= $user['email'] ?></span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="logout.php">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                   <div class="section__content section__content--p30">


                    <!-- Agrega esta sección en tu HTML para mostrar el historial de pagos -->
                    <h2>Historial de Pagos</h2>
                    <table border="2">
                        <thead>
                            <tr>
                                <th>Fecha de Pago</th>
                                <th>Monto Pagado</th>
                            </tr>
                        </thead>
                        <tbody>

                            <style>
                            .pagination {
                              display: inline-block;
                            }

                            .pagination a {
                              color: black;
                              float: left;
                              padding: 8px 16px;
                              text-decoration: none;
                            }

                            .pagination a.active {
                              background-color: #0f0f0f ;
                              color: white;
                            }

                            .pagination a:hover:not(.active) {background-color: #ddd;}
                            </style>


                            <?php

                            $recordsPerPage = 10;
                            $user_ids = $user['user_ids'];


                            $stmt_count = $conn->prepare('SELECT COUNT(*) as total FROM historial_pagos WHERE user_ids = :user_ids');
                            $stmt_count->bindParam(':user_ids', $user_ids);
                            $stmt_count->execute();
                            $totalRecords = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];

                            // Calcula la cantidad total de páginas
                            $totalPages = ceil($totalRecords / $recordsPerPage);

                            // Obtiene el número de la página actual, si no se especifica toma la primera página
                            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

                            // Calcula el offset para la consulta SQL
                            $offset = ($current_page - 1) * $recordsPerPage;


                            // Recupera el historial de pagos para el usuario actual
                            $stmt_historial = $conn->prepare('SELECT fecha_pago, monto_pagado FROM historial_pagos WHERE user_ids = :user_ids ORDER BY fecha_pago DESC LIMIT :offset,
                                :limit');
                            $stmt_historial->bindParam(':user_ids', $user_ids);
                            $stmt_historial->bindParam(':offset', $offset, PDO::PARAM_INT);
                            $stmt_historial->bindParam(':limit', $recordsPerPage, PDO::PARAM_INT);
                            $stmt_historial->execute();
                            $result_historial = $stmt_historial->fetchAll(PDO::FETCH_ASSOC);

                            // Muestra los registros del historial en la tabla HTML
                            foreach ($result_historial as $pago) {
                                echo "<tr>";
                                echo "<td>" . $pago['fecha_pago'] . "</td>";
                                echo "<td>" . $pago['monto_pagado'] . "</td>";
                                echo "</tr>";
                            }





                            echo "<div class='pagination'>";
                            for ($i = 1; $i <= $totalPages; $i++) {
                                echo "<a 'margin=5px' href='historialPagos.php?page=$i'>$i</a> ";
                                  // Display previous and next links
                                
                            }
                            echo "</div>";

                            /*
                            echo "<div class='pagination'>";
                                echo "<a class='prev' href='historialPagos.php?page=" . ($current_page - 1) . "'>&#9665; Anterior</a> ";
                                echo "<a class='next' href='historialPagos.php?page=" . ($current_page + 1) . "'>Siguiente &#9655;</a>";
                            echo "</div>";
                            */


                            ?>



                            

                        </tbody>
                    </table>

                        
                    </div>
                      



                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->


    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->
