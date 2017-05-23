<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* * *
 * Description of User
 *
 * @author cristhian
 */

require_once './control/DataBase.php';

class User {

    private $userId;
    private $name;
    private $surname;
    private $email;
    private $nickname;
    private $password;
    private $picture;
    private $birthdate;
    private $powersKey;

    public function __construct() {
        if (func_num_args() == 0) {
            $this->userId = 0;
            $this->name = "";
            $this->surname = "";
            $this->email = "";
            $this->nickname = "";
            $this->picture = "";
            $this->birthdate = "";
            $this->powersKey = 3;
        } else {
            $this->userId = func_get_arg(0);

            if ($result = self::searchUserById($this->userId)) {
                $result = $result->fetch_assoc();

                $this->name = $result["name"];
                $this->surname = $result["surname"];
                $this->email = $result["email"];
                $this->nickname = $result["nickname"];
                $this->password = $result["password"];
                $this->picture = $result["picture"];
                $this->birthdate = $result["birthdate"];
                $this->powersKey = $result["powers_key"];
            }
        }
    }

    /**
     * Verifica los datos en el usuario.
     * @return TRUE si los datos del usuario están completos, de lo contrario FALSE.
     */
    public function validateUser() {
        return !($this->name === "" || $this->surname === "" || $this->email === "" || $this->nickname === "" || $this->birthdate === "");
    }

    /**
     * Guarda el usuario en la base de datos.
     * @return TRUE si el usuario fue registrado correctamente, de lo contrario FALSE. 
     */
    public function insertUser() {
        $succes = FALSE;

        /* Verifica los datos del usuario */
        if ($this->validateUser()&&$this->getPassword()!=="") {
            $conn = DataBase::getConnection();

            /* Verifica la conexión */
            if ($conn->connect_errno == 0) {
                $stmt = $conn->prepare("INSERT INTO system_users(
						name, 
						surname, 
						email, 
						nickname, 
						password, 
						picture, 
						birthdate,
						powers_key) 
						VALUES(?,?,?,?,?,DEFAULT,?,3)");

                /* Verifica la sentencia SQL */
                if ($stmt) {
                    $bind = $stmt->bind_param('ssssss',
                            $this->name,
                            $this->surname,
                            $this->email,
                            $this->nickname,
                            $this->password,
                            $this->birthdate);

                    /* Verifica el enlace de los datos con la sentencia SQL */
                    if ($bind) {
                        /** Verifica la ejecución de la sentencia SQL */
                        if ($stmt->execute()) {
                            $succes = TRUE;
                        }
                    }
                }

                $stmt->close();
            }
        }

        return $succes;
    }

