<!-- LEFT BAR -->
<?php

    // da cambiare 
    /*if(isset($_SESSION["Tipo"])){
        $tipo_utente = $_SESSION["Tipo"];
    } else $tipo_utente = "Amministratore";*/

    if(!isset($_SESSION["TipoUtente"])){
        header("Location: /login.php");
    }

    $tipo_utente = $_SESSION["TipoUtente"];

?>
<aside id="left-col" class="uk-light">
    <div class="left-content-box  content-box-dark">
        <h4 class="uk-text-center uk-margin-remove-vertical text-light"><?php echo $_SESSION["Nome"] . ' ' . $_SESSION["Cognome"]; ?></h4>

        <div class="uk-position-relative uk-text-center uk-display-block">
            <span class="uk-text-muted"><?=$tipo_utente;?></span>
            <?php
                    if($tipo_utente == "Amministratore") {
                        echo '<br><h5>'.$_SESSION["Biblioteca"].'</h5>';
                    }
                    
                    ?>
        </div>
    </div>
    <div class="left-nav-wrap">
        <ul class="uk-nav uk-nav-default uk-nav-parent-icon" data-uk-nav>
            <?php if($tipo_utente == "Utilizzatore") { ?>
            <!-- MENU Utilizzatore -->

            <li class="uk-nav-header">MENU</li>
            <?php if($_SESSION["StatoAccount"] == "Attivo") { ?>
            <!--<li class="profilo-utente"><a href="profilo-utente.php"><span data-uk-icon="icon: user" class="uk-margin-small-right"></span>Profilo</a></li>-->
            <li class="prenotazioni-libri"><a href="prenotazioni-libri.php"><span data-uk-icon="icon: users" class="uk-margin-small-right"></span>Prenotazioni libri</a></li>
            <li class="prenotazioni-lettura"><a href="prenotazioni-lettura.php"><span data-uk-icon="icon: users" class="uk-margin-small-right"></span>Prenotazioni posti lettura</a></li>
            <li class="elenco-messaggi"><a href="elenco-messaggi.php"><span data-uk-icon="icon: comments" class="uk-margin-small-right"></span>Messaggi</a></li>
            <li class="elenco-segnalazioni"><a href="elenco-segnalazioni.php"><span data-uk-icon="icon: warning" class="uk-margin-small-right"></span>Segnalazioni</a></li>
            <?php } else { ?>
           <!--<li class="profilo-utente"><a href="profilo-utente.php"><span data-uk-icon="icon: user" class="uk-margin-small-right"></span>Profilo</a></li>-->
            <li class="elenco-segnalazioni"><a href="elenco-segnalazioni.php"><span data-uk-icon="icon: warning" class="uk-margin-small-right"></span>Segnalazioni</a></li>
            <li class="elenco-messaggi"><a href="elenco-messaggi.php"><span data-uk-icon="icon: comments" class="uk-margin-small-right"></span>Messaggi</a></li>


            <?php } ?>
            <!-- FINE MENU Utilizzatore -->

            <?php } else if (($tipo_utente == "Amministratore")) {
    ?>
            <!-- MENU Amministratore -->
            <li class="uk-nav-header">MENU</li>
            <li class="catalogo-biblioteca">
                <a href="catalogo-biblioteca.php"><span data-uk-icon="icon: folder" class="uk-margin-small-right"></span>Catalogo biblioteca</a>
            </li>
            <li class="elenco-prenotazioni">
                <a href="elenco-prenotazioni-biblioteca.php"><span data-uk-icon="icon: database" class="uk-margin-small-right"></span>Elenco prenotazioni</a>
            </li>
            <li class="prenotazioni-lettura"><a href="elenco-prenotazioni-posti-lettura.php"><span data-uk-icon="icon: users" class="uk-margin-small-right"></span>Prenotazioni posti lettura</a></li>
            <li class="elenco-messaggi">
                <a href="elenco-messaggi-amministratore.php"><span data-uk-icon="icon: comments" class="uk-margin-small-right"></span>Messaggi</a>
            </li>
            <li class="elenco-segnalazioni">
                <a href="elenco-segnalazioni-amministratore.php"><span data-uk-icon="icon: warning" class="uk-margin-small-right"></span>Segnalazioni</a>
            </li>
            <!-- FINE MENU Amministratore -->
            <?php } else if (($tipo_utente == "Volontario")) { ?>
            <!-- MENU Volontario -->

            <li class="uk-nav-header">MENU</li>
            <!--<li class="profilo-utente"><a href="profilo-utente.php"><span data-uk-icon="icon: user" class="uk-margin-small-right"></span>Profilo</a></li>-->
            <li class="elenco-prenotazioni"><a href="elenco-prenotazioni-volontario.php"><span data-uk-icon="icon: folder" class="uk-margin-small-right"></span>Elenco prenotazioni</a></li>
            <li class="eventi-consegna"><a href="eventi-consegna.php"><span data-uk-icon="icon: location" class="uk-margin-small-right"></span>Eventi di consegna</a></li>
             <!-- FINE MENU Volontario -->
            <?php } ?>
            <!-- Log out -->
            <li class="uk-nav-divider"></li>
            <li class="uk-flex-bottom"><a href="/logout.php"><span data-uk-icon="icon: sign-out"></span> Logout</a></li>

        </ul>
    </div>
</aside>
<!-- /LEFT BAR -->
