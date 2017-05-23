<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <?php
        if (!empty($_POST)) {
            require_once './entities/ForumAnswer.php';
            $answer = new ForumAnswer();
            $answer->setContent($_POST['content']);
            $answer->setUserId($_COOKIE['forum-user-id']);
            $answer->setEntryId($_GET['id']);
            
            if ($answer->validateForumAnswer()) {
                if ($answer->insertForumAnswer()) {
                    require_once './control/DataBase.php';
                    echo "<script>alert('Registro completo!');</script>";
                    echo "<meta http-equiv='refresh' content='0; url=#' />";
                    die();
                } else{
                    echo "Error: ".DataBase::getConnection()->errno;
                    echo "<script>alert('Error al registrar respuesta');</script>";
                }
            }
        }
        ?>
        
        <title>Foro: entrada</title>
        <?php
        include 'meta.php';
        ?>

        <style>
            
        </style>

    </head>
    <body>

        <?php include_once './top-nav.php'; ?>

        <div id="entryContainer">
            <?php
            require_once './control/DataBase.php';
            
            $result = DataBase::executeQuery("SELECT content, entry_id, forum_entrys.title AS entry, system_topics.title AS topic, date,time"
                    . " FROM forum_entrys INNER JOIN system_topics"
                    . " ON forum_entrys.topic_id = system_topics.topic_id"
                    . " WHERE forum_entrys.entry_id = ".$_GET["id"]);
            
            while($row = $result->fetch_assoc()){
                printf("
                <div class='entryContainer'>
                    <div id='entryTitle'><h2>%s</h2></div>
                    <div id='entryContent'><p>%s</p></div>
                    <div id='entryInfo'><p>%s: %s, %s</p></div>
                </div>",$row["entry"], $row["content"], $row["topic"], $row["date"], $row["time"]);
            }
            
            
            $result = DataBase::executeQuery("SELECT system_users.nickname, forum_answers.*"
                    . " FROM forum_answers INNER JOIN system_users"
                    . " ON forum_answers.user_id = system_users.user_id"
                    . " WHERE forum_answers.entry_id = ".$_GET["id"]);
            
            while($row = $result->fetch_assoc()){
                printf("
                <div class='entry answer'>
                    <div id='entryContent'><p>%s</p></div>
                    <div id='entryInfo'><p class='username'>%s:</p><p> %s %s</p></div>
                </div>  
                ", $row["content"],$row["nickname"],$row["date"], $row["time"]);
            }
            if (isset($_COOKIE['forum-user-id'])){
                printf("
                    <div class='entry answer'>
                        <form name='answer' action='#' method='POST'>
                            <div id='entryTitle'><h5>Responde a la pregunta:</h5></div>
                            <div id='entryContent'>
                                <div class='row'>
                                    <textarea maxlength='160' name='content'></textarea>
                                </div>
                            </div>
                            <div id='entryButton'><input type='submit' name='submit' value='Enviar'></div>
                        </form>
                    </div>");
            }
            ?>
            

        </div>
    </body>
</html>
