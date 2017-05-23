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
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
            .entry {
                width: 60%;
                border: 2px solid #707070;
                border-radius: 10px;
                box-shadow: 5px 5px 10px #c0c0c0;
                background-color: #EEEEEE;
                margin: 25px auto;
                position: relative;
                display: block;
                overflow: auto;
            }

            #entry-title, #entry-content,#entry-info{
                padding: 10px;
            }

            #entry-title {
                padding: 5px 50px;
            }

            #entry-content {
                background: #FFF;
                border-top: 2px solid #c0c0c0;
                border-bottom: 2px solid #c0c0c0;
                font-weight: bold;
                font-size: 14px;
            }

            #entry-info {
                padding: 5px 10px;
                text-align: right;
                color: #606060;
                font-size: 12px;
            }

            .entry h3,h5, p, a{
                display: block;
                position: relative;
                margin: 0px;
            }

            .answer {
                margin: 5px auto;
            }

            .row{
                overflow: hidden;
                width: 90%;
                position: relative;
                display: block;
                margin: 0px auto;
            }

            textarea {
                resize: none;
                border: 1px solid #A0A0A0;
                border-radius: 10px;
                width: 100%;
                display: block;
                width: 95%;
                margin: 0 auto;
                padding: 5px;
                float: none;
                position: relative;

            }
            
            input {
                resize: none;
                padding: 3px 10px;
                border: 1px solid #A0A0A0;
                border-radius: 10px;
            }
        </style>

    </head>
    <body>

        <?php include_once './top-nav.php'; ?>

        <div id="content">
            <?php
            require_once './control/DataBase.php';
            
            $result = DataBase::executeQuery("SELECT content, entry_id, forum_entrys.title AS entry, system_topics.title AS topic, date,time"
                    . " FROM forum_entrys INNER JOIN system_topics"
                    . " ON forum_entrys.topic_id = system_topics.topic_id"
                    . " WHERE forum_entrys.entry_id = ".$_GET["id"]);
            
            while($row = $result->fetch_assoc()){
                printf("
                <div class='entry'>
                    <div id='entry-title'><h3>%s</h3></div>
                    <div id='entry-content'><p>%s</p></div>
                    <div id='entry-info'><p>%s: %s, %s</p></div>
                </div>",$row["entry"], $row["content"], $row["topic"], $row["date"], $row["time"]);
            }
            
            
            $result = DataBase::executeQuery("SELECT system_users.nickname, forum_answers.*"
                    . " FROM forum_answers INNER JOIN system_users"
                    . " ON forum_answers.answer_id = system_users.user_id"
                    . " WHERE forum_answers.entry_id = ".$_GET["id"]);
            
            while($row = $result->fetch_assoc()){
                printf("
                <div class='entry answer'>
                    <div id='entry-content'><p>%s</p></div>
                    <div id='entry-info'><p>%s: %s %s</p></div>
                </div>  
                ", $row["content"],$row["nickname"],$row["date"], $row["time"]);
            }
            if (isset($_COOKIE['forum-user-id'])){
                printf("
                    <div class='entry answer'>
                        <form name='answer' action='#' method='POST'>
                            <div id='entry-title'><h5>Responde a la pregunta:</h5></div>
                            <div id='entry-content'>
                                <div class='row'>
                                    <textarea maxlength='160' name='content'></textarea>
                                </div>
                            </div>
                            <div id='entry-info'><input type='submit' name='submit' value='Enviar'></div>
                        </form>
                    </div>");
            }
            ?>
            

        </div>
    </body>
</html>
