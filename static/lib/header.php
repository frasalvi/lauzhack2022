<!--HEADER-->
<?php
if(!isset($_SESSION["TipoUtente"])){
	$logged = false;
} else {
	$logged = true;
	$personal_area_str = "Area personale";

	$tipo_utente = $_SESSION["TipoUtente"];

	switch($tipo_utente) {
		case "Amministratore" : $personal_area_url = "/area-amministratore/"; break;
		case "Utilizzatore" : $personal_area_url = "/area-utente/"; break;
		case "Volontario" : $personal_area_url = "/area-volontario/"; break;
		default : $logged = false; 
	}
}

?>


<header style="background-color: #fff; border-bottom: 1px solid #f2f2f2"
	data-uk-sticky="show-on-up: true; animation: uk-animation-slide-top" uk-sticky>
	<div class="uk-container">
		<nav id="navbar" data-uk-navbar="mode: click;">
			<div class="uk-navbar-left">
				<ul class="uk-navbar-nav">
					<li>
						<a class="uk-button-link" href="#" uk-icon="icon:menu; ratio:1.5"></a>
						<div class="uk-navbar-dropdown uk-navbar-dropdown-bottom-left">
							<ul class="uk-nav uk-navbar-dropdown-nav">
								<li class="uk-nav-header uk-text-small uk-text-primary">MENU</li>
								<li><a href="/"><span uk-icon="icon: home"></span> Home</a></li>
								<li><a href="/biblioteche.php"><span uk-icon="icon: location"></span> Biblioteche</a></li>
								<li><a href="/ricerca-libri.php"><span uk-icon="icon: search"></span> Ricerca libri</a></li>
								<li><a href="/statistiche.php"><span uk-icon="icon: database"></span> Statistiche</a></li>
							</ul>
						</div>
					</li>
				</ul>

			</div>
			<div class="uk-navbar-center">
				<a class="uk-navbar-item uk-logo" href="/"><img src="/template/logo.svg" /></a>
			</div>
			<div class="uk-navbar-right">

				<div class="uk-navbar-item">
					<a class="uk-navbar-toggle uk-hidden@m" data-uk-toggle data-uk-navbar-toggle-icon
						href="#offcanvas-nav"></a>
					<a href="<?php echo $logged ? $personal_area_url : "/login.php"; ?>"
						class="uk-button uk-button-default uk-visible@m"><?php echo $logged ? $personal_area_str : "Log in";?></a>
				</div>
			</div>
		</nav>
	</div>
</header>
<!-- FINE HEADER -->