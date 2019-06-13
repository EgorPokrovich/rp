<?php

    require_once 'main_paige_check.php';

    session_start();

    $_SESSION['name'] =  $_POST['login'];
    $_SESSION['secoundName'] = $_POST['password'];
//    $_SESSION['them_url'] = $_GET['action'];
//    $_SESSION['them_name'] = $_GET['gman123'];
//    $_SESSION['gname'] =  $_POST['gname'];
//    $_SESSION['glink'] = $_POST['glink'];

    $logCredits = ForumLogIn();

    $page_text = take_text_by_curl('http://roleplay.lainurol.com/login.php', $logCredits);

    $text_elementsLG = main_page_parce($page_text);

    $_SESSION['table_elem'] = $text_elementsLG;

    require_once 'show_games_table.php';

