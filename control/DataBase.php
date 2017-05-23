<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataBase
 *
 * @author cristhian
 */
class DataBase {

    const SERVER = "localhost";
    const USER = "root";
    const PASSWORD = "FALL0ut";
    const DATABASE = "forum";

    private static $connection;
    private static $errno;

    /**
     * Crea la conexión con la base de datos
     * @return un objeto <code>mysqli</code> que representa la conexión si esta se ejecuta correctamente, FALSE si algo falla. 
     */
    private static function connect() {
        self::$connection = new mysqli(
                self::SERVER, self::USER, self::PASSWORD, self::DATABASE
        );

        self::$errno = self::$connection->connect_errno;
        if (self::$errno == 0) {
            self::$connection->query("SET NAMES 'utf8'");
        } else {
            self::$connection = FALSE;
        }


        return self::$connection;
    }

    /**
     * Obtiene la conexión existente, si no existe la crea
     * @return un objeto <code>mysqli</code> que representa la conexión a la base de datos, FALSE si algo falla.
     */
    public static function getConnection() {
        if (self::$connection == NULL | self::$errno) {
            self::connect();
        }
        return self::$connection;
    }

    /**
     * Ejecuta una consulta a la base de datos
     * @param <code>string</code> $query La consulta a realizar.
     * @return <code>mysqli_result</code> de la consulta, <code>FALSE</code> si algo falla.
     */
    public static function executeQuery($query) {
        $conn = self::getConnection();
        if ($conn) {
            if (!$result = $conn->query($query)) {
                $result = FALSE;
            }
        } else {
            $result = FALSE;
            echo "<script>alert('Error en la conexion a la base de datos');</script>";

        }

        return $result;
    }
}
