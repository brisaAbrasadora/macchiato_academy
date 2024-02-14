<?php
namespace macchiato_academy\core;

class Security {
    public static function isUserGranted(string $role): bool {
        if ($role === 'ROLE_ANONYMOUS')
            return true; // Porque cualquier usuario puede acceder

        $usuario = App::get('appUser');
        if (is_null($usuario))
            return false; // No hay log in

        // Obtenemos el valor de la clave $role y luego el que porta el usuario
        $valor_role = App::get('config')['security']['roles'][$role];
        $valor_role_user = App::get('config')['security']['roles'][$usuario->getRole()];

        return ($valor_role_user >= $valor_role);
    }

    public static function encrypt(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param string $password
     * @param string $bdPassword
     * @return bool
     */
    public static function cword(string $password, string $bdPassword):bool {
        return password_verify($password, $bdPassword);
    }
}