<?php

require_once './entities/ForumEntry.php';
require_once './control/DataBase.php';

if (isset($_POST["question"])) {
    $newEntry = new ForumEntry();
    
    $newEntry->setTitle($_POST["title"]);
    $newEntry->setContent($_POST["content"]);
    $newEntry->setUserId($_COOKIE["forum-user-id"]);
    $newEntry->setTopicId($_POST["topic"]);
    
    if ($newEntry->insertForumEntry()) {
        echo "<script>alert('Entrada registrada');</script>";
    } else {
        echo "error:".DataBase::getConnection()->errno;
    }
    
}