<?php 
class Autoload{

    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoloader'));
    }

    static function autoloader($class){
        $classPath = $_ENV['config']->db->root.$class.'.php';
        if(file_exists($classPath)){
            require $classPath;
        }
        $toolsPath = lcfirst($class).".php";
        if (file_exists($toolsPath)) {
            require $toolsPath;
        }
    }
}