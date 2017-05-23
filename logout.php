<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

setcookie("forum-user-id", "", time() - 3600);
echo "<meta http-equiv='refresh' content='0; url=./index.php' />";