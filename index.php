<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="es">
    <head>
        <title>Forum Home</title>
        <?php
        include 'meta.php';
        ?>
        
    </head>
    <body>
        <?php
        $page = "home";
        include_once './top-nav.php';
        ?>
        
        <!--div id="last-entrys" class="entrys-container">
            <h3>Trending entrys</h3>
            <div class="forum-entry">
                <div class="entry">
                    <h3><a href="">Entry Title</a></h3>
                    <a href="" class="forum-topic">Topic</a>
                </div>
                <div class="entry entry-info">
                    <p>5 answers</p>
                    <p>asked date</p>
                </div>
            </div>
        </div-->
        
        <div id="latestEntries" class="entriesContainer">
            <h2>Ultimas Entradas</h2>
            <?php
            require_once './control/DataBase.php';
            
            $result = DataBase::executeQuery("SELECT entry_id, forum_entrys.title AS entry, system_topics.title AS topic, date,time"
                    . " FROM forum_entrys INNER JOIN system_topics"
                    . " ON forum_entrys.topic_id = system_topics.topic_id");
            
            while($row = $result->fetch_assoc()){
                printf("
                    <div class='forumEntry'>
                        <div class='entry'>
                            <h3><a href='entry.php?id=%s'>%s</a></h3>
                            <a href='' class='forumTopic'>%s</a>
                        </div>
                        <div class='entryInfo'>
                            <!--p>5 answers</p-->
                            <p>%s</p>
                            <p>%s</p>
                        </div>
                    </div>
                    ",$row["entry_id"], $row["entry"], $row["topic"], $row["date"], $row["time"]);
            }
            ?>
        </div>
        
    </body>
</html>
