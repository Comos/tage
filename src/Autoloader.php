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
        if(strpos($class,'Tests\\') === 0){
            $class[0] = 't';
        }
        if (\file_exists($file = \dirname(__DIR__) . '/' .\str_replace('\\', '/', $class) . '.php')) {
            require $file;
        }
    }
}
