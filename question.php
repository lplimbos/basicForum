<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Foro: Pregunta</title>
        <?php
        include "meta.php";
        ?>
    </head>
    <body>

        <?php
        $page = "question";
        include './top-nav.php';
        ?>

        <div id="content">
            <div id="new-question">
                <form action="ask.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <label>Pregunta:</label>
                        <input type="text" name="title">
                    </div>
                    <div class="row">
                        <label>Detalles:</label>
                        <textarea maxlength="160" name="content"></textarea>
                    </div>
                    <div class="row">
                        <label>Tema:</label>
                        <select name="topic">
                            <?php
                            require_once './control/DataBase.php';
                            
                            $result = DataBase::executeQuery("SELECT * FROM system_topics;");
                            
                            while ($row = $result->fetch_assoc()) {
                                printf("<option value='%s'>%s</option>", $row['topic_id'], $row['title']);
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <input type="submit" name="question" value="Publicar">  
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
