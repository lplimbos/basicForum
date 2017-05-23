<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ForumAnswer
 *
 * @author cristhian
 */
require_once './control/DataBase.php';

class ForumAnswer {

    private $answerId;
    private $date;
    private $time;
    private $content;
    private $userId;
    private $entryId;

    public function __construct() {
        if (func_num_args() == 0) {
            $this->answerId = 0;
            $this->date = "";
            $this->time = "";
            $this->content = "";
            $this->userId = 0;
            $this->entryId = 0;
        } else {
            $this->answerId = func_get_arg(0);

            if ($result = self::searchForumAnswerById($this->answerId)) {
                $result = $result->fetch_assoc();

                $this->date = $result['date'];
                $this->time = $result['time'];
                $this->content = $result['content'];
                $this->userId = $result['user_id'];
                $this->entryId = $result['entry_id'];
            }
        }
    }

    public function validateForumAnswer() {
        return !($this->content === "" || $this->userId === 0 || $this->entryId === 0);
    }

    public function insertForumAnswer() {
        $succes = FALSE;

        /* Verifica los datos de la entrada */
        if ($this->validateForumAnswer()) {
            $conn = DataBase::getConnection();

            /* Verifica la conexión */
            if ($conn->connect_errno == 0) {
                $stmt = $conn->prepare("INSERT INTO forum_answers("
                        . "date,"
                        . "time,"
                        . "content,"
                        . "user_id,"
                        . "entry_id) "
                        . "VALUES(CURDATE(),CURTIME(),?,?,?)");
                /* Verifica la sentencia SQL */
                if ($stmt) {
                    $bind = $stmt->bind_param('sii', $this->content, $this->userId, $this->entryId);
                    /* Verifica el enlace de los datos con la sentencia SQL */
                    if ($bind) {
                        /* Verifica la ejecución de la sentencia SQL */
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

    public function updateForumAnswer() {
        $succes = FALSE;

        /* Verifica los datos de la entrada */
        if ($this->validateForumAnswer()) {
            $conn = DataBase::getConnection();

            /* Verifica la conexión */
            if ($conn->connect_errno == 0) {
                $stmt = $conn->prepare("UPDATE  forum_answers set "
                        . "content=? "
                        . "where answer_id=?");

                /* Verifica la sentencia SQL */
                if ($stmt) {
                    $bind = $stmt->bind_param('si', $this->getContent(), $this->getAnswerId()
                    );

                    /* Verifica el enlace de los datos con la sentencia SQL */
                    if ($bind) {
                        /* Verifica la ejecución de la sentencia SQL */
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

    public static function deleteForumAnswer($answerId) {
        $succes = FALSE;
        $query = "DELETE FROM forum_answers where answer_id=?;";

        $conn = DataBase::getConnection();
        if ($conn->connect_errno == 0) {
            if ($stmt = $conn->prepare($query)) {
                if ($stmt->bind_param("i", $answerId)) {
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

    public static function searchForumAnswer($arg) {
        $query = "SELECT * FROM forum_answers where"
                . " content like '%$arg%'";

        $result = DataBase::executeQuery($query);

        return $result;
    }

    public static function searchForumAnswerById($answerId) {
        $query = "SELECT * FROM forum_answers where entry_id=$answerId";

        $result = DataBase::executeQuery($query);

        return $result;
    }

    public function getAnswerId() {
        return $this->answerId;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }

    public function getContent() {
        return $this->content;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getEntryId() {
        return $this->entryId;
    }

    public function setAnswerId($answerId) {
        $this->answerId = $answerId;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setTime($time) {
        $this->time = $time;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setEntryId($entryId) {
        $this->entryId = $entryId;
    }

}
