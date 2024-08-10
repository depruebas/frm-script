<?php

namespace libraries;

use libraries\Error;

Class PDO
{

  protected static $conn = null;

  public static function Connection( array $config = []) : array
  {

    if ( empty( $config))
    {
      error_log( date("Y-m-d H:i:s") . " - Config file empty \n", 
                  3, 
                  Config::get("config.app.ruta_logs")['error_log']."db_error.log");

      $return = [
        'success' => false,
        'data' => 'Config file empty',
      ];
      return ( [ 'success' => false, 'data' => $return]);
    }

    try
    {
      self::$conn = new \PDO( $config['dsn'], $config['username'], $config['password']);
      self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      self::$conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ); 

      return ( [ 'success' => true]);

    }
    catch ( \PDOException $e)
    {

      $_error = print_r( $e->getTrace(), true) . "\n" . $e->getMessage();

      error_log( date("Y-m-d H:i:s") . " - " . $_error . EOF, 
                  3, 
                  Config::get("config.app.ruta_logs")['error_log']."db_error.log");

      $return = [
        'success' => false,
        'data' => $_error,
      ];
      return ( [ 'success' => false, 'data' => $return]);
    }

  }

  public static function Close() : void 
  {
    self::$conn = null;
  }

  public static function Execute( array $params = []) : array
  {

    try
    {
      $stmt = self::$conn->prepare( $params['query']);
      $stmt->execute( $params['params'] );
      $data = $stmt->fetchAll( \PDO::FETCH_OBJ);
      $stmt->closeCursor();
      $count = $stmt->rowCount();

      $return = [ 'success' => true, 'count' => $count, 'data' => $data];
    }
    catch ( \PDOException $e)
    {
      $_error = print_r( $e->getTrace(), true) . "\n" . $e->getMessage();


      error_log( date("Y-m-d H:i:s") . " - " . $_error . "\n",
                 3, 
                 Config::get("config.app.ruta_logs")['error_log']."db_error.log");

      $return = [
        'success' => false,
        'data' => $_error,
      ];
    }

    unset ( $stmt);

    return ( $return);

  }

  public static function Insert( array $params = []) : array
  {

    $data = [];
    $fields = $fields_values = $a_values = "";

    foreach ( $params['fields'] as $key => $value)
    {
      $fields .= $key . ",";
      $fields_values .= " ?,";
      $a_values .= $value . ".:.";
    }

    $fields  = substr( $fields, 0, strlen( $fields) - 1);
    $fields_values  = substr( $fields_values, 0, strlen( $fields_values) - 1);
    $a_values  = substr( $a_values, 0, strlen( $a_values) - 3);

    try
    {

      $sql = "insert into " . $params['table'] . "( {$fields} ) values( ".$fields_values." )";

      $stmt = self::$conn->prepare( $sql, );
      $r = $stmt->execute( explode( ".:.", $a_values));
      $count = $stmt->rowCount();
      $id = self::$conn->lastInsertId();

      $return = [ 'success' => true, 'data' => $data, 'last_id' =>  $id, 'count' => $count];

    }
    catch ( \PDOException $e)
    {

      $_error = print_r( $e->getTrace(), true) . "\n" . $e->getMessage();

      error_log( date("Y-m-d H:i:s") . " - " . $_error . "\n",
                 3, 
                 Config::get("config.app.ruta_logs")['error_log']."db_error.log");

      $return = [
        'success' => false,
        'data' => $_error,
      ];

    }

    unset( $stmt);

    return ( $return );

  }


}