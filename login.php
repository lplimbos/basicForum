<!DOCTYPE html>
<html lang="es">
    <head>
    <?php
    include_once './entities/User.php';
    
    if (isset($_POST['sign-user'])){
        
        if (isset($_POST["username"]) && $_POST["username"] == "") {
            echo "<script>alert('El campo nombre esta vacío');</script>";
        }elseif (isset($_POST["password"]) && $_POST["password"] == "") {
            echo "<script>alert('El campo password esta vacío');</script>";
        } else {
            
            /*
             * Intenta el login
             */
            
            
            
            if ($userlogin = User::logIn($_POST['username'], $_POST['password'])) {
                $usuario = new User($userlogin);
                setcookie("forum-user-id", $userlogin, time()+3600);
                echo "<meta http-equiv='refresh' content='0; url=index.php' />";
                die();
            } else {
                
            }

            
        }
        
    }
    ?>
        <title>Foro: Log in</title>
        <?php
        include "meta.php";
        ?>
    </head>
    <body>
        <?php
            $page = "log";
            include 'top-nav.php';
        ?>
        
        <div id="loginContainer">
            <form action="#" method="post" enctype="multipart/form-data">
                <fieldset id="login">
                    <legend>Log in</legend>
                    <div class="form">
                        <div class="loginRow">
                            <label>Username:</label>
                            <input type="text" name="username">
                        </div>
                        <div class="loginRow">
                            <label>Password:</label>
                            <input type="password" name="password">
                        </div>
                        <div class="loginRow">
                            <input type="submit" name="sign-user" value="Ingresar" class="loginButton">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>
