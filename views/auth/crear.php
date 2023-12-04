<div class="contenedor crear">
<?php include_once __DIR__ . "/../templates/nombreSitio.php"; ?>


    <div class="contenedor-sm">
        <p class="descripcion-pagina"> Crea tu cuenta en UpTask! </p>

        <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

        <form class="formulario" method="POST" action="/crear">

        <div class="campo">
                <label for="text">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu nombre" name="nombre"
                value="<?php echo $usuario->nombre; ?>">
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu email" name="email"
                value="<?php echo $usuario->email; ?>">
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Tu contraseña" name="password">
            </div>

            <div class="campo">
                <label for="password2">Repetir Contraseña</label>
                <input type="password" id="password2" placeholder="Repite tu contraseña" name="password2">
            </div>


            <input type="submit" class="boton" value="Crear cuenta">

        </form>

        <div class="acciones">
            <a href="/"> Volver a pagina principal</a>
            <a href="/olvide"> ¿Olvidaste tu contraseña? </a>
        </div>

    </div>
</div>