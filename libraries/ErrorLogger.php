<?php

namespace libraries;

class ErrorLogger 
{

    private $logFile;

    public function __construct() 
    {
        $this->logFile = Config::get("config.app.ruta_logs")['error_log'] . '/errors.log';

        # Configure custom error and exception handling
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError( string $errno, string $errstr, string $errfile, string $errline) : bool
    {
        $error = [
            'type' => "Error",
            'message' => $errno . " - " . $errstr,
            'file' => $errfile,
            'line' => $errline,
            'trace' => "",
        ];

        $this->log( $error);
        # Do not run PHP's internal error handler
        return true;
    }

    public function handleException( Object $e) 
    {
        if ($e instanceof \PDOException) 
        {
            $type = "PDOException";
        } 
        else 
        {
            $type = "Uncaught Exception";
        }

        $error = [
            'type' => $type,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' =>$e->getTraceAsString()
        ];

        $this->log( $error);
    }

    public function handleShutdown() 
    {
        $last_error = error_get_last();
        if ($last_error !== null) 
        {
            $this->log(
                [
                    'type' => 'Fatal error',
                    'message' => $last_error['message'],
                    'file' => $last_error['file'],
                    'line' => $last_error['line'],
                    'trace' => '',
                ],
            );
        }
    }

    private function log( Array $message) 
    {
        file_put_contents( $this->logFile, "[" . date('Y-m-d H:i:s') . "] - " . json_encode( $message) . EOF, FILE_APPEND);
    }
}
