<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;


class DashboardController {
    public static function index(Router $router) {

        session_start();
        isAuth();

        $id = $_SESSION["id"];

        $proyectos = proyecto::belongsTo("propietarioId", $id);

        $router->render("dashboard/index", [
            "titulo" => "Proyectos",
            "proyectos" => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $proyecto = new Proyecto($_POST);
            
            //Validacion alertas
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                //Generar url unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //Almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION["id"];

                //Guardar el proyecto
                $proyecto->guardar();

                //Reedireccionar
                header("Location: /proyecto?id=" . $proyecto->url);

            }
        }

        $router->render("dashboard/crear-proyecto", [
            "titulo" => "Crear proyecto",
            "alertas" => $alertas
        ]);
    }

    public static function proyecto(Router $router) {
        session_start();
        isAuth();

        $token = $_GET["id"];

        if(!$token) header("Location: /dashboard");
        //Revisar que la persona que visita el proyecto es el que lo creo

        $proyecto = Proyecto::where("url", $token);
        if($proyecto->propietarioId !== $_SESSION["id"]) {
            header("Location: /dashboard");
        }


        $router->render("dashboard/proyecto", [
            "titulo" => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router) {
        session_start();
        $alertas = [];

        $usuario = Usuario::find($_SESSION["id"]);

        isAuth();

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if(empty($alertas)) {

                $existeUsuario = Usuario::where("email", $usuario->email);

                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    //Mostrar alerta
                    Usuario::setAlerta("error", "Email no valido, ya pertenece a otra cuenta");
                    $alertas = Usuario::getAlertas();
                } else {
                //Guardar cambios
                $usuario->guardar();

                Usuario::setAlerta("exito", "Guardado correctamente");
                $alertas = Usuario::getAlertas();

                //Asignar el nuevo nombre a la global de session
                $_SESSION["nombre"] = $usuario->nombre;
                }


            }
            
        }

        $router->render("dashboard/perfil", [
            "titulo" => "Perfil",
            "alertas" => $alertas,
            "usuario" => $usuario
        ]);
    }

    public static function cambiar_password(Router $router) {

        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = Usuario::find($_SESSION["id"]);

            //Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)) {
                //Comprobar que el password actual lo conoce el usuario
                $resultado = $usuario->comprobar_password();

                if($resultado) {
                    $usuario->password = $usuario->password_nuevo;

                    //Eliminar passwords
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //Hashear el nuevo password
                    $usuario->hashPassword();
                    //Guardar cambios
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta("exito", "Contraseña actualizada");
                        $alertas = $usuario->getAlertas();
                    }

                } else {
                    Usuario::setAlerta("error", "Contraseña incorrecta");
                    $alertas = $usuario->getAlertas();
                }
            }
        }


        $router->render("dashboard/cambiar-password", [
            "titulo" => "Cambiar Contraseña",
            "alertas" => $alertas
        ]);
    }

}