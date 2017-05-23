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

        if (isset($_POST['modificar'])) {

            if (isset($_POST["name"]) && $_POST["name"] == "") {
                echo "<script>alert('El campo nombre esta vacío');</script>";
            } elseif (isset($_POST["surname"]) && $_POST["surname"] == "") {
                echo "<script>alert('El campo apellidos esta vacío');</script>";
            } elseif (isset($_POST["email"]) && $_POST["email"] == "") {
                echo "<script>alert('El campo correo esta vacío');</script>";
            } elseif (isset($_POST["email"]) && !preg_match("/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/", $_POST["email"])) {
                echo "<script>alert('El campo correo es invalido');</script>";
            } else {

                /*
                 * Guarda el usuario, y luego redirecciona a home
                 */

                $usuario = new User($_COOKIE['forum-user-id']);
                $usuario->setName($_POST['name']);
                $usuario->setSurname($_POST['surname']);
                $usuario->setEmail($_POST['email']);

                if ($usuario->validateUser()) {
                    if ($usuario->updateUser()) {
                        echo "<script>alert('Datos guardados correctamente!');</script>";
                        echo "<meta http-equiv='refresh' content='0; url=./index.php' />";
                        die();
                    } else {
                        echo "<script>alert('Error al modificar usuario');</script>";
                    }
                }
            }
        } elseif (isset($_POST["deluser"])) {
            $usuario = new User($_COOKIE['forum-user-id']);
            if (User::deleteUser($_COOKIE['forum-user-id'])) {
                setcookie("forum-user-id", "", time() - 3600);
                echo "<meta http-equiv='refresh' content='0; url=./index.php' />";
                echo "<script>alert('Usuario eliminado correctamente!');</script>";
                die();
            }
        }
        ?>
        <title>Foro: Sign Up</title>
        <?php
        include "meta.php";
        ?>
    </head>
    <body>

        <?php
        $page = "sign";
        include 'top-nav.php';

        require_once './entities/User.php';

        $usuario = new User($_COOKIE['forum-user-id']);
        ?>

        <div id="signupContainer">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <fieldset id="signup">
                    <legend>Datos de usuario</legend>
                    <div class="form">
                        <div class="signupRow">
                            <label>Nombre(s):</label>
                            <input type="text" name="name" value="<?php echo $usuario->getName(); ?>">
                        </div>
                        <div class="signupRow">
                            <label>Apellidos:</label>
                            <input type="text" name="surname" value='<?php echo $usuario->getSurname(); ?>'>
                        </div>    
                        <div class="signupRow">
                            <label>E-Mail:</label>
                            <input type="text" name="email" value="<?php echo $usuario->getEmail(); ?>">
                        </div>

                        <div class="signupRow buttons">
                            <input type="submit" name="deluser" value="Eliminar Usuario" class="signupButton" id="modificarBorrar"><input type="submit" name="modificar" value="Envíar" class="signupButton" id="modificarEnviar">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>
