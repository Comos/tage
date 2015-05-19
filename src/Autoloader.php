<?php
namespace Comos\Tage;

/**
 * Class Autoloader
 */
class Autoloader
{

    static public function register()
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(
            new self(),
            'autoload'
        ));
    }

    static public function autoload($class)
    {
        if (0 !== \strpos($class, 'Comos\\Tage')) {
            return;
        }
        $class=substr($class,11);//skip Tage/

        if (\file_exists($file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php')) {
            require $file;
            return;
        }
        if (\file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR. 'tests' . DIRECTORY_SEPARATOR . '/' .\str_replace('\\', '/', $class) . '.php')) {
            require $file;
        }
    }
}
