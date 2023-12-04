<div class="contenedor olvide">
<?php include_once __DIR__ . "/../templates/nombreSitio.php"; ?>


    <div class="contenedor-sm">
        <p class="descripcion-pagina"> Reestablece tu contraseña </p>
        <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

        <form class="formulario" method="POST" action="/olvide">

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu email" name="email">
            </div>

            <input type="submit" class="boton" value="Enviar instrucciones">

        </form>

        <div class="acciones">
            <a href="/"> Volver a pagina principal</a>
            <a href="/crear"> ¿Aun no tienes una cuenta? ¡Crea una!</a>
        </div>

    </div>
</div>