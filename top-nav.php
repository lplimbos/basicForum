    <head>
        <title>Foro</title>
    </head>
        <div id="headerContainer">
            <div id="header">
                <div id="headerNav">

                        <?php
                        include_once './entities/User.php';
                        
                        if (!isset($page)){
                            $page = "";
                        }
                        
                        $home = "";
                        $forums = "";
                        $question = "";
                        $log = "";
                        $sign = "";
                        $user = "";
                        
                        switch ($page) {
                            case 'home':
                                $home = "selectedItem";
                                break;
                            case 'forums':
                                $forums = "selectedItem";
                                break;
                            case 'question':
                                $question = "selectedItem";
                                break;
                            case 'log':
                                $log = "selectedItem";
                                break;
                            case 'sign':
                                $sign = "selectedItem";
                                break;
                            case 'user':
                                $user = "selectedItem";
                                break;

                            default:
                                break;
                        }
                        
                        
                        printf("
                            <div class='headerItem'><a class='%s' href='./index.php'>Home</a></div>
                            <div class='headerItem'><a class='%s' href='./forums.php'>Forums</a></div>
                            ", $home, $forums);

                        if (isset($_COOKIE['forum-user-id'])){
                            /*
                             * Logear...
                             */
                            $usuario = new User($_COOKIE['forum-user-id']);
                            printf("
                                <div class='headerItem'><a class='%s' href='./question.php'>Ask</a></div>
                                ", $question);
                            printf("
                                <div class='headerItem rightItem'><a href='./logout.php'>Log out</a></div>
                                ");
                            printf("
                                <div class='headerItem rightItem'><a class='%s' href='./userpage.php'>%s</a></div>
                                ", $user,$usuario->getNickname());
                        } else {
                            printf("
                                <div class='headerItem rightItem'><a class='%s rightItem' href='./login.php'>Log in</a></div>
                                <div class='headerItem rightItem'><a class='%s rightItem' href='./sign.php'>Sign up</a></div>
                                ", $log, $sign);
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div id="footerContainer"></div>

            <div id="footer"></div>
        </div>