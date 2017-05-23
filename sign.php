<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html lang="es">
    <head>
    <?php
    include_once './entities/User.php';
    
    if (isset($_POST['sign-user'])){
        
        if (isset($_POST["name"]) && $_POST["name"] == "") {
            echo "<script>alert('El campo nombre esta vacío');</script>";
        } elseif (isset($_POST["surname"]) && $_POST["surname"] == "") {
            echo "<script>alert('El campo apellidos esta vacío');</script>";
        } elseif (isset($_POST["nickname"]) && $_POST["nickname"] == "") {
            echo "<script>alert('El campo nickname esta vacío');</script>";
        } elseif (isset($_POST["password"]) && $_POST["password"] == "") {
            echo "<script>alert('El campo password esta vacío');</script>";
        } elseif (isset($_POST["repassword"]) && $_POST["repassword"] == "") {
            echo "<script>alert('El campo nombre esta vacío');</script>";
        } elseif ($_POST["password"] != $_POST["repassword"]) {
            echo "<script>alert('Las contraseñas no coinciden');</script>";
        } elseif (isset($_POST["email"]) && $_POST["email"] == "") {
            echo "<script>alert('El campo correo esta vacío');</script>";
        } elseif (isset($_POST["email"]) && !preg_match("/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/", $_POST["email"])) {
            echo "<script>alert('El campo correo es invalido');</script>";
        } else {
            
            /*
             * Guarda el usuario, y luego redirecciona a home
             */
            
            $usuario = new User();
            $usuario->setName($_POST['name']);
            $usuario->setSurname($_POST['surname']);
            $usuario->setNickname($_POST['nickname']);
            $usuario->setPassword($_POST['password']);
            $usuario->setEmail($_POST['email']);
            $usuario->setBirthdate($_POST['year']."-".$_POST['month']."-".$_POST['day']);
            
            if ($usuario->validateUser()) {
                if ($usuario->insertUser()) {
                    $username = $usuario->getNickname();
                    $password = $usuario->getPassword();
                    echo "<script>alert('Registro completo!');</script>";
                    $userlogin = User::logIn($username, $password);
                    $usuario = new User($userlogin);
                    setcookie("forum-user-id", $userlogin, time()+3600);
                    echo "<meta http-equiv='refresh' content='0; url=./index.php' />";
                    die();
                    die();
                } else{
                    echo "<script>alert('Error al registrar usuario');</script>";
                }
            }
        }
        
    }
    ?>
        <title>Foro: Sign Up</title>
        <?php
        include 'meta.php';
        ?>
    </head>
    <body>
        
        <?php
            $page = "sign";
            include 'top-nav.php';
        ?>
        
        <div id="signupContainer">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <fieldset id="signup">
                    <legend>Sign Up</legend>
                    <div class="form">
                        <div class="signupRow">
                            <label>Nombre(s):</label>
                            <input type="text" name="name">
                        </div>
                        <div class="signupRow">
                            <label>Apellidos:</label>
                            <input type="text" name="surname">
                        </div>
                        <div class="signupRow">
                            <label>Nickname:</label>
                            <input type="text" name="nickname">
                        </div>
                        
                        <div class="signupRow">
                            <label>Password:</label>
                            <input type="password" name="password">
                        </div>
                        <div class="signupRow">
                            <label>Re-type Password:</label>
                            <input type="password" name="repassword">
                        </div>
                        <div class="signupRow">
                            <label>E-Mail:</label>
                            <input type="text" name="email">
                        </div>
                        <div class="signupRow">
                            <label>Fecha de Nacimiento:</label>
                            <select name="day" class="day">
                                <?php
                                for ($day = 1; $day <= 31; $day++) {
                                    echo "<option value='$day'>$day</option>";
                                }
                                ?>
                            </select>
                            <select name="month" class="month">
                                <?php
                                for ($month = 1; $month <= 12; $month++) {
                                    echo "<option value='$month'>$month</option>";
                                }
                                ?>
                            </select>
                            <select name="year" class="year">
                                <?php
                                for ($year = 2017; $year > 1980; $year--) {
                                    echo "<option value='$year'>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="signupRow buttons">
                            <input type="reset" value="Borrar" class="signupButton" id="signupBorrar"><input type="submit" name="sign-user" value="Envíar" class="signupButton" id="signupEnviar">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>
