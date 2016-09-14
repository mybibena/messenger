<?php

namespace Messenger;

class Bootstrap
{
    /** @var Bootstrap|null */
    private static $instance = null;

    private function __clone() {}

    private function __construct() {}

    /**
     * @static
     * @return Bootstrap
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Bootstrap();
        }
        return self::$instance;
    }

    // ########################################

    /**
     * Prepare application to start
     */
    public function process()
    {
        try {
            $this->prepareConstants();

            date_default_timezone_set('UTC');

//            TODO: remove this
//            ini_set('display_errors', 0);
//            error_reporting(0);

            spl_autoload_register(array($this, 'autoload'));
            register_shutdown_function(array($this, 'catchFatalError'));

            set_error_handler(array($this, 'phpErrorsHook'));
            set_exception_handler(array($this, 'phpExceptionsHook'));
        } catch (\Exception $exception) {
            $this->terminateApplication();
        }
    }

    // ########################################

    /**
     * Define constants
     */
    private function prepareConstants()
    {
        define('DS', DIRECTORY_SEPARATOR);
        define('ROOT_DIRECTORY', dirname(__DIR__) . DS);
    }

    /**
     * Autoloader
     * @param string $className
     */
    private function autoload($className)
    {
        try {
            if (strpos($className, 'Messenger') !== 0) {
                return;
            }

            $classPath = ROOT_DIRECTORY . 'src/' . str_replace('\\', DS, substr($className, 10)) . '.php';

            if (!is_file($classPath)) {
                return;
            }

            require($classPath);
            return;
        } catch (\Exception $exception) {
            $this->terminateApplication();
        }
    }

    // ########################################

    /**
     * Terminate application with error message
     *
     * @param string $message
     */
    public function terminateApplication($message = "The request cannot be processed. Please wait and try again later.")
    {
        exit($message);
    }

    /**
     * Terminate application on fatal error
     */
    public function catchFatalError()
    {
        $error = error_get_last();

        if (is_null($error)) {
            return;
        }

        $type = (int)$error['type'];
        if (!in_array($type, [E_ERROR, E_PARSE, E_COMPILE_ERROR, E_COMPILE_WARNING, E_CORE_ERROR])) {
            return;
        }

        $this->terminateApplication("Fatal error of type {$type}: {$error['message']}");
    }

    /**
     * Terminate application on error
     *
     * @param string $type
     * @param string $message
     */
    public function phpErrorsHook($type, $message)
    {
        switch ($type) {
            case E_WARNING:
                $type = 'E_WARNING';
                break;
            case E_RECOVERABLE_ERROR:
                $type = 'E_RECOVERABLE_ERROR';
                break;
            case E_STRICT:
                $type = 'E_STRICT';
                break;
            case E_NOTICE:
                $type = 'E_NOTICE';
                break;
            case E_USER_ERROR:
                $type = 'E_USER_ERROR';
                break;
            case E_USER_NOTICE:
                $type = 'E_USER_NOTICE';
                break;
            case E_USER_WARNING:
                $type = 'E_USER_WARNING';
                break;
            default: $type =
                'E_UNDEFINED';
                break;
        }

        $this->terminateApplication("Error of type {$type}: {$message}");
    }

    /**
     * Terminate application on exception
     *
     * @param \Exception $exception
     */
    public function phpExceptionsHook(\Exception $exception)
    {
        $this->terminateApplication("Exception: {$exception->getMessage()}");
    }
}