    /**
     * Actualiza los datos modificados del usuario en la base de datos.
     * @return TRUE si el usuario fue actualizado correctamente, de lo contrario FALSE.
     */
    public function updateUser() {
        $succes = FALSE;

        if ($this->userId != 0) {
            /** Verifica los datos del usuario */
            if ($this->validateUser()) {
                $conn = DataBase::getConnection();

                /** Verifica la conexión */
                if ($conn->connect_errno == 0) {
                    $stmt = $conn->prepare("UPDATE system_users set 
						name=?, 
						surname=?, 
						email=? 
						where user_id=?");

                    /** Verifica la sentencia SQL */
                    if ($stmt) {
                        $bind = $stmt->bind_param('sssi', $this->name, $this->surname, $this->email,$this->userId);

                        /** Verifica el enlace de los datos con la sentencia SQL */
                        if ($bind) {
                            /** Verifica la ejecución de la sentencia SQL */
                            if ($stmt->execute()) {
                                $succes = TRUE;
                            }
                        }
                    }

                    $stmt->close();
                }
            }
        }

        return $succes;
    }
    
    /**
     * Actualiza la contraseña del usuario en la base de datos.
     * @return TRUE si la contraseña fue actualizada correctamente, de lo contrario FALSE.
     */
    public function updateUserPassword() {
        $succes = FALSE;

        if ($this->userId != 0) {
            /** Verifica los datos del usuario */
            if ($this->getPassword()!=="") {
                $conn = DataBase::getConnection();

                /** Verifica la conexión */
                if ($conn->connect_errno == 0) {
                    $stmt = $conn->prepare("UPDATE  system_users set 
						password=? 
						where user_id=?");

                    /** Verifica la sentencia SQL */
                    if ($stmt) {
                        $bind = $stmt->bind_param('si',$this->getPassword(), $this->getUserId());

                        /** Verifica el enlace de los datos con la sentencia SQL */
                        if ($bind) {
                            /** Verifica la ejecución de la sentencia SQL */
                            if ($stmt->execute()) {
                                $succes = TRUE;
                            }
                        }
                    }

                    $stmt->close();
                }
            }
        }

        return $succes;
    }
    
    /**
     * Actualiza la imagen del usuario en la base de datos.
     * @return TRUE si la imagen fue actualizada correctamente, de lo contrario FALSE.
     */
    public function updateUserPicture() {
        $succes = FALSE;

        if ($this->userId != 0) {
            /** Verifica los datos del usuario*/
            if ($this->getPicture()!=="") {
                $conn = DataBase::getConnection();

                /** Verifica la conexión */
                if ($conn->connect_errno == 0) {
                    $stmt = $conn->prepare("UPDATE  system_users set 
						picture=? 
						where user_id=?");

                    /** Verifica la sentencia SQL */
                    if ($stmt) {
                        $bind = $stmt->bind_param('si', $this->getPicture(), $this->getUserId());

                        /** Verifica el enlace de los datos con la sentencia SQL */
                        if ($bind) {
                            /** Verifica la ejecución de la sentencia SQL */
                            if ($stmt->execute()) {
                                $succes = TRUE;
                            }
                        }
                    }

                    $stmt->close();
                }
            }
        }

        return $succes;
    }

    /**
     * Elimina un usuario de la base de datos.
     * @return TRUE si el usuario fue eliminado correctamente, de lo contrario FALSE.
     * @param EL user_id del usuario a eliminar.
     */
    public static function deleteUser($userId) {
        $succes = FALSE;
        $query = "DELETE FROM system_users where user_id=?;";

        $conn = DataBase::getConnection();
        if ($conn->connect_errno == 0) {
            if ($stmt = $conn->prepare($query)) {
                if ($stmt->bind_param("i", $userId)) {
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows > 0) {
                            $succes = TRUE;
                        }
                    }
                }
            }

            $stmt->close();
        }

        return $succes;
    }

    /**
     * Busca usuarios en la base de datos usando name | surname | nickname | email  parcial o rotal como referencia.
     * @param El name | surname | nickname | email parcial o total a buscar .
     * @return El result con los datos obtenidos.
     */
    public static function searchUser($arg) {
        $query = "SELECT * FROM system_users where name like '%$arg%' OR surname like '%$arg%' OR nickname like '%$arg%' OR email like '%$arg%'";

        $result = DataBase::executeQuery($query);

        return $result;
    }
    
    /**
     * Recupera el id del usuario logeado en la base de datos usando nickname | email como usuario y password.
     * @param El nickname | email como usuario y password a logear.
     * @return El id del usuario.
     */
    public static function logIn($username,$password) {
        $query = "SELECT * FROM system_users WHERE password LIKE '$password' AND nickname LIKE '$username' OR email LIKE '$username';";

        $result = DataBase::executeQuery($query);
        
        if ($row = $result->fetch_assoc()) {
            return $row["user_id"];
        }else {
            return FALSE;
        }

    }

    /**
     * Busca un usuario en la base de datos usando su user_id como referencia.
     * @param El user_id del usuario a buscar .
     * @return El result con los datos obtenidos.
     */
    public static function searchUserById($userId) {
        $query = "SELECT * FROM system_users where user_id=$userId";

        $result = DataBase::executeQuery($query);

        return $result;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setNickname($nickname) {
        $this->nickname = $nickname;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setPicture($picture) {
        $this->picture = $picture;
    }

    public function setBirthdate($birthdate) {
        $this->birthdate = $birthdate;
    }

    public function setPowersKey($powersKey) {
        $this->powersKey = $powersKey;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getName() {
        return $this->name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getNickname() {
        return $this->nickname;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getPicture() {
        return $this->picture;
    }

    public function getBirthdate() {
        return $this->birthdate;
    }

    public function getPowersKey() {
        return $this->powersKey;
    }

}
