<?php 

namespace Model;

use Model\ActiveRecord;

class Usuario extends ActiveRecord {
    protected static  $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "email", "password", "token", "confirmado"];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->password2 = $args["password2"] ?? null;
        $this->password_actual = $args["password_actual"] ?? null;
        $this->password_nuevo = $args["password_nuevo"] ?? null;
        $this->confirmado = $args["confirmado"] ?? "";
        $this->token = $args["token"] ?? "";
    }

    public function validarLogin() {
        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "El email no es valido";
        }

        if(!$this->password) {
            self::$alertas["error"][] = "La contraseña es obligatoria";
        }

        return self::$alertas;
    }

    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas["error"][] = "El nombre es obligatorio";
        }

        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        }

        if(!$this->password) {
            self::$alertas["error"][] = "La contraseña es obligatoria";
        }

        if(strlen($this->password) < 6 ) {
            self::$alertas["error"][] = "La contraseña debe contener al menos 6 caracteres";
        }

        if($this->password !== $this->password2) {
            self::$alertas["error"][] = "Las contraseñas no coinciden";
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "El email no es valido";
        }

        return self::$alertas;
    }

    public function validarPassword() {

        if(!$this->password) {
            self::$alertas["error"][] = "La contraseña es obligatoria";
        }

        if(strlen($this->password) < 6 ) {
            self::$alertas["error"][] = "La contraseña debe contener al menos 6 caracteres";
        }

        if($this->password !== $this->password2) {
            self::$alertas["error"][] = "Las contraseñas no coinciden";
        }

        return self::$alertas;

    }

    public function validar_perfil() {
        
        if(!$this->nombre) {
            self::$alertas["error"][] = "El nombre es obligatorio";
        }

        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        }

        return self::$alertas;

    }

    public function nuevoPassword() : array {
        if(!$this->password_actual) {
            self::$alertas["error"][] = "La contraseña actual es obligatorio";
        }

        if(!$this->password_nuevo) {
            self::$alertas["error"][] = "La nueva contraseña es obligatoria";
        }

        if(strlen($this->password_nuevo) < 6)  {
            self::$alertas["error"][] = "La nueva contraseña debe tener más de 6 caracteres";
        }

        return self::$alertas;
    }

    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password);
    }

    //Hashear password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    //Generar token
    public function crearToken() : void {
        $this->token = uniqid();
    }

}