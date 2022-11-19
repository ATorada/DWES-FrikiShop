<header>
    <div class="cabecera">
        <h1><a href="index.php">MerchaShop Ángel</a></h1>
        <div class="cabecera_usuario">
            <?php
            // Si el usuario está logueado, mostramos su nombre, su carrito y un enlace para cerrar sesión
            if (isset($_SESSION["usuario"])) {
                echo "<span class='usuario_registrado'>¡Bienvenido " . $_SESSION["usuario"] . "!</span>";
                //Si el usuario es administrador, mostramos un enlace para acceder al panel de usuarios
                if ($_SESSION["rol"] == 'admin') {
                    echo '<a href="usuarios.php" class="usuarios_cabecera">Usuarios</a>';
                }
                echo "<a href='carrito.php' class='carrito_cabecera'>🛒<span>" . (isset($_SESSION["carrito"]) ? count($_SESSION["carrito"]) : "0") . "</span></a>";
                echo '<a href="logout.php" class="logout">Cerrar sesión</a>';
            }
            ?>
        </div>
    </div>
</header>