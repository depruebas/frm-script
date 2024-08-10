<?php

    # Iniciamos el timer para ver el tiempo que tarda la api en procesar una peticíon
    $start_time = microtime(true);

    # Guardamos la fecha con la que se inicia la API en cada petición, despues la utilizaremos en los
    # logs para saber cuando fue la petición
    global $init_time;
    $init_time = date( "Y-m-d H:i:s");

    # Incluimos los headers para que
    # el content-type y el cache ( no queremos que se cacheen los resultados )
    header('Content-Type: text/plain; charset=utf-8');

    # Añadimos las librerias .php que vamos a necesitar en la API
    require_once dirname(__FILE__)."/libraries/Config.php";
    require_once dirname(__FILE__)."/libraries/Utils.php";
    require_once dirname(__FILE__)."/libraries/ErrorLogger.php";
    require_once dirname(__FILE__)."/libraries/Log.php";
    require_once dirname(__FILE__)."/libraries/PDO.php";

    use libraries\Config;
    use libraries\ErrorLogger;

    # Inicializamos CustomErrorLog, para procesar automaticamente los errores
    $e = new ErrorLogger();

    # Defimos las costantes del programa
    define( 'DEBUG', Config::get("config.app.debug"));
    define( 'ENVIRONMENT', Config::get("config.app.environment"));
    define( "EOF", "\n");

    # Guardamos en un array de entrada los datos generales de la llamada

    if ( count( $argv) < 3)
    {
        echo EOF . "Los parametros pasados no son correctos." . EOF;
        die;
    }

    $_class = trim( $argv[1]);
    $_method = trim( $argv[2]);

    ( isset( $argv[3]) ? $data = $argv[3] : $data = null);

    # Cargamos la clase (fichero) que vamos a utilizar dinamicamente
    $class_include = dirname(__FILE__)."/modules/".$_class.".php";

    if ( file_exists( $class_include))
    {
        require_once dirname(__FILE__)."/modules/CommonClass.php";
        require_once $class_include;

        $action = new $_class();
        $return = $action->{$_method} ( $data);

        echo $return;
    }
    else
    {

    return ( Log::Response( 
        [
            'success' => false,
            'type' => 'ERROR',
            'code' => RandomString(),
            'message' => "Clase " . $class_include . " no existe",
        ]
    ));

    }

    # Si tenemos la depuración activada se registra el tiempo que tarda en procesar las peticiones
    if ( DEBUG)
    {
    $time = microtime(true) - $start_time;

    if ( $time > 1)
    {
        # formatPeriod es una función que esta en el fichero Utils.php
        $time = formatPeriod( $time);
    }

    echo EOF.( $time ) . EOF;
    }
