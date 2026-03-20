<?php
/**
 * Vista Index - Redireccion al Dashboard
 * Inventio Max v9.0
 */

// Si el usuario esta autenticado, lo redirigimos al home
if(isset($_SESSION["user_id"])){
    include "core/app/view/home-view.php";
} else if(isset($_SESSION["client_id"])){
    // Si es un cliente, lo redirigimos a su vista
    include "core/app/view/clienthome-view.php";
} else {
    // Si no esta autenticado, mostramos mensaje de bienvenida
?>
<div class="jumbotron text-center">
    <h1>Bienvenido a Inventio Max</h1>
    <p>Sistema de Gestion de Inventario</p>
    <p>Por favor inicie sesion para continuar.</p>
</div>
<?php
}
?>
