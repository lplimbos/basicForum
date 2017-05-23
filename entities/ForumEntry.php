<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ForumEntry
 *
 * @author cristhian
 */

require_once './control/DataBase.php';

class ForumEntry {

    private $entryId;
    private $title;
    private $content;
    private $date;
    private $time;
    private $userId;
    private $topicId;

    public function __construct() {
        if (func_num_args() == 0) {
            $this->entryId = 0;
            $this->title = "";
            $this->date = "";
            $this->content = "";
            $this->userId = 0;
            $this->topicId = 0;
        } else {
            $this->entryId = func_get_arg(0);

            if ($result = self::searchForumEntryById($this->entryId)) {
                $result = $result->fetch_assoc();

                $this->title = $result['title'];
                $this->date = $result['date'];
                $this->content = $result['content'];
                $this->userId = $result['user_id'];
                $this->topicId = $result['entry_id'];
            }
        }
    }

    public function validateForumEntry() {
        return !($this->title === "" 
                || $this->content === ""
                || $this->userId === 0 
                || $this->topicId === 0);
    }

    public function insertForumEntry() {
        $succes = FALSE;

        /* Verifica los datos de la entrada */
        if ($this->validateForumEntry()) {
            $conn = DataBase::getConnection();
            
            /* Verifica la conexión */
            if ($conn->connect_errno == 0) {
                $stmt = $conn->prepare("INSERT INTO forum_entrys(
						title, 
						content,
                                                date,
                                                time,
                                                user_id,
                                                topic_id) 
						VALUES(?,?,CURDATE(),CURTIME(),?,?)");
                /* Verifica la sentencia SQL */
                if ($stmt) {
                    $bind = $stmt->bind_param('ssii',
                            $this->title,
                            $this->content,
                            $this->userId,
                            $this->topicId);
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

    public function updateForumEntry() {
        $succes = FALSE;

        if ($this->entryId!=0) {
            /* Verifica los datos de la entrada */
            if ($this->validateForumEntry()) {
                $conn = DataBase::getConnection();
                
                /* Verifica la conexión */
                if ($conn->connect_errno == 0) {
                    $stmt = $conn->prepare("UPDATE  forum_entrys set 
						title=?,
                                                content=?,
                                                user_id=?,
                                                topic_id=? 
						where entry_id=?");

                    /* Verifica la sentencia SQL */
                    if ($stmt) {
                        $bind = $stmt->bind_param('ssiii', $this->title, $this->content, $this->userId, $this->topicId, $this->entryId);

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

    public static function deleteForumEntry($entryId) {
        $succes = FALSE;
        $query = "DELETE FROM forum_entrys where entry_id=?;";

        $conn = DataBase::getConnection();
        if ($conn->connect_errno == 0) {
            if ($stmt = $conn->prepare($query)) {
                if ($stmt->bind_param("i", $entryId)) {
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
    
    public static function searchForumEntry($arg) {
        $query = "SELECT * FROM forum_entrys where"
                . " title like '%$arg%'"
                . " or content like '%$arg%'";
        $result = DataBase::executeQuery($query);

        return $result;
    }
    
    public static function searchForumEntryById($entryId) {
        $query = "SELECT * FROM forum_entrys where entry_id=$entryId";

        $result = DataBase::executeQuery($query);

        return $result;
    }

    

    
    public function getEntryId() {
        return $this->entryId;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getDate(){
        return $this->date;
    }
    
    public function getTime(){
        return $this->time;
    }
    
    public function getContent(){
        return $this->content;
    }
    
    public function getUserId(){
        return $this->userId;
    }
    
    public function getTopicId(){
        return $this->topicId;
    }
    
    
    
    
    public function setEntryId($entryId) {
        $this->entryId = $entryId;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function setDate($date){
        $this->date = $date;
    }
    
    public function setTime($time){
        $this->time = $time;
    }
    
    public function setContent($content){
        $this->content = $content;
    }
    
    public function setUserId($userId){
        $this->userId = $userId;
    }
    
    public function setTopicId($topicId){
        $this->topicId = $topicId;
    }
    

}
