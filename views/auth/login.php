<div class="contenedor login">
<?php include_once __DIR__ . "/../templates/nombreSitio.php"; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina"> Inicia sesión </p>
        <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

        <form class="formulario" method="POST" action="/">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu email" name="email">
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Tu contraseña" name="password">
            </div>

            <input type="submit" class="boton" value="Iniciar sesión">

        </form>

        <div class="acciones">
            <a href="/crear"> ¿Aun no tienes una cuenta? ¡Crea una!</a>
            <a href="/olvide"> ¿Olvidaste tu contraseña? </a>
        </div>

    </div>
</div>