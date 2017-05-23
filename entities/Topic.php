<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Topic
 *
 * @author cristhian
 */

require_once '../control/DataBase.php';

class Topic {
    private $topicId;
    private $title;
    private $mainDir;

    public function __construct() {
        if (func_num_args() == 0) {
            $this->topicId = 0;
            $this->title = "";
            $this->mainDir = "";
        } else {
            $this->topicId = func_get_arg(0);

            if ($result = self::searchTopicById($this->topicId)) {
                $result = $result->fetch_assoc();

                $this->title = $result["title"];
                $this->mainDir = $result["main_dir"];
            }
        }
    }

    public function validateTopic() {
        return !($this->title === "" || $this->mainDir === "");
    }

    public function insertTopic() {
        $succes = FALSE;

        /* Verifica los datos del usuario */
        if ($this->validateTopic()) {
            $conn = DataBase::getConnection();

            /* Verifica la conexión */
            if ($conn->connect_errno == 0) {
                $stmt = $conn->prepare("INSERT INTO system_topics(
						title, 
						main_dir) 
						VALUES(?,?)");

                /* Verifica la sentencia SQL */
                if ($stmt) {
                    $bind = $stmt->bind_param('ss', $this->getTitle(), $this->getMainDir());

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

    public function updateTopic() {
        $succes = FALSE;
        
        if ($this->topicId!=0) {
            /* Verifica los datos del usuario */
            if ($this->validateTopic()) {
                $conn = DataBase::getConnection();

                /* Verifica la conexión */
                if ($conn->connect_errno == 0) {
                    $stmt = $conn->prepare("UPDATE  system_topics set 
						title=?, 
						main_dir=?
						where topic_id=?");

                    /* Verifica la sentencia SQL */
                    if ($stmt) {
                        $bind = $stmt->bind_param('ss', $this->getTitle(), $this->getMainDir());

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
        }
        

        return $succes;
    }

    public static function deleteTopic($topicId) {
        $succes = FALSE;
        $query = "DELETE FROM system_topics where topic_id=?;";

        $conn = DataBase::getConnection();
        if ($conn->connect_errno == 0) {
            if ($stmt = $conn->prepare($query)) {
                if ($stmt->bind_param("i", $topicId)) {
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

    public static function searchTopic($arg) {
        $query = "SELECT * FROM system_topics where title like '%$arg%'";

        $result = DataBase::executeQuery($query);

        return $result;
    }

    public static function searchTopicById($topicId) {
        $query = "SELECT * FROM system_topics where topic_id=$topicId";

        $result = DataBase::executeQuery($query);

        return $result;
    }

    public function setTopicId($topicId) {
        $this->topicId = $topicId;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setMainDir($mainDir) {
        $this->mainDir = $mainDir;
    }

    public function getTopicId() {
        return $this->topicId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getMainDir() {
        return $this->mainDir;
    }

}
