<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo COMPANY; ?>
	</title>

    <?php include "./Views/inc/Styles.php"; ?>
</head>
<body>
	<?php
		$ajaxRequest = false;
		
		use App\Route;
use Controllers\LoginController;

require_once "./App/Route.php";

		$IV = new Route();

		$view = $IV->route();

		if ($view == "login" || $view == "404") {
			require_once "./Views/contents/" . $view . "-view.php";
		} else {
			session_start(['name' => 'SPM']);

			require_once "./Controllers/LoginController.php";
			$lc = new LoginController();

			if (!isset($_SESSION['token_spm']) || !isset($_SESSION['usuario_spm']) || !isset($_SESSION['privilegio_spm']) || !isset($_SESSION['id_spm']) ) {
				echo $lc->forceLogout();
			}
	?>
	<!-- Main container -->
	<main class="full-box main-container">
        <!-- Nav lateral -->
        <?php include "./Views/inc/SideNav.php"; ?>

        <!-- Page content -->
		<section class="full-box page-content">
            <?php 
				include "./Views/inc/NavBar.php";
				
				include $view;
			?>
		</section>
	</main>
	<?php
		} 
		include "./Views/inc/Scripts.php"; ?>
</body>
</html>