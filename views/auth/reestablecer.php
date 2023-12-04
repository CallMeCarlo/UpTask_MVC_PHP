<div class="contenedor reestablecer">
<?php include_once __DIR__ . "/../templates/nombreSitio.php"; ?>


    <div class="contenedor-sm">
        <p class="descripcion-pagina"> Reestablece tu contraseña </p>
        <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

        <?php if($mostrar) { ?>

        <form class="formulario" method="POST">

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Tu contraseña" name="password">
            </div>

            <div class="campo">
                <label for="password2">Repetir Contraseña</label>
                <input type="password" id="password2" placeholder="Repite tu contraseña" name="password2">
            </div>

            <input type="submit" class="boton" value="Enviar instrucciones">

        </form>

        <?php } ?>

        <div class="acciones">
            <a href="/"> Volver a pagina principal</a>
            <a href="/crear"> ¿Aun no tienes una cuenta? ¡Crea una!</a>
        </div>

    </div>
</div>
