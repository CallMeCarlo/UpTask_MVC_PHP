<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                //Verificar que el usuario existe 
                $usuario = Usuario::where("email", $usuario->email);
                if(!$usuario || !$usuario->confirmado) {
                    $alertas["error"][] = "El usuario no existe o no esta confirmado";
                } else {
                    //El usuario existe entonces validamos la contraseña
                    if(password_verify($_POST["password"], $usuario->password)) {
                        //Iniciamos la sesión del usuario
                        session_start();
                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;

                        //Redireccionar a la pagina de inicio
                        header("Location: /dashboard");
                        

                    } else {
                        $alertas["error"][] = "Contraseña incorrecta";
                    }
                }
            }
        }
        

        //Render a la vista
        $router->render("auth/login", [
            "titulo" => "Iniciar sesion",
            "alertas" => $alertas
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header("Location: /");
    }

    public static function crear(Router $router) {

        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            
            $existeUsuario = Usuario::where("email", $usuario->email);

            if(empty($alertas)) {
                if($existeUsuario) {
                    Usuario::setAlerta("error", "El correo ya esta registrado");
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hasheamos el password
                    $usuario->hashPassword();

                    //Eliminar la password2
                    unset($usuario->password2);

                    //Crear token
                    $usuario->crearToken();
                    $usuario->confirmado = 0;

                    //Creamos un nuevo usuario
                    $resultado = $usuario->guardar();
                    
                    //Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if($resultado) {
                        header("Location: /mensaje");
                    }

                }
            }

        }

        //Render a la vista
        $router->render("auth/crear", [
            "titulo" => "Crear cuenta",
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);

    }

    public static function olvide(Router $router) {

        $alertas = [];
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                //Buscar en BD si el correo existe
                $usuario = Usuario::where("email", $usuario->email);
                if($usuario && $usuario->confirmado) {
                    //Generar nuevo token
                    unset($usuario->password2);
                    $usuario->crearToken();

                    //Actualizar el usuario
                    $resultado = $usuario->guardar();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->reestablecerContraseña();

                    //Imprimir alerta de éxito
                    $alertas["exito"][] = "Las instrucciones fueron enviadas con éxito a tu correo electronico";


                } else {
                    $alertas["error"][] = "El usuario no existe o no esta confirmado";
                }
            }
        }

        $router->render("auth/olvide", [
            "titulo" => "Olvide mi contraseña",
            "alertas" => $alertas
        ]);
    }

    public static function reestablecer(Router $router) {

        $alertas = [];
        
        $token = s($_GET["token"]);
        $mostrar = true;
        
        if(!$token) {
            header("Location: /");
        }

        //Encontrar al usuario con este token
        $usuario = Usuario::where("token", $token);

        if(empty($usuario)) {
            $alertas["error"][] = "Token no válido";
            $mostrar = false;
        } else {
            
        }

        if($_SERVER["REQUEST_METHOD"] === "POST") {

            //Añadir el nuevo password
            $usuario->sincronizar($_POST);

            //Validar nuevo password
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                ///Hasheamos el password
                $usuario->hashPassword();

                //Eliminar la password2
                unset($usuario->password2);

                //Eliminar token
                $usuario->token = null;

                //guardar datos usuario
                $resultado = $usuario->guardar();

                //Redireccionar
                header("Location: /");
            }
        }

        $router->render("auth/reestablecer", [
            "titulo" => "Reestablcer contraseña",
            "alertas" => $alertas,
            "mostrar" => $mostrar
        ]);

    }
    
    public static function mensaje(router $router) {
        $router->render("auth/mensaje", [
            "titulo" => "Instrucciones Enviadas Correctamente"
        ]);

    }

    public static function confirmar(Router $router) {

        $token = s($_GET["token"]);

        if(!$token) {
            header("Location: /");
        }

        //Encontrar al usuario con este token
        $usuario = Usuario::where("token", $token);

        if(empty($usuario)) {
            Usuario::setAlerta("error", "Token no valido");
        } else {
            //Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            
            $usuario->guardar();
            Usuario::setAlerta("exito", "Cuenta confirmada correctamente");
        }

        $alertas = Usuario::getAlertas();

        $router->render("auth/confirmar", [
            "titulo" => "Confirma tu cuenta",
            "alertas" => $alertas
        ]);
    }

}